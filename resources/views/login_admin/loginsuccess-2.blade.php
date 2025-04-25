<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <title>Document</title>
    <style>
         *{
            margin: 0;
            padding: 0;
        }
        .navbar-1{
            background: linear-gradient(to right, #076a4c, #05da93);
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo{
        width: 600px;
        display: flex;
        height: 100px;
        align-items: center;
        margin-left: 50px;
        }
        .logos{
            width: 100px;
        }
        .navbar-2{
        display: flex;
            justify-content: space-between;
            height: 120px;
            align-items: center;
        }
        .title{
            font-size: 20px;
            margin-left: 20px;
        }
          /* Menu Container */
          .menu {
            margin-right: 50px;
        }

        /* Menu Links */
        .menu a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            margin-left: 30px;
            font-family: "Alegreya", serif;
        }

        /* Hover Effect */
        .menu a:hover {
            background-color: #05da93;
            color: white;
            box-shadow: 0px 4px 15px rgba(22, 196, 127, 0.3);
        }

        .menu a:hover::after {
            width: 100%;
            left: 0;
        }

        /* Responsive for Mobile */
        @media (max-width: 768px) {
            .menu {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }

            .menu a {
                width: 100%;
                text-align: center;
                padding: 12px 0;
            }
        }

             /* Background Wrapper */
        .background {
            position: relative;
            height: 800px;
            width: 100%;
            overflow: hidden;
        }

        /* Image Slider */
        .slider {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: background 1s ease-in-out;
            filter: blur(1px); /* Blur effect */
        }

        /* Dark Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Dark Overlay */
            z-index: 1;
        }

        /* Content */
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 2;
        }

        .content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 20px;
        }

        /* Button Styling */
        .btn-custom {
            display: inline-block;
            padding: 12px 25px;
            font-size: 18px;
            color: white;
            background: #11a39f;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease;
        }
/* Highlight Active Section */
        .btn-custom:hover {
            background: #0a615f;
        }

        .teamplate{
            height: 70vh;
        }
        /* General styling for the statement container */
.statement {
    display: flex;
    justify-content: space-between;
    gap: 50px;
    margin: 200px 50px;
    padding: 50px;
    background-color: #f4f9f8;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Mission Section Styling */
.mission {
    flex: 1;
    background: linear-gradient(135deg, #16c47f, #11a39f);
    color: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(22, 196, 127, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.mission h2 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.mission p {
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Vision Section Styling */
.vission {
    flex: 1;
    background: linear-gradient(135deg, #ff7e5f, #feb47b);
    color: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(255, 126, 95, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.vission h2 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.vission p {
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Hover Effect for both sections */
.mission:hover, .vission:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .statement {
        flex-direction: column;
        align-items: center;
    }
    .mission, .vission {
        width: 100%;
        margin-bottom: 20px;
    }
}



.courses {
    text-align: center;
}

.courses h2 {
    font-size: 2.5rem;
    margin-bottom: 30px;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 2px;
}

.courses span {
    color: #05da93; /* Updated Color */
}

/* Card Container */
.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: auto;
}

/* Individual Card Styling */
.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    color: black;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    height: 100%;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Icons */
.card i {
    font-size: 3rem;
    color: #05da93; /* Updated Color */
    margin-bottom: 15px;
}

/* Card Title */
.card h3 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 15px;
    text-transform: capitalize;
}

/* Card Paragraph */
.card p {
    font-size: 1rem;
    color: #333;
    margin-bottom: 20px;
}

/* Learn More Button */
.card a {
    text-decoration: none;
    display: inline-block;
    background-color: #05da93; /* Updated Color */
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: background-color 0.3s ease;
}

.card a:hover {
    background-color: #05da93; /* Darker Shade for Hover Effect */
}

/* Responsive Design */
@media (max-width: 992px) {
    .cards {
        grid-template-columns: repeat(2, 1fr); /* 2 Columns on Tablets */
    }
}

@media (max-width: 768px) {
    .cards {
        grid-template-columns: 1fr; /* 1 Column on Mobile */
    }
}

.links{
            margin-top: 50px;
            height: 70vh;
        }
        .foot{
            margin-bottom: 90px;
        }
        .footer{
            display: flex;
            background-color: #11a39f;
            height: 70vh;;
            justify-content: space-between;
        }
        .Follow-us{
            color: white;
            text-decoration: none;
            margin-right: 20px;
            display: flex;
            align-items: center;
            font-size: 20px;
            flex-direction: column;
        }

        /* Ensure images maintain aspect ratio */
.object-fit-cover {
    object-fit: cover;
}

/* Improve modal padding for readability */
.modal-body {
    padding: 20px;
}

/* Adjust modal content layout for small screens */
@media (max-width: 768px) {
    .modal-body .row {
        flex-direction: column;
    }

    .modal-body .col-md-5 {
        display: block;
        height: 250px;
    }
}
/* Fade & Slide-In Animation */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    opacity: 0;
    transition: transform 0.4s ease-out, opacity 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translateY(0);
    opacity: 1;
}

/* Smooth Modal Background */
.modal-backdrop {
    backdrop-filter: blur(8px);
    background-color: rgba(0, 0, 0, 0.5) !important;
    transition: opacity 0.1s ease;
}

/* Left Side Image Animation */
.modal-content {
    overflow: hidden;
    border-radius: 10px;
}

.modal-body .col-md-5 img {
    transition: transform 0.5s ease;
}

.modal.show .modal-body .col-md-5 img {
    transform: scale(1.05);
}

/* Footer Section */
.footer {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    background: linear-gradient(to right, #076a4c, #05da93);
    color: white;
    padding: 60px 10%;
    border-top-left-radius: 50px;
    border-top-right-radius: 50px;
    box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);
}

/* Background and Colors */
.links {
    background-color: #2c3e50;
    color: white;
    padding: 50px 20px;
}

.foot h3 {
    font-size: 28px;
    margin-bottom: 10px;
    text-align: center;
}

.foot p {
    font-size: 16px;
    text-align: center;
    margin-bottom: 20px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.btn {
    background-color: #e74c3c;
    color: white;
    padding: 15px 30px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 30px;
    display: block;
    margin: 0 auto;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #c0392b;
}

/* Footer Layout */
.footer {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-top: 50px;
    gap: 40px;
}

.school {
    flex: 1;
    max-width: 350px;
}

.school h1 {
    font-size: 32px;
    margin-bottom: 10px;
}

.school p {
    font-size: 16px;
    line-height: 1.6;
}

/* Quick Links and Pages */
.q-links, .Pages, .Follow-us {
    flex: 1;
    max-width: 250px;
}

h3 {
    font-size: 20px;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: bold;
}

.q-links p, .Pages p {
    font-size: 16px;
    margin: 5px 0;
    transition: color 0.3s ease;
}

.q-links p:hover, .Pages p:hover {
    color: #e74c3c;
    cursor: pointer;
}

/* Follow Us Section */
.Follow-us a {
    display: flex;
    align-items: center;
    margin: 10px 0;
    font-size: 16px;
    text-decoration: none;
    color: white;
    transition: color 0.3s ease;
}

.Follow-us a:hover {
    color: #e74c3c;
}

.Follow-us i {
    margin-right: 10px;
    font-size: 18px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .school, .q-links, .Pages, .Follow-us {
        max-width: 100%;
        margin-bottom: 30px;
    }

    .btn {
        width: 100%;
    }
}

    </style>
</head>
<body>
    <section id="home" class="navbar-1">
        <div class="contact">
            <a href="#" style="color: white; margin-right: 20px; margin-left:40px;"><i class="fas fa-phone-alt"></i> +123 456 7890</a>
            <a href="#" style="color: white; margin-right: 20px;"><i class="fas fa-envelope"></i>  support@example.com</a>
            <a href="#" style="color: white; margin-right: 20px;"><i class="fab fa-twitter"></i> twitter.com/example</a>
        </div>
        <div class="link">
            <a><i class="fa-brands fa-facebook" style="margin-right: 20px;"></i> </a> |
            <a><i class="fa-brands fa-instagram" style="margin-right: 20px;"></i> </a> |
            <a><i class="fa-brands fa-twitter" style="margin-right: 20px;"></i> </a> |
            <a><i class="fa-brands fa-youtube" style="margin-right: 30px;"></i> </a>
        </div>
    </section>

    <section class="navbar-2">
        <div class="logo">
            <img src="{{asset ('image/logo ibs,a.png')}}" alt="" class="logos">
            <div class="title">
                <h5 style="font-size: 20px;"><span style="color: #16C47F">INSTITUTE</span> OF BUSINESS SCIENCE AND <span style="color: #16C47F">MEDICAL ARTS</span></h5>
            </div>
        </div>
        <div class="menu" style="">
            <a href="#home">HOME</a>
            <a href="#mission">MISSION</a>
            <a href="#course">COURSE</a>
            <a href="#">ADMISSION</a>
            <a href="#">CONTACT</a>

            <!-- Login Button (Triggers Modal) -->
<button type="button" class="btn btn-primary fw-bold shadow-sm mt-3" data-bs-toggle="modal" data-bs-target="#customLoginModal">
    <i class="bi bi-box-arrow-in-right"></i> Login
</button>

<!-- Custom Admin Login Modal -->
<div class="modal fade admin-login-modal" id="customAdminLoginModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customAdminLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="customAdminLoginModalLabel">
                    <i class="fa fa-user-lock"></i> Admin Login
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Login Form) -->
            <div class="modal-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

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
                            <button type="button" class="toggle-admin-password input-group-text bg-light">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>


                    <!-- Login Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-3">
                        <i class="fa fa-sign-in-alt"></i> Login
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

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fa fa-key"></i> Reset Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Reset Password Form) -->
            <div class="modal-body p-4">
                @if (session('status'))
                    <div class="alert alert-success text-center">{{ session('status') }}</div>
                @endif

                <form action="{{ route('admin.password.reset') }}" method="POST">
                    @csrf
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="reset-username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="reset-username" class="form-control shadow-sm rounded-end" placeholder="Enter username" required autocomplete="username">
                        </div>
                    </div>

                    <!-- New Password Input -->
                    <div class="mb-3">
                        <label for="new-password" class="form-label fw-semibold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password" id="new-password" class="form-control shadow-sm rounded-end" placeholder="Enter new password" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password_confirmation" id="confirm-password" class="form-control shadow-sm rounded-end" placeholder="Confirm new password" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn w-100 fw-bold shadow-sm mt-3" style="background-color: #16C47F; color: white;">
                        <i class="fa fa-key"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</section>

<div class="background">
    <!-- Background Image Slider -->
    <div class="slider"></div>

    <!-- Dark Overlay -->
    <div class="overlay"></div>

    <!-- Content -->
    <div class="content">
        <h1>Welcome to IBSMA</h1>
        <p>Explore the best education opportunities with us. Join now and start your journey to success!</p>
        <a href="#" class="btn-custom">Learn More</a>
    </div>
</div>

<section id="mission" class="promise" style="margin-top: 100px;">
    <!-- Promise Statement -->
    <h2 >Our Promise</h2>
    <p>At IBSMA, we believe in the power of education to empower individuals and communities. Our mission is to create a world where every individual, regardless of their abilities, can develop their potential. We strive to provide a comprehensive and balanced education that equips students with the knowledge, skills, and values they need to succeed in the 21st century.</p>
</section>

<section id="about" class="about">
    <!-- About Us Section -->
    <div class="statement">
        <!-- Mission and Vision -->
        <div class="mission">
            <h2>Our Mission</h2>
            <p>Institute of Business, Science and Medical Arts envision to sustain her leadership in health science, business, computer education whose graduates are exposed to holistic education, tehnology-based instruction, and vigorously pursue through research, the discovery of new knowledge responsive to the needs of the global community.</p>
        </div>
        <div class="vission">
            <h2>Our Vision</h2>
            <p>Institue off Business, Science and Medical Arts exists to develop well-rounded professionals with describe traits excelling in leadership in education, business, medical and technical fields through competent and relevant instruction, research, and the creation of center of knowledge for their choses fields.</p>
        </div>
    </div>
</section>

<section id="course" class="template" style="margin-top:100px;">
    <div class="course">
        <div class="courses">
            <h2>Explore Our Academic Offerings <br> <span style="color: #05da93">Chart Your Path to Success </span></h2>
            <div class="cards">
                <!-- Course 1 -->
                <div class="card">
                    <i class="fa-solid fa-laptop-code"></i>
                    <h3>Bachelor Of Science in <span style="color: #05da93">Information Technology</span></h3>
                    <p>Learn HTML, CSS, JavaScript & more to become a front-end developer.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseModal1">Learn More</button>
                </div>

                <!-- Course 2 -->
                <div class="card">
                    <i class="fa-solid fa-user-shield"></i>
                    <h3>Bachelor Of Science in <span style="color: #05da93">Criminology</span></h3>
                    <p>Master Photoshop, Illustrator, and UI/UX design principles.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseModal2">Learn More</button>
                </div>

                <!-- Course 3 -->
                <div class="card">
                    <i class="fa-solid fa-chart-line"></i>
                    <h3>Bachelor Of Science in <span style="color: #05da93;">Business Administration</span></h3>
                    <p>Explore SEO, social media marketing, and brand strategy.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseModal3">Learn More</button>
                </div>

                <!-- Course 4 -->
                <div class="card">
                    <i class="fa-solid fa-calculator"></i>
                    <h3>Bachelor Of Science in <span style="color: #05da93">Accountancy</span></h3>
                    <p>Understand ethical hacking, network security, and risk assessment.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseModal4">Learn More</button>
                </div>

                <!-- Course 5 -->
                <div class="card">
                    <i class="fa-solid fa-school"></i>
                    <h3 style="color: #05da93">Senior High School</h3>
                    <p>Build Android & iOS apps with Flutter, React Native, and Swift.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseModal5">Learn More</button>
                </div>

                <!-- Course 6 -->
                <div class="card">
                    <i class="fa-solid fa-brain"></i>
                    <h3 style="color: #05da93">Diploma In Midwifery</h3>
                    <p>Learn Machine Learning, Deep Learning, and AI applications.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#courseModal6">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course Modals -->
<!-- Course 1 Modal: Information Technology -->
<div class="modal fade" id="courseModal1" tabindex="-1" aria-labelledby="courseModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left: Image -->
                    <div class="col-md-5 d-none d-md-block">
                        <img src="{{ asset('image/IT.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="IT Students">
                    </div>

                    <!-- Right: Course Details -->
                    <div class="col-md-7 p-4">
                        <div class="modal-header border-0 p-0">
                            <h5 class="modal-title text-success fw-bold" id="courseModalLabel1">Bachelor Of Science in Information Technology</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="mt-3">This program prepares students for careers in IT, focusing on programming, web development, and software engineering.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Front-end and Back-end Development</li>
                            <li><i class="fas fa-check-circle text-success"></i> Database Management</li>
                            <li><i class="fas fa-check-circle text-success"></i> Cybersecurity Fundamentals</li>
                            <li><i class="fas fa-check-circle text-success"></i> Cloud Computing</li>
                        </ul>
                        <button class="btn btn-success w-100">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course 2 Modal: Criminology -->
<div class="modal fade" id="courseModal2" tabindex="-1" aria-labelledby="courseModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block">
                        <img src="{{ asset('image/crim.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="Criminology Students">
                    </div>

                    <div class="col-md-7 p-4">
                        <div class="modal-header border-0 p-0">
                            <h5 class="modal-title text-success fw-bold" id="courseModalLabel2">Bachelor Of Science in Criminology</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="mt-3">This program focuses on law enforcement, forensic science, and criminal justice.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Criminal Law and Procedures</li>
                            <li><i class="fas fa-check-circle text-success"></i> Forensic Science and Investigation</li>
                            <li><i class="fas fa-check-circle text-success"></i> Security Management</li>
                            <li><i class="fas fa-check-circle text-success"></i> Crime Prevention Techniques</li>
                        </ul>
                        <button class="btn btn-success w-100">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course 3 Modal: Business Administration -->
<div class="modal fade" id="courseModal3" tabindex="-1" aria-labelledby="courseModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block">
                        <img src="{{ asset('image/bsba.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="Business Students">
                    </div>

                    <div class="col-md-7 p-4">
                        <div class="modal-header border-0 p-0">
                            <h5 class="modal-title text-success fw-bold" id="courseModalLabel3">Bachelor Of Science in Business Administration</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="mt-3">This program covers finance, marketing, and entrepreneurship.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Marketing Strategies</li>
                            <li><i class="fas fa-check-circle text-success"></i> Financial Management</li>
                            <li><i class="fas fa-check-circle text-success"></i> Leadership & Organizational Behavior</li>
                            <li><i class="fas fa-check-circle text-success"></i> Business Ethics</li>
                        </ul>
                        <button class="btn btn-success w-100">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course 4 Modal: Accountancy -->
<div class="modal fade" id="courseModal4" tabindex="-1" aria-labelledby="courseModalLabel4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block">
                        <img src="{{ asset('image/bsa.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="Accounting Students">
                    </div>

                    <div class="col-md-7 p-4">
                        <div class="modal-header border-0 p-0">
                            <h5 class="modal-title text-success fw-bold" id="courseModalLabel4">Bachelor Of Science in Accountancy</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="mt-3">Develop financial expertise and auditing skills for a successful accounting career.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Financial & Cost Accounting</li>
                            <li><i class="fas fa-check-circle text-success"></i> Taxation & Auditing</li>
                            <li><i class="fas fa-check-circle text-success"></i> Business Law & Ethics</li>
                            <li><i class="fas fa-check-circle text-success"></i> Corporate Finance</li>
                        </ul>
                        <button class="btn btn-success w-100">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course 5 Modal: Senior High School -->
<div class="modal fade" id="courseModal5" tabindex="-1" aria-labelledby="courseModalLabel5" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block">
                        <img src="{{ asset('image/senior-high.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="Senior High School">
                    </div>

                    <div class="col-md-7 p-4">
                        <div class="modal-header border-0 p-0">
                            <h5 class="modal-title text-success fw-bold" id="courseModalLabel5">Senior  High School</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="mt-3">Offers specialized tracks to prepare students for college or the workforce.</p>
                        <button class="btn btn-success w-100">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Course 6 Modal: Diploma in Midwifery -->
<div class="modal fade" id="courseModal6" tabindex="-1" aria-labelledby="courseModalLabel6" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left: Image -->
                    <div class="col-md-5 d-none d-md-block">
                        <img src="{{ asset('image/midwife.jpg') }}" class="img-fluid h-100 w-100 object-fit-cover" alt="Midwifery Students">
                    </div>

                    <!-- Right: Course Details -->
                    <div class="col-md-7 p-4">
                        <div class="modal-header border-0 p-0">
                            <h5 class="modal-title text-success fw-bold" id="courseModalLabel6">Diploma in Midwifery</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <p class="mt-3">This program prepares students for careers in maternal and newborn healthcare.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Maternal & Newborn Care</li>
                            <li><i class="fas fa-check-circle text-success"></i> Women’s Health Nursing</li>
                            <li><i class="fas fa-check-circle text-success"></i> Community & Public Health</li>
                            <li><i class="fas fa-check-circle text-success"></i> Basic Emergency Obstetric Care</li>
                        </ul>
                        <button class="btn btn-success w-100">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<section class="links" style="margin-top: 100px;">
    <div class="foot">
        <h3>Join now to get special offer at IBSMA!</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tristique, nunc eu tristique commodo, justo velit ultricies arcu, vel mattis felis neque in velit.</p>
        <button class="btn">Join Now</button>
    </div>

    <div class="footer">
        <div class="school">
            <h1>IBSMA</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tristique, nunc eu tristique commodo, justo velit ultricies arcu, vel mattis felis neque in velit.</p>
        </div>
        <div class="q-links">
            <h3>Quick Links</h3>
            <p>Home</p>
            <p>About us</p>
            <p>Our Services</p>
            <p>Contact Us</p>
        </div>
        <div class="Pages">
            <h3>Pages</h3>
            <p>Our Blog</p>
            <p>Our Team</p>
            <p>Testimonial</p>
        </div>
        <div class="Follow-us">
            <h3>Follow Us</h3>
            <a href="#"><i class="fa-brands fa-facebook"></i>Facebook</a>
            <a href="#"><i class="fa-brands fa-instagram"></i>Instagram</a>
            <a href="#"><i class="fa-brands fa-twitter"></i>Twitter</a>
            <a href="#"><i class="fa-brands fa-youtube"></i>YouTube</a>
        </div>
    </div>
</section>

<!-- Add Bootstrap JS at the bottom -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // List of background images
    const images = [
        "{{ asset('image/background.jpg') }}",
        "{{ asset('image/background2.jpg') }}",
        "{{ asset('image/background3.jpg') }}",
        "{{ asset('image/background4.jpg') }}"
    ];

    let currentIndex = 0;
    const slider = document.querySelector(".slider");

    function changeBackground() {
        slider.style.backgroundImage = `url(${images[currentIndex]})`;
        currentIndex = (currentIndex + 1) % images.length; // Loop through images
    }

    // Change image every 5 seconds
    changeBackground();
    setInterval(changeBackground, 5000);
</script>

<script>
    // for login
document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelector(".toggle-admin-password");
    const passwordField = document.getElementById("admin-password");

    if (!togglePassword || !passwordField) {
        console.error("Toggle button or password field not found!");
        return;
    }

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
</script>

</body>
</html>
