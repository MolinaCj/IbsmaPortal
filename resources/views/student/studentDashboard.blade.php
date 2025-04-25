@extends('layouts.student')

@section('content')
<div class="mt-4">
    <!-- Top Profile Container -->

    <div class="d-flex justify-content-between align-items-center mb-5 p-4 rounded-4 shadow-lg flex-wrap" style="background: #16C47F">
        {{-- border-top border-3 border-success" --}}

        <!-- Welcome Text (Left Side) -->
        <div class="text-center text-md-start">
            <h1 class="display-6 fw-bold mb-2 text-white">Welcome to your Dashboard!</h1>
            {{-- <p class="lead mb-0 text-white">Stay motivated and make every day productive.</p> --}}

            <!-- Quote of the Day -->
            <div class="mt-3">
                <p class="fs-4 text-white fw-bold"><em>"{{ $quote }}"</em></p>
            </div>
        </div>

        <!-- Profile Picture and Info (Right Side) -->
        <div class="d-flex align-items-center text-center text-md-end ms-auto">
            <div class="me-3">
                <h5 class="fw-bold mb-1 text-white">{{ $student->first_name }} {{ $student->last_name }}</h5>
                <p class="mb-0 text-white" style="font-size: 1.2rem; "><b>Student</b></p>
                @if($isEnrolled)
                <p class="text-white" style="font-size: 1rem; border: 2px solid white; padding: 10px; border-radius: 5px; display: inline-block;">Enrolled</p>
            @else
                <p class="text-danger" style="font-size: 1rem;">Not Enrolled</p>
            @endif


            </div>

            <!-- Clickable Profile Image -->
            <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/default-profile.png') }}"
                 alt="Profile Picture"
                 class="rounded-circle"
                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                 data-bs-toggle="modal" data-bs-target="#studentProfileModal">
        </div>
    </div>


    {{-- GRADES SECTION --}}
{{-- GRADES SECTION --}}
<div class="container mt-4 shadow-lg p-4 rounded bg-white">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold">
                My grades <i class="bi bi-clipboard-check text-primary"></i>
            </h2>
            <h5 class="fw-bold text-muted"><strong>Year Level: </strong>{{ $student->year_level }}</h5>
        </div>
    </div>

    @php
        $filteredGrades = $grades;
    @endphp

    <h5 class="fw-bold mt-4">Semester: {{ $currentSemester }}</h5>
    @if ($filteredGrades->isEmpty())
        <div class="alert alert-warning text-center mt-3">
            No grades available yet for Semester {{ $currentSemester }}.
        </div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped shadow-lg" style="font-size: 1.1rem;">
                <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162; border-radius: 20px 20px 0 0;">
                    <tr>
                        <th class="text-black">Subject Code</th>
                        <th class="text-black">Subject Name</th>
                        <th class="text-black">Units</th>
                        <th class="text-black">Grade</th>
                        <th class="text-black">Year Level</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($filteredGrades as $grade)
                        @php
                            $gradeValue = $grade->grade ?? 'N/A';
                            $gradeClass = is_numeric($gradeValue) && $gradeValue < 75 ? 'text-danger fw-bold' : 'text-success fw-bold';
                        @endphp
                        <tr>
                            <td>{{ $grade->subject->code }}</td>
                            <td>{{ $grade->subject->name }}</td>
                            <td>{{ $grade->subject->units }}</td>
                            <td class="{{ $gradeClass }}">{{ $gradeValue }}</td>
                            <td>{{ $grade->year_level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <h5 class="fw-bold mt-4">Semester: {{ $previousSemester }}</h5>
    @if ($previousGrades->isEmpty())
        <div class="alert alert-warning text-center mt-3">
            No grades available yet for Semester {{ $previousSemester }}.
        </div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped shadow-lg" style="font-size: 1.1rem;">
                <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162; border-radius: 20px 20px 0 0;">
                    <tr>
                        <th class="text-black">Subject Code</th>
                        <th class="text-black">Subject Name</th>
                        <th class="text-black">Units</th>
                        <th class="text-black">Grade</th>
                        <th class="text-black">Year Level</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($previousGrades as $grade)
                        @php
                            $gradeValue = $grade->grade ?? 'N/A';
                            $gradeClass = is_numeric($gradeValue) && $gradeValue < 75 ? 'text-danger fw-bold' : 'text-success fw-bold';
                        @endphp
                        <tr>
                            <td>{{ $grade->subject->code }}</td>
                            <td>{{ $grade->subject->name }}</td>
                            <td>{{ $grade->subject->units }}</td>
                            <td class="{{ $gradeClass }}">{{ $gradeValue }}</td>
                            <td>{{ $grade->year_level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


{{-- STUDENT SCHEDULE SECTION --}}
<div class="container mt-4 shadow-lg p-4 bg-white rounded">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold">
                My Schedule 📚 <i class="bi text-primary"></i>
            </h2>
            <h5 class="fw-bold text-muted"><strong>Semester:</strong> {{ $student->semester }}</h5>
        </div>
    </div>

    <!-- Schedule -->
    <div class="table-wrapper mt-3">
        @php
            $regularSubjects = $currentSemesterSubjects->filter(fn($subject) => empty($subject->special_subject));
            $specialSubjects = $currentSemesterSubjects->filter(fn($subject) => !empty($subject->special_subject));
        @endphp

        @if($regularSubjects->isEmpty() && $specialSubjects->isEmpty())
            <p class="text-center text-danger fw-bold">No subjects scheduled for this semester.</p>
        @else
            {{-- Regular Subjects Table --}}
            @if(!$regularSubjects->isEmpty())
                <h5 class="fw-bold text-center mt-4">Regular Subjects</h5>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped shadow-lg" style="font-size: 1.1rem;">
                        <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162; border-radius: 20px 20px 0 0;">
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($regularSubjects as $subject)
                                <tr>
                                    <td class="text-center">{{ $subject->code }}</td>
                                    <td class="text-center">{{ $subject->name }}</td>
                                    <td class="text-center">{{ $subject->day }}</td>
                                    <td class="text-center">{{ $subject->time }}</td>
                                    <td class="text-center">{{ $subject->room }}</td>
                                    <td class="text-center">{{ $subject->teacher ? $subject->teacher->name : 'TBA' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Special Subjects Table --}}
            @if(!$specialSubjects->isEmpty())
                <h5 class="fw-bold text-center mt-5">Special Subjects</h5>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped shadow-lg" style="font-size: 1.1rem;">
                        <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #d9534f; border-radius: 20px 20px 0 0;">
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specialSubjects as $subject)
                                <tr>
                                    <td class="text-center">{{ $subject->special_subject }}</td>
                                    <td class="text-center">{{ $subject->name }}</td>
                                    <td class="text-center">{{ $subject->day }}</td>
                                    <td class="text-center">{{ $subject->time }}</td>
                                    <td class="text-center">{{ $subject->room }}</td>
                                    <td class="text-center">{{ $subject->teacher ? $subject->teacher->name : 'TBA' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>
</div>



{{-- FOR SCHOOL CALENDAR --}}
<div class="container mt-4 shadow-lg p-4 bg-white rounded">

    <!-- Title & Description -->
    <div class="mb-4">
        <h2 class="fw-bold text-dark text-start">School Calendar 📅</h2>
        <p class="fw-bold text-muted text-start">Stay updated with the latest academic schedules.</p>
    </div>

    <!-- Table Wrapper -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm" style="font-size: 1.1rem;">
            <thead class="text-black text-center bg-white" style="border-top: 3px solid #1c9162;">
                <tr>
                    <th>Semester</th>
                    <th>School Year</th>
                    <th>Image</th>
                    <th>PDF</th>
                    <th>Word</th>
                </tr>
            </thead>
            <tbody>
                @if ($schoolCalendars)
                    <tr>
                        <td class="text-center">{{ $schoolCalendars->semester }}</td>
                        <td class="text-center">{{ $schoolCalendars->sy }}</td>
                        <td class="text-center">
                            @if ($schoolCalendars->image)
                                <img src="{{ asset('storage/' . $schoolCalendars->image) }}"
                                     alt="Calendar Image"
                                     class="img-thumbnail shadow-sm"
                                     width="80" height="80"
                                     style="cursor: pointer;"
                                     data-bs-toggle="modal"
                                     data-bs-target="#imageModal">
                            @else
                                <span class="text-muted fst-italic">No Image</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($schoolCalendars->pdf)
                                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                                    <a href="{{ asset('storage/' . $schoolCalendars->pdf) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-primary d-flex align-items-center justify-content-center">
                                        <i class="fas fa-file-pdf me-1"></i> View PDF
                                    </a>
                                    <a href="{{ asset('storage/' . $schoolCalendars->pdf) }}"
                                       download
                                       class="btn btn-sm btn-success d-flex align-items-center justify-content-center">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            @else
                                <span class="text-muted fst-italic">No PDF</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($schoolCalendars->word)  <!-- Check if Word file exists -->
                                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                                    {{-- <a href="{{ asset('storage/' . $schoolCalendars->word) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-secondary">
                                        <i class="fas fa-file-word"></i> View Word
                                    </a> --}}
                                    <a href="{{ asset('storage/' . $schoolCalendars->word) }}"
                                       download
                                       class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            @else
                                <span class="text-muted fst-italic">No Word File</span>
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4" class="text-center text-danger">No school calendar available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">School Calendar Image</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        @if(!empty($schoolCalendars->image))
                            <img src="{{ asset('storage/' . $schoolCalendars->image) }}"
                                 alt="Expanded Calendar Image"
                                 class="img-fluid rounded">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}"
                                 alt="No Image Available"
                                 class="img-fluid rounded">
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


<!-- Bulletin Board -->
<div class="container mt-4 shadow-lg p-4 bg-white rounded">
    <div class="mb-4">
        <!-- Title -->
        <h2 class="fw-bold text-dark text-start">Bulletin Board 📢</h2>
    </div>

    <!-- Table Wrapper (Responsive) -->
    <div class="table-responsive mt-3">
        @if ($announcements->isEmpty())
            <div class="alert alert-warning text-center" role="alert">
                There is no announcement uploaded.
            </div>
        @else
            <table class="table table-bordered table-striped shadow-sm" style="font-size: 1.1rem;">
                <thead class="text-black text-center bg-white" style="border-top: 3px solid #1c9162;">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Images</th>
                        <th>Action</th> <!-- New column for "Show All" button -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($announcements as $announcement)
                        @php
                            $images = $announcement->images;
                            $firstImage = $images->first();
                            $otherImages = $images->slice(1);
                            $hasMultipleImages = $images->count() > 1;
                        @endphp

                        <!-- First row (always visible) -->
                        <tr>
                            <td rowspan="1" class="align-top">{{ $announcement->title }}</td>
                            <td rowspan="1" class="align-top">{{ $announcement->description ?? 'No Description' }}</td>
                            <td class="text-center">
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image) }}"
                                         alt="Announcement Image"
                                         class="rounded-3 shadow-sm announcement-image"
                                         width="60" height="60"
                                         style="cursor: pointer; object-fit: cover;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal{{ $firstImage->id }}">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td rowspan="1" class="align-top">
                                {{-- PDF Link --}}
                                @if ($announcement->pdf)
                                <a href="{{ asset('storage/' . $announcement->pdf) }}"
                                   target="_blank"
                                   class="btn btn-sm bg-primary text-light btn-outline-secondary d-block mb-1">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                                @endif
                            </td>
                            <td rowspan="1" class="align-top">
                                {{-- Word Link --}}
                                @if ($announcement->word)
                                <a href="{{ asset('storage/' . $announcement->word) }}"
                                   target="_blank"
                                   class="btn btn-sm bg-primary text-light btn-outline-secondary d-block">
                                    <i class="fas fa-file-word"></i>Download Word
                                </a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($hasMultipleImages)
                                    <button class="btn btn-sm btn-outline-primary"
                                            onclick="showAdditionalImages({{ $announcement->id }})"
                                            id="show-btn-{{ $announcement->id }}">
                                        Show All
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Other images (hidden initially) -->
                        <tr id="additional-images-{{ $announcement->id }}" style="display: none;">
                            <td colspan="4" class="text-center">
                                @foreach ($otherImages as $image)
                                    <img src="{{ asset('storage/' . $image->image) }}"
                                         alt="Announcement Image"
                                         class="rounded-3 shadow-sm announcement-image m-1"
                                         width="60" height="60"
                                         style="cursor: pointer; object-fit: cover;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal{{ $image->id }}">
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>


<!-- Image Modals -->
<!-- Image Modals -->
@foreach ($announcements as $announcement)
    @foreach ($announcement->images as $image)
        <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Announcement Image</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $image->image) }}"
                             alt="Announcement Image"
                             class="img-fluid rounded-4">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<!-- JavaScript to Show Additional Images -->
<script>
    function showAdditionalImages(announcementId) {
        let hiddenRow = document.getElementById(`additional-images-${announcementId}`);
        let button = document.getElementById(`show-btn-${announcementId}`);

        if (hiddenRow.style.display === "none") {
            hiddenRow.style.display = "table-row";
            button.textContent = "Hide Images";
        } else {
            hiddenRow.style.display = "none";
            button.textContent = "Show All";
        }
    }
</script>
</div>
    <!-- Student Details Section -->
    {{-- <div class="card shadow-sm border-0 p-4 mb-4">
        <h2 class="fw-bold text-success mb-4 text-center text-md-start">My Basic Info</h2>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <p class="text-center text-md-start"><strong>Student ID:</strong> {{ $student->student_id }}</p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="text-center text-md-start"><strong>Email:</strong> {{ $student->email }}</p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="text-center text-md-start"><strong>Age:</strong> {{ $student->age }}</p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="text-center text-md-start"><strong>Birthdate:</strong> {{ $student->bdate }}</p>
            </div>
        </div>
    </div> --}}
<!-- Modal -->
<div class="modal fade" id="studentProfileModal" tabindex="-1" aria-labelledby="studentProfileModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body text-center">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/default-profile.png') }}"
                     alt="Profile Picture"
                     class="img-fluid"
                     style="width: 3in; height: 3in; object-fit: cover;">
            </div>
        </div>
    </div>
</div>
@endsection
