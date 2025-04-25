<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminProfileController extends Controller
{
    public function update(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);

        // Update session values for admin email
        Session::put('admin_email', $request->email);

        // If password is provided, update the session (simulating password change)
        if ($request->filled('password')) {
            Session::put('admin_password', Hash::make($request->password));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully.');
    }
}
