@extends('layouts.app')

@section('content')
<style>
    /* added march 25 */
    /* Customizing the stripe color */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(215, 215, 215, 0) !important; /* Lighter stripe with low opacity */
    }
</style>
<div class="container-fluid">
    @if (request('show_special') != 1)
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Subjects</h1>
            <!-- Add Subject Button -->
            <button class="btn mb-3" style="background-color: #16C47F; color: white;" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add Subject</button>
        </div>
    @endif

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

<div class="mb-2">
    <div class="col-12 d-flex justify-content-between">
        <form id="searchForm" action="{{ route('subjects.index') }}" method="GET" class="d-flex w-auto">
            <div class="input-group">
                <input type="text" name="search" class="form-control me-2" placeholder="Search" value="{{ request('search') }}">
                <button type="submit" class="btn" style="background-color: #16C47F; color: white; font-weight:600;">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
        <div style="display: flex;
flex-direction: row;">
            <a href="{{ route('admin.subject', ['show_special' => request('show_special') == 1 ? 0 : 1]) }}" 
                class="btn btn-info {{ request('show_special') == 1 ? 'active' : '' }}">
                {{ request('show_special') == 1 ? 'Show All Subjects' : 'Show Special Classes' }}
            </a>  
            @if (request('show_special') != 1)
                <button id="openAllDepartments" class="btn btn-primary" style="background-color: #16C47F; color: white; font-weight:600;">
                    Open All Departments
                </button>
            @endif
        </div>
        
    </div>
</div>

<script>
    // When the Open All Departments button is clicked
    document.getElementById("openAllDepartments").addEventListener("click", function() {
        openAllDepartments();
    });

    // Function to open all collapses (departments, years, semesters)
    function openAllDepartments() {
        const departmentCollapses = document.querySelectorAll('.collapse');
        departmentCollapses.forEach(function(collapse) {
            const collapseId = collapse.id;
            const departmentToggle = document.querySelector(`[data-bs-target="#${collapseId}"]`);
            if (departmentToggle) {
                new bootstrap.Collapse(collapse, { toggle: true });
                // Store the open state in localStorage
                localStorage.setItem(collapseId, 'open');
            }
        });

        // If you want to open all the years and semesters within the departments
        const yearButtons = document.querySelectorAll('.year-toggle');
        yearButtons.forEach(function(yearButton) {
            const yearCollapseId = yearButton.getAttribute('data-bs-target').slice(1);
            const yearCollapse = document.getElementById(yearCollapseId);
            if (yearCollapse) {
                new bootstrap.Collapse(yearCollapse, { toggle: true });
                localStorage.setItem(yearCollapseId, 'open');
            }
        });

        const semesterButtons = document.querySelectorAll('.semester-toggle');
        semesterButtons.forEach(function(semesterButton) {
            const semesterCollapseId = semesterButton.getAttribute('data-bs-target').slice(1);
            const semesterCollapse = document.getElementById(semesterCollapseId);
            if (semesterCollapse) {
                new bootstrap.Collapse(semesterCollapse, { toggle: true });
                localStorage.setItem(semesterCollapseId, 'open');
            }
        });
    }

    // Restore the collapse state from localStorage when the page reloads
    document.addEventListener('DOMContentLoaded', function() {
        const departmentCollapses = document.querySelectorAll('.collapse');
        departmentCollapses.forEach(function(collapse) {
            const collapseId = collapse.id;
            if (localStorage.getItem(collapseId) === 'open') {
                new bootstrap.Collapse(collapse, { toggle: true });
            }
        });

        // Also apply to year and semester collapses
        const yearCollapses = document.querySelectorAll('.collapse.year');
        yearCollapses.forEach(function(collapse) {
            const yearCollapseId = collapse.id;
            if (localStorage.getItem(yearCollapseId) === 'open') {
                new bootstrap.Collapse(collapse, { toggle: true });
            }
        });

        const semesterCollapses = document.querySelectorAll('.collapse.semester');
        semesterCollapses.forEach(function(collapse) {
            const semesterCollapseId = collapse.id;
            if (localStorage.getItem(semesterCollapseId) === 'open') {
                new bootstrap.Collapse(collapse, { toggle: true });
            }
        });

        // Restore scroll position
        const scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
        }
    });

    // Store scroll position when the user scrolls
    window.addEventListener('scroll', function() {
        localStorage.setItem('scrollPosition', window.scrollY);
    });

    // Trigger the Open All Departments button after a search is completed
    document.getElementById("searchForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission
        // Submit the form normally (search query)
        this.submit();

        // Wait a bit before triggering the open all departments action
        setTimeout(function() {
            openAllDepartments(); // Open all departments after the page reloads and search results are displayed
        }, 500); // Adjust timeout if necessary (500ms is a good default)
    });
</script>

@if (request('show_special') == 1)
    @if ($specialSubjects->isEmpty())
        <p>No special classes available.</p>
    @else
    <table class="table table-bordered mt-2">
        <thead class="text-light" style="background-color: #FF8C00;">
            <tr>                          
                <th>Code</th>
                <th>Name</th>
                <th>Description</th>
                <th>Units</th>
                <th>Room</th>
                <th>Department</th>
                <th>Instructor</th>
                <th>Actions</th>
                <th>Schedule</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Group special subjects by their department
                $groupedSubjects = $specialSubjects->groupBy(function($subject) {
                    return $subject->department ? $subject->department->name : 'N/A';
                });
            @endphp
    
            @foreach ($groupedSubjects as $departmentName => $subjects)
                <!-- Department Header -->
                <tr>
                    <td colspan="9" class="bg-warning text-center font-weight-bold">{{ $departmentName }}</td>
                </tr>
    
                @foreach ($subjects as $subject)
                    <tr data-id="{{ $subject->id }}">
                        <td>{{ $subject->subject->code }}</td>
                        <td contenteditable="true" class="editable" data-field="name">{{ $subject->name }}</td>
                        <td contenteditable="true" class="editable" data-field="description">{{ $subject->description }}</td>
                        <td contenteditable="true" class="editable" data-field="units">{{ $subject->units }}</td>
                        <td contenteditable="true" class="editable" data-field="room">{{ $subject->room }}</td>
                        <td>{{ $subject->department ? $subject->department->name : 'N/A' }}</td>
                        <td>{{ $subject->teacher ? $subject->teacher->name : 'N/A' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal-{{ $subject->id }}">
                                View/Edit
                            </button>
                            <button class="btn btn-success save-btn">
                                Save
                            </button>
                        
                            <!-- Schedule Modal -->
                            <div class="modal fade" id="scheduleModal-{{ $subject->id }}" tabindex="-1" aria-labelledby="scheduleModalLabel-{{ $subject->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="scheduleModalLabel-{{ $subject->id }}">Edit Schedule - {{ $subject->subject->code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="schedule-container">
                                                @foreach ($subject->schedule ? (is_string($subject->schedule) ? json_decode($subject->schedule, true) : $subject->schedule) : [] as $index => $schedule)
                                                    <div class="schedule-item mb-3 border p-3 rounded shadow-sm" data-index="{{ $index }}">
                                                        <div class="row g-2 align-items-end">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Day</label>
                                                                <select class="form-select schedule-day" data-index="{{ $index }}">
                                                                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                                                        <option value="{{ $day }}" {{ isset($schedule['day']) && $schedule['day'] == $day ? 'selected' : '' }}>{{ $day }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="time" class="form-control schedule-start-time" data-index="{{ $index }}" value="{{ $schedule['start_time'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">End Time</label>
                                                                <input type="time" class="form-control schedule-end-time" data-index="{{ $index }}" value="{{ $schedule['end_time'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-2 text-end">
                                                                <button type="button" class="btn btn-danger btn-sm remove-schedule-btn" data-index="{{ $index }}">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                        
                                            <div class="mt-3 text-end">
                                                <button type="button" class="btn btn-secondary add-schedule-btn">Add Schedule</button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- You could also hook up a save button here -->
                                            <button type="button" class="btn btn-success save-schedule-btn" data-id="{{ $subject->id }}">Save</button>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    // Delegate add/remove button clicks
                                    document.addEventListener('click', function (e) {
                                        // ADD schedule
                                        if (e.target.classList.contains('add-schedule-btn')) {
                                            const modal = e.target.closest('.modal');
                                            const container = modal.querySelector('.schedule-container');
                                
                                            // Check if a schedule item exists before adding
                                            if (container.querySelectorAll('.schedule-item').length === 0) {
                                                const newItem = document.createElement('div');
                                                newItem.className = 'schedule-item mb-3 border p-3 rounded shadow-sm';
                                                newItem.dataset.index = 0;
                                                newItem.innerHTML = `
                                                    <div class="row g-2 align-items-end">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Day</label>
                                                            <select class="form-select schedule-day" data-index="0">
                                                                ${['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'].map(day =>
                                                                    `<option value="${day}">${day}</option>`
                                                                ).join('')}
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Start Time</label>
                                                            <input type="time" class="form-control schedule-start-time" data-index="0">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">End Time</label>
                                                            <input type="time" class="form-control schedule-end-time" data-index="0">
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-schedule-btn" data-index="0">Remove</button>
                                                        </div>
                                                    </div>
                                                `;
                                
                                                container.appendChild(newItem);
                                            }
                                        }
                                
                                        // REMOVE schedule
                                        if (e.target.classList.contains('remove-schedule-btn')) {
                                            e.target.closest('.schedule-item').remove();
                                        }
                                    });
                                
                                    // Save button click handlers (static)
                                    document.querySelectorAll('.save-schedule-btn').forEach(button => {
                                        button.addEventListener('click', function () {
                                            const subjectId = this.dataset.id;
                                            const modal = this.closest('.modal');
                                            const scheduleItems = modal.querySelectorAll('.schedule-item');
                                
                                            const newSchedule = [];
                                
                                            scheduleItems.forEach(item => {
                                                const index = item.dataset.index;
                                                const day = item.querySelector(`.schedule-day[data-index="${index}"]`).value;
                                                const startTime = item.querySelector(`.schedule-start-time[data-index="${index}"]`).value;
                                                const endTime = item.querySelector(`.schedule-end-time[data-index="${index}"]`).value;
                                
                                                if (day && startTime && endTime) {
                                                    newSchedule.push({ day, start_time: startTime, end_time: endTime });
                                                }
                                            });
                                
                                            fetch(`/special-subjects/${subjectId}/update-schedule`, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: JSON.stringify({ schedule: newSchedule })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    alert('Schedule updated successfully!');
                                                    const bsModal = bootstrap.Modal.getInstance(modal);
                                                    bsModal.hide();
                                                } else {
                                                    alert('Failed to update schedule.');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('An error occurred while updating the schedule.');
                                            });
                                        });
                                    });
                                });
                                </script>
                                
                            
                            
                            
                        </td>                    
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    
    @endif
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new schedule
        document.querySelectorAll('.add-schedule-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const subjectId = row.getAttribute('data-id');
                const scheduleContainer = row.querySelector('.schedule-container');
                
                // Create a new schedule item dynamically
                const index = scheduleContainer.querySelectorAll('.schedule-item').length;

                const newSchedule = `
                    <div class="schedule-item" data-index="${index}">
                        <div class="form-group">
                            <label>Day</label>
                            <select class="form-select schedule-day" data-index="${index}">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="time" class="form-control schedule-start-time" data-index="${index}">
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input type="time" class="form-control schedule-end-time" data-index="${index}">
                        </div>
                        <button type="button" class="btn btn-danger remove-schedule-btn" data-index="${index}">Remove Schedule</button>
                    </div>
                `;

                scheduleContainer.insertAdjacentHTML('beforeend', newSchedule);
            });
        });

        // Remove schedule
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-schedule-btn')) {
                const scheduleItem = e.target.closest('.schedule-item');
                scheduleItem.remove();
            }
        });

        // Save updated data
        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const subjectId = row.getAttribute('data-id');
                const updatedData = {};

                row.querySelectorAll('.editable').forEach(element => {
                    if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
                        updatedData[element.getAttribute('data-field')] = element.value;
                    } else {
                        updatedData[element.getAttribute('data-field')] = element.innerText.trim();
                    }
                });

                const schedules = [];
                row.querySelectorAll('.schedule-item').forEach(item => {
                    schedules.push({
                        day: item.querySelector('.schedule-day').value,
                        start_time: item.querySelector('.schedule-start-time').value,
                        end_time: item.querySelector('.schedule-end-time').value,
                    });
                });

                updatedData.schedule = schedules;

                fetch(`/admin/update-special-subject/${subjectId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(updatedData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Updated successfully');
                    } else {
                        alert('Update failed');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>




@if (!request('show_special') || request('show_special') == 0)

            @php
                // Group by Department first, then Year, then Semester
                $groupedSubjects = $subjects->groupBy(['department.name', 'year', 'semester']);

                // Define the correct department order
                $departmentOrder = ['BSIT', 'BSA', 'BSBA', 'CRIM', 'MIDWIFERY'];

                // Sort the grouped subjects by department order
                $sortedSubjects = collect($groupedSubjects)->sortBy(function ($value, $key) use ($departmentOrder) {
                    return array_search(strtoupper($key), $departmentOrder);
                });
            @endphp

            <div class="accordion" id="departmentAccordion">
                @foreach ($sortedSubjects as $department => $years)
                    <div class="mb-4">
                        <!-- Department Button -->
                        <button class="w-100 text-center department-toggle text-light fw-bold py-4 px-4" type="button"
                        data-bs-toggle="collapse" data-bs-target="#dept-{{ Str::slug($department) }}"
                        style="background-color: #16B2C4; border: none; font-size: 2.25rem; border-radius: 10px;">
                        {{ strtoupper($department) }}
                        </button>
                        <div id="dept-{{ Str::slug($department) }}" class="collapse" data-parent="#departmentAccordion">
                            @foreach (collect($years)->sortKeys() as $year => $semesters)
                                <div class="ms-4 mt-2">
                                    <!-- Year Level Button -->
                                    <button class="w-100 text-start year-toggle text-light fw-bold py-3 px-4 ms-3" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#year-{{ Str::slug($department) }}-{{ $year }}"
                                        style="background-color: #16C428; border: none; font-size: 1.2rem; border-radius: 10px;"
                                        onclick="openAllSemesters({{ $year }}, '{{ Str::slug($department) }}')">
                                        {{ $year == 1 ? '1st Year' : ($year == 2 ? '2nd Year' : ($year == 3 ? '3rd Year' : ($year == 4 ? '4th Year' : 'Year Not Set'))) }}
                                    </button>
                        
                                    <div id="year-{{ Str::slug($department) }}-{{ $year }}" class="collapse">
                                        @foreach (collect($semesters)->sortKeys() as $semester => $subjects)
                                            <div class="ms-5 mt-2">
                                                <!-- Semester Button -->
                                                <button class="w-100 text-start semester-toggle text-light fw-bold py-3 px-4 ms-5" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#sem-{{ Str::slug($department) }}-{{ $year }}-{{ $semester }}"
                                                    style="background-color: #008897; border: none; font-size: 1.15rem; border-radius: 10px;">
                                                    {{ $semester == 1 ? '1st Semester' : ($semester == 2 ? '2nd Semester' : 'Semester Not Set') }}
                                                </button>
                                                <hr>
                                                <div id="sem-{{ Str::slug($department) }}-{{ $year }}-{{ $semester }}" class="collapse">
                                                    <!-- Subjects Table -->
                                                    <table class="table table-bordered mt-2">
                                                        <thead class="text-light" style="background-color: #16C47F;">
                                                            <tr>                          
                                                                <th>Code</th>
                                                                <th>Name</th>
                                                                <th>Description</th>
                                                                <th>Units</th>
                                                                <th>Schedule</th>
                                                                <th>Room</th>
                                                                <th>Department</th>
                                                                <th>Instructor</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($subjects as $subject)
                                                                <tr>
                                                                    <td class="d-flex justify-content-between align-items-center">
                                                                        <span>{{ $subject->code }}</span>
                                                                        @if ($subject->major)
                                                                            <span class="badge bg-primary">Major</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Minor</span>
                                                                        @endif
                                                                    </td>                                                                    
                                                                    <td>{{ $subject->name }}</td>
                                                                    <td>{{ $subject->description }}</td>
                                                                    <td>{{ $subject->units }}</td>
                                                                    {{-- <td>{{ $subject->day }}</td>
                                                                    <td>{{ $subject->start_time }} - {{ $subject->end_time }}</td> --}}
                                                                    <td>
                                                                        @if(is_array($subject->schedule) && count($subject->schedule) > 0)
                                                                            @foreach ($subject->schedule as $scheduleItem)
                                                                                <div>{{ $scheduleItem['day'] }}: {{ $scheduleItem['start_time'] }} - {{ $scheduleItem['end_time'] }}</div>
                                                                            @endforeach
                                                                        @else
                                                                            <p>No schedule available</p>
                                                                        @endif


                                                                    </td>                                                                       
                                                                    <td>{{ $subject->room }}</td>
                                                                    <td>{{ $subject->department ? $subject->department->name : 'N/A' }}</td>
                                                                    <td>{{ $subject->teacher ? $subject->teacher->name : 'N/A' }}</td>
                                                                    <td>
                                                                    <!-- Edit Button -->
                                                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">Edit</button>

                                                                    <!-- Delete Form -->
                                                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</button>
                                                                    </form>
                                                                    </td>
                                                                </tr>

                                                                <!-- Edit Subject Modal -->
                                                                <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg"> <!-- Changed from modal-dialog to modal-lg -->
                                                                        <div class="modal-content" style="color: rgb(0, 0, 0);">
                                                                        <div class="modal-content" style="color: rgb(0, 0, 0);">
                                                                            <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <div class="modal-header bg-success">
                                                                                    <h5 class="modal-title" style="color: white">Edit Subject</h5>
                                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                                </div>    
                                                                                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                                                                    <div class="mb-3">
                                                                                        <label class="form-label">Is this a Major?</label>
                                                                                        <div>
                                                                                          <label class="me-3">
                                                                                            <input 
                                                                                              type="radio" 
                                                                                              name="major" 
                                                                                              value="1" 
                                                                                              {{ old('major', $subject->major) == 1 ? 'checked' : '' }}
                                                                                            > Yes
                                                                                          </label>
                                                                                          <label>
                                                                                            <input 
                                                                                              type="radio" 
                                                                                              name="major" 
                                                                                              value="0" 
                                                                                              {{ old('major', $subject->major) == 0 ? 'checked' : '' }}
                                                                                            > No
                                                                                          </label>
                                                                                        </div>
                                                                                    </div> 
                                                                                    <div class="mb-3">
                                                                                        <label>Code</label>
                                                                                        <input type="text" class="form-control" name="code" value="{{ $subject->code }}" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label>Name</label>
                                                                                        <input type="text" class="form-control" name="name" value="{{ $subject->name }}" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label>Course Description</label>
                                                                                        <textarea class="form-control" name="description">{{ $subject->description }}</textarea>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label>Units</label>
                                                                                        <input type="number" class="form-control" name="units" value="{{ $subject->units }}">
                                                                                    </div>
                                                                                   
                                                                                    <div class="mb-3">
                                                                                        <label for="schedule">Schedule</label>
                                                                                        
                                                                                        <!-- Hidden input to store the schedule data as JSON -->
                                                                                        <input type="hidden" name="schedule_json" id="schedule-json-{{ $subject->id }}" value="{{ json_encode($subject->scheduleData ?? []) }}">
                                                                                        
                                                                                        <!-- Container to display schedule items -->
                                                                                        <div id="schedule-display-{{ $subject->id }}" class="mb-3">
                                                                                            <!-- Schedule items will be rendered here by JavaScript -->
                                                                                        </div>
                                                                                        
                                                                                        <!-- Form to add/edit a schedule -->
                                                                                        <div class="card mb-3">
                                                                                            <div class="card-header bg-light">
                                                                                                <span id="form-title-{{ $subject->id }}">Add New Schedule</span>
                                                                                            </div>
                                                                                            <div class="card-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-4">
                                                                                                        <div class="mb-2">
                                                                                                            <label>Day</label>
                                                                                                            <select class="form-control" id="new-day-{{ $subject->id }}">
                                                                                                                <option value="Monday">Monday</option>
                                                                                                                <option value="Tuesday">Tuesday</option>
                                                                                                                <option value="Wednesday">Wednesday</option>
                                                                                                                <option value="Thursday">Thursday</option>
                                                                                                                <option value="Friday">Friday</option>
                                                                                                                <option value="Saturday">Saturday</option>
                                                                                                                <option value="Sunday">Sunday</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-3">
                                                                                                        <div class="mb-2">
                                                                                                            <label>Start Time</label>
                                                                                                            <input type="time" class="form-control" id="new-start-time-{{ $subject->id }}">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-3">
                                                                                                        <div class="mb-2">
                                                                                                            <label>End Time</label>
                                                                                                            <input type="time" class="form-control" id="new-end-time-{{ $subject->id }}">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-2">
                                                                                                        <div class="mb-2">
                                                                                                            <!-- Hidden input to track if we're editing an existing item -->
                                                                                                            <input type="hidden" id="edit-index-{{ $subject->id }}" value="-1">
                                                                                                            
                                                                                                            <!-- Button container with better layout -->
                                                                                                            <div class="d-flex flex-column gap-2">
                                                                                                                <button type="button" class="btn btn-primary" id="add-schedule-btn-{{ $subject->id }}">Add</button>
                                                                                                                <button type="button" class="btn btn-secondary d-none" id="cancel-edit-btn-{{ $subject->id }}">Cancel</button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script>
                                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                                        const subjectId = '{{ $subject->id }}';
                                                                                        const jsonInput = document.getElementById(`schedule-json-${subjectId}`);
                                                                                        const displayContainer = document.getElementById(`schedule-display-${subjectId}`);
                                                                                        const addButton = document.getElementById(`add-schedule-btn-${subjectId}`);
                                                                                        const cancelButton = document.getElementById(`cancel-edit-btn-${subjectId}`);
                                                                                        const formTitle = document.getElementById(`form-title-${subjectId}`);
                                                                                        const editIndexInput = document.getElementById(`edit-index-${subjectId}`);
                                                                                        
                                                                                        const daySelect = document.getElementById(`new-day-${subjectId}`);
                                                                                        const startTimeInput = document.getElementById(`new-start-time-${subjectId}`);
                                                                                        const endTimeInput = document.getElementById(`new-end-time-${subjectId}`);
                                                                                        
                                                                                        // Function to render all schedule items
                                                                                        function renderScheduleItems() {
                                                                                            // Clear the display container
                                                                                            displayContainer.innerHTML = '';
                                                                                            
                                                                                            // Get the current schedule data
                                                                                            let scheduleData = [];
                                                                                            try {
                                                                                                scheduleData = JSON.parse(jsonInput.value);
                                                                                            } catch (e) {
                                                                                                console.error('Error parsing schedule data:', e);
                                                                                                scheduleData = [];
                                                                                            }
                                                                                            
                                                                                            // If there are no schedule items, show a message
                                                                                            if (scheduleData.length === 0) {
                                                                                                displayContainer.innerHTML = '<div class="alert alert-info">No schedule items added yet.</div>';
                                                                                                return;
                                                                                            }
                                                                                            
                                                                                            // Create a table to display the schedule items
                                                                                            const table = document.createElement('table');
                                                                                            table.className = 'table table-bordered';
                                                                                            table.innerHTML = `
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Day</th>
                                                                                                        <th>Start Time</th>
                                                                                                        <th>End Time</th>
                                                                                                        <th>Actions</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody></tbody>
                                                                                            `;
                                                                                            
                                                                                            // Add each schedule item to the table
                                                                                            scheduleData.forEach((item, index) => {
                                                                                                const row = document.createElement('tr');
                                                                                                row.innerHTML = `
                                                                                                    <td>${item.day}</td>
                                                                                                    <td>${item.start_time}</td>
                                                                                                    <td>${item.end_time}</td>
                                                                                                    <td>
                                                                                                        <button type="button" class="btn btn-primary btn-sm me-1 edit-btn" data-index="${index}">Edit</button>
                                                                                                        <button type="button" class="btn btn-danger btn-sm remove-btn" data-index="${index}">Remove</button>
                                                                                                    </td>
                                                                                                `;
                                                                                                
                                                                                                // Add event listener to the edit button
                                                                                                row.querySelector('.edit-btn').addEventListener('click', function() {
                                                                                                    const index = parseInt(this.getAttribute('data-index'));
                                                                                                    editScheduleItem(index);
                                                                                                });
                                                                                                
                                                                                                // Add event listener to the remove button
                                                                                                row.querySelector('.remove-btn').addEventListener('click', function() {
                                                                                                    const index = parseInt(this.getAttribute('data-index'));
                                                                                                    removeScheduleItem(index);
                                                                                                });
                                                                                                
                                                                                                table.querySelector('tbody').appendChild(row);
                                                                                            });
                                                                                            
                                                                                            // Add the table to the display container
                                                                                            displayContainer.appendChild(table);
                                                                                        }
                                                                                        
                                                                                        // Function to add a new schedule item or update an existing one
                                                                                        function addOrUpdateScheduleItem() {
                                                                                            const day = daySelect.value;
                                                                                            const startTime = startTimeInput.value;
                                                                                            const endTime = endTimeInput.value;
                                                                                            
                                                                                            // Validate inputs
                                                                                            if (!day || !startTime || !endTime) {
                                                                                                alert('Please fill in all schedule fields.');
                                                                                                return;
                                                                                            }
                                                                                            
                                                                                            // Get the current schedule data
                                                                                            let scheduleData = [];
                                                                                            try {
                                                                                                scheduleData = JSON.parse(jsonInput.value);
                                                                                            } catch (e) {
                                                                                                console.error('Error parsing schedule data:', e);
                                                                                                scheduleData = [];
                                                                                            }
                                                                                            
                                                                                            const editIndex = parseInt(editIndexInput.value);
                                                                                            
                                                                                            if (editIndex >= 0 && editIndex < scheduleData.length) {
                                                                                                // Update existing item
                                                                                                scheduleData[editIndex] = {
                                                                                                    day: day,
                                                                                                    start_time: startTime,
                                                                                                    end_time: endTime
                                                                                                };
                                                                                            } else {
                                                                                                // Add new item
                                                                                                scheduleData.push({
                                                                                                    day: day,
                                                                                                    start_time: startTime,
                                                                                                    end_time: endTime
                                                                                                });
                                                                                            }
                                                                                            
                                                                                            // Update the hidden input
                                                                                            jsonInput.value = JSON.stringify(scheduleData);
                                                                                            
                                                                                            // Reset the form
                                                                                            resetForm();
                                                                                            
                                                                                            // Re-render the schedule items
                                                                                            renderScheduleItems();
                                                                                        }
                                                                                        
                                                                                        // Function to edit a schedule item
                                                                                        function editScheduleItem(index) {
                                                                                            // Get the current schedule data
                                                                                            let scheduleData = [];
                                                                                            try {
                                                                                                scheduleData = JSON.parse(jsonInput.value);
                                                                                            } catch (e) {
                                                                                                console.error('Error parsing schedule data:', e);
                                                                                                return;
                                                                                            }
                                                                                            
                                                                                            // Make sure the index is valid
                                                                                            if (index < 0 || index >= scheduleData.length) {
                                                                                                console.error('Invalid schedule index:', index);
                                                                                                return;
                                                                                            }
                                                                                            
                                                                                            // Get the schedule item
                                                                                            const item = scheduleData[index];
                                                                                            
                                                                                            // Populate the form
                                                                                            daySelect.value = item.day;
                                                                                            startTimeInput.value = item.start_time;
                                                                                            endTimeInput.value = item.end_time;
                                                                                            
                                                                                            // Set the edit index
                                                                                            editIndexInput.value = index;
                                                                                            
                                                                                            // Update the form title and button text
                                                                                            formTitle.textContent = 'Edit Schedule';
                                                                                            addButton.textContent = 'Update';
                                                                                            
                                                                                            // Show the cancel button
                                                                                            addButton.classList.remove('w-100');
                                                                                            cancelButton.classList.remove('d-none');
                                                                                        }
                                                                                        
                                                                                        // Function to remove a schedule item
                                                                                        function removeScheduleItem(index) {
                                                                                            // Get the current schedule data
                                                                                            let scheduleData = [];
                                                                                            try {
                                                                                                scheduleData = JSON.parse(jsonInput.value);
                                                                                            } catch (e) {
                                                                                                console.error('Error parsing schedule data:', e);
                                                                                                scheduleData = [];
                                                                                            }
                                                                                            
                                                                                            // Remove the item at the specified index
                                                                                            scheduleData.splice(index, 1);
                                                                                            
                                                                                            // Update the hidden input
                                                                                            jsonInput.value = JSON.stringify(scheduleData);
                                                                                            
                                                                                            // Re-render the schedule items
                                                                                            renderScheduleItems();
                                                                                        }
                                                                                        
                                                                                        // Function to reset the form
                                                                                        function resetForm() {
                                                                                            // Clear the form
                                                                                            startTimeInput.value = '';
                                                                                            endTimeInput.value = '';
                                                                                            
                                                                                            // Reset the edit index
                                                                                            editIndexInput.value = -1;
                                                                                            
                                                                                            // Reset the form title and button text
                                                                                            formTitle.textContent = 'Add New Schedule';
                                                                                            addButton.textContent = 'Add';
                                                                                            
                                                                                            // Hide the cancel button
                                                                                            addButton.classList.add('w-100');
                                                                                            cancelButton.classList.add('d-none');
                                                                                        }
                                                                                        
                                                                                        // Add event listener to the add/update button
                                                                                        addButton.addEventListener('click', addOrUpdateScheduleItem);
                                                                                        
                                                                                        // Add event listener to the cancel button
                                                                                        cancelButton.addEventListener('click', resetForm);
                                                                                        
                                                                                        // Initial render of schedule items
                                                                                        renderScheduleItems();
                                                                                    });
                                                                                    </script>
 
                                                                                    <div class="mb-3">
                                                                                        <label>Room</label>
                                                                                        <input type="text" class="form-control" name="room" value="{{ $subject->room }}">
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="department_id" class="form-label">Department</label>
                                                                                        <select class="form-control" id="department_id" name="department_id" required onchange="filterTeachers()">
                                                                                            <option value="">Select Department</option>
                                                                                            @foreach($departments as $department)
                                                                                                <option value="{{ $department->id }}" {{ $subject->department_id == $department->id ? 'selected' : '' }}>
                                                                                                    {{ $department->name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="semester" class="form-label">Semester</label>
                                                                                        <select class="form-control" id="semester" name="semester" required>
                                                                                            <option value="" {{ is_null($subject->semester) ? 'selected' : '' }}>Not Set</option>
                                                                                            <option value="1" {{ $subject->semester == 1 ? 'selected' : '' }}>1st Semester</option>
                                                                                            <option value="2" {{ $subject->semester == 2 ? 'selected' : '' }}>2nd Semester</option>
                                                                                            <option value="2" {{ $subject->semester == 3 ? 'selected' : '' }}>Summer Semester</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="year" class="form-label">Year Level</label>
                                                                                        <select class="form-control" id="year" name="year" required>
                                                                                            <option value="" {{ is_null($subject->year) ? 'selected' : '' }}>Not Set</option>
                                                                                            <option value="1" {{ $subject->year == 1 ? 'selected' : '' }}>1st Year</option>
                                                                                            <option value="2" {{ $subject->year == 2 ? 'selected' : '' }}>2nd Year</option>
                                                                                            <option value="3" {{ $subject->year == 3 ? 'selected' : '' }}>3rd Year</option>
                                                                                            <option value="4" {{ $subject->year == 4 ? 'selected' : '' }}>4th Year</option>
                                                                                        </select>
                                                                                    </div>                                                                                 
                                                                                    <div class="mb-3">
                                                                                        <label for="teacher_id" class="form-label">Instructor</label>
                                                                                        <select class="form-control" id="teacher_id" name="teacher_id">
                                                                                            @php
                                                                                                $selectedTeacherId = $subject->teacher_id;
                                                                                                $selectedTeacherName = $subject->teacher->name ?? 'Select Instructor';
                                                                                            @endphp
                                                                                            <!-- Set the currently selected teacher as the selected option -->
                                                                                            <option value="{{ $selectedTeacherId }}" selected>{{ $selectedTeacherName }}</option>
                                                                                    
                                                                                            <!-- Filtered Teachers (Same Department) -->
                                                                                            @foreach($teachers as $dept_id => $teacherGroup)
                                                                                                @if($dept_id == $subject->department_id)
                                                                                                    <optgroup label="Same Department">
                                                                                                        @foreach($teacherGroup as $teacher)
                                                                                                            <!-- Don't add the currently selected teacher again -->
                                                                                                            @if($teacher->id != $selectedTeacherId)
                                                                                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </optgroup>
                                                                                                @endif
                                                                                            @endforeach
                                                                                    
                                                                                            <!-- Non-filtered Teachers (Other Departments) -->
                                                                                            @foreach($teachers as $dept_id => $teacherGroup)
                                                                                                @if($dept_id != $subject->department_id)
                                                                                                    <optgroup label="-----------------">
                                                                                                        @foreach($teacherGroup as $teacher)
                                                                                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                                                                        @endforeach
                                                                                                    </optgroup>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                                                                                                       
                                                                                </div>
                                                                                {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script> --}}

                                                                                <div class="modal-footer" style="position: sticky; bottom: 0;">
                                                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                                                </div>
                                                                                

                                                                                <script>
                                                                                    document.addEventListener("DOMContentLoaded", function () {
                                                                                        // ===================== //
                                                                                        // 1. Teacher Filtering
                                                                                        // ===================== //
                                                                                        var teachersByDepartment = @json($teachers);
                                                                                        var selectedTeacherId = "{{ $subject->teacher_id }}";
                                                                                    
                                                                                        window.filterTeachers = function () {
                                                                                            var departmentId = document.getElementById('department_id').value;
                                                                                            var teacherSelect = document.getElementById('teacher_id');
                                                                                    
                                                                                            var selectedTeacherName = teacherSelect.options[0].text;
                                                                                            teacherSelect.innerHTML = `<option value="" selected disabled>${selectedTeacherName}</option>`;
                                                                                    
                                                                                            if (teachersByDepartment[departmentId]) {
                                                                                                teachersByDepartment[departmentId].forEach(function (teacher) {
                                                                                                    if (teacher.id != selectedTeacherId) {
                                                                                                        var option = document.createElement('option');
                                                                                                        option.value = teacher.id;
                                                                                                        option.textContent = teacher.name;
                                                                                                        teacherSelect.appendChild(option);
                                                                                                    }
                                                                                                });
                                                                                            }
                                                                                        };
                                                                                    
                                                                                        // =============================== //
                                                                                        // 2. Open All Semesters Function
                                                                                        // =============================== //
                                                                                        window.openAllSemesters = function (year, departmentSlug) {
                                                                                            const semesterButtons = document.querySelectorAll(`#year-${departmentSlug}-${year} .semester-toggle`);
                                                                                            semesterButtons.forEach(function(button) {
                                                                                                const collapseId = button.getAttribute('data-bs-target').slice(1);
                                                                                                const semesterCollapse = document.getElementById(collapseId);
                                                                                                if (semesterCollapse) {
                                                                                                    new bootstrap.Collapse(semesterCollapse, { toggle: true });
                                                                                                }
                                                                                            });
                                                                                        };
                                                                                    
                                                                                        // =============================== //
                                                                                        // 3. Scroll + Collapse State Restore
                                                                                        // =============================== //
                                                                                    
                                                                                        // Save scroll position and opened collapses on form submit
                                                                                        document.querySelector("form")?.addEventListener("submit", function () {
                                                                                            localStorage.setItem('scrollPosition', window.scrollY);
                                                                                            const openedButtons = [];
                                                                                            document.querySelectorAll('.collapse.show').forEach(collapse => {
                                                                                                openedButtons.push(collapse.id);
                                                                                            });
                                                                                            localStorage.setItem('openedButtons', JSON.stringify(openedButtons));
                                                                                        });
                                                                                    
                                                                                        // Save on reload or navigation
                                                                                        window.addEventListener("beforeunload", function () {
                                                                                            localStorage.setItem('scrollPosition', window.scrollY);
                                                                                            const openedButtons = [];
                                                                                            document.querySelectorAll('.collapse.show').forEach(collapse => {
                                                                                                openedButtons.push(collapse.id);
                                                                                            });
                                                                                            localStorage.setItem('openedButtons', JSON.stringify(openedButtons));
                                                                                        });
                                                                                    
                                                                                        // Restore scroll and collapses after short delay
                                                                                        setTimeout(function () {
                                                                                            // Scroll restoration
                                                                                            const scrollPosition = localStorage.getItem('scrollPosition');
                                                                                            if (scrollPosition) {
                                                                                                window.scrollTo(0, parseInt(scrollPosition));
                                                                                            }
                                                                                    
                                                                                            // Collapse restoration
                                                                                            const openedButtons = JSON.parse(localStorage.getItem('openedButtons') || "[]");
                                                                                            document.querySelectorAll('.collapse').forEach(collapse => {
                                                                                                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                                                                                                if (openedButtons.includes(collapse.id)) {
                                                                                                    if (!collapse.classList.contains('show')) {
                                                                                                        bsCollapse.show();
                                                                                                    }
                                                                                                } else {
                                                                                                    if (collapse.classList.contains('show')) {
                                                                                                        bsCollapse.hide();
                                                                                                    }
                                                                                                }
                                                                                            });
                                                                                    
                                                                                            // Year section restoration
                                                                                            const lastOpened = localStorage.getItem('lastOpenedSection');
                                                                                            if (lastOpened) {
                                                                                                const toggleButton = document.querySelector(`[data-year="${lastOpened}"]`);
                                                                                                const section = document.getElementById(`year-${lastOpened}`);
                                                                                                if (toggleButton && section) {
                                                                                                    section.classList.remove("d-none");
                                                                                                    toggleButton.classList.add("active");
                                                                                                }
                                                                                            }
                                                                                        }, 300); // delay helps ensure content is loaded
                                                                                    
                                                                                        // Keep collapse state updated in localStorage
                                                                                        document.querySelectorAll('.collapse').forEach(collapse => {
                                                                                            collapse.addEventListener('shown.bs.collapse', function () {
                                                                                                let opened = JSON.parse(localStorage.getItem('openedButtons') || "[]");
                                                                                                if (!opened.includes(collapse.id)) {
                                                                                                    opened.push(collapse.id);
                                                                                                    localStorage.setItem('openedButtons', JSON.stringify(opened));
                                                                                                }
                                                                                            });
                                                                                    
                                                                                            collapse.addEventListener('hidden.bs.collapse', function () {
                                                                                                let opened = JSON.parse(localStorage.getItem('openedButtons') || "[]");
                                                                                                opened = opened.filter(id => id !== collapse.id);
                                                                                                localStorage.setItem('openedButtons', JSON.stringify(opened));
                                                                                            });
                                                                                        });
                                                                                    
                                                                                        // =============================== //
                                                                                        // 4. Manual Year Toggle (if any)
                                                                                        // =============================== //
                                                                                        document.querySelectorAll(".toggle-year").forEach(button => {
                                                                                            button.addEventListener("click", function () {
                                                                                                let year = this.getAttribute("data-year");
                                                                                                let section = document.getElementById(`year-${year}`);
                                                                                                if (section) {
                                                                                                    if (section.classList.contains("d-none")) {
                                                                                                        section.classList.remove("d-none");
                                                                                                        localStorage.setItem('lastOpenedSection', year);
                                                                                                    } else {
                                                                                                        section.classList.add("d-none");
                                                                                                        localStorage.removeItem('lastOpenedSection');
                                                                                                    }
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    
                                                                                        // Restore manually toggled year section (if applicable)
                                                                                        let lastOpened = localStorage.getItem('lastOpenedSection');
                                                                                        if (lastOpened) {
                                                                                            let toggleButton = document.querySelector(`[data-year="${lastOpened}"]`);
                                                                                            let section = document.getElementById(`year-${lastOpened}`);
                                                                                            if (toggleButton && section) {
                                                                                                section.classList.remove("d-none");
                                                                                                toggleButton.classList.add("active");
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                    </script>
                                                                                    
                                                                                    
                                                                                
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                           
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <script>
                            document.querySelectorAll('.department-toggle').forEach(button => {
                                button.addEventListener('click', function() {
                                    document.querySelectorAll('.collapse').forEach(collapse => {
                                        if (collapse !== document.querySelector(this.dataset.bsTarget)) {
                                            collapse.classList.remove('show');
                                        }
                                    });
                                });
                            });
                        </script>
                    </tbody>
                </table>
            </div>
@endif
<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Add New Subject</h5>
                    <button type="button" class="btn-close text-white btn-close-white" data-bs-dismiss="modal"></button>
                </div>  
                <div class="modal-body" style="max-height: 600px; max-width: 500px; overflow-y: auto;">
                    <!-- Form Fields Here -->
                    <div class="mb-3">
                        <label class="form-label">Major Subject?</label>
                        <div>
                            <label class="me-3">
                                <input type="radio" name="major" value="1" checked> Yes
                            </label>
                            <label>
                                <input type="radio" name="major" value="0"> No
                            </label>
                        </div>
                    </div>    
                    <div class="mb-3">
                        <label>Code</label>
                        <input type="text" class="form-control" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Course Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Units</label>
                        <input type="number" class="form-control" name="units">
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Schedule</h5>
                        </div>
                        <div class="card-body">
                            <div id="schedule-container">
                                <div class="schedule-entry">
                                    <div class="mb-3">
                                        <label for="day_1">Day</label>
                                        <select class="form-control day" name="schedule[0][day]" id="day_1">
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="start_time_1">Start Time</label>
                                        <input type="time" class="form-control start_time" name="schedule[0][start_time]" id="start_time_1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_time_1">End Time</label>
                                        <input type="time" class="form-control end_time" name="schedule[0][end_time]" id="end_time_1">
                                    </div>
                                    <button type="button" class="btn btn-danger remove-schedule-entry">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="add-schedule-entry">Add Another Schedule</button>
                        </div>
                    </div>

                    <script>
                        let scheduleCount = 1; // Start counting from the first index
                    
                        // Add new schedule entry
                        document.getElementById('add-schedule-entry').addEventListener('click', function () {
                            const container = document.getElementById('schedule-container');
                            const newSchedule = document.createElement('div');
                            newSchedule.classList.add('schedule-entry');
                            newSchedule.innerHTML = `
                                <div class="mb-3">
                                    <label for="day_${scheduleCount}">Day</label>
                                    <select class="form-control day" name="schedule[${scheduleCount}][day]" id="day_${scheduleCount}">
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="start_time_${scheduleCount}">Start Time</label>
                                    <input type="time" class="form-control start_time" name="schedule[${scheduleCount}][start_time]" id="start_time_${scheduleCount}">
                                </div>
                                <div class="mb-3">
                                    <label for="end_time_${scheduleCount}">End Time</label>
                                    <input type="time" class="form-control end_time" name="schedule[${scheduleCount}][end_time]" id="end_time_${scheduleCount}">
                                </div>
                                <button type="button" class="btn btn-danger remove-schedule-entry">Remove</button>
                            `;
                            
                            container.appendChild(newSchedule);
                            scheduleCount++;
                        });
                    
                        // Remove schedule entry
                        document.addEventListener('click', function (event) {
                            if (event.target && event.target.classList.contains('remove-schedule-entry')) {
                                event.target.closest('.schedule-entry').remove();
                            }
                        });
                    </script>

                    <div class="mb-3">
                        <label>Room</label>
                        <input type="text" class="form-control" name="room">
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-control" id="department_id" name="department_id" required onchange="filterTeachers()">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                            <option value="2">Summer Semester</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year Level</label>
                        <select class="form-control" id="year" name="year" required>
                            <option value="">Select Year Level</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>                
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Instructor</label>
                        <select class="form-control" id="teacher_id" name="teacher_id">
                            <option value="">Select Instructor</option>
                            @foreach($teachers as $dept_id => $teacherGroup)
                                @foreach($teacherGroup as $teacher)
                                    <option value="{{ $teacher->id }}" data-department="{{ $teacher->department_id }}">
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Submit button is inside the modal-footer now -->
                    <button type="submit" class="btn btn-success text-light">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    /* Custom card style for the schedule */
.card {
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #28a745;
    color: #fff;
}

.card-body {
    background-color: #f8f9fa;
}

.card-title {
    font-weight: bold;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

</style>

@endsection
