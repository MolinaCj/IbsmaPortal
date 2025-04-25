{{-- @extends('layouts.instructor')

@section('content')
<div class="container">
    <h2 class="mb-4">Instructor Schedule</h2>

    <div class="card">
        <div class="card-header">Schedule Images</div>
        <div class="card-body">
            @if ($scheduleImages->isEmpty())
                <p>No schedule images found.</p>
            @else
                <div class="row">
                    @foreach ($scheduleImages as $schedule)
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $schedule->name }}</h5>
                                    @if ($schedule->image)
                                        <img src="{{ asset('storage/' . $schedule->image) }}" 
                                             class="img-thumbnail schedule-img" 
                                             alt="Schedule Image" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#scheduleModal{{ $schedule->id }}">
                                        
                                        <!-- Bootstrap Modal -->
                                        <div class="modal fade" id="scheduleModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Schedule Image</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/' . $schedule->image) }}" class="img-fluid modal-img" alt="Schedule Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>No Image Available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .schedule-img {
        width: 100px;
        height: auto;
        cursor: pointer;
        transition: 0.3s;
    }
    .schedule-img:hover {
        transform: scale(1.1);
    }
    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: auto;
    }
</style>
@endsection --}}
@extends('layouts.instructor')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">{{ $teacher->name }}'s Weekly Schedule</h2>
    
    <div class="mb-4 text-center">
        <button class="btn btn-success" onclick="window.print()">Print Schedule</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr class="bg-success text-white">
                <th class="text-center">Time</th>
                @foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <th class="text-center">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>

        @php
            $baseColors = ['#f20000', '#0022ff', '#e600e2', '#ffee00', '#00fac0', '#ff7300', '#ff3300'];
            $count = count($baseColors);
            $timeSlots = range(7, 18);  // Time slots from 7:00 AM to 6:00 PM
            
            // Pre-process the schedule to identify overlapping subjects
            $processedSchedule = [];
            $skipCells = [];
            
            foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day) {
                $processedSchedule[$day] = [];
                $daySubjects = $weekSchedule[$day];
                
                // Group overlapping subjects
                $subjectGroups = [];
                $processedSubjects = [];
                
                foreach ($daySubjects as $subject) {
                    if (in_array($subject->id, $processedSubjects)) continue;
                    
                    $group = [$subject];
                    $processedSubjects[] = $subject->id;
                    
                    // Find overlapping subjects
                    foreach ($daySubjects as $otherSubject) {
                        if ($subject->id == $otherSubject->id || in_array($otherSubject->id, $processedSubjects)) continue;
                        
                        $overlap = false;
                        
                        // Check if schedules overlap
                        foreach ($subject->filtered_schedule as $schedule) {
                            if ($overlap) break;
                            
                            $subjectStart = \Carbon\Carbon::parse($schedule['start_time']);
                            $subjectEnd = \Carbon\Carbon::parse($schedule['end_time']);
                            
                            foreach ($otherSubject->filtered_schedule as $otherSchedule) {
                                $otherStart = \Carbon\Carbon::parse($otherSchedule['start_time']);
                                $otherEnd = \Carbon\Carbon::parse($otherSchedule['end_time']);
                                
                                // Check for overlap
                                if (($otherStart < $subjectEnd) && ($otherEnd > $subjectStart)) {
                                    $overlap = true;
                                    break;
                                }
                            }
                        }
                        
                        if ($overlap) {
                            $group[] = $otherSubject;
                            $processedSubjects[] = $otherSubject->id;
                        }
                    }
                    
                    $subjectGroups[] = $group;
                }
                
                // Process each group
                foreach ($subjectGroups as $group) {
                    $isConflict = count($group) > 1;
                    
                    // Find earliest start and latest end
                    $earliestStart = 24;
                    $latestEnd = 0;
                    
                    foreach ($group as $subject) {
                        foreach ($subject->filtered_schedule as $schedule) {
                            $startHour = \Carbon\Carbon::parse($schedule['start_time'])->hour;
                            $endHour = \Carbon\Carbon::parse($schedule['end_time'])->hour;
                            
                            $earliestStart = min($earliestStart, $startHour);
                            $latestEnd = max($latestEnd, $endHour);
                        }
                    }
                    
                    // Add to processed schedule
                    $processedSchedule[$day][] = [
                        'start' => $earliestStart,
                        'end' => $latestEnd,
                        'subjects' => $group,
                        'isConflict' => $isConflict
                    ];
                    
                    // Mark cells to skip
                    for ($hour = $earliestStart + 1; $hour < $latestEnd; $hour++) {
                        $skipCells[] = $day . '-' . $hour;
                    }
                }
            }
        @endphp

        <tbody>
            @foreach($timeSlots as $hour)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromTime($hour, 0)->format('g:i A') }}</td>

                    @foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        @php
                            $cellKey = $day . '-' . $hour;
                            if (in_array($cellKey, $skipCells)) continue;
                            
                            // Find schedule group that starts at this hour
                            $scheduleGroup = null;
                            foreach ($processedSchedule[$day] as $group) {
                                if ($group['start'] == $hour) {
                                    $scheduleGroup = $group;
                                    break;
                                }
                            }
                        @endphp

                        @if ($scheduleGroup)
                            @php
                                $duration = $scheduleGroup['end'] - $scheduleGroup['start'];
                                $isConflict = $scheduleGroup['isConflict'];
                            @endphp
                            
                            <td rowspan="{{ $duration }}" class="p-2 align-top border text-sm {{ $isConflict ? 'conflict-cell' : '' }}">
                                @if ($isConflict)
                                    <div class="conflict-banner">
                                        <span class="badge bg-danger">⚠ SCHEDULE CONFLICT</span>
                                    </div>
                                @endif
                                
                                @foreach ($scheduleGroup['subjects'] as $subject)
                                    @php
                                        $baseColor = $baseColors[$subject->id % $count];
                                    @endphp
                                    
                                    <div class="subject-block" style="background-color: {{ $baseColor }}; color: #fff; padding: 5px; margin-bottom: {{ !$loop->last ? '5px' : '0' }}; border-radius: 4px;">
                                        <strong>{{ $subject->name }}</strong><br>
                                        <span class="text-muted" style="background-color: white; padding: 2px 4px; border-radius: 3px;">
                                            {{ $subject->department->name ?? 'No Department' }}
                                        </span><br>
                                        
                                        @if ($subject->is_special)
                                            <span class="badge bg-warning text-dark">Special/Tutorial</span><br>
                                        @endif

                                        @foreach ($subject->filtered_schedule as $schedule)
                                            {{ \Carbon\Carbon::parse($schedule['start_time'])->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($schedule['end_time'])->format('g:i A') }}<br>
                                        @endforeach
                                        Room: {{ $subject->room ?? '—' }}
                                    </div>
                                    
                                    @if (!$loop->last)
                                        <div class="conflict-divider"></div>
                                    @endif
                                @endforeach
                            </td>
                        @else
                            <td class="text-center text-muted">—</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .schedule-img {
        width: 100px;
        height: auto;
        cursor: pointer;
        transition: 0.3s;
    }

    .schedule-img:hover {
        transform: scale(1.2);
    }

    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: auto;
    }
    
    .conflict-cell {
        border: 2px solid #dc3545 !important;
        background-color: rgba(255, 220, 220, 0.2);
    }
    
    .conflict-banner {
        text-align: center;
        margin-bottom: 5px;
    }
    
    .conflict-divider {
        border-top: 2px dashed #dc3545;
        margin: 5px 0;
    }
    
    .subject-block {
        border: 1px solid rgba(0,0,0,0.2);
    }
    
    /* PRINTTING SECTION  */
    @media print {
        @page {
            size: A4 landscape; /* A4 landscape */
            margin: 10mm; /* Slight margin around content */
            margin-left: -10%;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-size: 10px;
            overflow: hidden;
        }

        .btn, .mb-4 {
            display: none !important;
        }

        .container {
            padding: 6px;
            width: 100vw;
            max-width: 100vw;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            margin: 4px 0 10px;
        }

        /* Adjusting the table wrapper for better centering */
        .table-wrapper {
            display: flex;
            justify-content: center; /* Horizontally center the table */
            width: 100%;
            margin-left: 0%; /* Reducing the left margin for better centering */
            margin-right: 5%; /* Adding a bit of right margin */
        }

        table {
            width: 100%; /* Make table take up the available width */
            table-layout: auto; /* Let the table dynamically fill the page */
            border-collapse: collapse;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 4px;
            text-align: center;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            min-width: 90px; /* Ensures columns are not too narrow */
        }

        th {
            background-color: #28a745 !important;
            color: #fff !important;
            font-size: 10px;
        }

        td {
            font-size: 9px;
        }

        td div, td strong, td span {
            display: block;
            margin-bottom: 2px;
            line-height: 1.2;
            font-size: inherit;
        }

        td hr {
            border: none;
            border-top: 1px dashed #333;
            margin: 2px 0;
        }

        .badge {
            font-size: 8px !important;
            padding: 2px 4px !important;
        }
        
        .conflict-cell {
            border: 2px solid #dc3545 !important;
        }
        
        .conflict-banner {
            margin-bottom: 3px;
        }
        
        .conflict-divider {
            border-top: 2px dashed #dc3545;
            margin: 3px 0;
        }
        
        .subject-block {
            margin-bottom: 3px;
            padding: 3px !important;
        }
    }
</style>
@endsection










{{-- @php
    // Function to convert RGB to HSL
    function rgbToHsl($hex) {
        $r = hexdec(substr($hex, 1, 2)) / 255;
        $g = hexdec(substr($hex, 3, 2)) / 255;
        $b = hexdec(substr($hex, 5, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;

        if ($max != $min) {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2.0 - $max - $min) : $d / ($max + $min);

            if ($max == $r) {
                $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
            } elseif ($max == $g) {
                $h = ($b - $r) / $d + 2;
            } else {
                $h = ($r - $g) / $d + 4;
            }

            $h /= 6;
        }

        return ['h' => $h, 's' => $s, 'l' => $l];
    }

    // Function to convert HSL back to RGB
    function hslToRgb($h, $s, $l) {
        $r = $l;
        $g = $l;
        $b = $l;

        if ($s != 0) {
            $temp2 = $l < 0.5 ? $l * (1 + $s) : ($l + $s) - ($l * $s);
            $temp1 = 2 * $l - $temp2;

            $r = hueToRgb($temp1, $temp2, $h + 1 / 3);
            $g = hueToRgb($temp1, $temp2, $h);
            $b = hueToRgb($temp1, $temp2, $h - 1 / 3);
        }

        return ['r' => round($r * 255), 'g' => round($g * 255), 'b' => round($b * 255)];
    }

    // Helper function for HSL to RGB conversion
    function hueToRgb($temp1, $temp2, $h) {
        if ($h < 0) $h += 1;
        if ($h > 1) $h -= 1;
        if ($h < 1 / 6) return $temp1 + ($temp2 - $temp1) * 6 * $h;
        if ($h < 1 / 2) return $temp2;
        if ($h < 2 / 3) return $temp1 + ($temp2 - $temp1) * (2 / 3 - $h) * 6;
        return $temp1;
    }
@endphp --}}


<style>
    .schedule-img {
        width: 100px;
        height: auto;
        cursor: pointer;
        transition: 0.3s;
    }

    .schedule-img:hover {
        transform: scale(1.2);
    }

    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: auto;
    }
    /* PRINTTING SECTION  */
    @media print {
    @page {
        size: A4 landscape; /* A4 landscape */
        margin: 10mm; /* Slight margin around content */
        margin-left: -10%;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        font-size: 10px;
        overflow: hidden;
    }

    .btn, .mb-4 {
        display: none !important;
    }

    .container {
        padding: 6px;
        width: 100vw;
        max-width: 100vw;
        box-sizing: border-box;
    }

    h2 {
        text-align: center;
        font-size: 16px;
        margin: 4px 0 10px;
    }

    /* Adjusting the table wrapper for better centering */
    .table-wrapper {
        display: flex;
        justify-content: center; /* Horizontally center the table */
        width: 100%;
        margin-left: 0%; /* Reducing the left margin for better centering */
        margin-right: 5%; /* Adding a bit of right margin */
    }

    table {
        width: 100%; /* Make table take up the available width */
        table-layout: auto; /* Let the table dynamically fill the page */
        border-collapse: collapse;
        font-size: 10px;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px 4px;
        text-align: center;
        vertical-align: top;
        word-wrap: break-word;
        overflow-wrap: break-word;
        min-width: 90px; /* Ensures columns are not too narrow */
    }

    th {
        background-color: #28a745 !important;
        color: #fff !important;
        font-size: 10px;
    }

    td {
        font-size: 9px;
    }

    td div, td strong, td span {
        display: block;
        margin-bottom: 2px;
        line-height: 1.2;
        font-size: inherit;
    }

    td hr {
        border: none;
        border-top: 1px dashed #333;
        margin: 2px 0;
    }

    .badge {
        font-size: 8px !important;
        padding: 2px 4px !important;
    }
}





</style>




