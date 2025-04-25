@extends('layouts.app')

@section('content')
<div class="">
    <!-- Top Profile Container with small side gaps -->
    <div class="d-flex justify-content-between align-items-center mb-5 p-4 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #16C47F, #42A5F5); color: white; ">
        <div>
            <h1 class="display-4 fw-bold mb-0">Be Productive!!</h1>
            <p class="lead">Stay on top of your tasks and manage everything seamlessly.</p>
        </div>
        <div class="d-flex align-items-center">
            {{-- <img src="https://via.placeholder.com/80" alt="Profile Picture" class="rounded-circle me-3" style="width: 80px; height: 80px;">
            <div>
                <h5 class="fw-bold mb-1">Admin Name</h5>
                <p class="text-light mb-0">Administrator</p>
            </div> --}}
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4" style="margin: 0px 20px;">
    <!-- Animated Donut Charts in Dashboard Cards -->
    <style>
        @keyframes fillChart {
            from { --fill: 0deg; }
            to { --fill: var(--target); }
        }
        </style>

        <div class="row g-4" style="margin: 0px 20px;">
            @php
                $gradients = [
                    'linear-gradient(135deg, #FFA726, #FB8C00)',
                    'linear-gradient(135deg, #42A5F5, #1E88E5)',
                    'linear-gradient(135deg, #AB47BC, #8E24AA)',
                    'linear-gradient(135deg, #26A69A, #00897B)',
                    'linear-gradient(135deg, #EF5350, #E53935)',
                    'linear-gradient(135deg, #5C6BC0, #3949AB)',
                    'linear-gradient(135deg, #66BB6A, #43A047)',
                    'linear-gradient(135deg, #EC407A, #D81B60)'
                ];
                $colorIndex = 0;
            @endphp

            @foreach ($studentsByDepartment as $index => $dept)
                @php
                    $total = $dept->male + $dept->female;
                    $malePercent = $total > 0 ? round(($dept->male / $total) * 100) : 0;
                    $femalePercent = 100 - $malePercent;
                    $degree = ($malePercent / 100) * 360;
                @endphp

                <div class="col-md-3">
                    <div class="card shadow-lg border-0 rounded-4 hover-effect"
                         style="background: {{ $gradients[$colorIndex % count($gradients)] }}; color: white;">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold" style="font-size: 1.2rem;">
                                🎓 {{ $dept->department }}
                            </h5>

                            <!-- Animated Donut -->
                            <div style="margin: 20px auto; width: 100px; height: 100px; position: relative;">
                                <div id="chart-{{ $index }}" style="
                                    --target: {{ $degree }}deg;
                                    --fill: 0deg;
                                    width: 100px;
                                    height: 100px;
                                    border-radius: 50%;
                                    background: conic-gradient(
                                        #16C47F var(--fill),
                                        rgba(183, 208, 255, 0.9) 0deg
                                    );
                                    animation: fillChart 1.5s ease-out forwards;
                                    animation-delay: {{ $index * 0.3 }}s;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    position: relative;
                                ">
                                    <div style="
                                        width: 66px;
                                        height: 66px;
                                        background-color: white;
                                        border-radius: 50%;
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        justify-content: center;
                                        color: black;
                                        font-size: 0.7rem;
                                        font-weight: 600;
                                        line-height: 1.1;
                                    ">
                                        M: {{ $dept->male }}<br>
                                        F: {{ $dept->female }}
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <p class="mt-2">📊 Total:
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                    {{ $total }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @php $colorIndex++; @endphp
            @endforeach
        </div>
        </div>

        <div class="container mt-4">
            <div class="row g-4 justify-content-center">
                {{-- STUDENT STATISTICS --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 hover-effect">
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <i class="fas fa-user-graduate fa-lg me-2"></i>
                            <h5 class="mb-0">Student Statistics</h5>
                        </div>
                        <div class="card-body text-center">
                            <!-- Container to control canvas size -->
                            <div style="width: 5in; height: 4in; margin: auto;">
                                <canvas id="studentDoughnutChart"></canvas>
                            </div>
                            <div class="mt-3">
                                <h5 class="fw-bold">Grand Total: {{ $totalMale + $totalFemale }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INSTRUCTOR STATISTICS --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 hover-effect">
                        <div class="card-header bg-info text-white d-flex align-items-center">
                            <i class="fas fa-chalkboard-teacher fa-lg me-2"></i>
                            <h5 class="mb-0">Instructor Statistics</h5>
                        </div>
                        <div class="card-body text-center">
                            <div style="width: 5in; height: 4in; margin: auto;">
                                <canvas id="instructorDoughnutChart"></canvas>
                            </div>
                            <div class="mt-3">
                                <h5 class="fw-bold">Total Active Instructors: {{ $totalInstructors }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

 {{-- Chart.js Script --}}
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script>
     // Overall counts
     const totalMale = {{ $totalMale }};
     const totalFemale = {{ $totalFemale }};
     const totalOverall = totalMale + totalFemale;

     // Combined Doughnut Chart: Male, Female
     new Chart(document.getElementById('studentDoughnutChart'), {
         type: 'doughnut',
         data: {
             labels: ['Male', 'Female'], // Labels for Male and Female only
             datasets: [{
                 label: 'Student Count',
                 data: [totalMale, totalFemale], // Data for Male and Female
                 backgroundColor: ['#42A5F5', '#EF5350'], // Soft colors for Male and Female
                 borderWidth: 0,
             }]
         },
         options: {
             responsive: true,
             plugins: {
                 legend: {
                     position: 'bottom',
                     labels: {
                         boxWidth: 20,
                         font: { size: 14 }
                     }
                 },
                 tooltip: {
                     callbacks: {
                         label: function(tooltipItem) {
                             let label = tooltipItem.label;
                             let value = tooltipItem.raw;
                             return label + ': ' + value;
                         }
                     }
                 },
             },
             cutoutPercentage: 70, // Makes it a donut chart
             layout: {
                 padding: 20, // Padding around the chart for better spacing
             },
             animation: {
                 duration: 1000,
                 easing: 'easeOutBounce'
             }
         }
     });
 </script>
{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Overall counts for instructors
    const instructorMale = {{ $instructorMale }};
    const instructorFemale = {{ $instructorFemale }};
    const totalInstructors = instructorMale + instructorFemale;

    // Combined Doughnut Chart for Instructors: Male, Female
    new Chart(document.getElementById('instructorDoughnutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female'], // Labels for Male and Female instructors
            datasets: [{
                label: 'Instructor Count',
                data: [instructorMale, instructorFemale], // Data for Male and Female instructors
                backgroundColor: ['#42A5F5', '#EF5350'], // Soft colors for Male and Female
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let label = tooltipItem.label;
                            let value = tooltipItem.raw;
                            return label + ': ' + value;
                        }
                    }
                },
            },
            cutoutPercentage: 70, // Makes it a donut chart
            layout: {
                padding: 20, // Padding around the chart for better spacing
            },
            animation: {
                duration: 1000,
                easing: 'easeOutBounce'
            }
        }
    });
</script>
</div>

    <style>
        body {
            background-color: #f4f6f9;
        }
        .hover-effect {
            transition: transform 0.3s ease-in-out;
        }
        .hover-effect:hover {
            transform: translateY(-10px);
        }
        .card-title {
            font-size: 1.5rem;
        }
        .btn {
            transition: background-color 0.3s ease-in-out;
        }
    </style>
@endsection
