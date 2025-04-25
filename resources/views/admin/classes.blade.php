@extends('layouts.app')

@section('content')
<div class="mx-4">
    <h1 class="text-center mb-5" style="font-weight: bold; color: font-size: 2.5rem;">Class Schedules</h1>

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

    <div class="card border-0 shadow rounded-4 overflow-hidden">
        <div class="card-header text-white fw-bold py-3" style="background-color: #16C47F;">Your Classes</div>
        <div class="card-body p-4">
            <table class="table table-borderless align-middle text-center">
                <thead class="bg-light" style="color: #16C47F;">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->department ? $class->department->name : 'No Department' }}</td>
                            <td>
                                @if ($class->image)
                                    <img src="{{ asset('storage/' . $class->image) }}" alt="Class Image" class="rounded-circle shadow-sm" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                {{-- <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-outline-success btn-sm me-2" style="border-color: #16C47F; color: #16C47F;">Edit</a> --}}
                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="fixed-bottom d-flex justify-content-end p-4">
        <button type="button" class="btn text-white px-4 py-2 shadow-lg" 
            style="background-color: #16C47F;" 
            data-bs-toggle="modal" data-bs-target="#addClassModal">
            + Add Schedule
        </button>
    </div>
    
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4" style="transform: scale(0.8); transition: transform 0.3s ease-in-out;">
                <div class="modal-header text-white bg-success">
                    <h5 class="modal-title">Add a New Class</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('classes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Class Name -->
                        <div class="mb-3">
                            <label class="form-label">Class Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
    
                        <!-- Department -->
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="departmentDropdown" class="form-select" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Instructor Selection (Optional) -->
                        <div class="mb-3">
                            <label class="form-label">Assign Instructor (Optional)</label>
                            <select name="instructor_id" id="instructorDropdown" class="form-select">
                                <option value="" selected>For Everyone</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" data-department="{{ $teacher->department_id }}">
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Upload Image -->
                        <div class="mb-3">
                            <label class="form-label">Upload Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
    
                        <button type="submit" class="btn bg-success text-white w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const departmentDropdown = document.getElementById("departmentDropdown");
        const instructorDropdown = document.getElementById("instructorDropdown");
        const allInstructors = [...instructorDropdown.options]; // Store all options initially

        departmentDropdown.addEventListener("change", function() {
            const selectedDepartment = this.value;

            // Clear dropdown but ensure only one "For Everyone" option
            instructorDropdown.innerHTML = '';

            // Add the "For Everyone" option ONCE
            const forEveryoneOption = document.createElement("option");
            forEveryoneOption.value = "";
            forEveryoneOption.textContent = "For Everyone";
            instructorDropdown.appendChild(forEveryoneOption);

            // Filter and append instructors based on department
            allInstructors.forEach(option => {
                const deptId = option.getAttribute("data-department");
                if (!deptId || deptId === selectedDepartment) {
                    instructorDropdown.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>

<script>
    document.getElementById('addClassModal').addEventListener('shown.bs.modal', function () {
        document.querySelector('#addClassModal .modal-content').style.transform = 'scale(1)';
    });

    document.getElementById('addClassModal').addEventListener('hidden.bs.modal', function () {
        document.querySelector('#addClassModal .modal-content').style.transform = 'scale(0.8)';
    });
</script>
@endsection
