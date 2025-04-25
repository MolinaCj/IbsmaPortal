@extends('layouts.app')

@section('content')
<!-- In the head section of your layout file (typically in resources/views/layouts/admin.blade.php) -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Just before the closing body tag -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Your Loading Screen -->
<div id="loading-screen" style="display: none;">
    <div class="overlay"></div> <!-- Dimmed background -->
    <div class="loader-container">
        <img src="{{ asset('storage/ibsmalogo.png') }}" alt="Loading" class="loader-image">
        <p id="loading-message" style="color: white; font-size: 18px; margin-top: 10px;"></p>
    </div>
</div>

<style>
    /* Overlay effect (dims the background) */
    #loading-screen .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
        z-index: 9998; /* Places it behind the loader but above the rest of the content */
    }

    /* Loader container (centered image) */
    #loading-screen .loader-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 9999; /* Places it on top of the overlay */
    }

    #loading-screen img {
        width: 100px; /* You can adjust the size of your logo */
        height: auto;
    }
</style>

<div id="overlay" class="overlay" style="display: none;"></div>
<div class="mt-4">
    <div class="card shadow-sm p-4 mb-4">
        <div class="card shadow-sm p-4 mb-4">
            <h5 class="mb-3 fs-3 text-green text-center">Lock Grades</h5>
                <div class="d-flex justify-content-center mt-3" style="padding: 0.5%">
                    <button id="toggleGradingLockBtn" class="btn btn-danger px-4 py-2 fw-bold">
                        🔒 Checking...
                    </button>
                </div>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const button = document.getElementById('toggleGradingLockBtn');
                console.log('Button:', button);

                if (!button) return;

                // Check initial lock state
                fetch("{{ route('check.grading.lock') }}")
                    .then(response => response.json())
                    .then(data => {
                        console.log("Initial fetch result:", data);
                        if (data.success) {
                            updateButtonState(data.locked);
                        } else {
                            button.textContent = "❌ Error Checking";
                        }
                    })
                    .catch(error => {
                        console.error("Fetch error:", error);
                        button.textContent = "⚠️ Network Error";
                    });

                button.addEventListener('click', function () {
                    console.log("Button clicked");

                    fetch("{{ route('toggle.grading.lock') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Toggle response:", data);
                        if (data.success) {
                            updateButtonState(data.locked);
                            alert(data.locked ? "Grading is now locked." : "Grading is now unlocked.");
                        } else {
                            alert("Toggle failed.");
                        }
                    })
                    .catch(error => {
                        console.error("Toggle error:", error);
                    });
                });

                function updateButtonState(isLocked) {
                    console.log("Updating UI, locked:", isLocked);
                    button.textContent = isLocked ? "🔒 Grading is Locked" : "🔓 Grading is Unlocked";
                    button.classList.toggle("btn-secondary", isLocked);
                    button.classList.toggle("btn-danger", !isLocked);
                }
            });
            </script>


        <div class="card shadow-sm p-5 mb-4 mx-auto" style="max-width: 500px;">
            <h5 class="mb-3 fs-3 text-green">Current School Year</h5>
            @php
                $currentYear = \App\Models\Setting::first()->current_school_year ?? 'Not Set';
                $nextYear = is_numeric($currentYear) ? $currentYear + 1 : 'Not Set';
            @endphp

            <p class="fw-bold text-success display-4 text-center">
                {{ $currentYear }} - {{ $nextYear }}
            </p>

            <p class="fw-bold text-success display-6 text-center">Semester
                @if (\App\Models\Setting::first()->current_semester == 3)
                : Summer
            @elseif (\App\Models\Setting::first()->current_semester == 2)
                : 2nd
            @elseif (\App\Models\Setting::first()->current_semester == 1)
                : 1st
            @else
                : {{ \App\Models\Setting::first()->current_semester ?? 'Not Set' }}
            @endif
            </p>


        </div>
        <div class="card shadow-sm p-4 text-center">
            <h5 class="text-green">Semester Settings</h5>
            <form id="semesterForm" action="{{ route('admin.updateSemester') }}" method="POST">
                @csrf
                <input type="hidden" name="semester" value="{{ $currentSemester + 1 }}">

                @if ($currentSemester == 1)
                    <button type="button" class="btn btn-success mt-3" onclick="confirmSemesterChange(2)">Change to Semester 2</button>
                @elseif ($currentSemester == 2)
                    <button type="button" class="btn btn-success mt-3" onclick="confirmSemesterChange(3)">Change to Summer Class</button>
                @else
                    <button type="button" class="btn btn-secondary mt-3" disabled>Summer Class (Final Semester)</button>
                @endif
            </form>
        </div>
        <div class="card shadow-sm p-4 text-center">
            <!-- Graduation Button -->
            @if(in_array($currentSemester, [2, 3]))
            <button class="btn btn-info btn-lg graduation-btn" data-toggle="modal" data-target="#graduationModal">
                Check Graduation Eligibility
            </button>
            @endif


            <!-- Modal for Graduation Eligibility -->
            <div class="modal fade" id="graduationModal" tabindex="-1" role="dialog" aria-labelledby="graduationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="graduationModalLabel">Graduation Management</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="graduationForm" method="POST" action="{{ route('admin.graduation.update') }}">
                                @csrf

                                <!-- Soon-to-be Graduates Section -->
                                <div class="mb-5">
                                    <h4 class="text-primary mb-3">
                                        <i class="fas fa-user-graduate mr-2"></i>Soon-to-be Graduates
                                        <small class="text-muted">(Eligible students not yet graduated)</small>
                                    </h4>

                                    @foreach($groupedByDepartment as $departmentId => $students)
                                        @php $department = \App\Models\Department::find($departmentId); @endphp

                                        <div class="department-group mb-4">
                                            <h5 class="bg-light p-2">
                                                {{ $department->name }}
                                                <button type="button" class="btn btn-sm btn-outline-primary select-all-btn float-right" data-department="{{ $departmentId }}">
                                                    Select All
                                                </button>
                                            </h5>

                                            <table class="table table-hover">
                                                <tbody>
                                                    @foreach($students as $student)
                                                        @if(!$student->graduated)
                                                            <tr>
                                                                <td width="50">
                                                                    <input type="checkbox"
                                                                           name="students[]"
                                                                           value="{{ $student->student_id }}"
                                                                           data-department="{{ $departmentId }}">
                                                                </td>
                                                                <td>{{ $student->student_id }}</td>
                                                                <td>
                                                                    <strong>{{ $student->last_name }}, {{ $student->first_name }}</strong>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-warning">Pending Graduation</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Already Graduated Section -->
                                <div class="mt-5">
                                    <h4 class="text-success mb-3">
                                        <i class="fas fa-graduation-cap mr-2"></i>Graduated Students
                                        <small class="text-muted">({{ $currentSchoolYear }})</small>
                                    </h4>

                                    @foreach($groupedGraduated as $departmentId => $graduates)
                                        @php $department = \App\Models\Department::find($departmentId); @endphp

                                        <div class="department-group mb-4">
                                            <h5 class="bg-light p-2">{{ $department->name }}</h5>

                                            <table class="table table-hover">
                                                <tbody>
                                                    @foreach($graduates as $graduate)
                                                        <tr>
                                                            <td width="50">
                                                                <input type="checkbox"
                                                                       name="students[]"
                                                                       value="{{ $graduate->student_id }}"
                                                                       checked
                                                                       data-department="{{ $departmentId }}">
                                                            </td>
                                                            <td>{{ $graduate->student->student_id }}</td>
                                                            <td>
                                                                <strong>{{ $graduate->name }}</strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-success">Graduated</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="graduationForm">Update Graduation Status</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            // Enhanced Select All functionality
            document.querySelectorAll('.select-all-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const departmentId = this.getAttribute('data-department');
                    const checkboxes = document.querySelectorAll(
                        `input[name="students[]"][data-department="${departmentId}"]:not(:checked)`
                    );

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                        checkbox.closest('tr').classList.add('table-success');
                    });
                });
            });

            // Visual feedback when checking/unchecking
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[name="students[]"]')) {
                    const row = e.target.closest('tr');
                    if (e.target.checked) {
                        row.classList.add('table-success');
                    } else {
                        row.classList.remove('table-success');
                    }
                }
            });
            </script>

            <style>
            .department-group {
                border: 1px solid #eee;
                border-radius: 5px;
                padding: 10px;
                margin-bottom: 20px;
            }
            .table-hover tr:hover {
                background-color: rgba(0,0,0,.02);
            }
            .table-success {
                background-color: rgba(40, 167, 69, 0.1);
            }
            </style>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmSemesterChange(newSemester) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Changing the semester will affect all enrollments and schedules.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745", // Green color
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, proceed!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector('input[name="semester"]').value = newSemester;
                        document.getElementById('semesterForm').submit();
                    }
                });
            }
        </script>



        @if ($currentSemester == 3)
        <div class="mt-4 text-center">
            <form id="promoteYearForm" action="{{ route('admin.incrementYear') }}" method="POST">
                @csrf
                <button type="button" class="btn btn-success btn-lg px-5 py-3 fw-bold" onclick="confirmPromotion()">
                    Promote Students to Next Year
                </button>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function confirmPromotion() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Promoting students will update their year level. This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745", // Green color
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, promote!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show the loading screen when promotion is confirmed
                        let loader = document.getElementById("loading-screen");
                        let message = document.getElementById("loading-message");
                        loader.style.display = "flex"; // Show the loader

                        // Optional: Set and cycle messages while loading
                        const messages = [
                            "Checking all students...",
                            "Handling all requirements...",
                            "Finishing changes..."
                        ];
                        let currentIndex = 0;

                        function cycleMessages() {
                            message.textContent = messages[currentIndex];
                            currentIndex = (currentIndex + 1) % messages.length;
                        }

                        // Start cycling messages every 3 seconds
                        setInterval(cycleMessages, 3000);
                        cycleMessages(); // Start immediately

                        // Submit the form after confirmation
                        document.getElementById('promoteYearForm').submit();
                    }
                });
            }
        </script>
        @endif
    </div>
</div>

<div class="d-flex justify-content-center align-items-center vh-50">
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%; border-radius: 12px;">
        <h4 class="text-center mb-3 fw-bold text-primary">Export Complete Student Data</h4>

        <form action="{{ route('export.full-data') }}" method="GET" class="needs-validation" novalidate>

            <!-- School Year Selection -->
            <div class="mb-4">
                <label for="schoolYear" class="form-label fw-semibold text-dark">Select School Year:</label>
                <select name="year" id="schoolYear" class="form-select" required>
                    @foreach (App\Models\Student::getAvailableYears() as $year)
                        <option value="{{ $year }}" {{ $loop->first ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Please select a school year.
                </div>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <span>This export will include three sheets: Students, Grades, and Graduates.</span>
            </div>

            <!-- Export Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success fw-bold">
                    <i class="fas fa-file-excel me-2"></i> Export Complete Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById("schoolYear").addEventListener("change", function () {
        document.getElementById("export-form").action = "{{ route('export.students', '') }}/" + this.value;
    });
</script>

@if (!\App\Models\Setting::first() || \App\Models\Setting::first()->current_school_year < 2025)
<div class="modal fade show" id="schoolYearModal" tabindex="-1" aria-labelledby="schoolYearModalLabel" aria-hidden="true" style="display: block; background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="schoolYearModalLabel">Set Current Year</h5>
            </div>
            <div class="modal-body text-center">
                <form action="{{ route('admin.settings.updateSchoolYear') }}" method="POST">
                    @csrf
                    <label for="current_school_year" class="fw-bold text-green">Enter School Year:</label>
                    <input type="number" name="current_school_year" class="form-control text-center fs-4 p-3" required>
                    <button type="submit" class="btn btn-success mt-3 w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var overlay = document.getElementById("overlay");
        overlay.style.display = "block"; // Show overlay to disable interactions
        document.body.style.overflow = "hidden"; // Prevent scrolling
    });
</script>




@endif

<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
    .modal {
        z-index: 1050;
    }
    .text-green {
        color: #28a745 !important;
        font-weight: bold;
    }
    .btn-success {
        background-color: #28a745 !important;
        border: none;
    }
    .btn-success:hover {
        background-color: #218838 !important;
    }

    /* Style for the graduation button */
    .graduation-btn {
        font-size: 18px; /* Enlarge the text */
        padding: 15px 30px; /* Increase padding for a bigger button */
        transition: transform 0.3s ease, background-color 0.3s ease; /* Smooth transition effect */
    }

    /* Hover effect - changes background color and adds scale */
    .graduation-btn:hover {
        background-color: #28a745; /* A subtle lighter green */
        transform: scale(1.1); /* Slightly enlarge the button on hover */
    }

    /* When the button is clicked, apply a "graduation" glow effect */
    .graduation-btn:active {
        box-shadow: 0 0 15px 5px rgba(0, 255, 0, 0.6); /* Green glowing effect */
        transform: scale(0.98); /* Slightly shrink on click for a pressed effect */
    }

</style>
@endsection
