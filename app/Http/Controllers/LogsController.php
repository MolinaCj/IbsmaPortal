<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AdminLoginAttempt;
use App\Models\StudentLoginAttempt;
use App\Models\InstructorLoginAttempt;

class LogsController extends Controller
{
    public function logs()
    {
        // Ensure the admin is logged in
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'You must log in first.');
        }

        // Fetch the student login attempts and order by 'created_at' in descending order
        $studentLoginAttempts = StudentLoginAttempt::orderBy('created_at', 'desc')->get()->each(function($attempt) {
            $attempt->created_at = Carbon::parse($attempt->created_at);
        });

        // Fetch instructor login attempts and order by 'attempted_at' in descending order
        $instructorLoginAttempts = InstructorLoginAttempt::orderBy('attempted_at', 'desc')->get()->each(function($attempt) {
            $attempt->attempted_at = Carbon::parse($attempt->attempted_at);
        });

        // Fetch admin login attempts and order by 'created_at' in descending order
        $adminLoginAttempts = AdminLoginAttempt::orderBy('created_at', 'desc')->get()->each(function($attempt) {
            $attempt->created_at = Carbon::parse($attempt->created_at);
        });

        // Return the view with the login attempts data
        return view('admin.logs', compact('studentLoginAttempts', 'instructorLoginAttempts', 'adminLoginAttempts'));
    }

}

