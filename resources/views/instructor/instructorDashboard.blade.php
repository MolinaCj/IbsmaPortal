@extends('layouts.instructor')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center shadow-sm rounded-4 p-4 p-lg-5"
         style="background-color: #16C47F; color: white; position: relative;">
        <!-- Left Side - Welcome Message -->
        <div class="text-center text-lg-start">
            <h1 class="fw-bold mb-2">Welcome Back!</h1>
            <p class="fs-5">
                Glad to have you here,
                <strong>
                    @php
                        $title = 'Instructor'; // Default title if no name is provided
                        if (isset($teacher->gender)) {
                            if ($teacher->gender === 'Male') {
                                $title = 'Mr.';
                            } elseif ($teacher->gender === 'Female') {
                                $title = ($teacher->civil_status === 'Married' || $teacher->civil_status === 'Widowed') ? 'Mrs.' : 'Ms.';
                            }
                        }
                    @endphp
                    {{ $title }} {{ $teacher->name ?? '' }}
                </strong>.
            </p>
            <p class="text-white-75">Stay focused and inspire your students every day.</p>

            <!-- Quote of the Day -->
            <div class="mt-3">
                <p class="fs-4 text-white fw-bold"><em>"{{ $quote }}"</em></p>
            </div>
        </div>


        <!-- Profile Picture and Info -->
        <div class="d-flex flex-column align-items-center text-center mt-4 mt-lg-0">
            <div class="border rounded-circle overflow-hidden"
                 style="width: 1.5in; height: 1.5in; cursor: pointer;"
                 data-bs-toggle="modal" data-bs-target="#profileModal">
                <img src="{{ $teacher->profile_picture ? asset('storage/' . $teacher->profile_picture) : asset('images/default-profile.png') }}"
                     alt="Profile Picture"
                     class="img-fluid w-100 h-100"
                     style="object-fit: cover;">
            </div>
            <div class="mt-3">
                <h5 class="fw-bold mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
                <p class="text-light mb-0" style="font-size: 25px">Instructor</p>
            </div>
        </div>
    </div>
    @php
    $currentYear = DB::table('settings')->value('current_school_year') ?? date('Y');
    $subjectsByYear = $subjects->groupBy('year');
    $gradients = [
        'linear-gradient(135deg, #FFA726, #FB8C00)',
        'linear-gradient(135deg, #42A5F5, #1E88E5)',
        'linear-gradient(135deg, #AB47BC, #8E24AA)',
        'linear-gradient(135deg, #26A69A, #00897B)',
        'linear-gradient(135deg, #EF5350, #E53935)',
        'linear-gradient(135deg, #5C6BC0, #3949AB)',
        'linear-gradient(135deg, #66BB6A, #43A047)',
        'linear-gradient(135deg, #EC407A, #D81B60)'
    ];
    @endphp

<div class="py-4">
    @foreach ($subjectsByYear as $year => $subjectsGroup)
        @php
            $subjectsByDept = $subjectsGroup->groupBy('department.name');
            $yearStudentIds = collect();
            foreach ($subjectsGroup as $subject) {
                foreach ($subject->students as $student) {
                    if ($student->grades->where('school_year', $currentYear)->where('subject_id', $subject->id)->count() > 0) {
                        $yearStudentIds->push($student->student_id);
                    }
                }
            }
            $yearStudentCount = $yearStudentIds->unique()->count();
        @endphp

        <div class="card shadow-lg rounded-4 border-0 mb-4 p-3 bg-white">
            <div class="mb-3">
                <h4 class="text-success fw-bold mb-1">🎓 Year Level: {{ $year }}</h4>
                <p class="mb-2" style="font-size: 0.9rem;">📊 Total Students: {{ $yearStudentCount }}</p>
            </div>

            @foreach ($subjectsByDept as $deptName => $deptSubjects)
                @php
                    $deptStudentIds = collect();
                    foreach ($deptSubjects as $subject) {
                        foreach ($subject->students as $student) {
                            if ($student->grades->where('school_year', $currentYear)->where('subject_id', $subject->id)->count() > 0) {
                                $deptStudentIds->push($student->student_id);
                            }
                        }
                    }
                    $deptStudentCount = $deptStudentIds->unique()->count();
                @endphp

                <div class="mb-3">
                    <h5 class="fw-semibold text-primary mb-2">🏛 {{ $deptName }}</h5>

                    <div class="d-flex flex-wrap gap-3">
                        @php $colorIndex = 0; @endphp
                        @foreach ($deptSubjects as $subject)
                            @php
                                $maleCount = $subject->students->filter(fn($s) =>
                                    $s->sex === 'Male' && $s->grades->where('school_year', $currentYear)->where('subject_id', $subject->id)->count() > 0
                                )->count();

                                $femaleCount = $subject->students->filter(fn($s) =>
                                    $s->sex === 'Female' && $s->grades->where('school_year', $currentYear)->where('subject_id', $subject->id)->count() > 0
                                )->count();

                                $total = $maleCount + $femaleCount;
                                $gradient = $gradients[$colorIndex++ % count($gradients)];
                                $chartId = 'chart_' . $subject->id;
                            @endphp

                            <div class="subject-card text-white" style="background: {{ $gradient }};">
                                <div class="card-body p-2 d-flex flex-column justify-content-center align-items-center text-center" style="height: 100%;">
                                    <h6 class="card-title fw-bold mb-2" style="font-size: 0.95rem;">{{ $subject->name }}</h6>
                                    <canvas id="{{ $chartId }}" width="90" height="90" style="margin-bottom: 0.5rem;"></canvas>
                                    <p style="font-size: 0.8rem; margin: 0;">👥 {{ $total }}</p>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
                                    new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Male', 'Female'],
                                            datasets: [{
                                                label: 'Students',
                                                data: [{{ $maleCount }}, {{ $femaleCount }}],
                                                backgroundColor: ['#3498db', '#e91e63'],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                }
                                            },
                                            cutout: '60%'
                                        }
                                    });
                                });
                            </script>
                        @endforeach
                    </div>

                    <div class="mt-2 text-end">
                        <span class="badge bg-success p-1" style="font-size: 0.8rem;">Total in Department: {{ $deptStudentCount }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .subject-card {
        width: 160px;
        min-width: 160px;
        height: 200px;
        border-radius: 1rem;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
    }

    .subject-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        filter: brightness(1.05);
    }

    body {
        background-color: #f4f6f9;
    }
</style>







    <!-- School Calendar Section -->
    <div class="mt-4 p-4 bg-white shadow rounded-4 position-relative overflow-hidden">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-success mb-0">📅 School Calendar</h4>
            {{-- @if ($calendar && ($calendar->image || $calendar->pdf))
                <span class="badge bg-success fs-6">Updated</span>
            @endif --}}
        </div>

        @if ($calendar)
            @if ($calendar->image)
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $calendar->image) }}"
                         alt="School Calendar Image"
                         class="img-fluid w-100 rounded-4 shadow-sm"
                         style="transition: transform 0.3s ease-in-out; cursor: pointer;"
                         onclick="openSchoolCalendarModal('{{ asset('storage/' . $calendar->image) }}')">
                </div>
            @elseif ($calendar->pdf)
                <div class="bg-light p-3 rounded-4 shadow-sm text-center">
                    <iframe src="{{ asset('storage/' . $calendar->pdf) }}"
                            width="100%"
                            height="500px"
                            class="rounded-4 border"
                            style="box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);">
                    </iframe>
                    <a href="{{ asset('storage/' . $calendar->pdf) }}" target="_blank" class="btn btn-outline-success mt-3">
                        <i class="bi bi-file-earmark-pdf"></i> View Full PDF
                    </a>
                </div>
            @else
                <div class="text-center text-muted fst-italic py-4">
                    <i class="bi bi-calendar-x fs-1"></i>
                    <p class="mt-2">No School Calendar posted.</p>
                </div>
            @endif
        @else
            <div class="text-center text-muted fst-italic py-4">
                <i class="bi bi-calendar-x fs-1"></i>
                <p class="mt-2">No School Calendar posted.</p>
            </div>
        @endif
    </div>

    <!-- School Calendar Image Modal -->
    <div class="modal fade" id="schoolCalendarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">School Calendar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="schoolCalendarImage" src="" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for School Calendar Modal -->
    <script>
        function openSchoolCalendarModal(imageSrc) {
            document.getElementById('schoolCalendarImage').src = imageSrc;
            var myModal = new bootstrap.Modal(document.getElementById('schoolCalendarModal'));
            myModal.show();
        }
    </script>

    <!-- Bulletin Board -->
<div class="mt-4 shadow-lg p-4 bg-white rounded">
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

<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body text-center">
                <img src="{{ $teacher->profile_picture ? asset('storage/' . $teacher->profile_picture) : asset('images/default-profile.png') }}"
                     alt="Profile Picture"
                     class="img-fluid"
                     style="width: 3in; height: 3in; object-fit: cover;">
            </div>
        </div>
    </div>
</div>


{{-- <script>
    document.getElementById('profileDropdownToggle').addEventListener('click', function() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    window.addEventListener('click', function(e) {
        if (!document.getElementById('profileDropdownToggle').contains(e.target)) {
            document.getElementById('profileDropdown').style.display = 'none';
        }
    });
</script> --}}


@endsection
