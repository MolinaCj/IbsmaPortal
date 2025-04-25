@extends('layouts.student')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold display-6">
                My grades <i class="bi bi-clipboard-check text-primary"></i>
            </h2>
            <h5 class="fw-semibold text-muted fs-5"><strong>Year Level: </strong>{{ $student->year_level }}</h5>
        </div>
    </div>

    @php
        $filteredGrades = $grades;
    @endphp

    <h4 class="fw-bold mt-4">📘 Semester: {{ $currentSemester }}</h4>
    @if ($filteredGrades->isEmpty())
        <div class="alert alert-warning text-center mt-3 fs-5">
            No grades available yet for Semester {{ $currentSemester }}.
        </div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped shadow-lg" style="font-size: 1.1rem;">
                <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162;">
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
                            <td class="{{ $gradeClass }}">
                                {{ $gradingLocked ? $gradeValue : 'N/A' }}
                            </td>
                            <td>{{ $grade->year_level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <h4 class="fw-bold mt-4">📗 Semester: {{ $previousSemester }}</h4>
    @if ($previousGrades->isEmpty())
        <div class="alert alert-warning text-center mt-3 fs-5">
            No grades available yet for Semester {{ $previousSemester }}.
        </div>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped shadow-lg" style="font-size: 1.1rem;">
                <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162;">
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




{{-- <div class="container mt-4">

    <h5 class="text-center">Year Level: {{ $student->year_level }} | Semester: {{ $currentSemester }}</h5>

    @php

        $filteredGrades = $grades->where('semester', $currentSemester)->where('year_level', $student->year_level);
    @endphp

    @if ($filteredGrades->isEmpty())
        <div class="alert alert-warning text-center">
            No grades available yet for Semester {{ $currentSemester }}.
        </div>
    @else
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark text-center">
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Grade</th>
                    <th>Year Level</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($filteredGrades as $grade)
                    @php
                        $gradeValue = $grade->grade ?? 'N/A';
                        $gradeClass = is_numeric($gradeValue) && $gradeValue < 75 ? 'text-danger' : 'text-success';
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
    @endif
</div> --}}
{{-- <h2 class="text-center">{{ $student->first_name }}'s Grades</h2> --}}
{{-- // Filter grades to only include the current semester and year level --}}
@endsection
