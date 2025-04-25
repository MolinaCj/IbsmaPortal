@extends('layouts.instructor')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-start p-4">

    <!-- Announcements Card -->
    <div class="bg-white shadow-lg rounded-4 p-5 w-100 max-w-4xl announcement-card">

        <!-- Title -->
        <h1 class="text-4xl font-extrabold text-center announcement-title" style="color: black;">
            Bulletin Board <span style="color: inherit;">📢</span>
        </h1>


        <!-- Table -->
        <div class="table-responsive mt-4">
            @if ($announcements->isEmpty())
                <div class="alert alert-warning text-center" role="alert">
                    There is no announcement uploaded.
                </div>
            @else
                <table class="table table-bordered table-striped">
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
                                $firstImage = $images->first(); // Get the first image
                                $otherImages = $images->slice(1); // Get the rest of the images
                                $hasMultipleImages = $images->count() > 1; // Check if there are multiple images
                            @endphp

                            <!-- First row (always visible) -->
                            <tr>
                                <td rowspan="1" class="align-top">{{ $announcement->title }}</td>
                                <td rowspan="1" class="align-top">{{ $announcement->description ?? 'No Description' }}</td>
                                <td class="text-center">
                                    @if ($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage->image) }}"
                                             alt="Announcement Image"
                                             class="rounded-3 shadow-sm announcement-image mb-2"
                                             width="60" height="60"
                                             style="cursor: pointer; object-fit: cover;"
                                             data-bs-toggle="modal"
                                             data-bs-target="#imageModal{{ $firstImage->id }}">
                                    @else
                                        <span class="text-muted d-block mb-2">No Image</span>
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


                                <!-- Action Button Column (Only Shows if Multiple Images Exist) -->
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

                            <!-- Other images (hidden initially, will be shown in the same row) -->
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
</div>


<style>
    body {
        background-color: #f8f9fa; /* Light background */
    }
    .announcement-card {
        /* border-top: 4px solid #16C47F; */
        transition: transform 0.2s ease-in-out;
    }
    .announcement-card:hover {
        transform: scale(1.02);
    }
    .announcement-table th {
        color: #16C47F;
    }
    .announcement-image {
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
    }
    .announcement-image:hover {
        transform: scale(1.1);
    }
</style>
@endsection
