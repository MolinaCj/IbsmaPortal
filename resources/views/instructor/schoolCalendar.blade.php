@extends('layouts.instructor')

@section('content')
<div class="container-fluid bg-light py-4">
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">School Calendar Image</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                @if($schoolCalendars)
                    <div class="modal-body text-center">
                        @if($schoolCalendars->image)
                            <img src="{{ asset('storage/' . $schoolCalendars->image) }}"
                                alt="Expanded Calendar Image"
                                class="img-fluid rounded">
                        @else
                            <p class="text-muted fst-italic">No image available to display.</p>
                        @endif
                    </div>
                @else
                    <div class="modal-body text-center">
                        <p class="text-muted fst-italic">No calendar data posted yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Card Container -->
    <div class="bg-white border rounded-4 shadow-lg p-4 w-100 position-relative school-calendar-card">

        <!-- Top Border -->
        <div class="top-border"></div>

        <!-- Title -->
        <div class="text-center mb-4">
            <h1 class="fw-bold text-dark">📅 School Calendar</h1>
            <p class="text-muted">Stay updated with the latest academic schedules.</p>
        </div>

        <!-- Table -->
        <div class="table-responsive" style="max-height: 60vh; overflow: auto;">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Image</th>
                        <th>PDF</th>
                        <th>Word</th>  <!-- Add Word column here -->
                    </tr>
                </thead>
                <tbody>
                    @if ($schoolCalendars)
                        <tr>
                            <td>{{ $schoolCalendars->semester }}</td>
                            <td>{{ $schoolCalendars->sy }}</td>
                            <td>
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
                            <td>
                                @if ($schoolCalendars->pdf)
                                    <div class="d-flex gap-2">
                                        <a href="{{ asset('storage/' . $schoolCalendars->pdf) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-pdf"></i> View PDF
                                        </a>
                                        <a href="{{ asset('storage/' . $schoolCalendars->pdf) }}"
                                           download
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">No PDF</span>
                                @endif
                            </td>
                            <td>
                                @if ($schoolCalendars->word)  <!-- Check if Word file exists -->
                                    <div class="d-flex gap-2">
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
                            <td colspan="5" class="text-center text-danger">No school calendar available.</td> <!-- Updated colspan to 5 for the new column -->
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



<style>
    html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Prevents vertical scrolling */
        }
    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: auto;
    }
     /* Top Border */
     .top-border {
        width: 100%;
        height: 5px;
        background-color: #16C47F;
        position: absolute;
        top: 0;
        left: 0;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    /* Ensuring it stays at the top */
    .school-calendar-card {
        max-width: 98%;
        margin: 0 auto;
        backdrop-filter: blur(10px);
        max-height: 90vh;
        overflow: hidden;
        position: relative;
    }
</style>


@endsection
