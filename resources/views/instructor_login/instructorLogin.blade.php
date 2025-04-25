<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{asset ('css/instructor_login.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
 {{-- SCREEN LOADER --}}
    <!-- Loading Screen -->
    <div id="loading-screen" class="d-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 bg-white">
        <img src="{{ asset('storage/ibsmalogo.png') }}" alt="Loading" class="loader-image img-fluid">
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    let loader = document.getElementById("loading-screen");

    if (loader) {
        // Ensure loader is visible on page load
        loader.style.display = "flex";

        // Prevent scrolling while loader is visible
        document.body.style.overflow = "hidden";

        // Force scroll to top after page loads
        window.addEventListener("load", function () {
            setTimeout(() => {
                loader.style.opacity = "0"; // Smooth fade-out
                document.body.style.overflow = "auto"; // Restore scrolling

                setTimeout(() => {
                    loader.style.display = "none"; // Hide instead of removing
                    loader.style.zIndex = "-1"; // ✅ Lower z-index
                    loader.classList.add("hidden"); // ✅ Add hidden class
                    window.scrollTo(0, 0); // Scroll to top after hiding loader
                }, 500); // Delay for transition effect
            }, 2000); // Loader stays for 2 seconds before disappearing
        });

        // Show loader on form submission
        let form = document.querySelector("form");
        if (form) {
            form.addEventListener("submit", function () {
                loader.style.display = "flex";
                loader.style.opacity = "1"; // Reset opacity
                document.body.style.overflow = "hidden"; // Disable scrolling
            });
        }
    }
});

// Function to fade out loader smoothly
function fadeOutLoader(callback) {
    let loader = document.getElementById("loading-screen");
    if (!loader) return;

    loader.style.opacity = "0";
    setTimeout(() => {
        loader.style.display = "none";
        loader.style.zIndex = "-1"; // ✅ Lower z-index
        if (callback) callback();
    }, 800);
}
    </script>
<body>
     {{-- MODIFIED MARCH 28 --}}
     <section class="navbar">
        <!-- Bootstrap Navbar -->
        <nav class="navbar navbar-expand-lg bg-white shadow-sm px-3">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <!-- Logo and Title -->
                <div class="d-flex align-items-center flex-grow-1">
                    <img src="{{ asset('image/logo ibs,a.png') }}" alt="Logo" class="logos me-2" style="height: 50px;">
                    <h5 class="m-0 institute-text">
                        <span class="text-green">INSTITUTE</span> OF BUSINESS SCIENCE <br>
                        AND <span class="text-green">MEDICAL ARTS</span>
                    </h5>
                </div>

                <!-- Desktop Navigation -->
                <div class="collapse navbar-collapse justify-content-end d-none d-lg-flex">
                    <ul class="navbar-nav gap-3">
                        <li class="nav-item"><a class="nav-link" href="#">HOME</a></li>
                        <li class="nav-item"><a class="nav-link scroll-to-about" href="#about-section">ABOUT US</a></li>
                        <li class="nav-item"><a class="nav-link" href="#course">COURSES</a></li>
                        <li class="nav-item"><a class="nav-link open-modal" data-modal="mission-modal" href="#">MISSION & VISION</a></li>
                        <li class="nav-item"><a class="nav-link" href="#activity">ACTIVITIES</a></li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Login Button -->
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#customInstructorLoginModal">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>

                    <!-- Mobile Menu Button -->
                    <div class="d-lg-none d-flex flex-column justify-content-between burger-menu" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                        <span class="bg-success rounded" style="height: 4px; width: 30px;"></span>
                        <span class="bg-success rounded" style="height: 4px; width: 30px;"></span>
                        <span class="bg-success rounded" style="height: 4px; width: 30px;"></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Sidebar Menu -->
        <!-- Mobile Sidebar Menu -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" style="max-height: 100vh; background-color: #16C47F;">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-white fw-bold">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column gap-3">
                <a href="#" class="nav-link text-white fw-bold d-flex align-items-center gap-2 active">
                    <i class="bi bi-house-door-fill"></i> HOME
                </a>
                <a href="#course" class="nav-link text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-book-fill"></i> COURSES
                </a>
                <a href="#activity" class="nav-link text-white fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event-fill"></i> ACTIVITIES
                </a>
                <a href="#mission-vision-section" class="nav-link text-white fw-bold d-flex align-items-center gap-2 open-modal" data-modal="mission-modal">
                    <i class="bi bi-flag-fill"></i> MISSION & VISION
                </a>
                <a href="#about-section" class="nav-link text-white fw-bold d-flex align-items-center gap-2 scroll-to-about">
                    <i class="bi bi-info-circle-fill"></i> ABOUT US
                </a>
            </div>
        </div>


    </section>

    <!-- JavaScript for Menu Toggle -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let mobileMenuToggle = document.getElementById("mobileMenuToggle");
            let mobileMenu = document.getElementById("mobileMenu");
            let closeMenu = document.querySelector(".btn-close");
            let links = document.querySelectorAll(".offcanvas-body .nav-link");

            // Open Mobile Menu
            // mobileMenuToggle.addEventListener("click", function () {
            //     let bsOffcanvas = new bootstrap.Offcanvas(mobileMenu);
            //     bsOffcanvas.show();
            // });

            // Close Menu on Button Click
            closeMenu.addEventListener("click", function () {
                let bsOffcanvas = bootstrap.Offcanvas.getInstance(mobileMenu);
                bsOffcanvas.hide();
            });

            // Keep Active Link Highlighted
            links.forEach(link => {
                link.addEventListener("click", function () {
                    links.forEach(l => l.classList.remove("active")); // Remove active from all
                    this.classList.add("active"); // Add active to clicked link
                });
            });
        });
    </script>

        <!-- Custom Instructor Login Modal -->
<!-- Custom Instructor Login Modal -->
<div class="modal fade instructor-login-modal" id="customInstructorLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customInstructorLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="customInstructorLoginModalLabel">
                    <i class="bi bi-person-lock"></i>Login
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Login Form) -->
            <div class="modal-body p-4">
                <form action="{{ route('instructor.login.submit') }}" method="POST" id="instructorLoginForm">
                    @csrf
                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="instructor-email" class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="instructor-email" class="form-control shadow-sm rounded-end" placeholder="Enter email" required autocomplete="email">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="instructor-password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="bi bi-lock"></i></span>
                            <input type="password" id="instructor-password" name="password" class="form-control shadow-sm rounded-end" placeholder="Enter password" required>
                            <button type="button" class="btn btn-outline-secondary toggle-instructor-password">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-3">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>

                <!-- Feedback for login errors -->
                <div id="login-error-feedback" class="alert alert-danger text-center fw-bold mt-2" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const modalEl = document.getElementById('customInstructorLoginModal');
    modalEl.addEventListener('shown.bs.modal', () => {
        document.getElementById('instructor-email').focus();
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const loginForm = document.getElementById('instructorLoginForm');
        const feedback = document.getElementById('login-error-feedback');

        if (!loginForm || !feedback) return;

        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(loginForm);

            fetch("{{ route('instructor.login.submit') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value,
                    "X-User-Type": "instructor",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    if (data.type === 'locked') {
                        let m = Math.floor(data.remaining_seconds / 60);
                        let s = data.remaining_seconds % 60;
                        feedback.textContent = `Account locked. Try again in ${m}m ${s}s.`;
                    } else if (data.type === 'inactive') {
                        feedback.textContent = data.message || "Your account is inactive.";
                    } else if (data.type === 'attempts') {
                        feedback.textContent = data.message || "Incorrect credentials.";
                    } else {
                        feedback.textContent = data.message || "Invalid login attempt.";
                    }
                    feedback.style.display = 'block';
                } else {
                    feedback.textContent = "";
                    feedback.style.display = 'none';
                    showInstructorOtpModal(event); // your existing OTP handler
                }
            })
            .catch(err => {
                feedback.textContent = "An error occurred. Please try again.";
                feedback.style.display = 'block';
                console.error(err);
            });
        });
    });
</script>

    {{-- EDITED MARCH 24 --}}
    <!-- Mission Modal -->
{{-- <div id="mission-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Mission</h2>
        <p>Institute of  Business, Science, and Medical Arts exists to develop well-rounded professionals with desirable traits excelling in leadership in education, business, medical, and technical fields through competent and relevant instruction, research, and the creation of center of knowledge for their chosen fields.</p>
    </div>
</div> --}}

<!-- Vision Modal -->
{{-- <div id="vision-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Vision</h2>
        <p>Institute of Business, Science, and Medical Arts envision to sustain her leadership in health science, business, computer education whose graduates are exposed to holistic education, technology-based instruction, and vigorously pursue through research, the discovery of new knowledge responsive to the needs of the global community.</p>
    </div>
</div> --}}



<section class="body" style="margin-top: 100px;">
    <!-- Background Container -->
    <div class="position-relative mx-auto" style="width: 90%; max-width: 1700px; margin: 50px auto; border-radius: 20px; overflow: hidden; min-height: 850px; display: flex; align-items: center; justify-content: center;">
        <!-- Background Image Slider -->
        <div class="position-absolute w-100 h-100" style="background: url('{{ asset('image/background.jpg') }}') no-repeat center center/cover; transition: background-image 1s ease-in-out;"></div>

        <!-- Dark Overlay -->
        <div class="position-absolute w-100 h-100" style="background: rgba(0, 0, 0, 0.5);"></div>

        <!-- Aesthetic Heading in Top Left -->
        <h1 class="position-absolute text-white fw-bold" style="top: 20px; left: 20px; font-size: 26px; text-transform: uppercase; letter-spacing: 2px; padding: 12px 25px; border-radius: 10px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.2); transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
            WELCOME TO IBSMA PORTAL
        </h1>

        <!-- Content Container -->
        <div class="position-absolute d-flex flex-wrap justify-content-between" style="top: 80%; left: 50%; transform: translate(-50%, -50%); gap: 20px; width: 80%;">
            <!-- Content Box 1 -->
            <div class="text-center p-4" style="background: rgba(255, 255, 255, 0.15); border-radius: 15px; flex: 1; color: white; backdrop-filter: blur(10px); transition: transform 0.3s ease, background 0.3s ease;">
                <h2 style="font-size: 22px; margin-bottom: 10px; color: #16C47F;"><i class="fa-solid fa-graduation-cap" style="font-size: 24px; color: #16C47F; margin-right: 8px;"></i> Learn with Experts</h2>
                <p style="font-size: 16px; margin-bottom: 15px;">Gain knowledge from industry-leading instructors who provide hands-on training and real-world insights.</p>
            </div>

            <!-- Content Box 2 -->
            <div class="text-center p-4" style="background: rgba(255, 255, 255, 0.15); border-radius: 15px; flex: 1; color: white; backdrop-filter: blur(10px); transition: transform 0.3s ease, background 0.3s ease;">
                <h2 style="font-size: 22px; margin-bottom: 10px; color: #16C47F;"><i class="fa-solid fa-users" style="font-size: 24px; color: #16C47F; margin-right: 8px;"></i> Join a Community</h2>
                <p style="font-size: 16px; margin-bottom: 15px;">Connect with like-minded learners and professionals, participate in events, and expand your network.</p>
            </div>
        </div>
    </div>
</section>

{{-- ADDDED MARCH 24 --}}
<!-- About Us Section -->
<section class="about-section text-center text-white py-5 position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-start" data-aos="fade-right">
                <h1 class="fw-bold display-4">ABOUT US</h1>
                <p class="lead mt-3">
                    Institute of Business, Science, and Medical Arts is committed to developing well-rounded professionals
                    excelling in leadership across education, business, medical, and technical fields through
                    innovative instruction, research, and community engagement.
                </p>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="{{ asset('image/ibsmaimg.jpg')}}" alt="IBSMA Building" class="img-fluid rounded shadow-lg animate-img">
            </div>
        </div>
    </div>
</section>

<section id="course" class="courses" style="min-height: 100vh;">
    <!-- Courses Section Title -->
 <div class="title">
     <h1 class="courses-title" style="margin-top: 100px;">Featured Courses</h1>
 </div>

 {{-- MODIFIED MARCH 24 --}}
 <!-- Courses Grid Container -->
 <div class="courses-container container">
     <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
         <!-- Course 1: BS in Information Technology -->
         <div class="col">
             <div class="course-card card h-100 d-flex flex-column text-center p-3">
                 <img src="{{ asset('image/IT.jpg') }}" alt="IT Course Icon" class="course-icon card-img-top mx-auto d-block">
                 <div class="card-body d-flex flex-column">
                     <h2 class="card-title">Bachelor of Science in Information Technology (BSIT)</h2>
                     <p class="card-text flex-grow-1 text-justify">Develop skills in software development, cybersecurity, networking, and database management.</p>
                 </div>
             </div>
         </div>

         <!-- Course 2: Accountancy -->
         <div class="col">
             <div class="course-card card h-100 d-flex flex-column text-center p-3">
                 <img src="{{ asset('image/bsa.jpg') }}" alt="BSA Course Icon" class="course-icon card-img-top mx-auto d-block">
                 <div class="card-body d-flex flex-column">
                     <h2 class="card-title">Bachelor of Science in Accountancy (BSA)</h2>
                     <p class="card-text flex-grow-1 text-justify">Master financial accounting, auditing, taxation, and financial management principles.</p>
                 </div>
             </div>
         </div>

         <!-- Course 3: Business Administration -->
         <div class="col">
             <div class="course-card card h-100 d-flex flex-column text-center p-3">
                 <img src="{{ asset('image/bsba.jpg') }}" alt="BSBA Course Icon" class="course-icon card-img-top mx-auto d-block">
                 <div class="card-body d-flex flex-column">
                     <h2 class="card-title">Bachelor of Science in Business Administration (BSBA)</h2>
                     <p class="card-text flex-grow-1 text-justify">Learn the fundamentals of management, finance, and marketing for business success.</p>
                 </div>
             </div>
         </div>

         <!-- Course 4: Criminology -->
         <div class="col">
             <div class="course-card card h-100 d-flex flex-column text-center p-3">
                 <img src="{{ asset('image/crim.jpg') }}" alt="CRIM Course Icon" class="course-icon card-img-top mx-auto d-block">
                 <div class="card-body d-flex flex-column">
                     <h2 class="card-title">Bachelor of Science in Criminology (BSCrim)</h2>
                     <p class="card-text flex-grow-1 text-justify">Study law enforcement, criminal justice, forensic science, and crime prevention techniques.</p>
                 </div>
             </div>
         </div>

         <!-- Course 5: Nursing -->
         <div class="col">
             <div class="course-card card h-100 d-flex flex-column text-center p-3">
                 <img src="{{ asset('image/midwife.png') }}" alt="Nursing Course Icon" class="course-icon card-img-top mx-auto d-block">
                 <div class="card-body d-flex flex-column">
                     <h2 class="card-title">Bachelor of Science in Nursing (BSM)</h2>
                     <p class="card-text flex-grow-1 text-justify">
                         Prepare for a fulfilling career in healthcare with our BSN program. Gain hands-on experience in patient care, maternal and child health, critical care, and community nursing.
                     </p>
                 </div>
             </div>
         </div>
     </div>
 </div>


 <!-- Course Modals (Custom for Courses Only) -->
 <div id="course-modal-it" class="course-modal">
     <div class="course-modal-content">
         <span class="close-course-modal">&times;</span>
         <div class="modal-container">
             <!-- Right Side: Course Logo & Name -->
             <div class="modal-right">
                 <i class="fa-solid fa-laptop-code course-modal-icon"></i>
                 <h2>Bachelor of Science in Information Technology</h2>
             </div>
             <!-- Left Side: Course Description -->
             <div class="modal-left">
                 <p>This course prepares students with advanced skills in software engineering, web development, cybersecurity, data science, and network administration.</p>
             </div>
         </div>
     </div>
 </div>

 <div id="course-modal-accountancy" class="course-modal">
     <div class="course-modal-content">
         <span class="close-course-modal">&times;</span>
         <div class="modal-container">
             <div class="modal-right">
                 <i class="fa-solid fa-calculator course-modal-icon"></i>
                 <h2>Accountancy</h2>
             </div>
             <div class="modal-left">
                 <p>Gain proficiency in financial analysis, auditing, tax accounting, and business law, preparing you for a successful career in accounting and finance.</p>
             </div>
         </div>
     </div>
 </div>

 <div id="course-modal-business" class="course-modal">
     <div class="course-modal-content">
         <span class="close-course-modal">&times;</span>
         <div class="modal-container">
             <div class="modal-right">
                 <i class="fa-solid fa-briefcase course-modal-icon"></i>
                 <h2>Business Administration</h2>
             </div>
             <div class="modal-left">
                 <p>Learn effective leadership, marketing strategies, business operations, and financial management to excel in corporate or entrepreneurial roles.</p>
             </div>
         </div>
     </div>
 </div>

 <div id="course-modal-criminology" class="course-modal">
     <div class="course-modal-content">
         <span class="close-course-modal">&times;</span>
         <div class="modal-container">
             <div class="modal-right">
                 <i class="fa-solid fa-user-shield course-modal-icon"></i>
                 <h2>Criminology</h2>
             </div>
             <div class="modal-left">
                 <p>Study forensic science, criminal investigation, law enforcement, and correctional administration, equipping you for careers in public safety.</p>
             </div>
         </div>
     </div>
 </div>

 <div id="course-modal-midwifery" class="course-modal">
     <div class="course-modal-content">
         <span class="close-course-modal">&times;</span>
         <div class="modal-container">
             <div class="modal-right">
                 <i class="fa-solid fa-heart-pulse course-modal-icon"></i>
                 <h2>Diploma in Midwifery</h2>
             </div>
             <div class="modal-left">
                 <p>Gain hands-on training in maternal health, neonatal care, obstetric procedures, and community health education for a career in midwifery.</p>
             </div>
         </div>
     </div>
 </div>
</section>

{{-- ADDED MARCH 24 --}}
{{-- SECTION FOR MISSION AND VISION --}}
<section class="mission-vision-section pt-5"> <!-- Added pt-5 for top margin -->
    <div class="container">
        <h1 class="mb-5 fw-bold display-4" data-aos="fade-up">MISSION and VISION</h1>
        <div class="mission-vision-container">
            <div class="mission-vision-box" data-aos="fade-up" data-aos-delay="200">
                <h2 class="fw-bold">Our Mission</h2>
                <p>Institute of Business, Science, and Medical Arts exists to develop well-rounded professionals with desirable traits excelling in leadership in education, business, medical, and technical fields through competent and relevant instruction, research, and the creation of a center of knowledge for their chosen fields.</p>
            </div>
            <div class="mission-vision-box" data-aos="fade-up" data-aos-delay="400">
                <h2 class="fw-bold">Our Vision</h2>
                <p>Institute of Business, Science, and Medical Arts envisions sustaining its leadership in health science, business, and computer education whose graduates are exposed to holistic education, technology-based instruction, and vigorously pursue through research, the discovery of new knowledge responsive to the needs of the global community.</p>
            </div>
        </div>
    </div>
</section>

{{-- SCHOOL ACTIVITIES SECTION --}}
<section id="activity" class="school-activities" style="min-height: 100vh;">
    <div class="title">
        <h1 class="school-activities-title" style="text-align: center; font-size: 32px; font-weight: bold; color: #16C47F; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; margin-top: 200px;">School Activities</h1>
    </div>

    <div class="activities-wrapper" style="width: 90%; max-width: 1200px; margin: 0 auto; padding: 20px;">
        <div class="activities-container row g-3">

            <!-- Acquaintance Party -->
            <div class="activity-category col-md-4 col-sm-12 p-3" style="text-align: center; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 20px; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <h2 class="activities-subtitle" style="font-size: 22px; margin-bottom: 15px; color: #16C47F; text-transform: uppercase;">Acquaintance Party</h2>
                <div class="slider-container">
                    <button class="prev-btn" onclick="prevSlide('acc')">&#10094;</button>
                    <div class="activity-slider" id="acc">
                        <div class="activity">
                            <img src="{{ asset('acc/acc1.jpg') }}" alt="Welcome Night" class="clickable-image">
                            <p>Welcome Night</p>
                        </div>
                        <div class="activity">
                            <img src="{{ asset('acc/acc2.jpg') }}" alt="Dance Party" class="clickable-image">
                            <p>Dance Party</p>
                        </div>
                    </div>
                    <button class="next-btn" onclick="nextSlide('acc')">&#10095;</button>
                </div>
            </div>

            <!-- Intrams -->
            <div class="activity-category col-md-4 col-sm-12 p-3" style="text-align: center; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 20px; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <h2 class="activities-subtitle" style="font-size: 22px; margin-bottom: 15px; color: #16C47F; text-transform: uppercase;">Intrams</h2>
                <div class="slider-container">
                    <button class="prev-btn" onclick="prevSlide('intrams')">&#10094;</button>
                    <div class="activity-slider" id="intrams">
                        <div class="activity">
                            <img src="{{ asset('intrams/intrams6.jpg') }}" alt="Cheerdance" class="clickable-image">
                            <p>Intrams 2025 Parade</p>
                        </div>
                    </div>
                    <button class="next-btn" onclick="nextSlide('intrams')">&#10095;</button>
                </div>
            </div>

            <!-- Foundation Day -->
            <div class="activity-category col-md-4 col-sm-12 p-3" style="text-align: center; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 20px; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <h2 class="activities-subtitle" style="font-size: 22px; margin-bottom: 15px; color: #16C47F; text-transform: uppercase;">Foundation Day</h2>
                <div class="slider-container">
                    <button class="prev-btn" onclick="prevSlide('foundation')">&#10094;</button>
                    <div class="activity-slider" id="foundation">
                        <div class="activity">
                            <img src="{{ asset('foundation/foundation1.jpg') }}" alt="Talent Show" class="clickable-image">
                            <p>Mr. & Ms. IBSMA 2024</p>
                        </div>
                    </div>
                    <button class="next-btn" onclick="nextSlide('foundation')">&#10095;</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Image Viewer Modal -->
    <div id="image-modal" class="image-modal">
        <span class="close-image-modal">&times;</span>
        <img class="modal-image" id="full-size-image">
    </div>
</section>


{{-- FOOTER SECTION --}}
<section class="footer py-5" style="width: 100%; background: rgba(18, 18, 18, 0.9); backdrop-filter: blur(10px); color: white; box-shadow: 0px -4px 10px rgba(0, 0, 0, 0.2);">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4 text-center">

            <!-- School Information -->
            <div class="col">
                <div class="school">
                    <div class="school-logo d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('image/logo ibs,a.png') }}" alt="IBSMA Logo" style="width: 50px; height: auto;">
                        <h1 class="mb-0 fs-4">IBSMA</h1>
                    </div>
                    <p class="mt-3">Empowering students with the knowledge and skills to excel in the business and medical fields. Your success starts here!</p>
                    <p><i class="fa-solid fa-phone"></i> Contact: +123 456 7890</p>
                    <p><i class="fa-solid fa-envelope"></i> Email: info@ibsma.com</p>
                    <p><i class="fa-solid fa-location-dot"></i> Address: 123 Main St, City, State, 12345</p>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col">
                <div class="quick-links">
                    <h3>Quick Links</h3>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#about-section" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="#course" class="text-white text-decoration-none">Courses</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Mission and Vision</a></li>
                    </ul>
                </div>
            </div>

            <!-- Social Media -->
            <div class="col">
                <div class="footer-social">
                    <h3>Follow Us</h3>
                    <div class="footer-social-icons d-flex justify-content-center gap-3">
                        <a href="https://www.facebook.com/IBSMAnianAKO" class="text-white fs-4 text-decoration-none">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="text-center py-3 mt-4" style="background: rgba(13, 13, 13, 0.9);">
        <p class="mb-0">&copy; 2025 IBSMA. All rights reserved.</p>
    </div>
</section>

<!-- Instructor OTP Verification Modal -->
<div class="modal fade" id="instructorOtpVerificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="instructorOtpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="instructorOtpVerificationModalLabel">
                    <i class="bi bi-shield-lock"></i> Instructor OTP Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 text-center">
                <p>Please enter the OTP sent to <strong id="instructor-otp-email-display">{{ session('instructor_email') }}</strong>.</p>

                <form id="instructorVerifyOtpForm" method="POST">
                    @csrf
                    <input type="hidden" id="instructor-otp-email" name="email" value="{{ session('instructor_email') }}">
                    <input type="hidden" id="instructor-user-type" name="user_type" value="instructor">

                    <div class="mb-3">
                        <label for="instructor-otp-input" class="form-label fw-semibold">Enter OTP</label>
                        <input type="text" id="instructor-otp-input" name="otp" class="form-control shadow-sm text-center"
                               placeholder="Enter OTP" required maxlength="6" autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                        <i class="bi bi-check-circle"></i> Verify OTP
                    </button>
                </form>

                <div class="mt-3">
                    <button id="instructor-resend-otp-btn" class="btn btn-link text-primary fw-semibold">
                        <i class="bi bi-arrow-repeat"></i> Resend OTP
                    </button>
                    <p id="instructor-resend-status" class="text-muted small"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize instructor OTP email
    const otpEmailInput = document.getElementById("instructor-otp-email");
    const storedEmail = localStorage.getItem('instructor_email') || "{{ session('instructor_email') }}";

    if (otpEmailInput) {
        otpEmailInput.value = storedEmail;
        const displayElement = document.getElementById("instructor-otp-email-display");
        if (displayElement && storedEmail) {
            displayElement.textContent = storedEmail;
        }
    }

    console.log('Instructor OTP Verification Initialized:', {
        storedEmail: storedEmail,
        sessionEmail: "{{ session('instructor_email') }}"
    });
});

// Event listener for when instructor modal opens
document.getElementById('instructorOtpVerificationModal').addEventListener('show.bs.modal', function() {
    const email = localStorage.getItem('instructor_email') || "{{ session('instructor_email') }}";
    document.getElementById('instructor-otp-email').value = email;
    document.getElementById('instructor-user-type').value = 'instructor';

    const displayElement = document.getElementById("instructor-otp-email-display");
    if (displayElement) {
        displayElement.textContent = email;
    }

    console.log('Instructor OTP Modal Opened With Email:', email);
});
</script>

<!-- Bootstrap JS Bundle (Popper.js included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function () {
    // Array of image URLs
    const images = [
        "{{ asset('image/background.jpg') }}",
        "{{ asset('image/background2.jpg') }}",
        "{{ asset('image/background3.jpg') }}",
        "{{ asset('image/background4.jpg') }}"
    ];

    let currentIndex = 0;
    const slider = document.querySelector(".slider");

    function changeBackground() {
        // Add the slide-out animation
        slider.classList.add("slide-out");

        setTimeout(() => {
            // Change background image
            slider.style.backgroundImage = `url(${images[currentIndex]})`;

            // Reset animation and move to the next image
            slider.classList.remove("slide-out");
            slider.classList.add("slide-in");

            setTimeout(() => {
                slider.classList.remove("slide-in");
            }, 1000); // Ensure animation completes before removing class

            currentIndex = (currentIndex + 1) % images.length;
        }, 1000); // Match the animation duration
    }

    // Change the background every 5 seconds
    setInterval(changeBackground, 5000);

    // Initialize first image
    changeBackground();
});


// for activities-slider
document.addEventListener("DOMContentLoaded", function () {
    const sliders = document.querySelectorAll(".activity-slider");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("full-size-image");
    const closeModal = document.querySelector(".close-image-modal");

    // Initialize sliders
    sliders.forEach(slider => {
        slider.dataset.index = 0; // Set initial index
    });

    window.nextSlide = function (id) {
        let slider = document.getElementById(id);
        let slides = slider.children.length;
        let index = parseInt(slider.dataset.index);
        index = (index + 1) % slides;
        slider.dataset.index = index;
        slider.style.transform = `translateX(-${index * 100}%)`;
    };

    window.prevSlide = function (id) {
        let slider = document.getElementById(id);
        let slides = slider.children.length;
        let index = parseInt(slider.dataset.index);
        index = (index - 1 + slides) % slides;
        slider.dataset.index = index;
        slider.style.transform = `translateX(-${index * 100}%)`;
    };

    // Open image modal on image click
    document.querySelectorAll(".clickable-image").forEach(image => {
        image.addEventListener("click", function () {
            modal.style.display = "flex";
            modalImage.src = this.src;
        });
    });

    // Close image modal
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close modal when clicking outside the image
    modal.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});


// for course modals
document.addEventListener("DOMContentLoaded", function () {
    const openCourseButtons = document.querySelectorAll(".open-course-modal");
    const closeCourseButtons = document.querySelectorAll(".close-course-modal");
    const courseModals = document.querySelectorAll(".course-modal");

    // Open the course modal when button is clicked
    openCourseButtons.forEach(button => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-modal");
            document.getElementById(modalId).style.display = "flex";
        });
    });

    // Close the course modal when close button is clicked
    closeCourseButtons.forEach(button => {
        button.addEventListener("click", function () {
            this.closest(".course-modal").style.display = "none";
        });
    });

    // Close the course modal when clicking outside modal content
    courseModals.forEach(modal => {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});


// for smooth scrolling
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({
        behavior: "smooth"
    });
}

// open and closed modal for mission and vision
document.addEventListener("DOMContentLoaded", function () {
    const openModalButtons = document.querySelectorAll(".open-modal");
    const closeModalButtons = document.querySelectorAll(".close-modal");
    const modals = document.querySelectorAll(".custom-modal");

    // Open Modal
    openModalButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const modalId = this.getAttribute("data-modal");
            document.getElementById(modalId).style.display = "flex";
        });
    });

    // Close Modal
    closeModalButtons.forEach(button => {
        button.addEventListener("click", function () {
            this.closest(".custom-modal").style.display = "none";
        });
    });

    // Close Modal When Clicking Outside Content
    modals.forEach(modal => {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});

// for login
document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelector(".toggle-instructor-password");
    const passwordField = document.getElementById("instructor-password");

    togglePassword.addEventListener("click", function () {
        const icon = this.querySelector("i");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            passwordField.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    });
});

// OTP Modal Auto-show based on Session
document.addEventListener("DOMContentLoaded", function () {
        @if(session('show_otp_modal'))
            var otpModal = new bootstrap.Modal(document.getElementById('otpVerificationModal'));
            otpModal.show();
        @endif
    });
</script>

<script>
    document.querySelector("[data-bs-target='#customInstructorLoginModal']").addEventListener("click", function() {
        console.log("Instructor login button clicked!");
    });

    function showInstructorOtpModal(event) {
        event.preventDefault();

        const loginForm = event.target.closest("form");
        const loader = document.getElementById("loading-screen");

        if (!loader) {
            console.error("Loader element not found!");
            return;
        }

        // Show loader with full styling like student version
        loader.style.display = "flex";
        loader.style.opacity = "1";
        loader.style.zIndex = "2000";
        loader.classList.add("fade-pulse");

        fetch(loginForm.action, {
            method: "POST",
            body: new FormData(loginForm),
            headers: {
                "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value,
                "X-User-Type": "instructor"
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                localStorage.setItem('instructor_email', data.email);
                document.getElementById('instructor-otp-email').value = data.email;
                document.getElementById('instructor-user-type').value = 'instructor';

                const displayElement = document.getElementById("instructor-otp-email-display");
                if (displayElement) displayElement.textContent = data.email;

                return fetch('/instructor/send-otp', {
                    method: "POST",
                    body: new FormData(loginForm),
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-User-Type': 'instructor'
                    }
                });
            } else {
                throw new Error(data.message || "Invalid credentials");
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('OTP send failed');
            return response.json();
        })
        .then(otpData => {
            if (otpData.message === "OTP sent successfully!") {
                const loginModal = bootstrap.Modal.getInstance(document.getElementById("customInstructorLoginModal")) ||
                                   new bootstrap.Modal(document.getElementById("customInstructorLoginModal"));
                const otpModal = bootstrap.Modal.getInstance(document.getElementById("instructorOtpVerificationModal")) ||
                                 new bootstrap.Modal(document.getElementById("instructorOtpVerificationModal"));

                loginModal.hide();
                otpModal.show();

                fadeOutInstructorLoader();
            } else {
                throw new Error(otpData.message || "Failed to send OTP");
            }
        })
        .catch(error => {
            console.error("Instructor Login Error:", error);
            alert(error.message);
            fadeOutInstructorLoader();
        });
    }

    function fadeOutInstructorLoader(callback) {
        const loader = document.getElementById("loading-screen");
        loader.classList.remove("fade-pulse");
        loader.style.opacity = "0";
        setTimeout(() => {
            loader.style.display = "none";
            if (callback) callback();
        }, 800);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const otpEmailInput = document.getElementById("instructor-otp-email");
        const storedEmail = localStorage.getItem('instructor_email') || "{{ session('instructor_email') }}";

        if (otpEmailInput) {
            otpEmailInput.value = storedEmail;
            const displayElement = document.getElementById("instructor-otp-email-display");
            if (displayElement && storedEmail) {
                displayElement.textContent = storedEmail;
            }
        }

        console.log("Instructor OTP email initialized:", storedEmail);
    });
</script>

<script>
    $(document).ready(function() {
        @if(session('error'))
            $('#instructorOtpVerificationModal').modal('show');
        @endif

        // Initialize modal with instructor email
        $('#instructorOtpVerificationModal').on('show.bs.modal', function() {
            const email = localStorage.getItem('instructor_email') || "{{ session('instructor_email') }}";
            $('#otp-email').val(email);
            $('#user-type').val('instructor');
            console.log('Instructor OTP modal opened with email:', email);
        });

        $('#instructorVerifyOtpForm').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalBtnText = $submitBtn.html();

            $submitBtn.prop('disabled', true)
                      .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...');

            const token = $('meta[name="csrf-token"]').attr('content');
            const email = localStorage.getItem('instructor_email') || $('#otp-email').val();
            const otp = $('#instructor-otp-input').val();

            if (!email) {
                alert('Session expired. Please login again.');
                $submitBtn.prop('disabled', false).html(originalBtnText);
                return;
            }

            $.ajax({
                url: '/otp-verify',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Email': email,
                    'X-User-Type': 'instructor'
                },
                data: {
                    _token: token,
                    email: email,
                    otp: otp,
                    user_type: 'instructor'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        alert(response.message || 'Verification failed');
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                },
                error: function(xhr) {
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                    if (xhr.status === 419) {
                        alert('Session expired. Please refresh and try again.');
                        window.location.reload();
                    } else if (xhr.responseJSON?.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>

<script>
    document.getElementById('instructor-resend-otp-btn').addEventListener('click', function() {
        const button = this;
        const statusText = document.getElementById('instructor-resend-status');
        const email = localStorage.getItem('instructor_email') ||  document.getElementById('instructor-otp-email').value;

        if (!email) {
            statusText.textContent = "Session expired. Please login again.";
            return;
        }

        button.disabled = true;
        statusText.textContent = "Resending OTP...";

        const formData = new FormData();
        formData.append('email', email);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('user_type', 'instructor');

        fetch("{{ route('instructor.resend-otp') }}", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-User-Type": "instructor"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusText.textContent = "New OTP sent successfully!";
                console.log('Instructor OTP resent to:', email);
            } else {
                statusText.textContent = data.message || "Failed to resend OTP";
            }
            setTimeout(() => {
                button.disabled = false;
                statusText.textContent = "";
            }, 60000);
        })
        .catch(error => {
            console.error("Resend OTP Error:", error);
            statusText.textContent = "Error resending OTP";
            button.disabled = false;
        });
    });
</script>

{{-- added march 24 --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".open-modal").forEach(link => {
            link.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent default anchor behavior
                let section = document.querySelector(".mission-vision-section");
                if (section) {
                    let headerOffset = document.querySelector("header") ? document.querySelector("header").offsetHeight : 80; // Adjust if needed
                    let sectionPosition = section.getBoundingClientRect().top + window.scrollY - headerOffset;
                    window.scrollTo({ top: sectionPosition, behavior: "smooth" });
                }
            });
        });
    });
</script>

    {{-- added march 24 --}}
    <!-- AOS Initialization -->
    <script>
       AOS.init({
            duration: 1000, // Animation duration
            once: false, // Allow animation every time it scrolls into view
            easing: 'ease-in-out', // Smooth easing effect
            mirror: true // Ensures animations trigger when scrolling up and down
        });
    </script>

    {{-- added march 24 --}}
    {{-- smooth scrolling for about us --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".scroll-to-about").addEventListener("click", function (event) {
                event.preventDefault(); // Prevent default anchor behavior

                let section = document.querySelector(".about-section");
                if (section) {
                    let headerOffset = document.querySelector("header") ? document.querySelector("header").offsetHeight : 80; // Adjust if needed
                    let sectionPosition = section.getBoundingClientRect().top + window.scrollY - headerOffset;

                    window.scrollTo({ top: sectionPosition, behavior: "smooth" });
                }
            });
        });
    </script>

    {{-- ADDED MARCH 28 --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.innerWidth < 768) {
                AOS.init({
                    disable: true, // Disable animations on mobile
                });
            } else {
                AOS.init({
                    once: false,  // Animations play multiple times when scrolling
                    duration: 800, // Animation duration in milliseconds
                    offset: 100,  // Start animation when element is 100px in viewport
                });
            }
        });
    </script>

</body>
</html>

