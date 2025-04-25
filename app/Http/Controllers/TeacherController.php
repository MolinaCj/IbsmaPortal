<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ensure the admin is logged in
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'You must log in first.');
        }

        $search = $request->input('search');

        $teachers = Teacher::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })
        ->orderBy('name')
        ->paginate(10);

        $departments = Department::all();
        $inactiveTeachers = Teacher::where('is_active', false)->get();


        // Check if the request is an AJAX call
        if ($request->ajax()) {
            return response()->json($teachers->items()); // Return only the paginated results as JSON
        }

        return view('admin.teachers', compact('teachers', 'departments', 'search','inactiveTeachers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:teachers,email',
        'gender' => 'required|string|max:255',
        'civil_status' => 'required|string|max:255',
        'password' => 'required|string|min:6',
        'department_id' => 'required|exists:departments,id',
        'phoneNumber' => 'nullable|numeric',
    ]);

    Teacher::create([
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'civil_status' => $request->civil_status,
        'password' => Hash::make($request->password),
        'department_id' => $request->department_id,
        'phoneNumber' => $request->phoneNumber,
    ]);

    return redirect()->back()->with('success', 'Teacher added successfully!');
}

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {

    // }

    //for viewing the Subjects and Students in Admin/Teachers
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);

        $currentSemester = \DB::table('settings')->value('current_semester');

        // Assuming you're already getting subjects like this:
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->with([
                'students' => function ($query) use ($currentSemester) {
                    $query->wherePivot('semester', $currentSemester)
                        ->withPivot('grade', 'year_level', 'semester');
                },
                'department'
            ])
            ->get();

        // Optional: filter the subjects too, if subjects themselves have a 'semester' field
        $filteredSubjects = $subjects->filter(function ($subject) use ($currentSemester) {
            return $subject->students->isNotEmpty(); // Show only subjects with students in current semester
        });

        // Group by department
        $subjectsByDepartment = $filteredSubjects->groupBy('department_id');


        return view('admin.show', compact('teacher', 'subjects','currentSemester','subjectsByDepartment'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Teacher $teacher)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'gender' => 'required|string|max:255',
            'civil_status' => 'required|string|max:255',
            'department_id' => 'required|string|max:255',
            'phoneNumber' => 'nullable|numeric',
        ]);

        $teacher->update($request->only('name', 'email', 'gender', 'civil_status', 'department_id', 'phoneNumber'));

        return redirect()->back()->with('success', 'Teacher updated successfully!');
    }
    //for ACTIVE and inACTIVE instructors
    public function toggleStatus($id)
    {
        $teacher = Teacher::findOrFail($id);

        // Toggle the active status
        $teacher->is_active = !$teacher->is_active;

        // Only update the timestamp when setting to inactive
        if (!$teacher->is_active) {
            $teacher->updated_at = now();
        } else {
            // Store the reactivated teacher ID in session
            session()->push('reactivated_teachers', $teacher->id);
        }

        $teacher->save();

        return redirect()->back()->with('success', 'Teacher status updated successfully.');
}





    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->back()->with('deleted', 'Teacher deleted successfully!');
    }
}
