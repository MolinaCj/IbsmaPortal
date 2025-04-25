@extends('layouts.student')

@section('content')
<div class="container container-fluid bg-light py-4">

    <!-- Title -->
    <h1 class="text-center fw-bold mb-3" style="font-size: 2rem;">
        📢 Bulletin Board
    </h1>

    <!-- Table Wrapper (Responsive) -->
    <div class="table-responsive mt-4 shadow-lg p-3 rounded bg-white">
        @if ($announcements->isEmpty())
            <div class="alert alert-warning text-center fs-5" role="alert">
                There is no announcement uploaded.
            </div>
        @else
            <table class="table table-bordered table-striped" style="font-size: 1.1rem;">
                <thead class="text-black text-center bg-white" style="border-top: 3px solid #1c9162;">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Images</th>
                        <th>PDF</th>
                        <th>Word</th>
                        <th>Action</th>
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

                        <!-- First row -->
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

                        <!-- Additional images -->
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

@endsection

