@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<style>
    /* Modal Width to Fit Content */
 #viewStudentModal .modal-dialog {
     max-width: 95vw;
 }

 #viewStudentModal .modal-content {
     border-radius: 12px;
     overflow: hidden;
     background-color: #fff;
 }

 #viewStudentModal .modal-body {
     max-height: 80vh;
     overflow-y: auto;
     padding: 20px;
     background-color: #f8f9fa;
 }

 /* Year Level Heading with Divider */
 .year-level-heading {
     font-size: 24px;
     font-weight: bold;
     color: #333;
     margin-top: 25px;
     padding-bottom: 10px;
     border-bottom: 3px solid #16C47F;
 }

 /* Semester Container (Flexbox for 2-Column Layout) */
 .semester-container {
     display: grid;
     grid-template-columns: repeat(2, 1fr); /* 2 equal columns */
     gap: 20px;
     margin-top: 15px;
     justify-content: space-between;
 }

 /* Semester Card */
 .semester-card {
     background: #fff;
     border: 1px solid #ddd;
     border-radius: 12px;
     overflow: hidden;
     box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
     transition: transform 0.3s ease-in-out;
 }

 .semester-card:hover {
     transform: translateY(-5px);
     box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
 }

 /* Semester Header */
 .semester-header {
     background: #16C47F;
     color: white;
     padding: 12px 18px;
     font-size: 18px;
     font-weight: bold;
 }

 /* Custom Table for Each Semester */
 .custom-table {
     width: 100%;
     border-collapse: collapse;
 }

 .custom-table th,
 .custom-table td {
     padding: 12px;
     text-align: center;
     border-bottom: 1px solid #ddd;
 }

 .custom-table thead {
     background: #f1f1f1;
 }

 .custom-table tbody tr:hover {
     background: #f9f9f9;
 }

 /* Grade Input Field */
 .grade-input {
     width: 80px;
     padding: 6px;
     font-size: 1rem;
     text-align: center;
     border: 2px solid #ddd;
     border-radius: 6px;
 }

 .grade-input:focus {
     border-color: #42A5F5;
     outline: none;
     box-shadow: 0 0 5px rgba(66, 165, 245, 0.5);
 }

 /* Highlight Missing Grades */
 .table-danger td {
     background: #ffebee !important;
     color: #d32f2f;
 }

 /* Save Button with Gradient Effect */
 .custom-save-btn {
     background: linear-gradient(135deg, #16C47F, #42A5F5);
     border: none;
     padding: 12px 24px;
     font-weight: bold;
     color: white;
     border-radius: 50px;
     transition: background-color 0.3s ease;
 }

 .custom-save-btn:hover {
     background: linear-gradient(135deg, #42A5F5, #16C47F);
 }

 /* Summer Class Center Alignment */
 .semester-card.summer {
     grid-column: span 2;
     margin: 0 auto;
     width: 50%;
 }

 /* Responsive for Small Screens */
 @media (max-width: 768px) {
     .semester-container {
         grid-template-columns: 1fr; /* Stack on small screens */
     }

     .semester-card.summer {
         grid-column: span 1;
         width: 100%;
     }
 }
</style>
<div class="container-fluid">
    {{-- modified march 24 --}}
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Students</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-warning" id="viewIncompleteBtn" data-bs-toggle="modal" data-bs-target="#incompleteModal">
                View Incomplete/Failed Students
            </button>
            <button class="btn manage-enrollment bg-primary" data-bs-toggle="modal" data-bs-target="#enrollmentModal">
                Manage Enrollment
            </button>
            <button class="btn add-student bg-success text-white" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                Add Student
            </button>
        </div>
    </div>

    <!-- Modal for Adding Student -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addStudentModalLabel">
                        <i class="bi bi-plus-square"></i> Add New Student
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <h4>Student's Information</h4>
                            <!-- Student ID Field -->
                            <div class="col-md-6">
                                <label for="student_id" class="form-label fw-semibold">Student ID</label>
                                <input type="text" class="form-control shadow-sm" id="student_id" name="student_id" value="{{ old('student_id') }}" required>

                                {{-- Real-time warning --}}
                                <small id="studentIdFeedback" class="text-danger d-none">
                                    🚫 This Student ID is already taken.
                                </small>

                                {{-- Server-side validation fallback --}}
                                @error('student_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                {{-- Show last student ID --}}
                                @if(!empty($lastStudentId))
                                    <small class="text-muted d-block mt-1">
                                        ✅ <strong>{{ $lastStudentId }}</strong> is the latest student ID in the record.
                                    </small>
                                @endif
                            </div>


                            <!-- Last Name Field -->
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-semibold">Last Name</label>
                                <input type="text" class="form-control shadow-sm" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- First Name Field -->
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-semibold">First Name</label>
                                <input type="text" class="form-control shadow-sm" id="first_name" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" required>
                                @error('first_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Middle Name Field -->
                            <div class="col-md-6">
                                <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                                <input type="text" class="form-control shadow-sm" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" required>
                                @error('middle_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Suffix -->
                            <div class="col-md-6">
                                <label for="suffix" class="form-label fw-semibold">Suffix</label>
                                <input type="text" class="form-control shadow-sm" id="suffix" name="suffix" value="{{ old('suffix') }}" placeholder="Jr.,Sr.,II,III, etc.">
                                @error('suffix')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <script>
                                const nameFields = ['last_name', 'first_name', 'middle_name', 'suffix'];
                                let debounceTimer;

                                nameFields.forEach(field => {
                                    document.getElementById(field).addEventListener('input', () => {
                                        clearTimeout(debounceTimer);
                                        debounceTimer = setTimeout(checkDuplicateName, 300); // adjust delay as needed
                                    });
                                });

                                async function checkDuplicateName() {
                                    const lastName = document.getElementById('last_name').value.trim();
                                    const firstName = document.getElementById('first_name').value.trim();
                                    const middleName = document.getElementById('middle_name').value.trim();
                                    const suffix = document.getElementById('suffix').value.trim();

                                    if (lastName && firstName && middleName) {
                                        try {
                                            const response = await fetch("{{ route('students.checkDuplicateName') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    last_name: lastName,
                                                    first_name: firstName,
                                                    middle_name: middleName,
                                                    suffix: suffix
                                                })
                                            });

                                            const data = await response.json();

                                            let alertContainer = document.getElementById('name-duplicate-alert');

                                            if (!alertContainer) {
                                                alertContainer = document.createElement('div');
                                                alertContainer.id = 'name-duplicate-alert';
                                                alertContainer.classList.add('col-md-12', 'mt-2');
                                                document.querySelector('.row').prepend(alertContainer);
                                            }

                                            alertContainer.innerHTML = data.exists
                                                ? '<div class="alert alert-warning">Duplicate name is found in the record</div>'
                                                : '';
                                        } catch (error) {
                                            console.error("Error checking name:", error);
                                        }
                                    }
                                }
                            </script>


                            <div class="col-md-6">
                                <label for="age" class="form-label fw-semibold">Age</label>
                                <input type="text" class="form-control shadow-sm" id="age" name="age" placeholder="Only numeric Input" value="{{ old('age') }}" required>
                                @error('age')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sex" class="form-label fw-semibold">Gender</label>
                                <select class="form-control shadow-sm" id="sex" name="sex" required>
                                    <option value="" disabled {{ old('sex') ? '' : 'selected' }}>Select Gender</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('sex')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Date of Birth Field -->
                            <div class="col-md-6">
                                <label for="bdate" class="form-label fw-semibold">Date of Birth</label>
                                <input type="date" class="form-control shadow-sm" id="bdate" name="bdate" value="{{ old('bdate') }}" required>
                                @error('bdate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Place of Birth Field -->
                            <div class="col-md-6">
                                <label for="bplace" class="form-label fw-semibold">Place of Birth</label>
                                <input type="text" class="form-control shadow-sm" id="bplace" name="bplace" value="{{ old('bplace') }}" required>
                                @error('bplace')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Civil Status Field -->
                            <div class="col-md-6">
                                <label for="civil_status" class="form-label fw-semibold">Civil Status</label>
                                <select class="form-control shadow-sm" id="civil_status" name="civil_status" required>
                                    <option value="" disabled {{ old('civil_status') ? '' : 'selected' }}>Select Status</option>
                                    <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Divorced" {{ old('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                                @error('civil_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Phone Field -->
                            <div class="col-md-6">
                                <label for="cell_no" class="form-label fw-semibold">Phone Number</label>
                                <input type="text" class="form-control shadow-sm" id="cell_no" name="cell_no" placeholder="09XXXXXXXXX" value="{{ old('cell_no') }}" required>
                                @error('cell_no')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control shadow-sm" id="email" name="email" autocomplete="email" placeholder="example@gmail.com" value="{{ old('email') }}" required>

                                {{-- Real-time email feedback --}}
                                <small id="emailFeedback" class="text-danger d-none">
                                    🚫 This email is already taken.
                                </small>

                                {{-- Server-side validation fallback --}}
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <!-- Password Field -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password</label>

                                <div class="input-group">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control shadow-sm"
                                           placeholder="At least 8 characters, 1 number, 1 special character"
                                           required
                                           minlength="8"
                                           pattern="^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                                           title="Password must be at least 8 characters long, include at least one number and one special character.">

                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                                {{-- Real-time error message --}}
                                <small id="passwordFeedback" class="text-danger d-none">
                                    🚫 Password must be at least 8 characters, include 1 number and 1 special character.
                                </small>

                                {{-- Server-side fallback --}}
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Address Fields -->
                            <div class="row g-2 mt-2">
                                <h4>Address Fields</h4>

                                <!-- Address Input -->
                                <div class="col-md-6">
                                    <label for="address" class="form-label fw-semibold">House No., St., Village, Subd., etc.</label>
                                    <input type="text" class="form-control shadow-sm" id="address" name="address" autocomplete="street-address" value="{{ old('address') }}">
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Region Dropdown -->
                                <div class="col-md-6">
                                    <label for="region" class="form-label fw-semibold">Region</label>
                                    <select id="region" name="region" class="form-control shadow-sm" required>
                                        <option value="">Select Region</option>
                                        @if(old('region'))
                                            <option value="{{ old('region') }}" selected>{{ old('region') }}</option>
                                        @endif
                                    </select>
                                    @error('region')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Province Dropdown -->
                                <div class="col-md-6">
                                    <label for="province" class="form-label fw-semibold">Province</label>
                                    <select id="province" name="province" class="form-control shadow-sm" required>
                                        <option value="">Select Province</option>
                                        @if(old('province'))
                                            <option value="{{ old('province') }}" selected>{{ old('province') }}</option>
                                        @endif
                                    </select>
                                    @error('province')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- City/Municipality Dropdown -->
                                <div class="col-md-6">
                                    <label for="city" class="form-label fw-semibold">City/Municipality</label>
                                    <select id="city" name="city" class="form-control shadow-sm" required>
                                        <option value="">Select City/Municipality</option>
                                        @if(old('city'))
                                            <option value="{{ old('city') }}" selected>{{ old('city') }}</option>
                                        @endif
                                    </select>
                                    @error('city')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Barangay Dropdown -->
                                <div class="col-md-6">
                                    <label for="barangay" class="form-label fw-semibold">Barangay</label>
                                    <select id="barangay" name="barangay" class="form-control shadow-sm" required>
                                        <option value="">Select Barangay</option>
                                        @if(old('barangay'))
                                            <option value="{{ old('barangay') }}" selected>{{ old('barangay') }}</option>
                                        @endif
                                    </select>
                                    @error('barangay')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <h4>Parent's Information</h4>

                            <!-- Father's Information Field -->
                            <div class="col-md-6">
                                <h5>Father</h5>

                                <label for="father_last_name" class="form-label fw-semibold">Last Name</label>
                                <input type="text" class="form-control shadow-sm" id="father_last_name" name="father_last_name" value="{{ old('father_last_name') }}" required>
                                @error('father_last_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="father_first_name" class="form-label fw-semibold">First Name</label>
                                <input type="text" class="form-control shadow-sm" id="father_first_name" name="father_first_name" value="{{ old('father_first_name') }}" required>
                                @error('father_first_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="father_middle_name" class="form-label fw-semibold">Middle Name</label>
                                <input type="text" class="form-control shadow-sm" id="father_middle_name" name="father_middle_name" value="{{ old('father_middle_name') }}" required>
                                @error('father_middle_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Mother's Information Field -->
                            <div class="col-md-6">
                                <h5>Mother's Maiden Name</h5>

                                <label for="mother_last_name" class="form-label fw-semibold">Last Name</label>
                                <input type="text" class="form-control shadow-sm" id="mother_last_name" name="mother_last_name" value="{{ old('mother_last_name') }}" required>
                                @error('mother_last_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="mother_first_name" class="form-label fw-semibold">First Name</label>
                                <input type="text" class="form-control shadow-sm" id="mother_first_name" name="mother_first_name" value="{{ old('mother_first_name') }}" required>
                                @error('mother_first_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="mother_middle_name" class="form-label fw-semibold">Middle Name</label>
                                <input type="text" class="form-control shadow-sm" id="mother_middle_name" name="mother_middle_name" value="{{ old('mother_middle_name') }}" required>
                                @error('mother_middle_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <h4>Educational Background</h4>

                            <!-- Elementary -->
                            <div class="col-md-6">
                                <h5>Elementary</h5>

                                <label for="elem_school_name" class="form-label fw-semibold">School Name</label>
                                <input type="text" class="form-control shadow-sm" id="elem_school_name" name="elem_school_name" value="{{ old('elem_school_name') }}" required>
                                @error('elem_school_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="elem_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                <input type="text" class="form-control shadow-sm" id="elem_grad_year" name="elem_grad_year" placeholder="Valid input ex. 2XXX" value="{{ old('elem_grad_year') }}" required>
                                @error('elem_grad_year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- High School/Secondary -->
                            <div class="col-md-6">
                                <h5>Secondary</h5>

                                <label for="hs_school_name" class="form-label fw-semibold">School Name</label>
                                <input type="text" class="form-control shadow-sm" id="hs_school_name" name="hs_school_name" value="{{ old('hs_school_name') }}" required>
                                @error('hs_school_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="hs_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                <input type="text" class="form-control shadow-sm" id="hs_grad_year" name="hs_grad_year" placeholder="Valid input ex. 2XXX" value="{{ old('hs_grad_year') }}" required>
                                @error('hs_grad_year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Tertiary -->
                            <div class="col-md-6">
                                <h5>Tertiary</h5>

                                <label for="tertiary_school_name" class="form-label fw-semibold">School Name</label>
                                <input type="text" class="form-control shadow-sm" id="tertiary_school_name" name="tertiary_school_name" value="{{ old('tertiary_school_name') }}" required>
                                @error('tertiary_school_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label for="tertiary_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                <input type="text" class="form-control shadow-sm" id="tertiary_grad_year" name="tertiary_grad_year" placeholder="Valid input ex. 2XXX" value="{{ old('tertiary_grad_year') }}">
                                @error('tertiary_grad_year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <h4>Department</h4>
                            <div class="form-group">
                                <label for="department_id" class="form-label">Department</label>
                                <select name="department_id" id="department_id" class="form-control" required>
                                    <option value="">Select a Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="year_level" class="form-label">Year Level</label>
                                <select class="form-control" id="year_level" name="year_level">
                                    <option value="">Select Year</option>
                                    <option value="1" {{ old('year_level') == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year_level') == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year_level') == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year_level') == '4' ? 'selected' : '' }}>4th Year</option>
                                </select>
                                @error('year_level')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label for="section" class="form-label">Section</label>
                                <select class="form-control" id="section" name="section">
                                    <option value="" disabled selected>Select Section</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div> --}}

                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-control" id="semester" name="semester">
                                    <option value="">Select Semester</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Summer</option>
                                </select>
                                @error('semester')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success bg-success">
                                <i class="bi bi-save"></i> Add Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any() || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addStudentModal = new bootstrap.Modal(document.getElementById('addStudentModal'));
            addStudentModal.show();
        });
    </script>
@endif


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch regions from PSGC API
            fetch('/api/regions')
                .then(response => response.json())
                .then(data => {
                    const regionSelect = document.getElementById('region');
                    data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.code;
                        option.text = region.name;
                        regionSelect.appendChild(option);
                    });
                });

            // Fetch provinces or NCR cities when a region is selected
            document.getElementById('region').addEventListener('change', function () {
                const regionCode = this.value;
                const provinceSelect = document.getElementById('province');
                const citySelect = document.getElementById('city');
                const barangaySelect = document.getElementById('barangay');

                // Reset all selects
                provinceSelect.innerHTML = '<option value="">Select Province</option>';
                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                if (regionCode === '130000000') {
                    // NCR case: disable province, fetch cities-municipalities directly
                    provinceSelect.disabled = true;

                    fetch(`/api/region-cities-municipalities/${regionCode}`)
                        .then(response => response.json())
                        .then(data => {
                            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                            data.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.code;
                                option.text = city.name;
                                citySelect.appendChild(option);
                            });
                        });
                } else {
                    // Other regions: enable and fetch provinces
                    provinceSelect.disabled = false;

                    fetch(`/api/provinces/${regionCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.code;
                                option.text = province.name;
                                provinceSelect.appendChild(option);
                            });
                        });
                }
            });

            // Fetch cities & municipalities when a province is selected
            document.getElementById('province').addEventListener('change', function () {
                const provinceCode = this.value;
                const citySelect = document.getElementById('city');
                const barangaySelect = document.getElementById('barangay');

                citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                if (provinceCode) {
                    fetch(`/api/cities-municipalities/${provinceCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(entry => {
                                const option = document.createElement('option');
                                option.value = entry.code;
                                option.text = entry.name;
                                citySelect.appendChild(option);
                            });
                        });
                }
            });

            // Fetch barangays when a city or municipality is selected
            document.getElementById('city').addEventListener('change', function () {
                const cityOrMunicipalityCode = this.value;
                const barangaySelect = document.getElementById('barangay');

                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                if (cityOrMunicipalityCode) {
                    fetch(`/api/barangays/${cityOrMunicipalityCode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(barangay => {
                                const option = document.createElement('option');
                                option.value = barangay.code;
                                option.text = barangay.name;
                                barangaySelect.appendChild(option);
                            });
                        });
                }
            });
        });
    </script>


    {{-- picking semester logic --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentSemester = @json($currentSemester);

            if (currentSemester === 3) {
                // Disable buttons and modal triggers if semester is 3
                let confirmSpecialClassButton = document.getElementById("confirmSpecialClass");
                let specialClassModalTrigger = document.querySelector('[data-bs-target="#confirmSpecialClassModal"]');
                let viewIncompleteButton = document.getElementById("viewIncompleteBtn");

                if (confirmSpecialClassButton) confirmSpecialClassButton.disabled = true;
                if (specialClassModalTrigger) specialClassModalTrigger.setAttribute("disabled", "disabled");
                if (viewIncompleteButton) {
                    viewIncompleteButton.disabled = true;
                    viewIncompleteButton.removeAttribute("data-bs-toggle"); // Prevents modal from opening
                    viewIncompleteButton.removeAttribute("data-bs-target");
                }
            }
        });
    </script> --}}



    <!-- Success Toast Notification -->
@if (session('success'))
<div id="successToast" class="position-fixed top-0 end-0 m-3" style="
    z-index: 1050;
    min-width: 300px;
    background-color: #28A745;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: bold;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease-in-out;
">
    {{ session('success') }}
</div>

<!-- JavaScript for animation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successToast = document.getElementById('successToast');

        // Show with ease-in effect
        setTimeout(() => {
            successToast.style.opacity = '1';
            successToast.style.transform = 'translateX(0)';
        }, 100);

        // Hide with ease-out effect after 3 seconds
        setTimeout(() => {
            successToast.style.opacity = '0';
            successToast.style.transform = 'translateX(100%)';
        }, 3000);
    });
</script>
@endif


<!-- Delete Toast Notification -->
@if (session('deleted'))
    <div id="deleteToast" class="position-fixed top-0 end-0 m-3" style="
        z-index: 1050;
        min-width: 300px;
        background-color: #DC3545;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: bold;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.5s ease-in-out;
    ">
        {{ session('deleted') }}
    </div>

    <!-- JavaScript for animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteToast = document.getElementById('deleteToast');

            // Show with ease-in effect
            setTimeout(() => {
                deleteToast.style.opacity = '1';
                deleteToast.style.transform = 'translateX(0)';
            }, 100);

            // Hide with ease-out effect after 3 seconds
            setTimeout(() => {
                deleteToast.style.opacity = '0';
                deleteToast.style.transform = 'translateX(100%)';
            }, 3000);
        });
    </script>
@endif



    {{-- <!-- Search Form: Positioned to the right with modern aesthetics -->
    <div class="row mb-2">
        <div class="col-12 d-flex justify-content-end">
            <form action="{{ route('students.index') }}" method="GET" class="d-flex w-auto">
                <div class="input-group">
                    <input type="text" name="search" class="form-control shadow-lg rounded-start border-light" placeholder="Search by name or student ID" value="{{ request('search') }}">
                    <button type="submit" class="btn  rounded-end shadow-lg" style="background-color: #16C47F; color: white; font-weight:600;">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Sharp-edged Search Form -->
<div class="row mb-4">
    <div class="col-12">
        <form action="{{ route('students.index') }}" method="GET" id="custom-search-form" class="d-flex w-100 shadow-sm"
            style="background: #F4F4F4; padding: 8px; gap: 10px; border: 1px solid #ddd;">

            <!-- Search Input -->
            <input type="text" name="search" id="search-input" class="form-control border-0"
                placeholder="Search by name or student ID..."
                value="{{ request('search') }}"
                style="background: #fff; font-size: 0.9rem; padding: 10px;">


            <!-- Search Button -->
            <button type="submit" class="btn text-white"
                style="background-color: #16C47F; font-size: 0.9rem; padding: 10px 24px; border: none;">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

    <!-- Table displaying student records -->
    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto; border-bottom: 1px solid gray;">
                <table class="table table-striped table-hover align-middle" style="border-bottom: 1px solid rgb(176, 175, 175)">
                    <thead class="text-center" style="background-color: #16C47F; color: white; position: sticky; top: 0; z-index:1000;">
                        <tr>
                            <th style="padding: 15px; border-radius: 10px 0 0 0;">Student ID</th>
                            <th style="padding: 15px;">Last Name</th>
                            <th style="padding: 15px;">First Name</th>
                            <th style="padding: 15px;">Department</th>
                            <th style="padding: 15px;">Year Level</th>
                            <th style="padding: 15px;">Phone</th>
                            <th style="border-radius: 0 10px 0 0;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr class="text-center"
                        style="background-color: {{ $student->enrolled ? '#faf8f8' : 'rgba(255, 150, 150, 0.5)' }};
                               transition: all 0.3s;
                               color: black;">
                            <td style="padding: 15px; font-weight: 600;">{{ $student->student_id }}</td>
                            <td style="padding: 15px; font-weight: 600;">{{ $student->last_name }}</td>
                            <td style="padding: 15px; font-weight: 600;">{{ $student->first_name }}</td>
                            <td style="padding: 15px; font-weight: 600;">{{ $student->department->name }}</td>
                            <td style="padding: 15px; font-weight: 600;">
                                {{ $student->year_level }}
                                {{-- @if(!$student->regular && $student->year_level > 1)
                                    <span class="badge bg-danger">Irregular</span>
                                @endif --}}
                            </td>
                            <td style="padding: 15px; font-weight: 600;">{{ $student->cell_no }}</td>
                            <td>
                            <!-- View Button -->
                            <button class="btn btn-warning btn-sm"
                            style="background-color: #06a900; color: white; border-radius: 8px;"
                            data-bs-toggle="modal"
                            data-bs-target="#viewStudentModal{{ $student->student_id }}">
                                <i class="bi bi-eye"></i> View Grades
                            </button>
                             <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" style="background-color: #FFC107; color: white; border-radius: 8px;"
                            data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->student_id }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>

                                            <!-- Button for shifting department -->
                                            {{-- <button type="button" class="btn btn-primary" id="shiftDepartmentBtn{{ $student->student_id }}" data-bs-toggle="modal" data-bs-target="#shiftDepartmentModal{{ $student->student_id }}">
                                                Shift Department
                                            </button> --}}

                                            <!-- Modal for shifting department (unique per student) -->
                                            <div class="modal fade" id="shiftDepartmentModal{{ $student->student_id }}" tabindex="-1" aria-labelledby="shiftDepartmentModalLabel{{ $student->student_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="shiftDepartmentModalLabel{{ $student->student_id }}">Shift Department</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Form for selecting new department -->
                                                            <form id="shiftDepartmentForm{{ $student->student_id }}">
                                                                <div class="mb-3">
                                                                    <label for="newDepartment{{ $student->student_id }}" class="form-label">
                                                                        Current Department:
                                                                        <span class="text-muted">
                                                                            {{ $departments->firstWhere('id', $student->department_id)?->name ?? 'Unknown' }}
                                                                        </span>
                                                                    </label>
                                                                    <select class="form-select" id="newDepartment{{ $student->student_id }}" required>
                                                                        @foreach($departments as $department)
                                                                            <option value="{{ $department->id }}"
                                                                                @if($department->id == $student->department_id) disabled selected style="color: gray;" @endif>
                                                                                {{ $department->name }}
                                                                                @if($department->id == $student->department_id) (Current) @endif
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <button type="submit" class="btn btn-success">Shift Department</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                // Use a dynamic approach to handle each student individually
                                                document.getElementById('shiftDepartmentForm{{ $student->student_id }}').addEventListener('submit', function(event) {
                                                    event.preventDefault();

                                                    // Get the student ID dynamically from the button's data attribute
                                                    const studentId = "{{ $student->student_id }}";
                                                    const newDepartmentId = document.getElementById('newDepartment{{ $student->student_id }}').value;

                                                    fetch(`/admin/students/${studentId}/shift-department/${newDepartmentId}`, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        },
                                                        body: JSON.stringify({
                                                            student_id: studentId,
                                                            new_department_id: newDepartmentId
                                                        })
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        alert(data.message);
                                                        location.reload();  // Refresh the page
                                                    })
                                                    .catch(error => {
                                                        console.error("Error shifting department:", error);
                                                        alert('An error occurred while shifting the department.');
                                                    });
                                                });
                                            </script>

                            <!-- Delete Button -->
                            {{-- <form action="{{ route('students.destroy', $student->student_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                            </form> --}}

                            {{-- Enroll/Unenroll Students button  --}}
                            {{-- <button class="btn btn-sm toggle-enrollment {{ $student->enrolled ? 'btn-danger' : 'btn-success' }}"
                                    data-student-id="{{ $student->student_id }}">
                                <i class="bi {{ $student->enrolled ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                <span>{{ $student->enrolled ? 'Unenroll' : 'Enroll' }}</span>
                            </button> --}}

                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                            <script>
                                $(document).ready(function () {
                                    $(".toggle-enrollment").click(function (event) {
                                        event.preventDefault();

                                        let button = $(this);
                                        let studentId = button.data("student-id");

                                        button.prop("disabled", true); // Disable button to prevent multiple clicks

                                        $.ajax({
                                            url: `/students/${studentId}/toggle-enrollment`,
                                            type: "PATCH",
                                            data: { _token: "{{ csrf_token() }}" },
                                            success: function (response) {
                                                if (response.success) {
                                                    button.toggleClass("btn-success btn-danger");
                                                    button.find("i").toggleClass("bi-check-circle bi-x-circle");
                                                    button.find("span").text(response.enrolled ? "Unenroll" : "Enroll");

                                                    // Show SweetAlert2 notification
                                                    Swal.fire({
                                                        position: "top-end",
                                                        icon: "success",
                                                        title: `Student ${studentId} has been ${response.enrolled ? "enrolled" : "unenrolled"}.`,
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        icon: "error",
                                                        title: "Oops...",
                                                        text: "Failed to update enrollment status."
                                                    });
                                                }
                                            },
                                            error: function (xhr) {
                                                console.error(xhr.responseText);
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Error",
                                                    text: "Something went wrong. Try again."
                                                });
                                            },
                                            complete: function () {
                                                button.prop("disabled", false); // Re-enable button after request completes
                                            }
                                        });
                                    });
                                });
                            </script>


@php
// TO SHOW EVERY SUBJECT/RETAKES - multiple subjects per semester
// $groupedSubjects = $student->subjects
//     ->sortBy(['pivot.year_level', 'pivot.semester'])
//     ->groupBy(['pivot.year_level', 'pivot.semester']);

// FOR UNIQUE SPECIFIC SUBJECTS ONLY
    // ->unique('id')  // Ensure unique subjects based on 'id'
    $groupedSubjects = $student->subjects
    // ->unique('id')  // Ensure unique subjects based on 'id'
    ->sortBy(['pivot.year_level', 'pivot.semester'])
    ->groupBy(['pivot.year_level', 'pivot.semester']);
@endphp

<div class="modal fade" id="viewStudentModal{{ $student->student_id }}" tabindex="-1"
 aria-labelledby="viewStudentModalLabel{{ $student->student_id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">  <!-- Increased width -->
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #42A5F5, #16C47F);">
                {{-- <h5 class="modal-title fw-bold" id="viewStudentModalLabel{{ $student->student_id }}">
                    {{ $student->first_name }} {{ $student->last_name }} - Grades
                </h5> --}}
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <h2 class="modal-title fw-bold">
                {{ $student->last_name }}, {{ $student->first_name }}  - {{ $student->department->name ?? 'Department N/A' }} {{ $student->year_level }}
            </h2>
            <div class="modal-body">
                <form action="{{ route('admin.update.student.grades', $student->student_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="d-flex flex-column-reverse align-items-center gap-2 mt-2 card shadow-lg p-2">
                        @if($student->year_level != 4)
                        <button
                        type="button"
                        class="btn btn-success promote-btn"
                        data-student-id="{{ $student->student_id }}"
                        data-bs-dismiss="modal"
                        data-bs-toggle="modal"
                        data-bs-target="#promoteStudentModal">
                        Promote Student
                      </button>

                        @endif
                        <button class="btn btn-sm btn-outline-success recheck-btn" data-student-id="{{ $student->student_id }}">
                            <i class="fas fa-sync-alt"></i> Recheck Subjects
                        </button>
                    </div>
                    @forelse ($groupedSubjects as $yearLevel => $semesters)
                        <h4 class="fw-bold text-primary mt-3">Year Level: {{ $yearLevel }}</h4>

                        @php
                            $semesterCount = 0;
                            $seenSubjects = [];
                        @endphp

                        @foreach ($semesters as $semester => $subjects)
                            @if ($semesterCount % 2 == 0) <!-- Open row every 2 semesters -->
                            <div class="semester-container">
                            @endif

                            <!-- Semester Card: Dynamically place each semester -->
                            <div class="semester-card {{ $semester == 3 ? 'summer' : ''}}">
                                <div class="semester-header">
                                    @if($semester == 3)
                                        Summer
                                    @else
                                        Semester {{ $semester }}
                                    @endif
                                </div>
                                <div class="table-responsive">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Make sure this is declared ONCE per modal
                                                if (!isset($allSeenSubjects)) {
                                                    $allSeenSubjects = [];
                                                }
                                            @endphp
                                            {{-- Loop through each subject --}}
                                            @foreach ($subjects as $subject)
                                            @php
                                                $belongsToStudentDepartment = $subject->department_id == $student->department_id;

                                                if (!$belongsToStudentDepartment) {
                                                    continue;
                                                }

                                                $grade = $subject->pivot->grade ?? null;

                                                $highlightRed = is_null($grade) &&
                                                    ($yearLevel < $student->year_level ||
                                                    ($yearLevel == $student->year_level && $semester < $student->semester));

                                                // Tracking if we've seen this subject before
                                                $subjectId = $subject->id;
                                                $isRetake = isset($allSeenSubjects[$subjectId]);

                                                // Mark it as seen
                                                $allSeenSubjects[$subjectId] = true;
                                            @endphp
                                                <tr class="{{ $highlightRed ? 'table-danger' : '' }}">
                                                    <td>{{ $subject->code }}</td>
                                                    <td>
                                                        {{ $subject->name }}
                                                        @if($isRetake)
                                                            <span style="background: orange; color: black; padding: 2px 6px;">Retake</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="number" name="subjects[{{ $subject->id }}][grade]"
                                                            class="grade-input {{ isset($grade) && $grade < 75 ? 'border-danger' : '' }}"
                                                            value="{{ $grade }}" placeholder="Enter grade" step="0.01">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @php
                                $semesterCount++;
                            @endphp

                            @if ($semesterCount % 2 == 0 || $loop->last)
                            </div> <!-- Close row after 2 semesters or on the last semester -->
                            @endif
                        @endforeach

                @empty
                    <p class="text-muted text-center">No grades available.</p>
                @endforelse

                    <div class="text-end">
                        <button type="submit" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">Save Grades</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Modal for confirmation -->
<div class="modal fade" id="promoteStudentModal" tabindex="-1" role="dialog" aria-labelledby="promoteStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promoteStudentModalLabel">Confirm Promotion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to promote this student?<br>
                <span class="text-danger fw-bold">!!! YOU WON'T BE ABLE TO UNDO THIS !!!</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmPromoteBtn">Confirm Promotion</button>
            </div>
        </div>
    </div>
</div>

<!-- Script to handle promotion action -->
<script>
    let selectedStudentId = null;

    $(document).on('click', '.promote-btn', function () {
      selectedStudentId = $(this).data('student-id');
    });

    $('#confirmPromoteBtn').click(function () {
      if (!selectedStudentId) return;

      $.ajax({
        url: '{{ route("promote.student", ":studentId") }}'.replace(':studentId', selectedStudentId),
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function (response) {
          alert('Student promoted successfully!');
          $('#promoteStudentModal').modal('hide');
          location.reload();
        },
        error: function () {
          alert('An error occurred while promoting the student.');
          $('#promoteStudentModal').modal('hide');
        }
      });
    });
  </script>

                        <!-- Edit Modal for each student -->
                        <div class="modal fade" id="editStudentModal{{ $student->student_id }}" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content shadow-lg border-0">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="editStudentModalLabel">
                                            <i class="bi bi-pencil-square"></i> Edit Student Details
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $editRegion = $student->region;
                                            $editProvince = $student->province;
                                            $editCity = $student->city;
                                            $editBarangay = $student->barangay;
                                        @endphp
                                        <form action="{{ route('students.update', $student->student_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                <h4>Student's Information</h4>
                                                <!-- Student ID Field -->
                                                <div class="col-md-6">
                                                    <label for="student_id" class="form-label fw-semibold">Student ID</label>
                                                    <input type="text" class="form-control shadow-sm" id="student_id" name="student_id" value="{{ $student->student_id }}" required>
                                                </div>

                                                <!-- Last Name Field -->
                                                <div class="col-md-6">
                                                    <label for="last_name" class="form-label fw-semibold">Last Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="last_name" name="last_name" value="{{ $student->last_name }}" required>
                                                </div>
                                                <!-- First Name Field -->
                                                <div class="col-md-6">
                                                    <label for="first_name" class="form-label fw-semibold">First Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="first_name" name="first_name" value="{{ $student->first_name }}" required>
                                                </div>
                                                <!-- Middle Name Field -->
                                                <div class="col-md-6">
                                                    <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="middle_name" name="middle_name" value="{{ $student->middle_name }}" required>
                                                </div>
                                                <!-- Suffix -->
                                                <div class="col-md-6">
                                                    <label for="suffix" class="form-label fw-semibold">Suffix</label>
                                                    <input type="text" class="form-control shadow-sm" id="suffix" name="suffix" value="{{ $student->suffix }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="age" class="form-label fw-semibold">Age</label>
                                                    <input type="text" class="form-control shadow-sm" id="age" name="age" value="{{ $student->age }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sex" class="form-label fw-semibold">Gender</label>
                                                    <select class="form-control shadow-sm" id="sex" name="sex" required>
                                                        <option value="Male" {{ $student->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $student->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                                <!-- Date of Birth Field -->
                                                <div class="col-md-6">
                                                    <label for="bdate" class="form-label fw-semibold">Date of Birth</label>
                                                    <input type="date" class="form-control shadow-sm" id="bdate" name="bdate" value="{{ $student->bdate ? \Carbon\Carbon::createFromFormat('m-d-Y', $student->bdate)->format('Y-m-d') : '' }}" required>
                                                </div>
                                                <!-- Place of Birth Field -->
                                                <div class="col-md-6">
                                                    <label for="bplace" class="form-label fw-semibold">Place of Birth</label>
                                                    <input type="text" class="form-control shadow-sm" id="bplace" name="bplace" value="{{ $student->bplace }}" required>
                                                </div>
                                                <!-- Civil Status Field -->
                                                <div class="col-md-6">
                                                    <label for="civil_status" class="form-label fw-semibold">Civil Status</label>
                                                    <select class="form-control shadow-sm" id="civil_status" name="civil_status" required>
                                                        <option value="Single" {{ $student->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ $student->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ $student->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Divorced" {{ $student->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                </div>

                                                <!-- Phone Field -->
                                                <div class="col-md-6">
                                                    <label for="cell_no" class="form-label fw-semibold">Phone Number</label>
                                                    <input type="text" class="form-control shadow-sm" id="cell_no" name="cell_no" value="{{ $student->cell_no }}" required>
                                                </div>
                                                <!-- Email Field -->
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                                    <input type="email" class="form-control shadow-sm" id="email" name="email" value="{{ $student->email }}" required>
                                                </div>
                                                {{-- Password Field --}}
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password">
                                                </div>
                                                <!-- Address Fields -->
                                                <div class="row g-2 mt-2">
                                                    <h4>Address Fields</h4>
                                                    <div class="col-md-6">
                                                        <label for="address" class="form-label">House No., St., Village, Subd., etc.</label>
                                                        <input type="text" class="form-control shadow-sm" id="address" name="address" value="{{ $student->address }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="region" class="form-label">Region</label>
                                                        <select id="edit-region-{{ $student->student_id }}" name="region" class="form-control shadow-sm">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="province" class="form-label">Province</label>
                                                        <select id="edit-province-{{ $student->student_id }}" name="province" class="form-control shadow-sm">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="city" class="form-label">City/Municipality</label>
                                                        <select id="edit-city-{{ $student->student_id }}" name="city" class="form-control shadow-sm">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="barangay" class="form-label">Barangay</label>
                                                        <select id="edit-barangay-{{ $student->student_id }}" name="barangay" class="form-control shadow-sm">
                                                        </select>
                                                    </div>

                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const studentId = "{{ $student->student_id }}";
                                                        const regionSelect = document.getElementById('edit-region-' + studentId);
                                                        const provinceSelect = document.getElementById('edit-province-' + studentId);
                                                        const citySelect = document.getElementById('edit-city-' + studentId);
                                                        const barangaySelect = document.getElementById('edit-barangay-' + studentId);

                                                        const selectedRegion = "{{ $editRegion }}";
                                                        const selectedProvince = "{{ $editProvince }}";
                                                        const selectedCity = "{{ $editCity }}";
                                                        const selectedBarangay = "{{ $editBarangay }}";

                                                        console.log('Student ID:', studentId);
                                                        console.log('Selected Region:', selectedRegion);
                                                        console.log('Selected Province:', selectedProvince);
                                                        console.log('Selected City:', selectedCity);
                                                        console.log('Selected Barangay:', selectedBarangay);

                                                        // Load regions
                                                        fetch('/api/regions')
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                console.log('Fetched Regions:', data);
                                                                data.forEach(region => {
                                                                    const option = document.createElement('option');
                                                                    option.value = region.code;
                                                                    option.text = region.name;
                                                                    if (region.code === selectedRegion) option.selected = true;
                                                                    regionSelect.appendChild(option);
                                                                });

                                                                // Load provinces and cities if applicable
                                                                if (selectedRegion && selectedRegion !== '130000000') {
                                                                    fetch('/api/provinces/' + selectedRegion)
                                                                        .then(response => response.json())
                                                                        .then(data => {
                                                                            console.log('Fetched Provinces for Region:', data);
                                                                            provinceSelect.innerHTML = '<option value="">Select Province</option>';
                                                                            data.forEach(province => {
                                                                                const option = document.createElement('option');
                                                                                option.value = province.code;
                                                                                option.text = province.name;
                                                                                if (province.code === selectedProvince) option.selected = true;
                                                                                provinceSelect.appendChild(option);
                                                                            });

                                                                            // Load cities
                                                                            if (selectedProvince) {
                                                                                fetch('/api/cities-municipalities/' + selectedProvince)
                                                                                    .then(response => response.json())
                                                                                    .then(data => {
                                                                                        console.log('Fetched Cities for Province:', data);
                                                                                        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                                                                                        data.forEach(city => {
                                                                                            const option = document.createElement('option');
                                                                                            option.value = city.code;
                                                                                            option.text = city.name;
                                                                                            if (city.code === selectedCity) option.selected = true;
                                                                                            citySelect.appendChild(option);
                                                                                        });

                                                                                        // Load barangays
                                                                                        if (selectedCity) {
                                                                                            fetch('/api/barangays/' + selectedCity)
                                                                                                .then(response => response.json())
                                                                                                .then(data => {
                                                                                                    console.log('Fetched Barangays for City:', data);
                                                                                                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                                                                                                    data.forEach(barangay => {
                                                                                                        const option = document.createElement('option');
                                                                                                        option.value = barangay.code;
                                                                                                        option.text = barangay.name;
                                                                                                        if (barangay.code === selectedBarangay) option.selected = true;
                                                                                                        barangaySelect.appendChild(option);
                                                                                                    });
                                                                                                });
                                                                                        }
                                                                                    });
                                                                            }
                                                                        });
                                                                } else if (selectedRegion === '130000000') {
                                                                    provinceSelect.disabled = true;
                                                                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';

                                                                    fetch('/api/region-cities-municipalities/' + selectedRegion)
                                                                        .then(response => response.json())
                                                                        .then(data => {
                                                                            console.log('Fetched Cities for Region (130000000):', data);
                                                                            data.forEach(city => {
                                                                                const option = document.createElement('option');
                                                                                option.value = city.code;
                                                                                option.text = city.name;
                                                                                if (city.code === selectedCity) option.selected = true;
                                                                                citySelect.appendChild(option);
                                                                            });

                                                                            if (selectedCity) {
                                                                                fetch('/api/barangays/' + selectedCity)
                                                                                    .then(response => response.json())
                                                                                    .then(data => {
                                                                                        console.log('Fetched Barangays for City:', data);
                                                                                        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                                                                                        data.forEach(barangay => {
                                                                                            const option = document.createElement('option');
                                                                                            option.value = barangay.code;
                                                                                            option.text = barangay.name;
                                                                                            if (barangay.code === selectedBarangay) option.selected = true;
                                                                                            barangaySelect.appendChild(option);
                                                                                        });
                                                                                    });
                                                                            }
                                                                        });
                                                                }
                                                            });

                                                        // Region change
                                                        regionSelect.addEventListener('change', function () {
                                                            console.log('Region changed to:', this.value);
                                                            const regionCode = this.value;
                                                            provinceSelect.innerHTML = '<option value="">Select Province</option>';
                                                            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                                                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                                                            if (regionCode === '130000000') {
                                                                provinceSelect.disabled = true;
                                                                fetch('/api/region-cities-municipalities/' + regionCode)
                                                                    .then(response => response.json())
                                                                    .then(data => {
                                                                        console.log('Fetched Cities for Region:', data);
                                                                        data.forEach(city => {
                                                                            const option = document.createElement('option');
                                                                            option.value = city.code;
                                                                            option.text = city.name;
                                                                            citySelect.appendChild(option);
                                                                        });
                                                                    });
                                                            } else {
                                                                provinceSelect.disabled = false;
                                                                fetch('/api/provinces/' + regionCode)
                                                                    .then(response => response.json())
                                                                    .then(data => {
                                                                        console.log('Fetched Provinces for Region:', data);
                                                                        data.forEach(province => {
                                                                            const option = document.createElement('option');
                                                                            option.value = province.code;
                                                                            option.text = province.name;
                                                                            provinceSelect.appendChild(option);
                                                                        });
                                                                    });
                                                            }
                                                        });

                                                        // Province change
                                                        provinceSelect.addEventListener('change', function () {
                                                            console.log('Province changed to:', this.value);
                                                            const provinceCode = this.value;
                                                            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                                                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                                                            if (provinceCode) {
                                                                fetch('/api/cities-municipalities/' + provinceCode)
                                                                    .then(response => response.json())
                                                                    .then(data => {
                                                                        console.log('Fetched Cities for Province:', data);
                                                                        data.forEach(city => {
                                                                            const option = document.createElement('option');
                                                                            option.value = city.code;
                                                                            option.text = city.name;
                                                                            citySelect.appendChild(option);
                                                                        });
                                                                    });
                                                            }
                                                        });

                                                        // City change
                                                        citySelect.addEventListener('change', function () {
                                                            console.log('City changed to:', this.value);
                                                            const cityCode = this.value;
                                                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

                                                            if (cityCode) {
                                                                fetch('/api/barangays/' + cityCode)
                                                                    .then(response => response.json())
                                                                    .then(data => {
                                                                        console.log('Fetched Barangays for City:', data);
                                                                        data.forEach(barangay => {
                                                                            const option = document.createElement('option');
                                                                            option.value = barangay.code;
                                                                            option.text = barangay.name;
                                                                            barangaySelect.appendChild(option);
                                                                        });
                                                                    });
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <h4>Parent's Information</h4>
                                                <!-- Father's Information Field -->
                                                <div class="col-md-6">
                                                    <h5>Father</h5>
                                                    <label for="father_last_name" class="form-label fw-semibold">Last Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="father_last_name" name="father_last_name" value="{{ $student->father_last_name }}" required>

                                                    <label for="father_first_name" class="form-label fw-semibold">First Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="father_first_name" name="father_first_name" value="{{ $student->father_first_name }}" required>

                                                    <label for="father_middle_name" class="form-label fw-semibold">Middle Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="father_middle_name" name="father_middle_name" value="{{ $student->father_middle_name }}" required>
                                                </div>
                                                <!-- Mother's Information Field -->
                                                <div class="col-md-6">
                                                    <h5>Mother's Maiden Name</h5>
                                                    <label for="mother_last_name" class="form-label fw-semibold">Last Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="mother_last_name" name="mother_last_name" value="{{ $student->mother_last_name }}" required>

                                                    <label for="mother_first_name" class="form-label fw-semibold">First Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="mother_first_name" name="mother_first_name" value="{{ $student->mother_first_name }}" required>

                                                    <label for="mother_middle_name" class="form-label fw-semibold">Middle Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="mother_middle_name" name="mother_middle_name" value="{{ $student->mother_middle_name }}" required>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-3">
                                                <h4>Educational Background</h4>
                                                <!-- Elementary -->
                                                <div class="col-md-6">
                                                    <h5>Elementary</h5>
                                                    <label for="elem_school_name" class="form-label fw-semibold">School Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="elem_school_name" name="elem_school_name" value="{{ $student->elem_school_name }}" required>

                                                    <label for="elem_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                                    <input type="text" class="form-control shadow-sm" id="elem_grad_year" name="elem_grad_year" value="{{ $student->elem_grad_year }}" required>
                                                </div>
                                                <!-- High School/Secondary -->
                                                <div class="col-md-6">
                                                    <h5>Secondary</h5>
                                                    <label for="hs_school_name" class="form-label fw-semibold">School Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="hs_school_name" name="hs_school_name" value="{{ $student->hs_school_name }}" required>

                                                    <label for="hs_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                                    <input type="text" class="form-control shadow-sm" id="hs_grad_year" name="hs_grad_year" value="{{ $student->hs_grad_year }}" required>
                                                </div>
                                                <!-- Tertiary -->
                                                <div class="col-md-6">
                                                    <h5>Tertiary</h5>
                                                    <label for="tertiary_school_name" class="form-label fw-semibold">School Name</label>
                                                    <input type="text" class="form-control shadow-sm" id="tertiary_school_name" name="tertiary_school_name" value="{{ $student->tertiary_school_name }}" required>

                                                    <label for="tertiary_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                                    <input type="text" class="form-control shadow-sm" id="tertiary_grad_year" name="tertiary_grad_year" value="{{ $student->tertiary_grad_year }}">
                                                </div>
                                            </div>
                                            <div class="mt-4 d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn" style="background-color:  #16C47F; color: white; font-weight:600;">
                                                    <i class="bi bi-save"></i> Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($errors->any() || session('error'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const editStudentModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
                                    editStudentModal.show();
                                });
                            </script>
                        @endif

                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination with custom styling --}}
@if ($students->hasPages())
<ul class="custom-pagination">
    {{-- Previous Page Link --}}
    @if ($students->onFirstPage())
        <li class="disabled"><span>«</span></li>
    @else
        <li><a href="{{ $students->previousPageUrl() }}" rel="prev">«</a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
        <li class="{{ $page == $students->currentPage() ? 'active' : '' }}">
            <a href="{{ $url }}">{{ $page }}</a>
        </li>
    @endforeach

    {{-- Next Page Link --}}
    @if ($students->hasMorePages())
        <li><a href="{{ $students->nextPageUrl() }}" rel="next">»</a></li>
    @else
        <li class="disabled"><span>»</span></li>
    @endif
</ul>
@endif
        </div>
    </div>

    {{-- <div class="col-md-6">
                                <label for="middle_name" class="form-label fw-semibold">Suffix</label>
                                <input type="text" class="form-control shadow-sm" id="middle_name" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" required>
                            </div> --}}

     {{-- <div class="mb-3">
                                <label for="section" class="form-label">Section</label>
                                <select class="form-control shadow-sm" id="sex" name="sex" required>
                                    <option value="" disabled selected>Select Section</option>
                                    <option value="A" {{ old('section', $student->section) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('section', $student->section) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('section', $student->section) == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('section', $student->section) == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                            </div> --}}

<!-- Incomplete Students Modal -->
<div class="modal fade" id="incompleteModal" tabindex="-1" aria-labelledby="incompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="incompleteModalLabel">
                    <i class="fas fa-user-clock"></i> Add to Special Class
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="incomplete-students-container" class="p-2">
                    <p class="text-center text-muted">Loading students...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn btn-success" id="confirmSpecialClass">
                    <i class="fas fa-check"></i> Confirm Selection
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Special Class Confirmation Modal -->
<div class="modal fade" id="confirmSpecialClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="confirmSpecialClassForm">
                <div class="modal-body">
                    <p>Please select the <strong>semester</strong> and <strong>instructor</strong> for the special class:</p>

                    <!-- Semester Selection -->
                    <div class="mb-3">
                        <label for="semesterSelect" class="form-label">Semester:</label>
                        <select id="semesterSelect" class="form-select">
                            <option value="">Select Semester</option>
                            <option value="1" {{ $currentSemester > 1 ? 'disabled' : '' }}>Semester 1</option>
                            <option value="2" {{ $currentSemester > 2 ? 'disabled' : '' }}>Semester 2</option>
                            <option value="3" {{ $currentSemester > 3 ? 'disabled' : '' }}>Semester 3</option>
                        </select>
                    </div>

                    <!-- Instructor Selection -->
                    <div class="mb-3">
                        <label for="instructorSelect" class="form-label">Instructor:</label>
                        <select id="instructorSelect" class="form-select">
                            <option value="">Select Instructor</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    let confirmButton = document.getElementById("confirmSpecialClass");

    document.querySelector('[data-bs-target="#incompleteModal"]').addEventListener("click", function() {
        fetch("{{ route('admin.students.incomplete') }}")
            .then(response => response.json())
            .then(data => {
                let container = document.getElementById("incomplete-students-container");
                container.innerHTML = "";

                if (!data.students || Object.keys(data.students).length === 0) {
                    container.innerHTML = `<p class="text-center text-muted">No students found.</p>`;
                    return;
                }

                let specialStudents = data.specialStudents || [];

                Object.entries(data.students).forEach(([subject_id, grades]) => {
                    if (!grades.length) return;

                    let subject = grades[0].subject;
                    let department = grades[0].student.department;

                    let subjectSection = document.createElement("div");
                    subjectSection.classList.add("card", "mb-3", "shadow-sm");

                    subjectSection.innerHTML = `
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-book"></i> ${subject.name}</span>
                            <span class="badge bg-light text-dark">${department.name}</span>
                        </div>
                        <div class="card-body">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input select-all" data-subject="${subject_id}">
                                <strong>Select All</strong>
                            </label>
                            <ul class="list-group mt-2" id="student-list-${subject_id}"></ul>
                        </div>
                    `;

                    let studentList = subjectSection.querySelector(`#student-list-${subject_id}`);
                    grades.forEach(grade => {
                        let isSpecial = grade.special == 1 || specialStudents.includes(grade.student_id);
                        let statusBadge = grade.grade < 75 ? `<span class="badge bg-danger">Failed (${grade.grade})</span>` : "";

                        let listItem = document.createElement("li");
                        listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");

                        if (isSpecial) {
                            listItem.classList.add("bg-secondary", "text-white", "opacity-75");
                        }

                        listItem.innerHTML = `
                            <div>
                                <input type="checkbox" class="form-check-input student-checkbox me-2"
                                    data-id="${grade.id}"
                                    data-subject="${subject_id}"
                                    ${isSpecial ? 'disabled' : ''}>
                                ${grade.student.first_name} ${grade.student.last_name}
                            </div>
                            ${statusBadge}
                        `;

                        studentList.appendChild(listItem);
                    });

                    container.appendChild(subjectSection);
                });

                confirmButton.disabled = false;

                document.querySelectorAll(".select-all").forEach(selectAllCheckbox => {
                    selectAllCheckbox.addEventListener("change", function() {
                        let subjectId = this.dataset.subject;
                        let studentCheckboxes = document.querySelectorAll(`.student-checkbox[data-subject="${subjectId}"]:not(:disabled)`);
                        studentCheckboxes.forEach(cb => cb.checked = this.checked);
                    });
                });
            })
            .catch(error => {
                console.error("Error fetching students:", error);
                document.getElementById("incomplete-students-container").innerHTML = `<p class="text-center text-danger">Error loading students.</p>`;
            });
    });

    confirmButton.addEventListener("click", function() {
        let selectedStudents = [];
        document.querySelectorAll(".student-checkbox:checked:not(:disabled)").forEach(checkbox => {
            selectedStudents.push(checkbox.dataset.id);
        });

        if (selectedStudents.length === 0) {
            showNotification("Please select at least one student.", "error");
            return;
        }

        window.selectedStudentIds = selectedStudents;

        let confirmModal = new bootstrap.Modal(document.getElementById("confirmSpecialClassModal"));
        confirmModal.show();
    });

    document.getElementById("confirmSpecialClassForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let semester = document.getElementById("semesterSelect").value;
        let instructorId = document.getElementById("instructorSelect").value;
        let currentSemester = "{{ $currentSemester }}"; // Grabbed from Blade

        if (!semester || !instructorId) {
            showNotification("Please select both semester and instructor.", "error");
            return;
        }

        // Check if the selected semester is the same as current
        if (semester === currentSemester) {
            let confirmSameSemester = confirm("The selected semester is the same as the current semester. Are you sure you want to continue?");
            if (!confirmSameSemester) return;
        }

        // Proceed with request if confirmed or semesters differ
        fetch("{{ route('admin.students.assignSpecialClass') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                student_ids: window.selectedStudentIds,
                semester: semester,
                instructor_id: instructorId
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log("Raw Response:", data);
            return JSON.parse(data);
        })
        .then(data => {
            showNotification(data.message, "success");
            setTimeout(() => {
                location.reload();
            }, 1500);
        })
        .catch(error => {
            console.error("Error updating students:", error);
            showNotification("An error occurred, but changes might have been saved.", "warning");
            setTimeout(() => {
                location.reload();
            }, 2000);
        });
    });


    function showNotification(message, type) {
        let notification = document.createElement("div");
        notification.className = `alert alert-${type === "success" ? "success" : type === "warning" ? "warning" : "danger"} fixed-top m-3`;
        notification.style.zIndex = "1050";
        notification.innerHTML = `<strong>${message}</strong>`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});

$(document).on('click', '.recheck-btn', function () {
    const studentId = $(this).data('student-id');

    $.ajax({
        url: `/admin/students/${studentId}/recheck-subjects`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            alert(response.message);
            // Optionally refresh the part of the page or update the UI
        },
        error: function () {
            alert('Error occurred. Please try again.');
        }
    });
});

function promoteStudent(studentId) {
    console.log("Promoting Student ID:", studentId); // Check if the student ID is correct
    $.ajax({
        url: '/students/promote/' + studentId,  // Ensure this matches your backend route
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',  // CSRF token for security
            student_id: studentId,  // The student ID to promote
        },
        success: function(response) {
            console.log(response); // Check the server's response
            if (response.success) {
                alert(response.success);
                location.reload();  // Reload the page after success (optional)
            } else if (response.error) {
                alert(response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('An error occurred while promoting the student. Please try again.');
        }
    });
}




</script>




{{-- <script>
    document.getElementById("confirmSpecialClassForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let semester = document.getElementById("semesterSelect").value;
        let instructorId = document.getElementById("instructorSelect").value;

        if (!semester || !instructorId) {
            alert("Please select both semester and instructor.");
            return;
        }

        fetch("{{ route('admin.students.assignSpecialClass') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                student_ids: selectedStudentIds,
                semester: semester,
                instructor_id: instructorId
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            document.querySelector("#confirmSpecialClassModal .btn-close").click();
        });
    });
    </script>
     --}}



       <!-- Enrollment Modal -->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg enrollment-modal-dialog">
            <div class="modal-content enrollment-modal-content">
                <!-- Fixed Header -->
                <div class="modal-header text-white fixed-top enrollment-modal-header bg-success">
                    <h5 class="modal-title" id="enrollmentModalLabel">Select Students for Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="enrollmentForm">
                        @csrf

                        <!-- Fixed Filters -->
                        <div class="enrollment-filter-section">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="departmentFilter" class="form-label">Filter by Department</label>
                                    <select id="departmentFilter" class="form-select">
                                        <option value="">All Departments</option>
                                        @foreach ($departments as $department)
                                            <option value="dept-{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="yearLevelFilter" class="form-label">Filter by Year Level</label>
                                    <select id="yearLevelFilter" class="form-select">
                                        <option value="">All Year Levels</option>
                                        @foreach (range(1, 4) as $year)
                                            <option value="year-{{ $year }}">Year {{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        let searchInput = document.getElementById("searchStudent");
                                        let studentRows = document.querySelectorAll(".student-row");

                                        searchInput.addEventListener("keyup", function () {
                                            let searchValue = searchInput.value.toLowerCase();

                                            studentRows.forEach(row => {
                                                let studentID = row.children[1].textContent.toLowerCase();
                                                let studentName = row.children[2].textContent.toLowerCase();

                                                if (studentID.includes(searchValue) || studentName.includes(searchValue)) {
                                                    row.style.display = "";
                                                } else {
                                                    row.style.display = "none";
                                                }
                                            });
                                        });
                                    });

                                </script>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="searchStudent" class="form-label">Search Student</label>
                                    <input type="text" id="searchStudent" class="form-control" placeholder="Search by Name or Student ID">
                                </div>
                            </div>
                        </div>

                        <!-- Scrollable Table -->
                        <div class="enrollment-table-container">
                            <table class="table table-bordered table-hover mt-3">
                                <thead class=" text-center sticky-header">
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Year Level</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($departments as $department)
                                        <tr class="table-primary department-group" data-department="dept-{{ $department->id }}">
                                            <td colspan="5"><strong>{{ $department->name }}</strong></td>
                                        </tr>
                                        @foreach ($department->students->groupBy('year_level') as $yearLevel => $students)
                                            <tr class="table-secondary year-group" data-year="year-{{ $yearLevel }}">
                                                <td colspan="5"><strong>Year Level: {{ $yearLevel }}</strong></td>
                                            </tr>
                                            @foreach ($students->where('graduated', '!=', 1)->sortBy('last_name') as $student) <!-- Exclude graduated students -->
                                                <tr class="student-row" data-department="dept-{{ $department->id }}" data-year="year-{{ $yearLevel }}">
                                                    <td>
                                                        <input type="checkbox" name="student_ids[]" value="{{ $student->student_id }}" {{ $student->enrolled ? 'checked' : '' }}>
                                                    </td>
                                                    <td>{{ $student->student_id }}</td>
                                                    <td>{{ $student->last_name }}, {{ $student->first_name }}</td>
                                                    <td>{{ $student->year_level }}</td>
                                                    <td>{{ $department->name }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Fixed Footer with Button -->
                        <div class="modal-footer fixed-bottom enrollment-modal-footer">
                            <button type="submit" class="btn enrollment-btn-success w-100" id="submitEnrollment">Save Enrollment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script>
    $(document).ready(function () {
        $('#departmentFilter, #yearLevelFilter').change(function () {
            let selectedDept = $('#departmentFilter').val();
            let selectedYear = $('#yearLevelFilter').val();

            $('.student-row').hide();
            $('.department-group, .year-group').hide();

            if (!selectedDept && !selectedYear) {
                $('.student-row, .department-group, .year-group').show();
            } else {
                $('.student-row').each(function () {
                    let matchesDept = !selectedDept || $(this).data('department') === selectedDept;
                    let matchesYear = !selectedYear || $(this).data('year') === selectedYear;

                    if (matchesDept && matchesYear) {
                        $(this).show();
                        $(this).prevAll('.year-group:first').show();
                        $(this).prevAll('.department-group:first').show();
                    }
                });
            }
        });

        $('#selectAll').click(function () {
            $('input[name="student_ids[]"]').prop('checked', this.checked);
        });

        $('#enrollmentForm').submit(function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $('#submitEnrollment').prop('disabled', true).text('Processing...');
            //if bulk student were updated successfully
            $.ajax({
                url: "{{ route('admin.students.enroll.bulk') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    location.reload();
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "An error occurred. Please try again.",
                        timer: 2000
                    });
                },
                complete: function () {
                    $('#submitEnrollment').prop('disabled', false).text('Save Enrollment');
                }
            });
        });
    });
</script>

{{-- checking of the student id script --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
  const studentIdInput = document.getElementById('student_id');
  const feedback = document.getElementById('studentIdFeedback');

  studentIdInput.addEventListener('input', _.debounce(function() {
    const studentId = studentIdInput.value.trim();

    if (!studentId) {
      feedback.classList.add('d-none');
      studentIdInput.classList.remove('is-invalid');
      return;
    }

    fetch(`/check-student-id?student_id=${encodeURIComponent(studentId)}`)
      .then(response => response.json())
      .then(data => {
        if (data.exists) {
          feedback.textContent = '🚫 This Student ID is already taken.';
          feedback.classList.remove('d-none');
          studentIdInput.classList.add('is-invalid');
        } else {
          feedback.classList.add('d-none');
          studentIdInput.classList.remove('is-invalid');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        feedback.textContent = '⚠️ Error checking Student ID.';
        feedback.classList.remove('d-none');
      });
  }, 500));
});
</script>

{{-- checking of the student email script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
      const emailInput = document.getElementById('email');
      const emailFeedback = document.getElementById('emailFeedback');

      emailInput.addEventListener('input', _.debounce(function() {
        const email = emailInput.value.trim();

        if (!email) {
          emailFeedback.classList.add('d-none');
          emailInput.classList.remove('is-invalid');
          return;
        }

        fetch(`/check-email?email=${encodeURIComponent(email)}`)
          .then(response => response.json())
          .then(data => {
            if (data.exists) {
              emailFeedback.textContent = '🚫 This email is already taken.';
              emailFeedback.classList.remove('d-none');
              emailInput.classList.add('is-invalid');
            } else {
              emailFeedback.classList.add('d-none');
              emailInput.classList.remove('is-invalid');
            }
          })
          .catch(err => {
            console.error('Error:', err);
            emailFeedback.textContent = '⚠️ Error checking email.';
            emailFeedback.classList.remove('d-none');
          });
      }, 500));
    });
    </script>


{{-- for add student password --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Password toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = "password";
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });

        // Live password validation
        const passwordInput = document.getElementById('password');
        const feedback = document.getElementById('passwordFeedback');
        const regex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

        passwordInput.addEventListener('input', function () {
            const value = passwordInput.value;
            if (!value || regex.test(value)) {
                feedback.classList.add('d-none');
                passwordInput.classList.remove('is-invalid');
            } else {
                feedback.classList.remove('d-none');
                passwordInput.classList.add('is-invalid');
            }
        });
    });
    </script>



<style>
    .sticky-header {
        position: sticky;
        top: 0;
        background: #343a40;
        color: white;
        z-index: 1000;
    }
</style>

</div>
@endsection
