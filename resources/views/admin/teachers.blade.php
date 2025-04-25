@extends('layouts.app')

@section('content')
<div class="container-fluid">
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
   /* Loading Screen Styles */
   #loading-screen {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }
    
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
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<script>
    // Loading screen functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Hide loading screen when page is fully loaded
        const loadingScreen = document.getElementById('loading-screen');
        
        // Set a minimum display time for the loader (at least 800ms)
        setTimeout(function() {
            loadingScreen.style.display = 'none';
        }, 800);
        
        // Show loading screen when navigating away
        document.addEventListener('click', function(e) {
            // Check if the clicked element is a link or submit button that would navigate away
            const target = e.target.closest('a, button[type="submit"]');
            if (target) {
                // Exclude elements that shouldn't trigger the loader
                const excludeSelectors = [
                    '[data-bs-toggle="modal"]',  // Modal toggles
                    '[data-bs-toggle="collapse"]', // Collapse toggles
                    '.btn-close',  // Close buttons
                    '.remove-schedule-btn', // Schedule removal buttons
                    '.add-schedule-btn', // Schedule add buttons
                    '.save-btn:not([type="submit"])' // Save buttons that don't submit forms
                ];
                
                const shouldExclude = excludeSelectors.some(selector => 
                    target.matches(selector)
                );
                
                if (!shouldExclude && !e.ctrlKey && !e.metaKey) {
                    // If it's a normal navigation (not opening in new tab)
                    const message = target.closest('form') ? 
                        'Saving changes...' : 
                        'Loading...';
                    
                    document.getElementById('loading-message').textContent = message;
                    loadingScreen.style.display = 'block';
                }
            }
        });
        
        // Also show loading on form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                document.getElementById('loading-message').textContent = 'Saving changes...';
                loadingScreen.style.display = 'block';
            });
            
            // Handle form validation errors
            form.addEventListener('invalid', function(e) {
                // This event bubbles up from invalid form elements
                // Hide the loading screen when any validation error occurs
                loadingScreen.style.display = 'none';
            }, true); // Use capturing phase to catch the event early
            
            // Also listen for input events on required fields to handle browser validation
            form.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('invalid', function() {
                    loadingScreen.style.display = 'none';
                });
            });
        });
        
        // Add a timeout as a fallback to ensure the loading screen doesn't get stuck
        let loadingTimeout = setTimeout(function() {
            loadingScreen.style.display = 'none';
        }, 5000); // 5 seconds max loading time
        
        // Clear the timeout when the page successfully loads
        window.addEventListener('load', function() {
            clearTimeout(loadingTimeout);
        });
    });
    
    // Show loading screen immediately when the page starts loading
    window.addEventListener('beforeunload', function() {
        document.getElementById('loading-screen').style.display = 'block';
    });
</script>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        <h1 class="h2">Teachers</h1>  {{-- old class = mt-4 --}}

            <!-- Button to Open Inactive Teachers Modal -->
            <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#inactiveTeachersModal">
                <i class="bi bi-archive"></i> View Inactive Teachers
            </button>
            <button class="btn" style="background-color: #16C47F; color: white;" data-bs-toggle="modal" data-bs-target="#addTeacherModal">Add Teacher</button>
    </div>

    <!-- Inactive Teachers Modal -->
    <div class="modal fade" id="inactiveTeachersModal" tabindex="-1" aria-labelledby="inactiveTeachersLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inactiveTeachersLabel">Inactive Teachers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Phone</th>
                                <th>Last Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inactiveTeachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->name }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>{{ $teacher->department ? $teacher->department->name : 'N/A' }}</td>
                                    <td>{{ $teacher->phoneNumber }}</td>
                                    <td>{{ $teacher->updated_at->setTimezone('Asia/Shanghai')->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <!-- Reactivate Teacher -->
                                        <form action="{{ route('teachers.toggleStatus', $teacher->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle"></i> Reactivate
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No inactive teachers found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
    <!-- Sharp-edged Search Form -->
<div class="row mb-4">
    <div class="col-12">
        <form action="{{ route('teachers.index') }}" method="GET" id="custom-teacher-search-form" class="d-flex w-100 shadow-sm"
            style="background: #F4F4F4; padding: 8px; gap: 10px; border: 1px solid #ddd;">

            <!-- Search Input -->
            <input type="text" name="search" id="teacher-search-input" class="form-control border-0"
                placeholder="Search by teacher name..." value="{{ request('search') }}"
                style="background: #fff; font-size: 0.9rem; padding: 10px;">


            <!-- Search Button -->
            <button type="submit" class="btn text-white"
                style="background-color: #16C47F; font-size: 0.9rem; padding: 10px 24px; border: none;">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>



    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto; border-bottom: 1px solid gray;">
                <table class="table table-striped table-hover align-middle" style="border-bottom: 1px solid rgb(176, 175, 175)">
                    <thead class="text-center" style="background-color: #16C47F; color: white; position: sticky; top: 0; z-index:1000;">
                        <tr>
                            <th style="padding: 15px; border-radius: 10px 0 0 0;">Name</th>
                            <th style="padding: 15px;">Email</th>
                            <th style="padding: 15px;">Department</th>
                            <th style="padding: 15px;">Phone Number</th>
                            <th style=" padding: 15px; border-radius: 0 10px 0 0;">Actions</th>
                        </tr>
                    </thead>
                    <!-- Main Teacher Table (Only Active Teachers) -->
                    <tbody>
                        @php
                            $reactivatedTeachers = session('reactivated_teachers', []);
                        @endphp

                        @forelse ($teachers->where('is_active', true) as $teacher)
                        <tr class="text-center {{ in_array($teacher->id, $reactivatedTeachers) ? 'table-info' : '' }}"
                            style="background-color: #f8f9fa; transition: all 0.3s;">
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->name }}</td>
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->email }}</td>
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->department ? $teacher->department->name : 'N/A' }}</td>
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->phoneNumber }}</td>
                                <td>
                                    <!-- View Button -->
                                    <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTeacherModal{{ $teacher->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <!-- Delete Button -->
                                    {{-- <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this teacher?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form> --}}
                                    <!-- Set Inactive/Active Button -->
                                    <form id="toggleStatusForm-{{ $teacher->id }}" action="{{ route('teachers.toggleStatus', $teacher->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-sm {{ $teacher->is_active ? 'btn-danger' : 'btn-success' }}"
                                                onclick="confirmToggleStatus({{ $teacher->id }}, '{{ $teacher->is_active ? 'inactive' : 'active' }}')">
                                            <i class="bi {{ $teacher->is_active ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                            {{ $teacher->is_active ? 'Set Inactive' : 'Set Active' }}
                                        </button>
                                    </form>
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <script>
                                        function confirmToggleStatus(teacherId, status) {
                                            Swal.fire({
                                                title: "Are you sure?",
                                                text: "This will set the instructor as " + status + ".",
                                                icon: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: status === 'inactive' ? "#dc3545" : "#28a745",
                                                cancelButtonColor: "#6c757d",
                                                confirmButtonText: "Yes, proceed!",
                                                cancelButtonText: "Cancel"
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('toggleStatusForm-' + teacherId).submit();
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>

                            {{-- modified march 25 --}}
                            <!-- Edit Modal for each teacher -->
                            <div class="modal fade" id="editTeacherModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg"> <!-- Increased modal size for better layout -->
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="modal-body bg-light">
                                            <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="p-3">
                                                @csrf
                                                @method('PUT')

                                                <!-- Name -->
                                                <div class="mb-3">
                                                    <label for="name_{{ $teacher->id }}" class="form-label fw-bold">Name</label>
                                                    <input type="text" class="form-control shadow-sm rounded-3" id="name_{{ $teacher->id }}" name="name" value="{{ $teacher->name }}" required>
                                                </div>

                                                <!-- Email -->
                                                <div class="mb-3">
                                                    <label for="email_{{ $teacher->id }}" class="form-label fw-bold">Email</label>
                                                    <input type="email" class="form-control shadow-sm rounded-3" id="email_{{ $teacher->id }}" name="email" value="{{ $teacher->email }}" required>
                                                </div>

                                                <!-- Department -->
                                                <div class="mb-3">
                                                    <label for="department_id_{{ $teacher->id }}" class="form-label fw-bold">Department</label>
                                                    <select class="form-control shadow-sm rounded-3" id="department_id_{{ $teacher->id }}" name="department_id" required>
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}" {{ $teacher->department_id == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Gender -->
                                                <div class="mb-3">
                                                    <label for="gender_{{ $teacher->id }}" class="form-label fw-bold">Gender</label>
                                                    <select class="form-control shadow-sm" id="gender_{{ $teacher->id }}" name="gender" required>
                                                        <option value="" disabled>Select Gender</option>
                                                        <option value="Male" {{ $teacher->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $teacher->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>

                                                <!-- Civil Status -->
                                                <div class="mb-3">
                                                    <label for="civil_status_{{ $teacher->id }}" class="form-label fw-bold">Civil Status</label>
                                                    <select class="form-control shadow-sm" id="civil_status_{{ $teacher->id }}" name="civil_status" required>
                                                        <option value="" disabled>Select Status</option>
                                                        <option value="Single" {{ $teacher->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ $teacher->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ $teacher->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Divorced" {{ $teacher->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                </div>

                                                <!-- Phone Number -->
                                                <div class="mb-3">
                                                    <label for="phoneNumber_{{ $teacher->id }}" class="form-label fw-bold">Phone Number</label>
                                                    <input type="text" class="form-control shadow-sm rounded-3" id="phoneNumber_{{ $teacher->id }}" name="phoneNumber" value="{{ $teacher->phoneNumber }}">
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">Update Teacher</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             {{-- Pagination with custom styling --}}
@if ($teachers->hasPages())
<ul class="custom-pagination">
    {{-- Previous Page Link --}}
    @if ($teachers->onFirstPage())
        <li class="disabled"><span>«</span></li>
    @else
        <li><a href="{{ $teachers->previousPageUrl() }}" rel="prev">«</a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
        <li class="{{ $page == $teachers->currentPage() ? 'active' : '' }}">
            <a href="{{ $url }}">{{ $page }}</a>
        </li>
    @endforeach

    {{-- Next Page Link --}}
    @if ($teachers->hasMorePages())
        <li><a href="{{ $teachers->nextPageUrl() }}" rel="next">»</a></li>
    @else
        <li class="disabled"><span>»</span></li>
    @endif
</ul>
@endif
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded-3 overflow-hidden">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addTeacherModalLabel">Add Teacher</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teachers.store') }}" method="POST" class="p-3">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label fw-semibold">Gender</label>
                        <select class="form-control shadow-sm" id="gender" name="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="civil_status" class="form-label fw-semibold">Civil Status</label>
                        <select class="form-control shadow-sm" id="civil_status" name="civil_status" required>
                            <option value="" disabled selected>Select Status</option>
                            {{-- <option value="statusOpt">Select Status</option> --}}
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label fw-bold">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label fw-bold">Phone Number</label>
                        <input type="number" class="form-control" id="phoneNumber" name="phoneNumber">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-light text-light fw-bold bg-success">Add Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
