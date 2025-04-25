<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\SpecialSubject;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    // Display all subjects
    public function index(Request $request)
{
    // Ensure the admin is logged in
    if (!session()->has('admin_logged_in')) {
        return redirect()->route('admin.login')->with('error', 'You must log in first.');
    }

    // Retrieve all departments and teachers
    $departments = Department::all();
    $teachers = Teacher::all()->groupBy('department_id');

    // Get search term from request
    $search = $request->input('search');

    // Initialize the query for subjects
    $subjectsQuery = Subject::query();

    // If there's a search term, apply filters
    if ($search) {
        $subjectsQuery->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhereHas('department', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('teacher', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
    }

    $subjects = $subjectsQuery->with('department', 'teacher')->get();

    foreach ($subjects as $subject) {
        $subject->scheduleData = $subject->schedule; // No json_decode needed
    }
    


    // Group subjects by department, year, and semester
    $groupedSubjects = $subjects->groupBy(['department.name', 'year', 'semester']);

    // Define the correct department order
    $departmentOrder = ['BSIT', 'BSA', 'BSBA', 'CRIM', 'MIDWIFERY'];

    // Sort the grouped subjects by department order
    $sortedSubjects = collect($groupedSubjects)->sortBy(function ($value, $key) use ($departmentOrder) {
        return array_search(strtoupper($key), $departmentOrder);
    });

    // Check if we need to display special subjects
    $showSpecialClasses = $request->show_special == 1;

    // Fetch special subjects if show_special is true
    $specialSubjects = $showSpecialClasses
        ? SpecialSubject::with('department', 'teacher')->get()
        : collect();

    // Return the view with subjects data
    return view('admin.subject', compact('sortedSubjects', 'departments', 'teachers', 'search', 'subjects','specialSubjects'));
}

    // Store a newly created subject
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if a subject with the same code and department_id already exists
                    $exists = Subject::where('code', $value)
                        ->where('department_id', $request->department_id)
                        ->exists();
                    
                    if ($exists) {
                        $fail('This subject code already exists in the selected department.');
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if a subject with the same name and department_id already exists
                    $exists = Subject::where('name', $value)
                        ->where('department_id', $request->department_id)
                        ->exists();
                    
                    if ($exists) {
                        $fail('This subject name already exists in the selected department.');
                    }
                },
            ],
            'description' => 'nullable|string',
            'units' => 'nullable|integer',
            'schedule' => 'nullable|array', // Validate that schedule is an array
            'schedule.*.day' => 'nullable|string', // Validate each day within the schedule array
            'schedule.*.start_time' => 'nullable|date_format:H:i', // Validate each start time
            'schedule.*.end_time' => 'nullable|date_format:H:i', // Validate each end time
            'room' => 'nullable|string',
            'teacher_id' => 'nullable|exists:teachers,id',
            'department_id' => 'required|exists:departments,id',
            'semester' => 'required|in:1,2',
            'year' => 'required|in:1,2,3,4',
            'major' => 'required|in:0,1',
        ]);
    
        // Prepare schedule data
        $schedule = [];
        if ($request->has('schedule')) {
            // Loop through the schedule array and prepare the correct structure
            foreach ($request->schedule as $scheduleItem) {
                $schedule[] = [
                    'day' => $scheduleItem['day'],
                    'start_time' => $scheduleItem['start_time'],
                    'end_time' => $scheduleItem['end_time'],
                ];
            }
        }
    
        // Save the subject, including the schedule
        try {
            Subject::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'units' => $request->units,
                'schedule' => $schedule, // Array that will be automatically cast to JSON
                'room' => $request->room,
                'teacher_id' => $request->teacher_id,
                'department_id' => $request->department_id,
                'semester' => $request->semester,
                'year' => $request->year,
                'major' => $request->major,
            ]);
    
            return redirect()->route('subjects.index')->with('success', 'Subject created successfully :).');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: Unable to create subject due to duplication or other issues.');
        }
    }
    
    
    
    
    
    


    // Show the form to edit an existing subject
public function edit(Subject $subject)
{
    $departments = Department::all();
    $teachers = Teacher::all()->groupBy('department_id'); // Get teachers grouped by department
    return view('admin.subject', compact('subject', 'departments', 'teachers'));
}




public function update(Request $request, Subject $subject)
{
    // Log the raw request data for debugging
    Log::info('Raw Request Data:', $request->all());
    
    // Validate the incoming data
    $request->validate([
        'code' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) use ($request, $subject) {
                // Check if a subject with the same code and department_id already exists, excluding the current subject
                $exists = Subject::where('code', $value)
                    ->where('department_id', $request->department_id)
                    ->where('id', '!=', $subject->id) // Exclude the current subject being updated
                    ->exists();
                
                if ($exists) {
                    $fail('This subject code already exists in the selected department.');
                }
            },
        ],
        'name' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) use ($request, $subject) {
                // Check if a subject with the same name and department_id already exists, excluding the current subject
                $exists = Subject::where('name', $value)
                    ->where('department_id', $request->department_id)
                    ->where('id', '!=', $subject->id) // Exclude the current subject being updated
                    ->exists();
                
                if ($exists) {
                    $fail('This subject name already exists in the selected department.');
                }
            },
        ],
        'description' => 'nullable|string',
        'units' => 'nullable|integer',
        'teacher_id' => 'required|exists:teachers,id',
        'room' => 'nullable|string',
        'department_id' => 'required|exists:departments,id',
        'semester' => 'required|in:1,2,3',
        'year' => 'required|in:1,2,3,4',
        'major' => 'required|in:0,1',
        'schedule_json' => 'nullable|string',
    ]);

    // Process schedule data from JSON
    $scheduleData = [];
    if ($request->has('schedule_json') && !empty($request->schedule_json)) {
        try {
            $scheduleData = json_decode($request->schedule_json, true);
            
            // Validate each schedule item
            foreach ($scheduleData as $key => $item) {
                if (empty($item['day']) || empty($item['start_time']) || empty($item['end_time'])) {
                    unset($scheduleData[$key]);
                }
            }
            
            // Re-index the array
            $scheduleData = array_values($scheduleData);
        } catch (\Exception $e) {
            Log::error('Error decoding schedule JSON: ' . $e->getMessage());
        }
    }
    
    // Log the processed schedule data
    Log::info('Processed Schedule Data:', $scheduleData);

    // Update the subject with the validated data
    $subject->update([
        'code' => $request->code,
        'name' => $request->name,
        'description' => $request->description,
        'units' => $request->units,
        'teacher_id' => $request->teacher_id,
        'room' => $request->room,
        'department_id' => $request->department_id,
        'semester' => $request->semester,
        'year' => $request->year,
        'major' => $request->major,
        'schedule' => $scheduleData,
    ]);

    return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
}




public function updateSpecial(Request $request, $id)
{
    $specialSubject = SpecialSubject::findOrFail($id);

    // Validate request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'units' => 'required|integer|min:1',
        'room' => 'nullable|string|max:50',
        'schedule' => 'nullable|array',
        'schedule.*.day' => 'required_with:schedule|string',
        'schedule.*.start_time' => 'required_with:schedule|date_format:H:i',
        'schedule.*.end_time' => 'required_with:schedule|date_format:H:i',
    ]);

    // Prepare the schedule array (no need to json_encode)
    $scheduleData = $request->input('schedule') ?: [];

    // Update the special subject
    $specialSubject->update([
        'name' => $request->name,
        'description' => $request->description,
        'units' => $request->units,
        'room' => $request->room,
        'schedule' => $scheduleData, // Eloquent will handle JSON encoding automatically
    ]);

    return response()->json(['success' => true, 'message' => 'Special subject updated successfully']);
}

public function updateSchedule(Request $request, $id)
{
    $subject = SpecialSubject::findOrFail($id);
    $schedule = $request->input('schedule');

    if (!is_array($schedule)) {
        return response()->json(['success' => false, 'message' => 'Invalid schedule format.']);
    }

    $subject->schedule = json_encode($schedule);
    $subject->save();

    return response()->json(['success' => true]);
}






    // Delete the subject from the database
    public function destroy(Subject $subject)
    {
        // Delete the subject
        $subject->delete();

        // Redirect back with a success message
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
