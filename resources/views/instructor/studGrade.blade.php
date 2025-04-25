@extends('layouts.instructor')

@section('content')
<!-- Add this in your Blade template, usually in the <head> or before </body> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    @php
        $gradingLocked = \App\Models\Setting::first()?->grading_locked ?? 0;
    @endphp
    <h1 class="text-center text-success">Student Grades</h1>
    <!-- Button to open modal INCOMPLETE LSISTS -->
    <div style="display: flex;justify-content: space-between;">
        <div style="padding: 20px 0px;">
            <button id="showIncompleteModal" class="btn btn-warning">View Incomplete Students</button>
        </div>
        <div class="mb-3" style="display: flex; justify-content: flex-end; gap: 10px;">
            {{-- Incomplete Button --}}  
            <div style="height: 100%;">
                <button type="button" id="toggleIncompleteBtn" class="btn btn-danger btn-sm w-100 h-100" @if($gradingLocked) disabled @endif>
                    Incomplete Toggle
                </button>
            </div>
            
            
            <a href="{{ route('instructor.studGrade', ['special' => 0]) }}" 
                class="btn btn-success {{ !$isSpecialClass ? 'active' : '' }}">
                 Regular Class Students
             </a>
             <a href="{{ route('instructor.studGrade', ['special' => 1]) }}" 
                class="btn btn-info {{ $isSpecialClass ? 'active' : '' }}">
                 Special Class Students
             </a>

            <script>
                if ($isSpecialClass) {
    $subjects = $teacher->subjects()
        ->where('semester', $currentSemester)
        ->whereHas('grades', function ($query) use ($currentSemester, $currentYear) {
            $query->where('semester', $currentSemester)
                ->where('school_year', $currentYear)
                ->where('special', 1)
                ->whereNotNull('teacher_id'); // ✅ Ensures a teacher is assigned
        })
        ->with(['students' => function ($query) use ($currentSemester, $currentYear) {
            $query->whereHas('grades', function ($gradeQuery) use ($currentSemester, $currentYear) {
                $gradeQuery->where('semester', $currentSemester)
                    ->where('school_year', $currentYear)
                    ->where('special', 1)
                    ->whereNotNull('teacher_id'); // ✅ Ensures only students in special classes with a teacher
            });
        }])
        ->get();



    // Fetch special class grades
    $editableGrades = Grade::whereIn('subject_id', $subjectIds)
        ->where('school_year', $currentYear)
        ->where('semester', $currentSemester)
        ->where('special', 1)
        ->whereNotNull('teacher_id') // ✅ Only fetch grades with an assigned teacher
        ->latest('id')
        ->get()
        ->unique(fn($grade) => $grade->student_id . '-' . $grade->subject_id);
}

            </script>
        </div>        
    </div>
    
    <!-- Modal INCOMPLETE LISTS-->
    <div id="incompleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Incomplete Students</h5>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Grade</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="incompleteList">
                            <!-- Data will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let editedGrades = JSON.parse(localStorage.getItem('editedGrades')) || {};

            // Show Incomplete Modal & Fetch Data
            $('#showIncompleteModal').click(function () {
                $.ajax({
                    url: '{{ route("instructor.getIncomplete") }}',
                    method: 'GET',
                    success: function (data) {
                        let groupedSubjects = {};

                        data.forEach(grade => {
                            let subjectName = grade.subject && grade.subject.name ? grade.subject.name : 'Unknown Subject';
                            let studentName = grade.student ? `${grade.student.last_name}, ${grade.student.first_name}` : 'Unknown Student';

                            if (!groupedSubjects[subjectName]) {
                                groupedSubjects[subjectName] = [];
                            }

                            groupedSubjects[subjectName].push({
                                studentName: studentName,
                                gradeId: grade.id,
                                studentId: grade.student_id,
                                subjectId: grade.subject_id,
                                grade: grade.grade ?? 0
                            });
                        });

                        let html = '';
                        Object.keys(groupedSubjects).forEach(subject => {
                            html += `<tr class="table-primary">
                                        <td colspan="3"><strong>${subject}</strong></td>
                                    </tr>`;

                            groupedSubjects[subject].forEach(student => {
                                html += `<tr data-id="${student.gradeId}">
                                            <td>${student.studentName}</td>
                                            <td>
                                                <input type="number" 
                                                    class="form-control grade-input" 
                                                    name="grades[${student.studentId}][${student.subjectId}]" 
                                                    value="${student.grade}" 
                                                    min="0" max="100" step="0.01">
                                            </td>
                                            <td><button class="btn btn-success updateGrade">Update</button></td>
                                        </tr>`;
                            });
                        });

                        $('#incompleteList').html(html);
                        $('#incompleteModal').modal('show');
                    },
                    error: function (xhr) {
                        console.error("Error fetching incomplete students:", xhr.responseText);
                    }
                });
            });

            // Format Grade Input (Ensure 3-4 digit numbers become decimal)
            $(document).on('input', '.grade-input', function () {
                let value = this.value.replace(/\D/g, ''); // Remove non-numeric characters

                if (parseInt(value) > 9999) {
                    value = "9999";
                }

                if (value.length > 2) {
                    let integerPart = value.slice(0, 2);
                    let decimalPart = value.slice(2);

                    if (integerPart === "10" && (decimalPart === "0" || decimalPart === "00")) {
                        this.value = "100";
                    } else {
                        this.value = `${integerPart}.${decimalPart}`;
                    }
                } else {
                    this.value = value;
                }
            });

            // Handle Grade Update (Incomplete Modal)
            $(document).on('click', '.updateGrade', function () {
                let row = $(this).closest('tr');
                let gradeId = row.data('id');
                let inputField = row.find('.grade-input');
                let newGrade = inputField.val();

                console.log("Attempting to update:", { gradeId, newGrade });

                $.ajax({
                    url: '{{ route("instructor.updateIncompleteGrade") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: gradeId,
                        grade: newGrade
                    },
                    success: function (response) {
                        console.log('Server Response:', response);

                        if (response.success) {
                            row.find('.updateGrade').removeClass('btn-success').addClass('btn-secondary').text('Updated');

                            // Also update the main form's grade input and localStorage
                            let inputSelector = `input[name="grades[${row.find('.grade-input').attr('name').match(/\d+/g)[0]}][${row.find('.grade-input').attr('name').match(/\d+/g)[1]}]"]`;
                            let mainInput = $(inputSelector);

                            if (mainInput.length > 0) {
                                mainInput.val(newGrade);
                                mainInput.addClass('highlight'); // Optional: highlight change
                            }

                            // Update localStorage so new value persists
                            editedGrades[row.find('.grade-input').attr('name')] = newGrade;
                            localStorage.setItem('editedGrades', JSON.stringify(editedGrades));
                        } else {
                            alert('Failed to update grade: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        console.error("AJAX error:", xhr.status, xhr.responseText);
                    }
                });
            });

            // Restore Grades from Local Storage
            $('.grade-input').each(function () {
                let key = this.name;
                if (editedGrades[key]) {
                    this.value = editedGrades[key];
                }
            });

            // Clear LocalStorage on Logout
            $('#logoutButton')?.click(function () {
                localStorage.removeItem('editedGrades');
            });
                // Refresh page when modal is closed
            $('#incompleteModal').on('hidden.bs.modal', function () {
                location.reload();
            });
            
        });
    </script>
    
        <form id="bulkGradeForm">
            @csrf
            @php
                $currentYear = DB::table('settings')->value('current_school_year') ?? date('Y');
                $subjectsByYear = $subjects->groupBy('year');
            @endphp
        
            @foreach ($subjectsByYear as $year => $subjects)
                @php
                    $yearStudentIds = collect();
                    foreach ($subjects as $subject) {
                        foreach ($subject->students as $student) {
                            if ($student->grades->where('school_year', $currentYear)->where('subject_id', $subject->id)->count() > 0) {
                                $yearStudentIds->push($student->student_id);
                            }
                        }
                    }
                    $yearStudentCount = $yearStudentIds->unique()->count();
                @endphp
                <div class="mb-4">
                    <button type="button" class="btn btn-outline-success w-100 text-start toggle-year fs-4 py-3" data-year="{{ $year }}">
                        Year Level: {{ $year }} - {{ $yearStudentCount }} students
                    </button>                
                    <div class="year-subjects d-none mt-2" id="year-{{ $year }}">
                        @foreach ($subjects as $subject)    
                            @php
                                $studentCount = $subject->students->filter(fn($student) => 
                                    $student->grades->where('school_year', $currentYear)->where('subject_id', $subject->id)->count() > 0
                                )->count();
                            @endphp
                            <div class="card mb-3 shadow-sm border-success">
                                <div class="card-header bg-success text-white">
                                    <h4>{{ $subject->name }} ({{ $subject->code }}) - {{ $subject->department->name }}</h4>
                                    <p><strong>Time:</strong> {{ $subject->time }} | <strong>Room:</strong> {{ $subject->room }}</p>
                                    <p><strong>Total Students:</strong> {{ $studentCount }}</p>
                                    
                                    @php
                                        $teacher = Auth::guard('teacher')->user();
                                    @endphp 
                                    <div style="display: flex; justify-content: space-between;">
                                        <button type="button" class="btn btn-light toggle-students" data-subject-id="{{ $subject->id }}">
                                            View Students
                                        </button>
                                        <button type="button" class="btn btn-primary print-grades" 
                                            data-subject-id="{{ $subject->id }}" 
                                            data-teacher-name="{{ $teacher->name ?? 'Instructor' }}"
                                            data-department="{{ $subject->department->name }}"  
                                            data-year-level="{{ $subject->year }}">
                                            Print Grades
                                        </button>
                                    </div>
                                    {{-- Printing Section  --}}
                                    <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        document.querySelectorAll(".print-grades").forEach(button => {
                                            button.addEventListener("click", function () {
                                                let subjectId = this.getAttribute("data-subject-id");
                                                let teacherName = this.getAttribute("data-teacher-name");
                                                let department = this.getAttribute("data-department");  // From subject->department->name
                                                let yearLevel = this.getAttribute("data-year-level");  // From subject->year
                                                let table = document.querySelector(`#students-${subjectId} .student-table`);
                                    
                                                if (!table) {
                                                    alert("No student data available for printing.");
                                                    return;
                                                }
                                    
                                                // Clone the table to modify it without affecting the original
                                                let clonedTable = table.cloneNode(true);
                                    
                                                // Remove "Status" column (if it exists)
                                                let statusColumnIndex = [...clonedTable.querySelectorAll("th")].findIndex(th => 
                                                    th.textContent.trim().toLowerCase() === "status"
                                                );
                                                
                                                if (statusColumnIndex !== -1) {
                                                    clonedTable.querySelectorAll("tr").forEach(row => {
                                                        let cells = row.querySelectorAll("th, td");
                                                        if (cells.length > statusColumnIndex) {
                                                            cells[statusColumnIndex].remove();
                                                        }
                                                    });
                                                }
                                    
                                                // Process grades and incomplete status
                                                clonedTable.querySelectorAll(".mark-incomplete").forEach(button => {
                                                    let td = button.closest("td");
                                                    let isIncomplete = button.textContent.trim() === "Incomplete";
                                                    if (isIncomplete) {
                                                        // Set grade to 0 if incomplete
                                                        let gradeCell = button.closest("tr").querySelector(".grade");
                                                        if (gradeCell) gradeCell.textContent = "0";
                                                        td.innerHTML = `<span class="badge bg-danger">Incomplete</span>`;
                                                    } else {
                                                        td.innerHTML = ""; // Remove 'Complete' text
                                                    }
                                                });
                                    
                                                // Open a new print window with the modified table
                                                let newWindow = window.open("", "_blank");
                                                newWindow.document.write(`
                                                    <html>
                                                    <head>
                                                        <title>Print Grades - ${department} (Year ${yearLevel})</title>
                                                        <style>
                                                            body { font-family: Arial, sans-serif; margin: 20px; }
                                                            .header { text-align: center; margin-bottom: 20px; }
                                                            .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
                                                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                                                            th, td { border: 1px solid black; padding: 8px; text-align: left; }
                                                            th { background-color: #28a745; color: white; }
                                                            .incomplete-badge { background-color: #dc3545; color: white; padding: 2px 5px; border-radius: 3px; }
                                                        </style>
                                                    </head>
                                                    <body>
                                                        <div class="header">
                                                            <h2>Subject Grades Report</h2>
                                                            <div class="info-row">
                                                                <div><strong>Department:</strong> ${department}</div>
                                                                <div><strong>Year Level:</strong> ${yearLevel}</div>
                                                            </div>
                                                            <div class="info-row">
                                                                <div><strong>Instructor:</strong> ${teacherName}</div>
                                                                <div><strong>Date Printed:</strong> ${new Date().toLocaleDateString()}</div>
                                                            </div>
                                                        </div>
                                                        ${clonedTable.outerHTML}
                                                    </body>
                                                    </html>
                                                `);
                                                newWindow.document.close();
                                                newWindow.print();
                                    
                                                // Detect when print window is closed
                                                let interval = setInterval(() => {
                                                    if (newWindow.closed) {
                                                        clearInterval(interval);
                                                        localStorage.clear(); // Clear all localStorage
                                                        location.reload(); // Refresh the page
                                                    }
                                                }, 500);
                                            });
                                        });
                                    });
                                    </script>
                                    
                                    
                                                            
                                </div>
                                <div class="card-body d-none" id="students-{{ $subject->id }}">
                                    <input type="text" class="form-control mb-3 search-student" data-subject-id="{{ $subject->id }}" placeholder="Search student...">
                                    <table class="table table-striped table-hover student-table" data-subject-id="{{ $subject->id }}">
                                        <thead class="table-success">
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>GPA</th>
                                                <th>Grade</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $allStudents = collect();
                                                $studentIds = [];
        
                                                $filteredStudents = $subject->students->filter(fn($student) => $student->year_level == $subject->year && $student->semester == $subject->semester);
        
                                                foreach ($filteredStudents as $student) {
                                                    if (!in_array($student->student_id, $studentIds)) {
                                                        $allStudents->push(['student' => $student, 'status' => 'Regular']);
                                                        $studentIds[] = $student->student_id;
                                                    }
                                                }
        
                                                foreach ($editableGrades->where('subject_id', $subject->id) as $repeat) {
                                                    if (!in_array($repeat->student->student_id, $studentIds)) {
                                                        $allStudents->push(['student' => $repeat->student, 'status' => 'Re-taking']);
                                                        $studentIds[] = $repeat->student->student_id;
                                                    }
                                                }
        
                                                $allStudents = $allStudents->sortBy('student.last_name');
                                            @endphp
        
                                            @foreach ($allStudents as $data)
                                                @php
                                                    $gradeRecord = $editableGrades
                                                        ->where('student_id', $data['student']->student_id)
                                                        ->where('subject_id', $subject->id)
                                                        ->sortByDesc('school_year')
                                                        ->first();

                                                    $isIncomplete = optional($gradeRecord)->incomplete ?? false;
                                                @endphp
                                                <tr id="student-row-{{ $data['student']->student_id }}-{{ $subject->id }}" 
                                                    class="{{ $isIncomplete ? 'table-danger' : '' }}">
                                                
                                                    <td>
                                                        {{ $data['student']->student_id }}
                                                        @if($gradingLocked)
                                                            <span class="badge bg-danger">Grading Locked</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $data['student']->last_name }}, {{ $data['student']->first_name }}</td>
                                                    <td>
                                                        <input type="text" class="form-control gpa-input"
                                                            id="gpa-{{ $data['student']->student_id }}-{{ $subject->id }}"
                                                            name="gpas[{{ $data['student']->student_id }}][{{ $subject->id }}]"
                                                            min="0" max="5" step="0.1"
                                                            oninput="limitGPAInput(this)"
                                                            style="width: 80px;"> <!-- Set custom width here -->
                                                    </td>                                                                                                                                                                                       
                                                    <td>
                                                        <input type="number" class="form-control grade-input"
                                                            id="grade-{{ $data['student']->student_id }}-{{ $subject->id }}"
                                                            name="grades[{{ $data['student']->student_id }}][{{ $subject->id }}]"
                                                            value="{{ optional($editableGrades->where('student_id', $data['student']->student_id)->where('subject_id', $subject->id)->sortByDesc('school_year')->first())->grade }}"
                                                            min="0" max="100" step="0.01">
                                                    </td>
                                                    <script>
                                                        // Function to convert two-digit input to GPA format like 33 -> 3.3
                                                        function limitGPAInput(input) {
                                                            let val = input.value.trim();

                                                            // If the value is a two-digit number (e.g., "33"), convert to decimal format (e.g., "3.3")
                                                            if (/^\d{2}$/.test(val)) {
                                                                val = val[0] + '.' + val[1]; // Convert 33 to 3.3
                                                            }

                                                            let floatVal = parseFloat(val);
                                                            if (isNaN(floatVal)) {
                                                                input.value = '';
                                                                return;
                                                            }

                                                            // If the value is a single digit, keep it as it is (no automatic conversion to 1.0)
                                                            if (val.length === 1) {
                                                                input.value = val; // Keep single digit as is
                                                                return;
                                                            }

                                                            // Clamp between 0.0 and 5.0
                                                            floatVal = Math.max(0, Math.min(5, floatVal));

                                                            // Set the formatted value (with one decimal place)
                                                            input.value = floatVal.toFixed(1);
                                                        }
                                                        function percentageToGPA(grade) {
                                                            if (grade > 99.5) return 1.0;
                                                            if (grade > 98.5) return 1.1;
                                                            if (grade > 97.5) return 1.2;
                                                            if (grade > 96.5) return 1.3;
                                                            if (grade > 95.5) return 1.4;
                                                            if (grade > 94.5) return 1.5;
                                                            if (grade > 93.5) return 1.6;
                                                            if (grade > 92.5) return 1.7;
                                                            if (grade > 91.5) return 1.8;
                                                            if (grade > 90.5) return 1.9;
                                                            if (grade > 89.5) return 2.0;
                                                            if (grade > 88.5) return 2.1;
                                                            if (grade > 87.5) return 2.2;
                                                            if (grade > 86.5) return 2.3;
                                                            if (grade > 85.5) return 2.4;
                                                            if (grade > 84.5) return 2.5;
                                                            if (grade > 83.5) return 2.6;
                                                            if (grade > 82.5) return 2.7;
                                                            if (grade > 81.5) return 2.8;
                                                            if (grade > 80.5) return 2.9;
                                                            if (grade > 79.5) return 3.0;
                                                            if (grade > 78.5) return 3.1;
                                                            if (grade > 77.5) return 3.2;
                                                            if (grade > 76.5) return 3.3;
                                                            if (grade > 75.5) return 3.4;
                                                            if (grade > 74.5) return 3.5;
                                                            return 5.0;
                                                        }

                                                    
                                                        function gpaToPercentage(gpa) {
                                                            if (gpa <= 1.0) return 100;
                                                            if (gpa <= 1.1) return 99;
                                                            if (gpa <= 1.2) return 98;
                                                            if (gpa <= 1.3) return 97;
                                                            if (gpa <= 1.4) return 96;
                                                            if (gpa <= 1.5) return 95;
                                                            if (gpa <= 1.6) return 94;
                                                            if (gpa <= 1.7) return 93;
                                                            if (gpa <= 1.8) return 92;
                                                            if (gpa <= 1.9) return 91;
                                                            if (gpa <= 2.0) return 90;
                                                            if (gpa <= 2.1) return 89;
                                                            if (gpa <= 2.2) return 88;
                                                            if (gpa <= 2.3) return 87;
                                                            if (gpa <= 2.4) return 86;
                                                            if (gpa <= 2.5) return 85;
                                                            if (gpa <= 2.6) return 84;
                                                            if (gpa <= 2.7) return 83;
                                                            if (gpa <= 2.8) return 82;
                                                            if (gpa <= 2.9) return 81;
                                                            if (gpa <= 3.0) return 80;
                                                            if (gpa <= 3.1) return 79;
                                                            if (gpa <= 3.2) return 78;
                                                            if (gpa <= 3.3) return 77;
                                                            if (gpa <= 3.4) return 76;
                                                            if (gpa <= 3.5) return 75;
                                                            return 74;
                                                        }

                                                    
                                                        // Handle grade input to auto-update GPA
                                                        document.querySelectorAll('.grade-input').forEach(input => {
                                                            input.addEventListener('input', function () {
                                                                const grade = parseFloat(this.value);
                                                                const ids = this.id.split('-').slice(1); // studentId-subjectId
                                                                const gpaInput = document.getElementById('gpa-' + ids.join('-'));

                                                                if (!isNaN(grade)) {
                                                                    gpaInput.value = percentageToGPA(grade).toFixed(1);
                                                                }
                                                            });

                                                            // Initialize on page load
                                                            input.dispatchEvent(new Event('input'));
                                                        });

                                                        // Handle GPA input to auto-update grade and format input
                                                        document.querySelectorAll('.gpa-input').forEach(input => {
                                                            input.addEventListener('input', function () {
                                                                limitGPAInput(this); // Ensure GPA is validated and formatted

                                                                const gpa = parseFloat(this.value);
                                                                const ids = this.id.split('-').slice(1); // studentId-subjectId
                                                                const gradeInput = document.getElementById('grade-' + ids.join('-'));

                                                                if (!isNaN(gpa)) {
                                                                    gradeInput.value = gpaToPercentage(gpa).toFixed(2);
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                    
                                                    
                                                    <td>
                                                        <span class="badge {{ $data['status'] === 'Regular' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $data['status'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn {{ $isIncomplete ? 'btn-secondary' : 'btn-danger' }} mark-incomplete"
                                                            data-student-id="{{ $data['student']->student_id }}"
                                                            data-subject-id="{{ $subject->id }}"
                                                            style="display: none;"
                                                            @if(!empty(optional($editableGrades->where('student_id', $data['student']->student_id)->where('subject_id', $subject->id)->sortByDesc('school_year')->first())->grade)) disabled @endif> <!-- Disable button if grade exists -->
                                                            {{ $isIncomplete ? 'Incomplete' : 'Set Incomplete' }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button id="saveAllGradesBtn" type="submit" class="btn btn-success">Save All Grades</button>
        </form>
    <script>
        //grades coloring
        document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".grade-input").forEach(input => {
            // Apply color immediately on load
            updateGradeStyle(input);

            // Live update while typing
            input.addEventListener("input", function () {
                updateGradeStyle(input);
            });
        });
    });

    function updateGradeStyle(input) {
        let grade = parseFloat(input.value);

        // Reset styles
        input.style.backgroundColor = "white";

        if (!isNaN(grade)) {
            if (grade >= 90) {
                input.style.color = "green";
                input.style.border = "2px solid green";
            } else if (grade >= 80) {
                input.style.color = "goldenrod"; // Dark yellow for contrast
                input.style.border = "2px solid goldenrod";
            } else if (grade >= 75) {
                input.style.color = "orange";
                input.style.border = "2px solid orange";
            } else {
                input.style.color = "red";
                input.style.border = "2px solid red";
            }
        } else {
            // Reset to default if input is empty or invalid
            input.style.color = "black";
            input.style.border = "1px solid #ced4da"; // Bootstrap default border
        }
    }
       //
        document.addEventListener('DOMContentLoaded', function () {
            let gradingLocked = @json($gradingLocked);
            if (gradingLocked) {
                document.querySelectorAll('.grade-input').forEach(input => {
                    input.disabled = true;
                });
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log("Script Loaded.");
    
            // ==================== Toggle Incomplete Buttons ====================
            const toggleButton = document.getElementById("toggleIncompleteBtn");
            if (toggleButton) {
                const incompleteButtons = document.querySelectorAll(".mark-incomplete");
                toggleButton.addEventListener("click", function () {
                    incompleteButtons.forEach(button => {
                        button.style.display = (button.style.display === "none") ? "inline-block" : "none";
                    });
                });
            }
    
            // ==================== Disable Incomplete on Grade Input ====================
            document.querySelectorAll('.grade-input').forEach(input => {
                input.addEventListener('input', function () {
                    let row = this.closest('tr'); // Get the closest row
                    let incompleteButton = row.querySelector('.mark-incomplete'); // Get the "Incomplete" button in that row
    
                    if (this.value.trim() !== '') {
                        incompleteButton.disabled = true; // Disable button if input has value
                    } else {
                        incompleteButton.disabled = false; // Enable button if input is empty
                    }
                });
            });
    
            // ==================== Mark Incomplete Button ====================
            document.body.addEventListener('click', function (event) {
                if (event.target.classList.contains('mark-incomplete')) {
                    let studentId = event.target.dataset.studentId;
                    let subjectId = event.target.dataset.subjectId;
                    let button = event.target;
                    let row = document.getElementById(`student-row-${studentId}-${subjectId}`);
                    let gradeInput = row.querySelector('.grade-input'); // Get the grade input

                    console.log(`Clicked 'mark-incomplete' for Student ID: ${studentId}, Subject ID: ${subjectId}`);

                    fetch('/mark-incomplete', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ student_id: studentId, subject_id: subjectId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("JSON response:", data);

                        if (data.success) {
                            // Toggle row highlight
                            row.classList.toggle('table-danger', data.is_incomplete);

                            // Update button text and style
                            button.textContent = data.is_incomplete ? 'Incomplete' : 'Set Incomplete';
                            button.classList.toggle('btn-secondary', data.is_incomplete);
                            button.classList.toggle('btn-danger', !data.is_incomplete);

                            if (data.is_incomplete && gradeInput) {
                                // Remove grade from localStorage when marking as incomplete
                                let key = `${gradeInput.name}`;
                                let editedGrades = JSON.parse(localStorage.getItem('editedGrades')) || {};

                                if (editedGrades[key]) {
                                    delete editedGrades[key];
                                    localStorage.setItem('editedGrades', JSON.stringify(editedGrades));
                                }

                                // Clear the input visually
                                gradeInput.value = '';
                                gradeInput.classList.remove('highlight');
                            }
                        } else {
                            console.warn("Error from server:", data.message);
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
                }
            });

    
            // ==================== Restore Opened Sections ====================
            let openedYears = JSON.parse(localStorage.getItem('openedYears')) || [];
            let openedSubjects = JSON.parse(localStorage.getItem('openedSubjects')) || [];
    
            function saveOpenedState() {
                localStorage.setItem('openedYears', JSON.stringify(openedYears));
                localStorage.setItem('openedSubjects', JSON.stringify(openedSubjects));
            }
    
            document.querySelectorAll('.year-subjects').forEach(year => {
                let yearId = year.id.replace('year-', '');
                if (openedYears.includes(yearId)) {
                    year.classList.remove('d-none');
                }
            });
    
            document.querySelectorAll('.toggle-students').forEach(button => {
                let subjectId = button.dataset.subjectId;
                let studentSection = document.getElementById(`students-${subjectId}`);
    
                if (openedSubjects.includes(subjectId)) {
                    studentSection.classList.remove('d-none');
                    button.textContent = 'Hide Students';
                }
            });
    
            // ==================== Toggle Year Level ====================
            document.querySelectorAll('.toggle-year').forEach(button => {
                button.addEventListener('click', function () {
                    let yearId = this.dataset.year;
                    let yearSubjects = document.getElementById(`year-${yearId}`);
                    let isOpen = !yearSubjects.classList.contains('d-none');
    
                    document.querySelectorAll('.year-subjects').forEach(subjects => {
                        if (subjects.id !== `year-${yearId}`) {
                            subjects.classList.add('d-none');
                        }
                    });
    
                    yearSubjects.classList.toggle('d-none');
    
                    if (isOpen) {
                        openedYears = openedYears.filter(y => y !== yearId);
                    } else {
                        openedYears.push(yearId);
                    }
                    saveOpenedState();
                });
            });
    
            // ==================== Toggle View Students ====================
            document.querySelectorAll('.toggle-students').forEach(button => {
                button.addEventListener('click', function () {
                    let subjectId = this.dataset.subjectId;
                    let studentSection = document.getElementById(`students-${subjectId}`);
                    let isOpen = !studentSection.classList.contains('d-none');
    
                    studentSection.classList.toggle('d-none');
                    this.textContent = isOpen ? 'View Students' : 'Hide Students';
    
                    if (isOpen) {
                        openedSubjects = openedSubjects.filter(s => s !== subjectId);
                    } else {
                        openedSubjects.push(subjectId);
                    }
                    saveOpenedState();
                });
            });
    
            // ==================== Search Students ====================
            document.querySelectorAll('.search-student').forEach(input => {
                input.addEventListener('keyup', function () {
                    const searchText = this.value.toLowerCase();
                    document.querySelectorAll(`.student-table[data-subject-id='${this.dataset.subjectId}'] tbody tr`).forEach(row => {
                        row.style.display = row.children[1].textContent.toLowerCase().includes(searchText) ? '' : 'none';
                    });
                });
            });
    
            // ==================== AJAX Grade Submission ====================
            let form = document.getElementById('bulkGradeForm');
            let saveButton = document.getElementById('saveAllGradesBtn');
    
            form.addEventListener('submit', function (e) {
                e.preventDefault();
    
                let formData = new FormData(this);
                fetch("{{ route('instructor.updateGrade') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const toastEl = document.getElementById('gradeSuccessToast');
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
    
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        alert('Error updating grades. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // ==================== Grade Input Formatting ====================
            let inputs = document.querySelectorAll('.grade-input');

            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    saveButton.style.display = 'block';
                    let value = this.value.replace(/\D/g, ''); // Remove non-numeric characters

                    if (value.length > 4) {
                        value = value.slice(0, 4); // Limit to 4 digits
                    }

                    if (value.length > 2) {
                        let integerPart = value.slice(0, 2);
                        let decimalPart = value.slice(2);
                        this.value = `${integerPart}.${decimalPart}`;
                    } else {
                        this.value = value;
                    }

                    this.classList.add('highlight'); // Keep highlighting
                });
            });
            // ==================== Clear LocalStorage on Logout ====================
            document.getElementById('logoutButton')?.addEventListener('click', function () {
                localStorage.removeItem('editedGrades');
                localStorage.removeItem('openedYears');
                localStorage.removeItem('openedSubjects');
            });
        }); // <- This correctly closes the `DOMContentLoaded`
    </script>
    

    <style>
        #saveAllGradesBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none; /* Hidden by default */
            z-index: 1000;
            padding: 10px 20px;
            font-size: 18px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        .highlight {
            background-color: #ffeb99 !important; /* Light Yellow */
            transition: background-color 0.5s ease-in-out;
        }
    </style>
    {{-- Shows Button When Input Changes --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let saveButton = document.getElementById('saveAllGradesBtn');
            let form = document.getElementById('bulkGradeForm');
            let originalValues = {};
    
            // Store initial values to prevent unnecessary button shows
            document.querySelectorAll('.grade-input').forEach(input => {
                originalValues[input.name] = input.value;
            });
    
            document.body.addEventListener('input', function (event) {
                if (event.target.classList.contains('grade-input')) {
                    let input = event.target;
                    if (input.value !== originalValues[input.name]) {
                        saveButton.style.display = 'block'; // Show button when input changes
                    }
                }
            });
    
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent default submission for AJAX handling
                let formData = new FormData(form);
    
                fetch("{{ route('instructor.updateGrade') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const toastEl = document.getElementById('gradeSuccessToast');
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
    
                        saveButton.style.display = 'none'; // Hide button only if successful
                    } else {
                        alert('Error updating grades. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
    let saveButton = document.getElementById('saveAllGradesBtn');
    let incompleteButton = document.getElementById('showIncompleteModal');

    function toggleIncompleteButton() {
        if (saveButton.style.display === 'block') {
            incompleteButton.style.display = 'none';
        } else {
            incompleteButton.style.display = 'inline-block';
        }
    }

    // Initial check in case "Save All Grades" is already active
        toggleIncompleteButton();

        // Monitor input changes to show "Save All Grades" and hide "View Incomplete Students"
        document.querySelectorAll('.grade-input').forEach(input => {
            input.addEventListener('input', function () {
                saveButton.style.display = 'block';
                toggleIncompleteButton();
            });
        });

        // When grades are submitted, reset state
        saveButton.addEventListener('click', function () {
            setTimeout(() => {
                saveButton.style.display = 'none';
                toggleIncompleteButton();
            }, 500); // Delay to ensure UI updates correctly
        });
    });

    </script>
    
    <div id="gradeSuccessToast" class="toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Grades updated successfully!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endsection