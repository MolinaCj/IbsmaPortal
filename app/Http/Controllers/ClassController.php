<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClassController extends Controller
{
    public function index()
    {
        // Ensure the admin is logged in
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'You must log in first.');
        }
    
        // Fetch classes with department and instructor
        $classes = ClassModel::with(['department', 'instructor'])->get();
    
        // Fetch all departments and teachers for dropdowns
        $departments = Department::all();
        $teachers = Teacher::all();
    
        return view('admin.classes', compact('classes', 'departments', 'teachers'));
    }
    


    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'department_id' => 'required|exists:departments,id',
        'instructor_id' => 'nullable|exists:teachers,id', // Ensures the instructor is a valid teacher
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('classes', 'public');
        $data['image'] = $imagePath;
    }

    ClassModel::create([
        'name' => $data['name'],
        'department_id' => $data['department_id'],
        'instructor_id' => $data['instructor_id'] ?? null, // Set to NULL if not assigned
        'image' => $data['image'] ?? null
    ]);

    return redirect()->route('classes.index')->with('success', 'Class added successfully.');
}


    public function destroy(ClassModel $class)
    {
        if ($class->image) {
            Storage::disk('public')->delete($class->image);
        }

        $class->delete();
        return redirect()->route('classes.index')->with('deleted', 'Class deleted successfully.');
    }
}
