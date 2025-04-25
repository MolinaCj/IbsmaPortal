<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\InstructorOtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Models\InstructorLoginAttempt;
use App\Http\Controllers\InstructorAuthController;


class InstructorLoginController extends Controller
{
    public function showHomepage()
    {
        return view('instructor_login.instructorLogin');
    }

    // Login without OTP
    public function instructorLogin(Request $request)
    {
        Log::info('Instructor login attempt', ['email' => $request->email]);

        $email = $request->email;
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        $status = 'failed'; // Default
        $instructor = Teacher::where('email', $email)->first();

        if (!$instructor) {
            Log::warning('Instructor login failed - User not found', ['email' => $email]);
            $this->logInstructorAttempt($email, $ip, $userAgent, $status);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'type' => 'attempts',
                'attempts_left' => 0
            ]);
        }

        if ($instructor->is_active === 0) {
            Log::warning('Instructor login failed - Inactive account', ['email' => $email]);
            $this->logInstructorAttempt($email, $ip, $userAgent, $status);

            return response()->json([
                'success' => false,
                'message' => 'You cannot login. Your account is currently inactive.',
                'type' => 'inactive'
            ]);
        }

        if ($instructor->lockout_time && now()->lessThan($instructor->lockout_time)) {
            $remainingSeconds = now()->diffInSeconds($instructor->lockout_time);
            Log::warning('Instructor login failed - Account locked', [
                'email' => $email,
                'locked_until' => $instructor->lockout_time,
                'remaining_seconds' => $remainingSeconds
            ]);

            $this->logInstructorAttempt($email, $ip, $userAgent, $status);

            return response()->json([
                'success' => false,
                'message' => "Account locked. Try again later.",
                'type' => 'locked',
                'remaining_seconds' => $remainingSeconds
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('teacher')->attempt($credentials)) {
            // Successful login
            $instructor->update([
                'login_attempts' => 0,
                'lockout_time' => null,
                'lockout_count' => 0
            ]);

            session([
                'instructor_email' => $email,
                'user_type' => 'instructor'
            ]);
            $request->session()->save();

            Log::info('Instructor login successful', ['email' => $email]);

            $status = 'success';
            $this->logInstructorAttempt($email, $ip, $userAgent, $status);

            return response()->json([
                'success' => true,
                'email' => $email,
                'user_type' => 'instructor'
            ]);
        } else {
            // Failed login
            $instructor->increment('login_attempts');
            $remainingAttempts = max(0, 5 - $instructor->login_attempts);

            if ($instructor->login_attempts >= 5) {
                $lockoutMinutes = pow(2, $instructor->lockout_count) * 5;
                $instructor->update([
                    'lockout_time' => now()->addMinutes($lockoutMinutes),
                    'login_attempts' => 0,
                    'lockout_count' => $instructor->lockout_count + 1
                ]);

                Log::warning('Instructor account locked', [
                    'email' => $email,
                    'lockout_duration' => $lockoutMinutes . ' minutes'
                ]);

                $this->logInstructorAttempt($email, $ip, $userAgent, $status);

                return response()->json([
                    'success' => false,
                    'message' => "Too many failed attempts. Account locked for {$lockoutMinutes} minutes.",
                    'type' => 'locked',
                    'remaining_seconds' => $lockoutMinutes * 60
                ]);
            }

            Log::warning('Instructor login failed - Invalid credentials', [
                'email' => $email,
                'attempts_left' => $remainingAttempts
            ]);

            $this->logInstructorAttempt($email, $ip, $userAgent, $status);

            return response()->json([
                'success' => false,
                'message' => "Incorrect credentials. You have {$remainingAttempts} attempt(s) left.",
                'type' => 'attempts',
                'attempts_left' => $remainingAttempts
            ]);
        }
    }

    private function logInstructorAttempt($email, $ip, $userAgent, $status)
    {
        InstructorLoginAttempt::create([
            'email' => $email,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'status' => $status
        ]);
    }



    public function sendOtp(Request $request)
    {
        Log::info("sendOtp() called for: " . $request->email);

        $request->validate([
            'email' => 'required|email|exists:teachers,email',
        ]);

        // Verify user type matches instructor
        if (session('user_type') !== 'instructor') {
            Log::error("Invalid user type for OTP request", ['email' => $request->email]);
            return response()->json(['error' => 'Invalid request'], 403);
        }

        $instructor = Teacher::where('email', $request->email)->first();

        if (!$instructor) {
            Log::error("Instructor not found for email: " . $request->email);
            return response()->json(['error' => 'Instructor not found'], 404);
        }

        $otp = rand(100000, 999999);
        Log::info("Generated OTP: " . $otp);

        $instructor->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new InstructorOtpMail($otp));
            Log::info("OTP email sent successfully.");
            return response()->json([
                'message' => 'OTP sent successfully!',
                'user_type' => 'instructor'
            ]);
        } catch (\Exception $e) {
            Log::error("Error sending OTP email: " . $e->getMessage());
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        // Get email from instructor-specific session
        $email = session('instructor_email');
        $userType = session('user_type');

        // Verify this is an instructor OTP request
        if ($userType !== 'instructor') {
            Log::error("Invalid user type for OTP verification", [
                'email' => $email,
                'user_type' => $userType
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification request'
            ], 403);
        }

        Log::info('Instructor OTP Verification Attempt', [
            'session_email' => $email,
            'request_email' => $request->email,
            'otp' => $request->otp,
            'timestamp' => Carbon::now(),
        ]);

        if (!$email) {
            Log::error('OTP Verification Failed - Email missing');
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $instructor = Teacher::where('email', $email)->first();

        if ($instructor && $instructor->otp == $request->otp && Carbon::now()->lt($instructor->otp_expires_at)) {
            Auth::guard('teacher')->loginUsingId($instructor->id);
            session()->regenerate();
            $instructor->update(['otp' => null, 'otp_expires_at' => null]);

            return response()->json([
                'success' => true,
                'redirect' => route('instructor.instructorDashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP'
        ], 422);
    }

    public function instructorResendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:teachers,email']);

        // Verify user type
        if (session('user_type') !== 'instructor') {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 403);
        }

        $otp = rand(100000, 999999);
        Log::info("Generated OTP: " . $otp);
        Teacher::where('email', $request->email)->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new InstructorOtpMail($otp));
            return response()->json([
                'success' => true,
                'message' => 'OTP resent successfully!',
                'user_type' => 'instructor'
            ]);
        } catch (\Exception $e) {
            Log::error("Error resending OTP email: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP'], 500);
        }
    }


    // Logout Instructor
    public function logout()
    {
        Auth::guard('teacher')->logout();
        session()->forget('instructor_logged_in'); // Only remove instructor session
        return redirect()->route('instructor_login.homepage')->with('success', 'Logged out successfully');
    }

}
