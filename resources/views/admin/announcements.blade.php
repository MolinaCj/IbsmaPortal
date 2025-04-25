@extends('layouts.app')

@section('content')

<div class="">

<div class="mx-4">

    <h1 class="text-center mb-5" style="font-weight: bold; color: #000000; font-size: 2.5rem;">Manage Bulletin Board 📢</h1>

<div class="">
    {{-- <h1><i class="bi bi-megaphone-fill" style="color: #16c47f;"></i>Bulletin Board</h1> --}}



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

    <div class="card border-0 shadow rounded-4 overflow-hidden mb-4">
        <div class="card-header text-white fw-bold py-3" style="background-color: #16C47F;">Existing Announcements</div>
        <div class="card-body p-4">
            <table class="table table-bordered align-middle text-center">
                <thead class="bg-light" style="color: #16C47F;">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($announcements as $announcement)
                        @php
                            $images = $announcement->images;
                            $firstImage = $images->first(); // First image
                            $otherImages = $images->slice(1); // Remaining images
                            $hasMultipleImages = $images->count() > 1; // Check for multiple images
                        @endphp

                        <!-- First row (Always visible) -->
                        <tr>
                            <td rowspan="1" style="vertical-align: top;">{{ $announcement->title }}</td>
                            <td rowspan="1" style="vertical-align: top;">{{ $announcement->description ?? 'No Description' }}</td>

                            <!-- First image -->
                            <td class="text-center">
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image) }}"
                                         alt="Announcement Image"
                                         class="rounded-circle shadow-sm"
                                         width="60" height="60"
                                         style="object-fit: cover; cursor:pointer;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal{{ $firstImage->id }}">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>

                            <!-- Actions (Includes Delete & Show All Button) -->
                            <td class="text-center">
                                @if ($hasMultipleImages)
                                    <button class="btn btn-sm btn-outline-primary"
                                            onclick="showAdditionalImages({{ $announcement->id }})"
                                            id="show-btn-{{ $announcement->id }}">
                                        Show All
                                    </button>
                                @endif

                                @if ($announcement->pdf)
                                    <a href="{{ asset('storage/' . $announcement->pdf) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-1">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                @endif

                                @if ($announcement->word)
                                    <a href="{{ asset('storage/' . $announcement->word) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-1">
                                        <i class="fas fa-file-word"></i> Word
                                    </a>
                                @endif

                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $announcement->id }}">
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Other images (Hidden initially, will be shown in the same row) -->
                        <tr id="additional-images-{{ $announcement->id }}" style="display: none;">
                            <td colspan="4" class="text-center">
                                @foreach ($otherImages as $image)
                                    <img src="{{ asset('storage/' . $image->image) }}"
                                         alt="Announcement Image"
                                         class="rounded-circle shadow-sm m-1"
                                         width="60" height="60"
                                         style="cursor: pointer; object-fit: cover;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal{{ $image->id }}">
                                @endforeach
                            </td>
                        </tr>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="confirmDeleteModal{{ $announcement->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header text-white" style="background-color: #DC3545;">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this announcement?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="fixed-bottom d-flex justify-content-end p-4">
        <button type="button" class="btn text-white px-4 py-2 shadow-lg" style="background-color: #16C47F;" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">+ Add Announcement</button>
    </div>

    {{-- MODAL FOR ADDING NEW Announcements --}}
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4" style="transform: scale(0.8); transition: transform 0.3s ease-in-out;">
                <div class="modal-header text-white bg-success">
                    <h5 class="modal-title">Add New Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" id="description" name="description" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Images</label>
                            <input type="file" id="images" name="images[]" class="form-control" multiple accept="image/*">
                            <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                        <div class="text-center fw-bold text-muted">OR</div>
                        <div class="mb-3">
                            <label for="pdf" class="form-label">Upload PDF File</label>
                            <input type="file" id="pdf" name="pdf" class="form-control" accept="application/pdf">
                        </div>
                        <div class="text-center fw-bold text-muted">OR</div>
                        <div class="mb-3">
                            <label for="word" class="form-label">Upload Word File</label>
                            <input type="file" id="word" name="word" class="form-control" accept=".doc,.docx">
                        </div>
                        <button type="submit" class="btn text-white w-100 bg-success" style="background-color: #16C47F;">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById("images").addEventListener("change", function(event) {
        let previewContainer = document.getElementById("imagePreview");
        previewContainer.innerHTML = "";

        Array.from(event.target.files).forEach(file => {
            if (file.type.startsWith("image/")) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.width = "100px";
                    img.style.height = "100px";
                    img.style.objectFit = "cover";
                    img.classList.add("rounded", "border");
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
    </script>

</div>

<script>
    document.getElementById('addAnnouncementModal').addEventListener('shown.bs.modal', function () {
        document.querySelector('#addAnnouncementModal .modal-content').style.transform = 'scale(1)';
    });

    document.getElementById('addAnnouncementModal').addEventListener('hidden.bs.modal', function () {
        document.querySelector('#addAnnouncementModal .modal-content').style.transform = 'scale(0.8)';
    });
</script>

<!-- Image Modals -->
@foreach ($announcements as $announcement)
    @foreach ($announcement->images as $image)
        <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content rounded-4">
                    <div class="modal-header text-white" style="background-color: #16C47F;">
                        <h5 class="modal-title">Announcement Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="Announcement Image" class="img-fluid rounded-4">
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
