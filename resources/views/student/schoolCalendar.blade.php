@extends('layouts.student')

@section('content')
<div class="container container-fluid bg-light py-4">
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fs-5">School Calendar Image</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    @if ($schoolCalendars && $schoolCalendars->image)
                        <img src="{{ asset('storage/' . $schoolCalendars->image) }}"
                             alt="Expanded Calendar Image"
                             class="img-fluid rounded shadow">
                    @else
                        <img src="{{ asset('path/to/placeholder/image.jpg') }}"
                             alt="No Image Available"
                             class="img-fluid rounded shadow">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Title -->
    <div class="text-center mb-4">
        <h1 class="fw-bold text-dark display-6">📅 School Calendar</h1>
        <p class="text-muted fs-5">Stay updated with the latest academic schedules.</p>
    </div>

    <!-- Table Wrapper -->
    <div class="table-responsive shadow-lg p-2 rounded bg-white">
        <table class="table table-bordered table-striped" style="font-size: 1.1rem;">
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
                                     class="img-thumbnail shadow"
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
                        <td colspan="4" class="text-center text-danger fs-5">No school calendar available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>




{{-- <style>
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
        background-color: #1c9162;
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
</style> --}}
@endsection


