@extends('layouts.student')

@section('content')
{{-- SCHEDULE SECTION --}}
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold display-6">
                My Schedule 📚 <i class="bi text-primary"></i>
            </h2>
            <h5 class="fw-semibold text-muted fs-5"><strong>Semester:</strong> {{ $student->semester }}</h5>
        </div>
    </div>

    <!-- Schedule -->
    <div class="table-wrapper mt-3">
        @if($uniqueSubjects->isEmpty())
            <p class="text-center text-danger fw-bold fs-5">No subjects scheduled for this semester.</p>
        @else
            <div class="table-responsive mt-3" style="overflow-x: auto; white-space: nowrap;">
                <table class="table table-bordered table-striped shadow-lg schedule-table" style="font-size: 1.1rem;">
                    <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162;">
                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uniqueSubjects as $subject)
                            <tr>
                                <!-- Subject details -->
                                <td class="text-center">{{ $subject->code }}</td>
                                <td class="text-center">{{ $subject->name }}</td>
    
                                <!-- Check if schedule is an array or JSON string -->
                                @php
                                    $schedule = is_array($subject->schedule) ? $subject->schedule : json_decode($subject->schedule, true);
                                @endphp
                                <td class="text-center">
                                    @if(is_array($schedule))
                                        @foreach ($schedule as $sched)
                                            <div>{{ $sched['day'] ?? 'TBA' }}</div>
                                        @endforeach
                                    @else
                                        <div>TBA</div>
                                    @endif
                                </td>
    
                                <td class="text-center">
                                    @if(is_array($schedule))
                                        @foreach ($schedule as $sched)
                                            <div>
                                                {{ \Carbon\Carbon::parse($sched['start_time'])->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($sched['end_time'])->format('g:i A') }}
                                            </div>
                                        @endforeach
                                    @else
                                        <div>TBA</div>
                                    @endif
                                </td>
    
                                <td class="text-center">{{ $subject->room ?? 'TBA' }}</td>
                                <td class="text-center">{{ $subject->teacher->name ?? 'TBA' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            @if($currentSemesterSpecialSubjects->isNotEmpty())
                <h4 class="fw-bold mt-4">Special Subjects</h4>
                <div class="table-responsive mt-3" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-bordered table-striped shadow-lg schedule-table" style="font-size: 1.1rem;">
                        <thead class="text-black text-center" style="background-color: white; border-top: 3px solid #1c9162;">
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currentSemesterSpecialSubjects as $specialSubject)
                                <tr>
                                    <td class="text-center">{{ $specialSubject->subject->code }}</td>
                                    <td class="text-center">{{ $specialSubject['name'] }}</td>
    
                                    @php
                                        $schedule = is_array($specialSubject['schedule']) ? $specialSubject['schedule'] : json_decode($specialSubject['schedule'], true);
                                    @endphp
                                    <td class="text-center">
                                        @if(is_array($schedule))
                                            @foreach ($schedule as $sched)
                                                <div>{{ $sched['day'] ?? 'TBA' }}</div>
                                            @endforeach
                                        @else
                                            <div>TBA</div>
                                        @endif
                                    </td>
    
                                    <td class="text-center">
                                        @if(is_array($schedule))
                                            @foreach ($schedule as $sched)
                                                <div>
                                                    {{ \Carbon\Carbon::parse($sched['start_time'])->format('g:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($sched['end_time'])->format('g:i A') }}
                                                </div>
                                            @endforeach
                                        @else
                                            <div>TBA</div>
                                        @endif
                                    </td>
    
                                    <td class="text-center">{{ $specialSubject['room'] ?? 'TBA' }}</td>
                                    <td class="text-center">{{ $specialSubject['teacher']->name ?? 'TBA' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>
    
    
    
    
    
    
      
</div>



@endsection
