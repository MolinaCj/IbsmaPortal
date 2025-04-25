<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Mail\StudentOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\StudentLoginAttempt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class StudentLoginController extends Controller
{
    public function showHomepage()
    {
        return view('student_login.studentLogin');
    }

    // Login without OTP
    public function studentLogin(Request $request)
    {
        Log::info('Student login attempt', ['email' => $request->email]);

        $email = $request->email;
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Log every login attempt
        $status = 'failed'; // Default status is 'failed'

        // Check if student exists
        $student = Student::where('email', $email)->first();

        if (!$student) {
            Log::warning('Student login failed - User not found', ['email' => $email]);

            // Log failed attempt
            StudentLoginAttempt::create([
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => $status
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'type' => 'attempts',
                'attempts_left' => 0
            ]);
        }

        // Check lockout status
        if ($student->lockout_time && now()->lessThan($student->lockout_time)) {
            $remainingSeconds = now()->diffInSeconds($student->lockout_time);
            Log::warning('Student account locked', [
                'email' => $email,
                'locked_until' => $student->lockout_time,
                'remaining_seconds' => $remainingSeconds
            ]);

            // Log failed attempt due to lockout
            StudentLoginAttempt::create([
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => $status
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Account locked. Try again later.',
                'type' => 'locked',
                'remaining_seconds' => $remainingSeconds
            ]);
        }

        // Attempt login
        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            // Successful login — reset counters
            $student->update([
                'login_attempts' => 0,
                'lockout_time' => null,
                'lockout_count' => 0
            ]);

            session([
                'student_email' => $email,
                'user_type' => 'student'
            ]);
            $request->session()->save();

            Log::info('Student login successful', ['email' => $email]);

            // Log successful attempt
            $status = 'success';
            StudentLoginAttempt::create([
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => $status
            ]);

            return response()->json([
                'success' => true,
                'email' => $email,
                'user_type' => 'student'
            ]);
        } else {
            $student->increment('login_attempts');
            $remainingAttempts = max(0, 5 - $student->login_attempts);

            if ($student->login_attempts >= 5) {
                $lockoutMinutes = pow(2, $student->lockout_count) * 5; // 5, 10, 20, etc.
                $student->update([
                    'lockout_time' => now()->addMinutes($lockoutMinutes),
                    'login_attempts' => 0,
                    'lockout_count' => $student->lockout_count + 1
                ]);

                Log::warning('Student account locked due to failed attempts', [
                    'email' => $email,
                    'lockout_duration' => $lockoutMinutes . ' minutes'
                ]);

                // Log failed attempt due to lockout
                StudentLoginAttempt::create([
                    'email' => $email,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'status' => $status
                ]);

                return response()->json([
                    'success' => false,
                    'message' => "Too many failed attempts. Account locked for {$lockoutMinutes} minutes.",
                    'type' => 'locked',
                    'remaining_seconds' => $lockoutMinutes * 60
                ]);
            }

            Log::warning('Student login failed - Invalid credentials', [
                'email' => $email,
                'attempts_left' => $remainingAttempts
            ]);

            // Log failed attempt
            StudentLoginAttempt::create([
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'status' => $status
            ]);

            return response()->json([
                'success' => false,
                'message' => "Incorrect credentials. You have {$remainingAttempts} attempt(s) left.",
                'type' => 'attempts',
                'attempts_left' => $remainingAttempts
            ]);
        }
    }




    public function sendOtp(Request $request)
    {
        Log::info("Student sendOtp() called for: " . $request->email);

        $request->validate([
            'email' => 'required|email|exists:students,email',
        ]);

        // Verify user type matches student
        if (session('user_type') !== 'student') {
            Log::error("Invalid user type for student OTP request", ['email' => $request->email]);
            return response()->json(['error' => 'Invalid request'], 403);
        }

        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            Log::error("Student not found for email: " . $request->email);
            return response()->json(['error' => 'Student not found'], 404);
        }

        $otp = rand(100000, 999999);
        Log::info("Generated student OTP: " . $otp);

        $student->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new StudentOtpMail($otp));
            Log::info("Student OTP email sent successfully.");
            return response()->json([
                'message' => 'OTP sent successfully!',
                'user_type' => 'student'
            ]);
        } catch (\Exception $e) {
            Log::error("Error sending student OTP email: " . $e->getMessage());
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
    }


    public function verifyOtp(Request $request)
    {
        // Get email from student-specific session
        $email = session('student_email');
        $userType = session('user_type');

        // Verify this is a student OTP request
        if ($userType !== 'student') {
            Log::error("Invalid user type for student OTP verification", [
                'email' => $email,
                'user_type' => $userType
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification request'
            ], 403);
        }

        Log::info('Student OTP Verification Attempt', [
            'session_email' => $email,
            'request_email' => $request->email,
            'otp' => $request->otp,
            'timestamp' => Carbon::now(),
        ]);

        if (!$email) {
            Log::error('Student OTP Verification Failed - Email missing');
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $student = Student::where('email', $email)->first();

        if ($student && $student->otp == $request->otp && Carbon::now()->lt($student->otp_expires_at)) {
            Auth::guard('student')->loginUsingId($student->id);
            session()->regenerate();
            $student->update(['otp' => null, 'otp_expires_at' => null]);

            return response()->json([
                'success' => true,
                'redirect' => route('student.studentDashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP'
        ], 422);
    }


    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:students,email']);

        // Verify user type
        if (session('user_type') !== 'student') {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 403);
        }

        $otp = rand(100000, 999999);
        Log::info("Generated OTP: " . $otp);
        Student::where('email', $request->email)->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new StudentOtpMail($otp));
            return response()->json([
                'success' => true,
                'message' => 'OTP resent successfully!',
                'user_type' => 'student'
            ]);
        } catch (\Exception $e) {
            Log::error("Error resending student OTP email: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP'], 500);
        }
    }





    // Logout Instructor
    public function logout()
    {
       Auth::guard('student')->logout();
       session()->forget('student_logged_in'); // Only remove student session
       return redirect()->route('student_login.homepage')->with('success', 'Logged out successfully');
    }

}
