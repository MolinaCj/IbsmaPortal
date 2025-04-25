<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AdminLoginAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;


class AdminLoginController extends Controller
{
    public function showLoginForm()
{
    try {
        return view('login_admin.login'); // dot notation
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


    // Show Register Form
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    // // Handle Admin Registration
    public function adminRegister(Request $request)
{
    $request->validate([
        'username' => 'required|string|unique:admins',
        'password' => [
        'required',
        'string',
        'min:8',
        'regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/',
        'confirmed',
        ],
    ]);

    // Generate a secure random key
    $emergencyKey = Str::random(32);

    // Store in database
    $admin = Admin::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'emergency_key' => $emergencyKey,
    ]);

    // Store the key in session
    session(['emergency_key' => $emergencyKey]);

    // Redirect to route that loads the view using the session
    return redirect()->route('admin.emergency.key');
}

public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $username = Str::lower($request->username);
    $key = 'login_attempts_' . $username;
    $lockKey = 'lockout_' . $username;
    $ip = $request->ip();
    $agent = $request->header('User-Agent');

    // 1. Check if locked out
    if (Cache::has($lockKey)) {
        $lockoutTime = Cache::get($lockKey);
        $minutesLeft = Carbon::parse($lockoutTime)->diffInMinutes(now());

        AdminLoginAttempt::create([
            'username' => $username,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'status' => 'failed',
        ]);

        return back()->with('error', "Account is temporarily locked. Try again in {$minutesLeft} minute(s).");
    }

    $admin = Admin::where('username', $username)->first();

    // 2. Invalid username
    if (!$admin) {
        AdminLoginAttempt::create([
            'username' => $username,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'status' => 'failed',
        ]);

        return back()->with([
            'error' => 'Incorrect username.',
            'error_type' => 'login',
            'showForgotUsername' => true
        ]);
    }

    // 3. Password is wrong
    if (!Hash::check($request->password, $admin->password)) {
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes(30)); // keep attempts alive for 30 mins

        $attemptsLeft = max(0, 5 - ($attempts % 5 === 0 ? 5 : $attempts % 5));

        AdminLoginAttempt::create([
            'username' => $username,
            'admin_id' => $admin->id,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'status' => 'failed',
        ]);

        // Check if it's a multiple of 5
        if ($attempts % 5 === 0) {
            $lockMultiplier = intdiv($attempts, 5); // 5, 10, 15 → 1, 2, 3
            $lockMinutes = $lockMultiplier * 5; // Lock time increases by 5 minutes
            $lockUntil = now()->addMinutes($lockMinutes);
            Cache::put($lockKey, $lockUntil, $lockUntil);

            return back()->with('error', "Too many failed attempts. Account locked for {$lockMinutes} minute(s).");
        }

        return back()->with([
            'error' => "Incorrect password. Attempts left before lock: {$attemptsLeft}",
            'error_type' => 'login',
            'showForgotPassword' => true
        ]);
    }

    // 4. Successful login
    Cache::forget($key);
    Cache::forget($lockKey);

    AdminLoginAttempt::create([
        'username' => $username,
        'admin_id' => $admin->id,
        'ip_address' => $ip,
        'user_agent' => $agent,
        'status' => 'success',
    ]);

    session([
        'admin_logged_in' => true,
        'admin_id' => $admin->id,
    ]);

    return redirect()->route('admin.dashboard');
}


public function resetPassword(Request $request)
{
    $request->validate([
        'username' => 'required|string|exists:admins,username',
        'password' => 'required|string|min:6|confirmed',
        'emergency_key' => 'required|string',
    ]);

    $admin = Admin::where('username', $request->username)->first();

    if (!$admin) {
        return back()->withInput()->with([
            'error' => 'Invalid username.',
            'error_type' => 'password_reset',
            'show_modal' => 'resetPasswordModal'
        ]);
    }

    if ($request->emergency_key !== $admin->emergency_key) {
        return back()->withInput()->with([
            'error' => 'Invalid emergency key.',
            'error_type' => 'password_reset',
            'show_modal' => 'resetPasswordModal'
        ]);
    }

    $admin->password = Hash::make($request->password);
    $admin->save();

    // return redirect()->route('admin.login')->with('status', 'Password successfully updated.');
    return redirect()->route('admin.login')->with([
        'status' => 'Password successfully updated.',
        'status_type' => 'password_reset'
    ]);

}

public function resetUsername(Request $request)
{
    $request->validate([
        'emergency_key' => 'required|string',
        'new_username' => 'required|string|unique:admins,username',
    ]);

    $admin = Admin::where('emergency_key', $request->emergency_key)->first();

    if (!$admin) {
        return back()->withInput()->with([
            'error' => 'Invalid emergency key.',
            'error_type' => 'username_reset',
            'show_modal' => 'resetUsernameModal'
        ]);
    }

    $admin->username = $request->new_username;
    $admin->save();

    // return redirect()->route('admin.login')->with('status', 'Username successfully updated.');
    return redirect()->route('admin.login')->with([
        'status' => 'Username successfully updated.',
        'status_type' => 'username_reset'
    ]);

}


    public function logout()
    {
        $username = Admin::find(session('admin_id'))->username;
        Cache::forget('login_attempts_' . Str::lower($username));
        Cache::forget('lockout_' . Str::lower($username));

        session()->flush();
        return redirect()->route('admin.login');
    }

}
