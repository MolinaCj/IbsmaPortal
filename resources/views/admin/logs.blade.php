@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4" style="font-size: 2.5rem;">Login Attempt Logs</h1>

    <!-- Button to toggle log visibility -->
    <div class="mb-4">
        <button id="toggleStudentLogs" class="btn btn-success btn-lg px-4 py-2">Show Student Login Attempts</button>
        <button id="toggleInstructorLogs" class="btn btn-primary btn-lg px-4 py-2 ml-2">Show Instructor Login Attempts</button>
        <button id="toggleAdminLogs" class="btn btn-warning btn-lg px-4 py-2 ml-2">Show Admin Login Attempts</button>
        <button id="showAllLogs" class="btn btn-secondary btn-lg px-4 py-2 ml-2">Show All Logs</button>
    </div>

    <!-- Student Login Attempts Table -->
    <div id="studentLogs" class="d-none">
        <h3 class="mb-3" style="font-size: 2rem;">Student Login Attempts</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Status</th>
                    <th>Attempted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentLoginAttempts as $attempt)
                    <tr
                        @if (!App\Models\Student::where('email', $attempt->email)->exists())
                            class="table-danger"
                        @elseif ($attempt->status == 'success')
                            style="background-color: #16C47F; color: white;"
                        @endif
                    >
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->email }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->ip_address }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->user_agent }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->status }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Instructor Login Attempts Table -->
    <div id="instructorLogs" class="d-none mt-4">
        <h3 class="mb-3" style="font-size: 2rem;">Instructor Login Attempts</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Status</th>
                    <th>Attempted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instructorLoginAttempts as $attempt)
                    <tr
                        @if (!App\Models\Teacher::where('email', $attempt->email)->exists())
                            class="table-danger"
                        @elseif ($attempt->status == 'success')
                            style="background-color: #16C47F; color: white;"
                        @endif
                    >
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->email }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->ip_address }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->user_agent }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->status }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->attempted_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Admin Login Attempts Table -->
    <div id="adminLogs" class="d-none mt-4">
        <h3 class="mb-3" style="font-size: 2rem;">Admin Login Attempts</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Status</th>
                    <th>Attempted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adminLoginAttempts as $attempt)
                    <tr
                        @if (!App\Models\Admin::where('username', $attempt->username)->exists())
                            class="table-danger"
                        @elseif ($attempt->status == 'success')
                            style="background-color: #16C47F; color: white;"
                        @endif
                    >
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->username }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->ip_address }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->user_agent }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->status }}</td>
                        <td style="font-size: 1.1rem; padding: 1rem;">{{ $attempt->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    const sectionButtons = {
        studentLogs: 'toggleStudentLogs',
        instructorLogs: 'toggleInstructorLogs',
        adminLogs: 'toggleAdminLogs'
    };

    // Function to hide all sections
    function hideAllSections() {
        Object.keys(sectionButtons).forEach(section => {
            document.getElementById(section).classList.add('d-none');
        });
    }

    // Reset all button texts to their "Show" versions
    function resetAllButtonTexts() {
        Object.entries(sectionButtons).forEach(([sectionId, buttonId]) => {
            document.getElementById(buttonId).textContent = `Show ${formatSectionLabel(sectionId)} Login Attempts`;
        });
    }

    // Toggle visibility of specific sections
    function toggleSection(sectionId, buttonId) {
        const section = document.getElementById(sectionId);
        const isCurrentlyVisible = !section.classList.contains('d-none');

        hideAllSections();
        resetAllButtonTexts();

        if (!isCurrentlyVisible) {
            section.classList.remove('d-none');
            document.getElementById(buttonId).textContent = `Hide ${formatSectionLabel(sectionId)} Login Attempts`;
        }
    }

    // Helper to format section names nicely
    function formatSectionLabel(sectionId) {
        return sectionId.replace('Logs', '').charAt(0).toUpperCase() + sectionId.replace('Logs', '').slice(1);
    }

    // Show all logs
    let allLogsVisible = false; // Track toggle state for Show All Logs

document.getElementById('showAllLogs').addEventListener('click', function () {
    const allSections = Object.keys(sectionButtons);
    const showAllButton = document.getElementById('showAllLogs');

    if (!allLogsVisible) {
        // Show all logs
        allSections.forEach(section => {
            document.getElementById(section).classList.remove('d-none');
        });

        // Reset individual buttons to "Show..." state
        resetAllButtonTexts();

        showAllButton.textContent = 'Hide All Logs';
        allLogsVisible = true;
    } else {
        // Hide all logs
        allSections.forEach(section => {
            document.getElementById(section).classList.add('d-none');
        });

        showAllButton.textContent = 'Show All Logs';
        allLogsVisible = false;
    }
});

    // Add event listeners
    Object.entries(sectionButtons).forEach(([sectionId, buttonId]) => {
        document.getElementById(buttonId).addEventListener('click', function () {
            toggleSection(sectionId, buttonId);
        });
    });
</script>
@endsection
