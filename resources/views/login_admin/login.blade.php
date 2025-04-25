<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{asset ('css/login.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    {{-- SCREEN LOADER --}}
    <div id="loading-screen">
        <img src="{{ asset('storage/ibsmalogo.png') }}" alt="Loading" class="loader-image">
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let loader = document.getElementById("loading-screen");

            // Ensure the loader is visible on page load
            loader.style.display = "flex";

            // Hide loader when the page fully loads
            window.addEventListener("load", function () {
                setTimeout(() => {
                    loader.style.display = "none";
                }, 1000); // 1-second delay for a smooth effect
            });

            // Show loader on form submission
            let form = document.querySelector("form");
            if (form) {
                form.addEventListener("submit", function () {
                    loader.style.display = "flex";
                });
            }
        });
    </script>


    <!-- Success Notification -->
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <i class="fa fa-check-circle"></i> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Notification -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                let alert = document.querySelector(".alert");
                if (alert) {
                    alert.style.transition = "opacity 0.5s";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }
            }, 2000);
        });
    </script>


    <section class="navbar">
        <div class="social">
            <a href="https://www.facebook.com/IBSMAnianAKO"><i class="fa-brands fa-facebook"></i></a>
            {{-- <a href="#"><i class="fa-brands fa-instagram"></i></a> |
            <a href="#"><i class="fa-brands fa-twitter"></i></a> |
            <a href="#"><i class="fa-brands fa-youtube"></i></a> --}}
        </div>

            <div class="links">
                    <div class="left" style="display: flex; justify-content: space-between; gap: 20px; margin-right:30px;">
                        <a href="#"><strong>HOME</strong></a>
                        <a href="#course"><strong>COURSES</strong></a>
                        <a href="#activity"><strong>ACTIVITIES</strong></a>
                    </div>
                <div class="title">
                    <img src="{{ asset('image/logo ibs,a.png') }}" alt="" class="logos">
                    <h5 style="font-size: 20px;">
                        <span style="color: #16C47F">INSTITUTE</span> OF BUSINESS SCIENCE <br>
                        AND <span style="color: #16C47F">MEDICAL ARTS</span>
                    </h5>
                </div>
                {{-- modified march 24 --}}
                <div class="right" style="display: flex; justify-content: space-between; gap: 20px; margin-left:30px;">
                    <!-- Mission Modal Trigger -->
                <a href="#mission-vision-section" class="open-modal" data-modal="mission-modal"><strong>MISSION</strong></a>

                <!-- Vision Modal Trigger -->
                <a href="#mission-vision-section" class="open-modal" data-modal="vision-modal"><strong>VISION</strong></a>

                <a href="#about-section" class="scroll-to-about"><strong>ABOUT US</strong></a>
                </div>
            </div>

           <!-- Login Button (Triggers Modal) -->
<button type="button" class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#customAdminLoginModal">
    <i class="fa fa-sign-in-alt"></i> Login
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
                @if(session('error') && (!session('error_type') || session('error_type') == 'login'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif


                @if(session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
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
                            <button type="button" class="toggle-admin-password input-group-text bg-light" onclick="togglePassword()">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-3">
                        <i class="fa fa-sign-in-alt"></i> Login
                    </button>

                    <!-- Forgot Password Link -->
                    @if(session('showForgotPassword'))
                        <div class="text-center mt-3">
                            <a href="#" class="text-decoration-none text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                Forgot Password?
                            </a>
                        </div>
                    @endif
                    {{--  Forgot Username Link --}}
                    @if(session('showForgotUsername'))
                        <div class="text-center mt-3">
                            <a href="#" class="text-decoration-none text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#resetUsernameModal">
                                Forgot Username?
                            </a>
                        </div>
                    @endif
                </form>
                {{-- KUNG ILALAGAY ITO AFTER NG FORM --}}
                <!-- Register Admin Link -->
                @php
                    $adminExists = \App\Models\Admin::exists(); // Check if any admin exists
                @endphp

                @if (!$adminExists)
                    <div class="text-center mt-3">
                        <a href="#" class="text-success fw-bold" data-bs-toggle="modal" data-bs-target="#customAdminRegisterModal">Register as Admin</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@if(session('error') && (!session('error_type') || session('error_type') === 'login') && url()->previous() == route('admin.login.submit'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loginModal = new bootstrap.Modal(document.getElementById('customAdminLoginModal'));
        loginModal.show();
    });
</script>
@endif

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fa fa-key"></i> Reset Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
                @endif --}}

                @if(session('error') && session('error_type') == 'password_reset') <!-- or username_reset -->
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.password.reset') }}" method="POST" onsubmit="return checkPasswordsMatch()">
                    @csrf

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="reset-username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="reset-username" class="form-control shadow-sm rounded-end" placeholder="Enter username" value="{{ old('username') }}" required autocomplete="username">
                        </div>
                    </div>

                    <!-- Emergency Key -->
                    <div class="mb-3">
                        <label for="emergency_key" class="form-label fw-semibold">Emergency Key</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-key"></i></span>
                            <input type="text" name="emergency_key" id="reset-password-emergency-key" class="form-control shadow-sm rounded-end" placeholder="Enter emergency key" required>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="new-password" class="form-label fw-semibold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password"
                                   name="password"
                                   id="new-password"
                                   class="form-control shadow-sm rounded-0"
                                   placeholder="Enter password"
                                   required
                                   minlength="8"
                                   pattern="^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                                   title="Password must be at least 8 characters long, include at least one number and one special character">
                            <span class="input-group-text toggle-password" data-target="new-password"><i class="bi bi-eye-slash"></i></span>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="confirm-password-reset" class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password"
                                   id="confirm-password-reset"
                                   name="password_confirmation"
                                   class="form-control shadow-sm rounded-0"
                                   placeholder="Confirm password"
                                   required>
                            <span class="input-group-text toggle-password" data-target="confirm-password-reset"><i class="bi bi-eye-slash"></i></span>
                        </div>
                        <small id="password-error" class="text-danger d-none">Passwords do not match.</small>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn w-100 fw-bold shadow-sm mt-3" style="background-color: #16C47F; color: white;">
                        <i class="fa fa-key"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('show_modal') == 'resetPasswordModal')
            let resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            resetPasswordModal.show();
        @elseif(session('status_type') == 'password_reset')
            let loginModal = new bootstrap.Modal(document.getElementById('customAdminLoginModal'));
            loginModal.show();
        @endif
    });

    // Toggle password visibility
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            e.preventDefault();
            const button = e.target.closest('.toggle-password');
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = button.querySelector('i');

            if (input && icon) {
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            }
        }
    });

    // Password match validation
    const confirmPassword = document.getElementById('confirm-password-reset');
    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            const password = document.getElementById('new-password').value;
            const errorElement = document.getElementById('password-error');
            if (this.value !== password) {
                errorElement.classList.remove('d-none');
            } else {
                errorElement.classList.add('d-none');
            }
        });
    }
</script>


<!-- Reset Username Modal -->
<div class="modal fade" id="resetUsernameModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetUsernameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="resetUsernameModalLabel">
                    <i class="fa fa-user"></i> Reset Username
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Reset Username Form) -->
            <div class="modal-body p-4">
                {{-- @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
                @endif --}}

                @if(session('error') && session('error_type') == 'username_reset') <!-- or username_reset -->
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.username.reset') }}" method="POST">
                    @csrf
                    <!-- Old Username Input -->
                    {{-- <div class="mb-3">
                        <label for="reset-username" class="form-label fw-semibold">Old Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="reset-username" class="form-control shadow-sm rounded-end" placeholder="Enter old username" required autocomplete="username">
                        </div>
                    </div> --}}

                    <!-- Emergency Key Input -->
                    <div class="mb-3">
                        <label for="emergency_key" class="form-label fw-semibold">Emergency Key</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-key"></i></span>
                            <input type="text" name="emergency_key" id="emergency_key" class="form-control shadow-sm rounded-end" placeholder="Enter emergency key" required>
                        </div>
                    </div>

                    <!-- New Username Input -->
                    <div class="mb-3">
                        <label for="new-username" class="form-label fw-semibold">New Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="new_username" id="new-username" class="form-control shadow-sm rounded-end" placeholder="Enter new username" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn w-100 fw-bold shadow-sm mt-3" style="background-color: #16C47F; color: white;">
                        <i class="fa fa-user"></i> Reset Username
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('status_type') == 'username_reset')
            let loginModal = new bootstrap.Modal(document.getElementById('customLoginModal'));
            loginModal.show();
        @elseif(session('show_modal') == 'resetUsernameModal')
            let resetUsernameModal = new bootstrap.Modal(document.getElementById('resetUsernameModal'));
            resetUsernameModal.show();
        @endif
    });
</script>





<!-- Custom Admin Register Modal -->
<div class="modal fade" id="customAdminRegisterModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customAdminRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="customAdminRegisterModalLabel">
                    <i class="fa fa-user-plus"></i> Admin Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Register Form) -->
            <div class="modal-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.register.submit') }}" method="POST">
                    @csrf
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="admin-register-username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="admin-register-username" class="form-control shadow-sm rounded-end" placeholder="Enter username" required>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="admin-register-password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password"
                                   name="password"
                                   id="admin-register-password"
                                   class="form-control shadow-sm rounded-0"
                                   placeholder="Enter password"
                                   required
                                   minlength="8"
                                   pattern="^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                                   title="Password must be at least 8 characters long, include at least one number and one special character">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="admin-register-password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-3">
                        <label for="admin-register-password-confirm" class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password"
                                   name="password_confirmation"
                                   id="admin-register-password-confirm"
                                   class="form-control shadow-sm rounded-0"
                                   placeholder="Confirm password"
                                   required
                                   minlength="8"
                                   pattern="^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
                                   title="Password must be at least 8 characters long, include at least one number and one special character">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="admin-register-password-confirm">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-3">
                        <i class="fa fa-user-plus"></i> Register
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const password = document.getElementById('admin-register-password');
    const confirmPassword = document.getElementById('admin-register-password-confirm');

    confirmPassword.addEventListener('input', function () {
        if (confirmPassword.value !== password.value) {
            confirmPassword.setCustomValidity("Passwords do not match");
        } else {
            confirmPassword.setCustomValidity("");
        }
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
</section>
{{-- INAYOS MARCH 24 --}}
    <!-- Mission Modal -->
{{-- <div id="mission-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Mission</h2>
        <p>Institute of  Business, Science, and Medical Arts exists to develop well-rounded professionals with desirable traits excelling in leadership in education, business, medical, and technical fields through competent and relevant instruction, research, and the creation of center of knowledge for their chosen fields.</p>
    </div>
</div> --}}

{{-- INAYOS MARCH 24 --}}
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
                     <h2 class="card-title">Bachelor of Science in Nursing (BSN)</h2>
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

{{-- /* BAGONG LAGAY MARCH 23 */ --}}
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



    <!-- Bootstrap JS Bundle (Popper.js included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

{{-- script for mission vision added march 24 --}}
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
