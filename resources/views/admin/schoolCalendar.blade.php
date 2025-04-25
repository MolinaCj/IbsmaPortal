@extends('layouts.app');

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center min-vh-100 p-4">

    <!-- Page Title -->
    <h1 class="text-4xl font-extrabold text-center announcement-title">
        <i class="bi bi-calendar-fill" style="color: #16C47F"></i> School Calendar
    </h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Upload School Calendar -->
    <div class="bg-white shadow-lg rounded-4 p-5 w-100 max-w-4xl announcement-card mt-4">
        <h3 class="text-center text-white py-2 rounded-4" style="background-color: #16C47F;">Add New Calendar</h3>
        <form action="{{ route('schoolCalendar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="semester" class="form-label fw-bold">Semester</label>
                <select class="form-select shadow-sm" id="semester" name="semester" required>
                    <option value="" disabled selected>Select Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="Summer Class">Summer Class</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="sy" class="form-label fw-bold">School Year</label>
                <input type="text" id="sy" name="sy" class="form-control shadow-sm" placeholder="YYYY-YYYY">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label fw-bold">Upload Image</label>
                <input type="file" id="image" name="image" class="form-control shadow-sm" accept="image/*">
            </div>
            <div class="text-center fw-bold text-muted">OR</div>
            <div class="mb-3">
                <label for="pdf" class="form-label fw-bold">Upload PDF</label>
                <input type="file" id="pdf" name="pdf" class="form-control shadow-sm" accept="application/pdf">
            </div>
            <div class="text-center fw-bold text-muted">OR</div>
            <div class="mb-3">
                <label for="word" class="form-label fw-bold">Upload Word File</label>
                <input type="file" id="word" name="word" class="form-control shadow-sm" accept=".doc,.docx">
            </div>

            <button type="submit" class="btn text-white w-100 py-2 fw-bold" style="background-color: #16C47F;">Submit</button>
        </form>
    </div>

    <!-- School Calendar Table -->
    <div class="bg-white shadow-lg rounded-4 p-5 w-100 max-w-4xl announcement-card mt-4">
        <h3 class="text-center text-white py-2 rounded-4" style="background-color: #16C47F;">School Calendar</h3>

        <div class="table-responsive mt-3">
            <table class="table table-hover announcement-table text-center">
                <thead class="table-success">
                    <tr>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Image</th>
                        <th>PDF</th>
                        <th>Word File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schoolCalendars as $schoolCalendar)
                        <tr>
                            <td class="fw-semibold">{{ $schoolCalendar->semester }}</td>
                            <td>{{ $schoolCalendar->sy }}</td>
                            <td>
                                @if ($schoolCalendar->image)
                                    <img src="{{ asset('storage/' . $schoolCalendar->image) }}" alt="School Calendar Image"
                                         width="60" height="60"
                                         class="rounded-3 shadow-sm announcement-image"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imageModal{{ $schoolCalendar->id }}">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                @if ($schoolCalendar->pdf)
                                    <a href="{{ asset('storage/' . $schoolCalendar->pdf) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-pdf"></i> View PDF
                                    </a>
                                    <a href="{{ asset('storage/' . $schoolCalendar->pdf) }}" download class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">No PDF</span>
                                @endif
                            </td>
                            <td>
                                @if ($schoolCalendar->word)
                                    {{-- <a href="{{ asset('storage/' . $schoolCalendar->word) }}" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="fas fa-file-word"></i> View Word
                                    </a> --}}
                                    <a href="{{ asset('storage/' . $schoolCalendar->word) }}" download class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">No Word File</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Image Modals (Outside Table) -->
    @foreach ($schoolCalendars as $schoolCalendar)
        @if ($schoolCalendar->image)
            <div class="modal fade" id="imageModal{{ $schoolCalendar->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded-4">
                        <div class="modal-header text-white" style="background-color: #16C47F;">
                            <h5 class="modal-title">School Calendar Image</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $schoolCalendar->image) }}" alt="Class Image" class="img-fluid rounded-4">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

</div>

<style>
    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: auto;
    }
</style>



{{-- inside ng last tr --}}
{{-- <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $schoolCalendar->id }}">
                                    Edit
                                </button>
                            </td> --}}
                            {{-- <td>
                                <form action="{{ route('schoolCalendar.destroy', $schoolCalendar->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td> --}}

{{-- if i-aapply before mag end ang for each--}}
<!-- Edit Modal -->
                        {{-- <div class="modal fade" id="editModal{{ $schoolCalendar->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit School Calendar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.schoolCalendar.update', $schoolCalendar->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label for="semester" class="form-label">Semester</label>
                                                <select class="form-control" id="semester" name="semester" required>
                                                    <option value="" disabled selected>Select Semester</option>
                                                    <option value="1st Semester" {{ $schoolCalendar->semester == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                                    <option value="2nd Semester" {{ $schoolCalendar->semester == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="sy" class="form-label">School Year</label>
                                                <input type="text" class="form-control" id="sy" name="sy" value="{{ $schoolCalendar->sy }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="image" class="form-label">Change Image</label>
                                                <input type="file" class="form-control" id="image" name="image">
                                                @if ($schoolCalendar->image)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/' . $schoolCalendar->image) }}" alt="Current Image" width="100" class="img-thumbnail">
                                                    </div>
                                                @endif
                                            </div>

                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
@endsection
