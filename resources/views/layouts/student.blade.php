<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
     /* ===== Base Styles ===== */
body {
    font-family: 'Arial', sans-serif;
    padding: 0;
    margin: 0;
    background-color: #f8f9fa;
    overflow-x: hidden;
}

/* ===== Sidebar Styles ===== */
.sidebar {
    height: 100vh;
    background:  #ffffff; /* Dark navy to slate blue */
    position: fixed;
    top: 0;
    left: -250px; /* Hidden by default */
    width: 250px;
    z-index: 1050;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
    /* border-right:2px solid #000000; */
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
}

/* Show sidebar when active */
.sidebar.active {
    left: 0;
}

/* ===== Nav Links ===== */
.sidebar .nav-link {
    color: #000000;
    padding: 15px 25px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 5px 10px;
    border-radius: 12px;
    transition: all 0.3s ease-in-out;
    text-decoration: none;
}

/* ===== Active Link - Glow & Border ===== */
.sidebar .nav-link.active {
    /* background:  #13a56b; */
    color: #000000;
    padding-left: 30px;
    border-left: 5px solid #1c9162;
    /* border-right: 5px solid #1c9162; */
    /* box-shadow: 0 4px 10px #009559; */
    border-radius: 12px;
    font-weight: bold;
    transition: all 0.3s ease;
}

/* ===== Hover Effect with Soft Glow ===== */
.sidebar .nav-link:hover {
    background:#0FAF80;
    color: #fff;
    transform: translateX(8px);
    box-shadow: 0 8px 25px #009559;
}

/* ===== Icon Animation ===== */
.sidebar .nav-link i {
    font-size: 1.5rem;
    transition: transform 0.3s ease, color 0.3s ease;
}

/* Hover Effect on Icon */
.sidebar .nav-link:hover i {
    transform: scale(1.2);
    color: #FFC857;
}

/* ===== Main Content ===== */
.main-content {
    padding-top: 70px;
    position: relative;
    z-index: 1;
    transition: margin-left 0.3s ease-in-out;
}

/* ===== Burger Icon - Dark Navy Blue ===== */
.burger-icon {
    position: fixed;
    top: 15px;
    left: 15px;
    font-size: 2rem;
    cursor: pointer;
    color: #1E293B;
    z-index: 1100;
    transition: transform 0.3s ease, color 0.3s ease;
}

/* Hover Rotation Effect */
.burger-icon:hover {
    transform: rotate(90deg);
    color: #1c9162;
}

/* ===== Overlay ===== */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1049;
    display: none;
}

/* Show overlay when sidebar is active */
.overlay.active {
    display: block;
}

/* ===== Responsive Design ===== */
@media (max-width: 992px) {
    .sidebar {
        left: -250px;
    }

    .sidebar.active {
        left: 0;
    }

    .overlay.active {
        display: block;
    }

    .main-content {
        margin-left: 0 !important;
    }
}

/* Hide burger icon on desktop and keep sidebar open */
@media (min-width: 993px) {
    .burger-icon {
        display: none;
    }

    .sidebar {
        left: 0;
    }

    .overlay {
        display: none;
    }

    .main-content {
        margin-left: 250px;
    }
}



    </style>
</head>

<body>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- Burger Icon -->
<div class="burger-icon" id="burger-icon">
    <i class="bi bi-list"></i>
</div>

<div class="d-flex">
    <!-- Sidebar -->
<nav id="sidebar" class="sidebar">
    <div class="position-sticky pt-3 mt-5">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.studentDashboard') ? 'active' : '' }}"
                   href="{{ route('student.studentDashboard') }}">
                    <i class="bi bi-house-door"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}"
                   href="{{ route('student.profile') }}">
                    <i class="bi bi-person-fill"></i> <span>Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.grades') ? 'active' : '' }}"
                   href="{{ route('student.grades') }}">
                    <i class="bi bi-person-badge"></i> <span>Grades</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.schedule') ? 'active' : '' }}"
                   href="{{ route('student.schedule') }}">
                    <i class="bi bi-book"></i> <span>Schedule</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.schoolCalendar') ? 'active' : '' }}"
                   href="{{ route('student.schoolCalendar') }}">
                    <i class="bi bi-calendar"></i> <span>School Calendar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.announcements') ? 'active' : '' }}"
                   href="{{ route('student.announcements') }}">
                    <i class="bi bi-megaphone"></i> <span>Bulletin Board</span>
                </a>
            </li>
            <li class="nav-item">
                <form id="student-logout-form" action="{{ route('student.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('student-logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</nav>

    <!-- Main Content -->
    <div class="container-fluid main-content p-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>


    // Burger icon to toggle sidebar
    const burgerIcon = document.getElementById('burger-icon');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    burgerIcon.addEventListener('click', function () {
        sidebar.classList.toggle('active'); // Show/hide sidebar
        overlay.classList.toggle('active'); // Show/hide overlay
    });

    // Close sidebar when clicking outside (on overlay)
    overlay.addEventListener('click', function () {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Close sidebar when clicking a nav-link (for mobile)
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 992) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });
</script>

</body>

</html>
