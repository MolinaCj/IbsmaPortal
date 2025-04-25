@extends('layouts.student')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-5 fs-5">
    <!-- Profile Picture Section -->
    <div class="text-center mb-4">
        <div class="border rounded-circle overflow-hidden mx-auto shadow"
             style="width: 150px; height: 150px;"
             data-bs-toggle="modal"
             data-bs-target="#profileModal">
            <img id="previewImage"
                 src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/default-profile.png') }}"
                 alt="Profile Picture"
                 class="img-fluid w-100 h-100"
                 style="object-fit: cover;">
        </div>

        <!-- Upload Form -->
        <form action="{{ route('student.uploadPp') }}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf
            <input type="file" name="profile_picture" class="form-control form-control-sm mb-2 mx-auto shadow-sm fs-6" style="max-width: 200px;" onchange="previewSelectedImage(event)">
            <button type="submit" class="btn text-white mx-auto d-block fs-5" style="background-color: #16C47F; width: 200px;">Upload</button>
        </form>
    </div>

    <!-- Header -->
    <h2 class="fw-bold mb-3 text-black text-center fs-2">🎓 Student Profile</h2>

    <!-- Personal Information -->
    <div class="border-bottom border-3 border-dark mb-2" style="border-color: #1c9162 !important; margin-top: 15px;"></div>
    <h3 class="fw-bold mb-3 text-black text-start fs-3">👤 Personal Information</h3>
    <div class="row text-start">
        <div class="col-md-6">
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Student ID:</strong> {{ $student->student_id }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Name:</strong> {{ $student->first_name }} {{$student->middle_name}} {{ $student->last_name }} {{ $student->suffix }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Age:</strong> {{ $student->age }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Sex:</strong> {{ $student->sex }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Civil Status:</strong> {{ $student->civil_status }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Birthday:</strong> {{ $student->bdate }}</div>
        </div>
        <div class="col-md-6">
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Email:</strong> {{ $student->email }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Place of Birth:</strong> {{ $student->bplace }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2">
                <strong>Address:</strong>
                {{ $student->address }},
                {{ $barangayName }},
                {{ $cityName }},
                {{ $provinceName }},
                {{ $regionName }}
            </div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Department:</strong> {{ $student->department->name }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Year Level:</strong> {{ $student->year_level }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Contact Number:</strong> {{ $student->cell_no }}</div>
        </div>
    </div>

    <!-- Parent Information -->
    <div class="border-bottom border-3 border-dark mb-2" style="border-color: #1c9162 !important; margin-top: 15px;"></div>
    <h3 class="fw-bold mt-4 mb-3 text-black text-start fs-3">👨‍👩‍👧‍👦 Parent's Information</h3>
    <div class="row text-start">
        <div class="col-md-6">
            <h4 class="fw-bold text-start text-muted fs-4">Father's Full Name</h4>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>First Name:</strong> {{ $student->father_first_name }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Middle Name:</strong> {{ $student->father_middle_name }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Last Name:</strong> {{ $student->father_last_name }}</div>
        </div>
        <div class="col-md-6">
            <h4 class="fw-bold text-start text-muted fs-4">Mother's Maiden Full Name</h4>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>First Name:</strong> {{ $student->mother_first_name }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Middle Name:</strong> {{ $student->mother_middle_name }}</div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2"><strong>Maiden Last Name:</strong> {{ $student->mother_last_name }}</div>
        </div>
    </div>

    <!-- Educational Background -->
    <div class="border-bottom border-3 border-dark mb-2" style="border-color: #1c9162 !important; margin-top: 15px;"></div>
    <h3 class="fw-bold mt-4 mb-3 text-black text-start fs-3">🏫 Educational Background</h3>

    <div class="row text-start">
        <div class="col-md-4">
            <h4 class="fw-bold text-start text-muted fs-4">Elementary</h4>
            <div class="p-3 border-bottom rounded shadow-sm mb-2" style="min-height: 120px; display: flex; align-items: center;">
                <strong>School Name:</strong> {{ $student->elem_school_name }}
            </div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2" style="min-height: 120px; display: flex; align-items: center;">
                <strong>Year Graduated:</strong> {{ $student->elem_grad_year }}
            </div>
        </div>
        <div class="col-md-4">
            <h4 class="fw-bold text-start text-muted fs-4">Secondary</h4>
            <div class="p-3 border-bottom rounded shadow-sm mb-2" style="min-height: 120px; display: flex; align-items: center;">
                <strong>School Name:</strong> {{ $student->hs_school_name }}
            </div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2" style="min-height: 120px; display: flex; align-items: center;">
                <strong>Year Graduated:</strong> {{ $student->hs_grad_year }}
            </div>
        </div>
        <div class="col-md-4">
            <h4 class="fw-bold text-start text-muted fs-4">Tertiary</h4>
            <div class="p-3 border-bottom rounded shadow-sm mb-2" style="min-height: 120px; display: flex; align-items: center;">
                <strong>School Name:</strong> {{ $student->tertiary_school_name }}
            </div>
            <div class="p-3 border-bottom rounded shadow-sm mb-2" style="min-height: 120px; display: flex; align-items: center;">
                <strong>Year Graduated:</strong> {{ $student->tertiary_grad_year }}
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPreviewImage"
                     src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/default-profile.png') }}"
                     alt="Profile Picture"
                     class="img-fluid"
                     style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>
<script>
    function previewSelectedImage(event) {
        const input = event.target;
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            // Update profile preview and modal image
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('modalPreviewImage').src = e.target.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection

