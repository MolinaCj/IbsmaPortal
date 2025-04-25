<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Portal - IBSMA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=Jaini&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/login2.css')}}">
</head>

<body class="bg-light">
    <!-- Rounded Navbar -->
    <nav class="custom-navbar">
        <div class="d-flex align-items-center">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('image/logo ibs,a.png') }}" alt="School Logo" class="navbar-logo">
            </a>
            <div class="school-name">Institute of Business Science and Medical Arts</div>
        </div>

        <!-- Burger Menu on Mobile -->
        <div class="burger-menu d-lg-none" id="burgerMenu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

       <!-- Navbar Links for Desktop -->
        <div class="navbar-links d-none d-lg-flex">
            <a href="#home" class="nav-link"><i class="fas fa-home"></i> Home</a>
            <a href="#about" class="nav-link"><i class="fas fa-info-circle"></i> About</a>
            <a href="#courses" class="nav-link"><i class="fas fa-book"></i> Courses</a>
            <a href="#activities" class="nav-link"><i class="fas fa-phone-alt"></i> Activities</a>
            <a href="#login" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
        </div>

        <!-- Mobile Menu Links -->
        <div id="mobileMenu" class="d-lg-none">
            <a href="#home" class="nav-link"><i class="fas fa-home"></i> Home</a>
            <a href="#about" class="nav-link"><i class="fas fa-info-circle"></i> About</a>
            <a href="#courses" class="nav-link"><i class="fas fa-book"></i> Courses</a>
            <a href="#activities" class="nav-link"><i class="fas fa-phone-alt"></i> Activities</a>
            <!-- Trigger Button -->
<a href="#" class="btn btn-login d-block mt-3" data-bs-toggle="modal" data-bs-target="#customAdminLoginModal">
    <i class="fas fa-sign-in-alt"></i> Login
</a>

<!-- Custom Admin Login Modal -->
<div class="modal fade" id="customAdminLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customAdminLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">

            <!-- Modal Header -->
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold" id="customAdminLoginModalLabel">
                    <i class="fa fa-user-lock"></i> Admin Login
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Login Form) -->
            <div class="modal-body p-4">
                <!-- Error Message (if any) -->
                @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Admin Login Form -->
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="admin-username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="admin-username" class="form-control shadow-sm rounded-end" placeholder="Enter username" required autocomplete="username">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="admin-password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password" id="admin-password" name="password" class="form-control shadow-sm" placeholder="Enter password" required>
                            <button type="button" class="toggle-admin-password input-group-text bg-light" onclick="togglePassword()">
                                <i class="fas fa-eye-slash" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-2">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <!-- Forgot Password Link -->
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




        </div>
    </nav>

    <!-- Auto Sliding Background -->
    <div class="slider-container">
        <div class="slider-img active" style="background-image: url({{ asset('image/background.jpg') }});"></div>
        <div class="slider-img" style="background-image: url({{ asset('image/background2.jpg') }});"></div>
        <div class="slider-img" style="background-image: url({{ asset('image/background3.jpg') }});"></div>

        <!-- School Name Overlay with Animation -->
<div class="school-name-overlay" id="animatedText">Institute of Business Science and Medical Arts</div>

        <!-- Overlay to Improve Readability -->
        <div class="slider-overlay"></div>


        <!-- Vision and Mission Section -->
        <section id="about" class="vision-mission-section d-flex justify-content-center align-items-center gap-3 flex-lg-row flex-column mt-5 mb-5">
            <div class="glass-box col-lg-5 p-4 text-center">
                <i class="fas fa-eye vision-icon"></i>
                <h4 class="mt-2">Our Vision</h4>
                <p>Our vision is to be a globally recognized institution for excellence in business, science, and medical education, fostering innovation and leadership.</p>
            </div>
            <div class="glass-box col-lg-5 p-4 text-center">
                <i class="fas fa-bullseye vision-icon"></i>
                <h4 class="mt-2">Our Mission</h4>
                <p>Our mission is to provide high-quality education and training in business and medical sciences, empowering students to become industry leaders.</p>
            </div>
        </section>
    </div>

   <!-- Course Section with Image Icons -->
<section id="courses" class="courses-section mt-5 mb-5">
    <div class="container">
        <h2 class="text-center text-green mb-5">Explore Our <span style="color:#16c47f">Featured Courses</span></h2>

        <!-- Course Grid Layout -->
        <div class="row justify-content-center g-4">

            <!-- Course 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/IT.jpg') }}" alt="IT Course" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Information Technology</h4>
                    <p>Develop skills in software development, cybersecurity, and database management.</p>
                </div>
            </div>

            <!-- Course 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/bsba.jpg') }}" alt="Business Admin" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Business Administration</h4>
                    <p>Gain insights into business strategies and leadership skills.</p>
                </div>
            </div>

            <!-- Course 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/midwife.jpg') }}" alt="Nursing" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Nursing</h4>
                    <p>Prepare for a rewarding career in healthcare with clinical expertise.</p>
                </div>
            </div>

            <!-- Course 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/crim.jpg') }}" alt="Criminology" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Criminology</h4>
                    <p>Understand law enforcement and forensic science principles.</p>
                </div>
            </div>

            <!-- Course 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/bsa.jpg') }}" alt="Accountancy" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Accountancy</h4>
                    <p>Master accounting, auditing, and financial management.</p>
                </div>
            </div>

            <!-- Course 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/course-arts.png') }}" alt="Multimedia Arts" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Multimedia Arts</h4>
                    <p>Explore creativity through visual arts and digital design.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- School Activities Section -->
<section id="activities" class="activities-section mt-5 mb-5">
    <div class="container" style="margin-top: 270px;">
        <h2 class="text-center text-green mb-5">Our Exciting <span style="color:#16c47f">School Activities</span></h2>

        <!-- Activities Grid Layout -->
        <div class="row justify-content-center g-4">

            <!-- Activity 1: Acquaintance Party -->
            <div class="col-lg-4 col-md-6">
                <div class="activity-card glass-card p-4 text-center">
                    <div id="acquaintanceSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('acc/acc1.jpg') }}" class="activity-img d-block w-100" alt="Acquaintance Party 1" onclick="openModal('{{ asset('acc/acc1.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('acc/acc2.jpg') }}" class="activity-img d-block w-100" alt="Acquaintance Party 2" onclick="openModal('{{ asset('acc/acc2.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('acc/acc3.jpg') }}" class="activity-img d-block w-100" alt="Acquaintance Party 3" onclick="openModal('{{ asset('acc/acc3.jpg') }}')">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3 activity-title">Acquaintance Party</h4>
                    <p>Welcome new students and build lasting friendships in a fun and exciting environment.</p>
                </div>
            </div>

            <!-- Activity 2: Intramurals -->
            <div class="col-lg-4 col-md-6">
                <div class="activity-card glass-card p-4 text-center">
                    <div id="intramuralsSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('intrams/intrams1.jpg') }}" class="activity-img d-block w-100" alt="Intramurals 1" onclick="openModal('{{ asset('intrams/intrams1.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('intrams/intrams2.jpg') }}" class="activity-img d-block w-100" alt="Intramurals 2" onclick="openModal('{{ asset('intrams/intrams2.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('intrams/intrams3.jpg') }}" class="activity-img d-block w-100" alt="Intramurals 3" onclick="openModal('{{ asset('intrams/intrams3.jpg') }}')">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3 activity-title">Intramurals</h4>
                    <p>Experience thrilling competitions and showcase your athletic skills.</p>
                </div>
            </div>

            <!-- Activity 3: Foundation Day -->
            <div class="col-lg-4 col-md-6">
                <div class="activity-card glass-card p-4 text-center">
                    <div id="foundationSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('foundation/foundation1.jpg') }}" class="activity-img d-block w-100" alt="Foundation Day 1" onclick="openModal('{{ asset('foundation/foundation1.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('foundation/foundation2.jpg') }}" class="activity-img d-block w-100" alt="Foundation Day 2" onclick="openModal('{{ asset('foundation/foundation2.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('foundation/foundation3.jpg') }}" class="activity-img d-block w-100" alt="Foundation Day 3" onclick="openModal('{{ asset('foundation/foundation3.jpg') }}')">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3 activity-title">Foundation Day</h4>
                    <p>Celebrate the legacy of our institution with exciting activities and ceremonies.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Image Modal for Zoom -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center">
                <img id="modalImage" class="img-fluid rounded" alt="Activity Image">
            </div>
        </div>
    </div>
</div>





<!-- Footer Section with Links -->
<section id="about" class="modern-footer mt-5">
    <div class="container">
        <div class="row gy-4 justify-content-between align-items-start text-white">

            <!-- About Section Link in Footer -->
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-title">About IBSMA</h5>
                <p class="footer-description">
                    IBSMA is committed to delivering world-class education, fostering critical thinking, and empowering future leaders with quality knowledge.
                </p>
                <a href="#about" class="footer-link text-white"><i class="fas fa-info-circle"></i> Learn More About Us</a>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-4">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="#home" class="footer-link">Home</a></li>
                    <li><a href="#about" class="footer-link">About Us</a></li>
                    <li><a href="#courses" class="footer-link">Courses</a></li>
                    <li><a href="#activities" class="footer-link">Activities</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-4">
                <h5 class="footer-title">Contact Info</h5>
                <ul class="footer-info">
                    <li><i class="fas fa-map-marker-alt"></i> Francisco Street, Marfrancisco, Pinamalayan, Oriental Mindoro</li>
                    <li><i class="fas fa-envelope"></i> ibsmainc2003@gmail.com
                    </li>
                    <li><i class="fas fa-phone-alt"></i> +63 998 305 2912</li>
                </ul>
            </div>

            <!-- Social Media Links -->
            <div class="col-lg-3 col-md-6 text-center">
                <h5 class="footer-title">Connect with Us</h5>
                <div class="footer-social d-flex justify-content-center gap-3">
                    <a href="#" class="social-link" data-bs-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link" data-bs-toggle="tooltip" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link" data-bs-toggle="tooltip" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link" data-bs-toggle="tooltip" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <!-- Divider Line -->
        <hr class="footer-divider my-4">

        <!-- Footer Bottom Section -->
        <div class="row align-items-center text-center">
            <div class="col-lg-6 mb-2 mb-lg-0">
                <p class="mb-0">&copy; <span id="currentYear"></span> IBSMA. All Rights Reserved.</p>
            </div>
            <div class="col-lg-6">
                <ul class="footer-bottom-links d-flex justify-content-center gap-3">
                    <li><a href="#" class="footer-bottom-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-bottom-link">Terms of Service</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>


    <!-- Copyright Section -->
    <div class="footer-bottom text-center mt-4">
        <p class="m-0">&copy; <span id="currentYear"></span> IBSMA. All Rights Reserved.</p>
    </div>
</footer>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        // Toggle Burger Menu
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            const burgerIcon = document.getElementById('burgerMenu');

            menu.classList.toggle('show');
            burgerIcon.classList.toggle('active');
        }

        // JavaScript for Automatic Image Slider
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slider-img');

        function showSlides() {
            slides.forEach((slide) => {
                slide.classList.remove('active');
            });

            slideIndex++;
            if (slideIndex >= slides.length) {
                slideIndex = 0;
            }

            slides[slideIndex].classList.add('active');
        }

        // Change image every 5 seconds
        setInterval(showSlides, 5000);


        let currentIndex = 0;
    const totalSlides = document.querySelectorAll('.course-card').length;
    const slidesToShow = 3;
    const slider = document.querySelector('.course-slider');

    function slideCourses() {
        currentIndex++;
        if (currentIndex >= totalSlides / slidesToShow) {
            currentIndex = 0;
        }
        slider.style.transform = `translateX(-${currentIndex * 100 / slidesToShow}%)`;
    }

    // Auto-slide every 6 seconds
    setInterval(slideCourses, 6000);

    document.getElementById('currentYear').textContent = new Date().getFullYear();

         // Open Modal and Zoom Image
    function openModal(imageSrc) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

       // Sticky Navbar on Scroll
       window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.custom-navbar');
        if (window.scrollY > 20) {
            navbar.classList.add('sticky');
        } else {
            navbar.classList.remove('sticky');
        }
    });


    function toggleAdminPassword() {
        const passwordField = document.getElementById('admin-password');
        const toggleIcon = document.getElementById('toggleAdminIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }
    </script>
</body>
</html>
