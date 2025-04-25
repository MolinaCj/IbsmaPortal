@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold" style="color: #16C47F;">{{ $teacher->name }}'s Profile</h1>
            <p class="text-muted">Explore assigned subjects and enrolled students</p>
        </div>

        {{-- Subjects Section --}}
        <div class="row">
            {{-- @php
                $subjectsByDepartment = $teacher->subjects->groupBy('department_id');
            @endphp --}}

            @forelse ($subjectsByDepartment as $departmentId => $subjects)
                <div class="col-md-15 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header text-white" style="background-color: #16C47F; border-radius: 15px 15px 0 0;">
                            <h4 class="mb-0">{{ $subjects->first()->department->name }}</h4>
                        </div>
                        <div class="card-body p-4" style="background-color: #f8f9fa;">
                            @foreach ($subjects as $subject)
                                <div class="mb-3 p-3 rounded-3" style="border-left: 5px solid #16C47F; background-color: #fff;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1" style="color: #16C47F;">{{ $subject->name }}</h5>
                                            <small class="text-muted">{{ $subject->code }}</small>
                                        </div>
                                        @php
                                            $studentsData = $subject->students
                                                ->sortByDesc(fn($s) => $s->pivot->updated_at) // Or use 'created_at'
                                                ->unique('id') // Now only the latest record per student remains
                                                ->map(function($student) {
                                                    return [
                                                        "student_id" => $student->id,
                                                        "first_name" => $student->first_name,
                                                        "last_name" => $student->last_name,
                                                        "grade" => $student->pivot->grade ?? "N/A",
                                                        // "is_retaking" => $student->pivot->grade === null || $student->pivot->grade == 0
                                                    ];
                                                })
                                                ->values();
                                        @endphp
                                        <button class="btn btn-sm text-white view-students-btn"
                                                style="background-color: #16C47F;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#studentsModal"
                                                data-subject-name="{{ $subject->name }}"
                                                data-students='@json($studentsData)'>
                                            View Students
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    <p class="text-muted">No subjects assigned.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Students Modal --}}
    <div class="modal fade custom-modal" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentsModalLabel">Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body custom-modal-body">
                    {{-- Search and Filter --}}
                    <div class="search-container">
                        <input type="text" id="studentSearch" class="search-input" placeholder="Search students...">
                        <select id="gradeFilter" class="filter-select">
                            <option value="all">All Grades</option>
                            <option value="N/A">Grade: N/A</option>
                        </select>
                    </div>

                    {{-- Student List --}}
                    <ul class="list-group" id="studentsList">
                        <!-- Students will be injected dynamically -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Dynamic Modal --}}
    <script>
            document.addEventListener('DOMContentLoaded', function () {
        const studentsModalLabel = document.getElementById('studentsModalLabel');
        const studentsList = document.getElementById('studentsList');
        const searchInput = document.getElementById('studentSearch');
        const gradeFilter = document.getElementById('gradeFilter');

        document.querySelectorAll('.view-students-btn').forEach(button => {
            button.addEventListener('click', function () {
                const subjectName = this.getAttribute('data-subject-name');
                const students = JSON.parse(this.getAttribute('data-students'));

                studentsModalLabel.textContent = `${subjectName} - Enrolled Students`;
                renderStudents(students);

                // Filter logic
                searchInput.addEventListener('input', () => filterStudents(students));
                gradeFilter.addEventListener('change', () => filterStudents(students));
            });
        });

        function renderStudents(students) {
                studentsList.innerHTML = '';

                if (students.length > 0) {
                    students.forEach(student => {
                        let retakeBadge = student.is_retaking ? `<span class="badge bg-danger ms-2">Retaking</span>` : '';

                        studentsList.innerHTML += `
                            <li class="list-group-item student-item">
                                <strong>${student.first_name} ${student.last_name}</strong>
                                <span class="badge text-white" style="background-color: #16C47F;">
                                    Grade: ${student.grade}
                                </span>
                                ${retakeBadge}
                            </li>
                        `;
                    });
                } else {
                    studentsList.innerHTML = '<li class="list-group-item text-muted">No students enrolled.</li>';
                }
            }


            function filterStudents(students) {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedGrade = gradeFilter.value;

                const filteredStudents = students.filter(student => {
                    const fullName = `${student.first_name} ${student.last_name}`.toLowerCase();
                    const grade = student.grade; // ✅ FIXED: Use student.grade directly

                    const matchesSearch = fullName.includes(searchTerm);
                    const matchesGrade = selectedGrade === 'all' || grade === selectedGrade;

                    return matchesSearch && matchesGrade;
                });

                renderStudents(filteredStudents);
            }
        });
    </script>



    {{-- Custom Styles --}}
    <style>
        /* Main Container */
        .container {
            max-width: 98%;
        }

        /* Row for 2 Columns with Better Spacing */
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Gap between columns */
        }

        /* Subject Column - Responsive 2 Column Layout */
        .col-md-6 {
            flex: 0 0 calc(50% - 10px); /* Set 50% width with a 10px gap */
            max-width: calc(50% - 10px);
        }

        /* Subject Card with Hover Effect */
        .subject-card {
            background: #fff;
            border-left: 5px solid #16C47F;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        /* Subject Container */
        .subject-container {
            max-height: 500px;
            overflow-y: auto;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Stylish Scrollbar */
        .subject-container::-webkit-scrollbar {
            width: 10px;
        }

        .subject-container::-webkit-scrollbar-thumb {
            background-color: #16C47F;
            border-radius: 5px;
        }

        /* View Students Button */
        .view-students-btn {
            padding: 10px 14px;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .view-students-btn:hover {
            background-color: #14af6c;
        }

        /* Modal Animation */
        .custom-modal .modal-dialog {
            transform: scale(0.9);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        .custom-modal.show .modal-dialog {
            transform: scale(1);
            opacity: 1;
        }

        /* Modal Body */
        .custom-modal-body {
            max-height: 500px;
            overflow-y: auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .custom-modal-body::-webkit-scrollbar {
            width: 10px;
        }

        .custom-modal-body::-webkit-scrollbar-thumb {
            background-color: #16C47F;
            border-radius: 5px;
        }

        /* Student List Item */
        .student-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 8px;
            transition: background 0.3s ease-in-out;
        }

        .student-item:hover {
            background: #e9f7f0;
            transform: translateX(5px);
        }

        /* Search & Filter Container */
        .search-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-input,
        .filter-select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fff;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 15px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #16C47F;
            background: #fff;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .pagination a:hover {
            background: #16C47F;
            color: #fff;
        }

        .pagination .active {
            background: #16C47F;
            color: #fff;
            border-color: #16C47F;
        }

        /* Responsive - Stack to 1 Column for Smaller Screens */
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>



@endsection
