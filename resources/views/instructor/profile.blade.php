@extends('layouts.instructor')

@section('content')
<div class="container-fluid py-5">
    <!-- Profile Card -->
    <div class="card shadow-lg border-0 rounded-4 p-4 mx-auto text-center w-100 position-relative profile-card">

        <!-- Top Border (Separate Div to Ensure Visibility) -->
        <div class="top-border"></div>

        <!-- Title -->
        <h1 class="text-start px-3">Change Profile Picture</h1>

        <!-- Profile Picture Section -->
        <div class="text-center mb-4">
            <div class="border rounded-circle overflow-hidden mx-auto" style="width: 2in; height: 2in;">
                <img src="{{ $instructor->profile_picture ? asset('storage/' . $instructor->profile_picture) : asset('images/default-profile.png') }}"
                     alt="Profile Picture"
                     class="img-fluid w-100 h-100"
                     style="object-fit: cover;">
            </div>
            <form action="{{ route('instructor.uploadPp') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="file" name="profile_picture" class="form-control form-control-sm mb-2 mx-auto" style="width: 2in;">
                <button type="submit" class="btn text-white mx-auto d-block"
                        style="background-color: #16C47F; width: 2in;">Change Profile Picture</button>
            </form>
        </div>
    </div>
</div>

<!-- Hover Effect (CSS) -->
<style>
    body {
        background-color: #f8f9fa; /* Light background */
    }
    /* Ensuring the border is applied properly */
    .top-border {
        width: 100%;
        height: 5px;
        background-color: #16C47F;
        position: absolute;
        top: 0;
        left: 0;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    .profile-card {
        max-width: 100%;
        margin: 0 10px;
        border-top: 5px solid #16C47F; /* Top border */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<!-- Instructor Profile -->
        {{-- <h3 class="fw-bold mb-3 text-center" style="color: #16C47F;">Instructor Profile</h3>
        <div class="d-flex justify-content-center text-center">
            <div>
                <div class="mb-2"><strong>Name:</strong> {{ $instructor->name }}</div>
                <div class="mb-2"><strong>Email:</strong> {{ $instructor->email }}</div>
                <div class="mb-2"><strong>Cellphone No.:</strong> {{ $instructor->phoneNumber }}</div>
            </div>
        </div> --}}

{{-- <!-- Parent Information -->
        <h3 class="fw-bold mt-4 mb-3" style="color: #16C47F;">Parent's Information</h3>
        <div class="text-start d-flex justify-content-between">
            <div>
                <h4>Father's Full Name</h4>
                <div class="mb-2"><strong>First Name:</strong> {{ $student->father_first_name }}</div>
                <div class="mb-2"><strong>Middle Name:</strong> {{ $student->father_middle_name }}</div>
                <div class="mb-2"><strong>Last Name:</strong> {{ $student->father_last_name }}</div>
            </div>
            <div>
                <h4>Mother's Maiden Full Name</h4>
                <div class="mb-2"><strong>First Name:</strong> {{ $student->mother_first_name }}</div>
                <div class="mb-2"><strong>Middle Name:</strong> {{ $student->mother_middle_name }}</div>
                <div class="mb-2"><strong>Maiden Last Name:</strong> {{ $student->mother_last_name }}</div>
            </div>
        </div> --}}

        <!-- Educational Background -->
        {{-- <h3 class="fw-bold mt-4 mb-3" style="color: #16C47F;">Educational Background</h3>

        <!-- Elementary -->
        <div class="text-start mb-4">
            <h4 class="fw-bold" style="color: #000000;">Elementary</h4>
            <div class="mb-2"><strong>School Name:</strong> {{ $student->elem_school_name }}</div>
            <div class="mb-2"><strong>Year Graduated:</strong> {{ $student->elem_grad_year }}</div>
        </div>

        <!-- Secondary -->
        <div class="text-start mb-4">
            <h4 class="fw-bold" style="color: #000000;">Secondary</h4>
            <div class="mb-2"><strong>School Name:</strong> {{ $student->hs_school_name }}</div>
            <div class="mb-2"><strong>Year Graduated:</strong> {{ $student->hs_grad_year }}</div>
        </div>

        <!-- Tertiary -->
        <div class="text-start">
            <h4 class="fw-bold" style="color: #000000;">Tertiary</h4>
            <div class="mb-2"><strong>School Name:</strong> {{ $student->tertiary_school_name }}</div>
            <div class="mb-2"><strong>Year Graduated:</strong> {{ $student->tertiary_grad_year }}</div>
        </div> --}}
@endsection

