<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Graduate;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\SpecialSubject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
// Display a listing of the resource.
 // Display a listing of the students
 public function index(Request $request)
 {
     // Ensure the admin is logged in
     if (!session()->has('admin_logged_in')) {
         return redirect()->route('admin.login')->with('error', 'You must log in first.');
     }
     $currentSemester = Setting::orderBy('id', 'desc')->value('current_semester') ?? 1;
     $teachers = Teacher::all();
     $search = $request->input('search');
     $departmentId = $request->input('department_id');

     $departments = Department::with(['students' => function ($query) {
         $query->orderBy('year_level')->orderBy('last_name');
     }])->get();

     $students = Student::where('graduated', '!=', 1) // Exclude graduated students
         ->when($search, function ($query, $search) {
             $query->where(function ($q) use ($search) {
                 $q->where('last_name', 'like', '%' . $search . '%')
                   ->orWhere('student_id', 'like', '%' . $search . '%')
                   ->orWhere('first_name', 'like', '%' . $search . '%');
             });
         })
         ->when($departmentId, function ($query, $departmentId) {
             $query->where('department_id', $departmentId);
         })
         ->with(['grades.subject'])
         ->paginate(40);

         $lastStudentId = Student::latest('created_at')->value('student_id');

     return view('admin.students', compact('students', 'departments','teachers','currentSemester', 'lastStudentId'));
 }


 public function bulkEnroll(Request $request)
 {
     $studentIds = $request->input('student_ids', []);

     // Set 'enrolled' to 1 for selected students, 0 for unselected ones
     Student::query()->update(['enrolled' => 0]); // Unenroll all first
     Student::whereIn('student_id', $studentIds)->update(['enrolled' => 1]); // Enroll selected

     return response()->json(['message' => 'Enrollment updated successfully']);
 }

 public function getIncompleteStudents()
 {
     $currentSchoolYear = \DB::table('settings')->value('current_school_year');

     $students = Grade::where('school_year', $currentSchoolYear)
         ->where(function ($query) {
             $query->where('incomplete', 1)
                   ->orWhere('grade', '<', 75);
                //    ->orWhereNull('grade'); // Now includes NULL grades
         })
         ->select(['id', 'student_id', 'subject_id', 'department_id', 'grade', 'incomplete'])
         ->with([
             'student' => function ($query) {
                 $query->select('student_id', 'first_name', 'last_name', 'department_id')
                       ->where('graduated', '!=', '1')
                       ->where('enrolled', '!=', '0');
             },
             'student.department' => function ($query) {
                 $query->select('id', 'name');
             },
             'subject' => function ($query) {
                 $query->select('id', 'name');
             }
         ])
         ->get()
        //  ->filter(function ($grade) {
        //      // In case the student was filtered out by the relationship, remove this grade
        //      return $grade->student !== null;
        //  })
         ->groupBy('subject_id');

     // Fetch students who already have a special class for these subjects
     $specialStudents = Grade::where('school_year', $currentSchoolYear)
         ->where('special', 1)
         ->pluck('student_id')
         ->toArray();

     return response()->json([
         'students' => $students,
         'specialStudents' => $specialStudents // Send special students separately
     ]);
 }



// public function markSpecial(Request $request)
// {
//     $studentIds = $request->input('student_ids');

//     Grade::whereIn('id', $studentIds)->update(['special' => 1]);

//     return response()->json(['message' => 'Selected students have been marked for the special class.']);
// }


public function assignSpecialClass(Request $request)
{
    DB::beginTransaction();  // Start the transaction

    try {
        Log::info('assignSpecialClass request received', ['request' => $request->all()]);

        // Validate the incoming request
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:grades,id',
            'semester' => 'required|integer|in:1,2,3',
            'instructor_id' => 'required|exists:teachers,id',
        ]);

        Log::info('Validation passed', ['validated_data' => $validated]);

        // Get existing grade records for selected students
        $grades = Grade::whereIn('id', $validated['student_ids'])->get();
        Log::info('Fetched existing grades', ['grades' => $grades]);

        // Update the old records to mark them as special
        $updated = Grade::whereIn('id', $validated['student_ids'])->update(['special' => 1]);
        if ($updated === 0) {
            throw new \Exception('No grades were updated.');
        }
        Log::info('Updated existing grades to special', ['updated_count' => $updated]);

        // Insert new special class records
        foreach ($grades as $grade) {
            $newGrade = Grade::create([
                'student_id' => $grade->student_id,
                'department_id' => $grade->department_id,
                'subject_id' => $grade->subject_id,
                'semester' => $validated['semester'],
                'year_level' => $grade->year_level, // Keep same year level
                'school_year' => \DB::table('settings')->value('current_school_year'),
                'grade' => null, // New special class, no grade yet
                'incomplete' => 0, // Still incomplete until finished
                'special' => 1, // Mark as special
                'teacher_id' => $validated['instructor_id'], // Assign teacher
            ]);

            // Check if a special subject entry already exists before creating a new one
            $existingSpecialSubject = SpecialSubject::where('subject_id', $grade->subject_id)
                ->where('semester', $validated['semester'])
                ->where('year', $validated['semester'])
                ->where('teacher_id', $validated['instructor_id'])
                ->first();

            if (!$existingSpecialSubject) {
                // Insert the same data into the special_subjects table only if it doesn't exist already
                SpecialSubject::create([
                    'name' => $grade->subject->name,
                    'description' => $grade->subject->description,
                    'units' => $grade->subject->units,
                    'schedule' => json_encode([
                        'day' => $grade->subject->day, // store day in the schedule
                        'time' => $grade->subject->time, // store time in the schedule
                        'room' => $grade->subject->room, // store room in the schedule
                    ]), // encode as JSON
                    'subject_id' => $grade->subject_id,
                    'teacher_id' => $validated['instructor_id'],
                    'department_id' => $grade->department_id,
                    'semester' => $validated['semester'],
                    'year' => $grade->year_level,
                ]);
                Log::info('Inserted new special subject', ['special_subject' => $grade->subject->name]);
            }

            Log::info('Inserted new special class grade', ['new_grade' => $newGrade]);
        }

        DB::commit();  // Commit the transaction if everything is successful

        Log::info('Special class assignment completed');

        return response()->json(['message' => 'Special class assigned successfully.']);

    } catch (Exception $e) {
        DB::rollBack();  // Roll back the transaction if any error occurs
        // Log the error and return a response
        Log::error('Error occurred during special class assignment', ['error' => $e->getMessage()]);

        return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}





// public function studGrade(Request $request)
// {
//     $teacher = Auth::guard('teacher')->user();
//     $currentSemester = \DB::table('settings')->value('current_semester');
//     $currentYear = Setting::orderBy('id', 'desc')->value('current_school_year') ?? date('Y');

//     $subjectIds = $teacher->subjects()->pluck('id');

//     // Check if special class filter is applied
//     $isSpecialClass = $request->query('special', false);

//     // Fetch only special class subjects and students if special class filter is active
//     $subjects = $teacher->subjects()
//         ->whereHas('grades', function ($query) use ($currentYear, $currentSemester, $isSpecialClass) {
//             $query->where('school_year', $currentYear)
//                 ->where('semester', $currentSemester)
//                 ->where('special', 1); // Ensure only special students
//         })
//         ->with(['students' => function ($query) use ($currentYear, $currentSemester) {
//             $query->whereHas('grades', function ($gradeQuery) use ($currentYear, $currentSemester) {
//                 $gradeQuery->where('school_year', $currentYear)
//                     ->where('semester', $currentSemester)
//                     ->where('special', 1); // Ensure only special students
//             });
//         }])
//         ->get();

//     // Fetch editable grades only for special students
//     $editableGrades = Grade::whereIn('subject_id', $subjectIds)
//         ->where('school_year', $currentYear)
//         ->where('semester', $currentSemester)
//         ->where('special', 1) // Ensure only special students
//         ->orderBy('id', 'desc') // Ensure the latest record is first
//         ->get()
//         ->unique(fn($grade) => $grade->student_id . '-' . $grade->subject_id); // Keep only the latest record

//     return view('instructor.studGrade', compact('subjects', 'editableGrades', 'isSpecialClass'));
// }

// Show the form for creating a new resource.
public function create()
{
    return view('admin.students.create');  // Show form to create new student
}

// Store a newly created resource in storage.
public function store(Request $request)
{
    try {
        // Log incoming request
        Log::info('Received student registration data:', $request->all());
        $currentSchoolYear = Setting::value('current_school_year');

        // Validate request
        $validatedData = $request->validate([
            'student_id' => 'required|string|max:255|unique:students,student_id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'age' => 'required|integer|between:1,150',
            'sex' => 'required|string|max:255',
            'bdate' => 'required|date',
            'bplace' => 'required|string|max:255',
            'civil_status' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'cell_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'email' => 'required|email|unique:students,email',
            'father_last_name' => 'required|string|max:255',
            'father_first_name' => 'required|string|max:255',
            'father_middle_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'mother_first_name' => 'required|string|max:255',
            'mother_middle_name' => 'required|string|max:255',
            'elem_school_name' => 'required|string|max:255',
            'hs_school_name' => 'required|string|max:255',
            'tertiary_school_name' => 'string|max:255',
            'elem_grad_year' => 'required|integer|between:1900,' . date('Y'),
            'hs_grad_year' => 'required|integer|between:1900,' . date('Y'),
            'tertiary_grad_year' => 'nullable|integer|between:1900,' . date('Y'),
            'department_id' => 'required|exists:departments,id',
            'year_level' => 'required|integer|between:1,4',
            'semester' => 'required|integer|between:1,3',
            'password' => [
                            'required',
                            'string',
                            'min:8',
                            'regex:/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/'
                        ],

        ]);

        // Create the student (default as regular)
        $validatedData['school_year'] = $currentSchoolYear;
        $validatedData['regular'] = 1; // Assume regular initially
        $student = Student::create($validatedData);

        // Get all subjects for the department once (for efficiency)
        $departmentSubjects = Subject::where('department_id', $student->department_id)->get();

        if ($departmentSubjects->isEmpty()) {
            Log::warning("No subjects found for department ID: " . $student->department_id);
            return back()->with('error', 'No subjects found for this department.');
        }

        // Process subjects for each year level up to student's current year
        for ($year = 1; $year <= $student->year_level; $year++) {
            // Calculate school year for this year level (current year minus years difference)
            $yearsToSubtract = $student->year_level - $year;
            $subjectSchoolYear = $currentSchoolYear - $yearsToSubtract;

            // Filter subjects for this year level
            $yearSubjects = $departmentSubjects->where('year', $year);

            // Determine if prerequisites should be ignored (only check for current year)
            if ($year == $student->year_level) {
                if ($student->year_level > 1) {
                    $filteredSubjects = $yearSubjects; // Skip prereq check for 2nd+ year current level
                } else {
                    // Enforce prerequisites for 1st-year students (current year only)
                    $filteredSubjects = $yearSubjects->filter(function ($subject) use ($student) {
                        return is_null($subject->prerequisite_id) ||
                            Grade::where('student_id', $student->student_id)
                                ->where('subject_id', $subject->prerequisite_id)
                                ->where('grade', '>=', 75)
                                ->exists();
                    });
                }
            } else {
                // For previous years, include all subjects (assume prerequisites were met)
                $filteredSubjects = $yearSubjects;
            }

            // Insert subjects with appropriate school year
            foreach ($filteredSubjects as $subject) {
                Log::info('Assigning subject: ' . $subject->id . ' to student: ' . $student->student_id .
                          ' for year level ' . $year . ' (school year ' . $subjectSchoolYear . ')');

                Grade::create([
                    'student_id' => (string) $student->student_id,
                    'department_id' => $student->department_id,
                    'subject_id' => $subject->id,
                    'semester' => $subject->semester,
                    'year_level' => $year,
                    'grade' => null,
                    'enrolled' => 1,
                    'school_year' => $subjectSchoolYear,
                ]);
            }
        }

        Log::info("Student ID {$student->student_id} set as Regular");

        return redirect()->route('students.index')->with('success', 'Student added successfully with assigned subjects.');

    } catch (ValidationException $e) {
        Log::error('Validation failed: ' . $e->getMessage());
        return back()->withErrors($e->validator)->withInput();
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Database error while storing student data: ' . $e->getMessage());
        return back()->withInput()->with('error', 'A database error occurred while adding the student.');
    } catch (Exception $e) {
        Log::error('Error storing student data: ' . $e->getMessage());
        return back()->withInput()->with('error', 'An error occurred while adding the student. Please try again.');
    }
}

    public function checkStudentId(Request $request)
    {
        $exists = Student::where('student_id', $request->student_id)->exists();

        return response()->json(['exists' => $exists]);
    }


    public function checkEmail(Request $request)
    {
        $exists = Student::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkDuplicateName(Request $request)
    {
        $exists = Student::where('last_name', $request->last_name)
            ->where('first_name', $request->first_name)
            ->where('middle_name', $request->middle_name)
            ->where(function ($query) use ($request) {
                $query->whereNull('suffix')
                      ->orWhere('suffix', $request->suffix);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }





    // Display the specified resource.
    public function show(Student $student)
    {
        return view('students.show', compact('students'));  // Show single student's details
    }

    // Show the form for editing the specified resource.
    public function edit(Student $student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('students.edit', compact('students'));  // Show form to edit student
    }

    public function updateGrades(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        // Update grades for existing subjects
        foreach ($request->subjects as $subjectId => $data) {
            Grade::where('student_id', $student->student_id)
                ->where('subject_id', $subjectId)
                ->update(['grade' => $data['grade']]);
        }

        // Fetch all grades for the student
        $grades = Grade::where('student_id', $student->student_id)->get();

        // Get the previous year level (current year - 1)
        $previousYearLevel = $student->year_level - 1;

        // Check if there are failing grades from the previous year level
        $hasFailingGradeFromLastYear = $grades
            ->where('grade', '<', 75)
            ->where('year_level', $previousYearLevel)
            ->isNotEmpty();

        // Update student's regular status based on failing grades from the last year
        $student->update(['regular' => $hasFailingGradeFromLastYear ? 0 : 1]);

        return redirect()->back()->with('success', 'Grades updated successfully.');
    }

    public function recheckStudentSubjects($studentId)
    {
        $student = Student::where('student_id', $studentId)->firstOrFail();
        $currentSchoolYear = DB::table('settings')->value('current_school_year');

        // Get all passed subjects
        $passedSubjects = Grade::where('student_id', $studentId)
            ->where('grade', '>=', 75)
            ->pluck('subject_id')
            ->toArray();

        // Get subjects that require those passed subjects as prerequisites
        $eligibleSubjects = Subject::whereIn('prerequisite_id', $passedSubjects)
            ->whereNotIn('id', Grade::where('student_id', $studentId)->pluck('subject_id'))
            ->where('year', '<=', $student->year_level) // Ensure subject year level matches student year level
            ->get();

        $count = 0;

        foreach ($eligibleSubjects as $subject) {
            // Create the grade entry only if not already taken
            Grade::create([
                'student_id' => $studentId,
                'department_id' => $student->department_id,
                'subject_id' => $subject->id,
                'semester' => $subject->semester,
                'year_level' => $student->year_level,
                'school_year' => $currentSchoolYear,
                'grade' => null,
            ]);
            $count++;
        }

        return response()->json([
            'message' => "Re-added $count eligible subject(s) for student {$student->first_name} {$student->last_name}.",
            'subjects_added' => $eligibleSubjects->pluck('name')
        ]);
    }

    public function incrementYearLevelForStudent($studentId)
    {
        // Get the current school year from settings
        $currentSchoolYear = Setting::getSchoolYear();

        Log::info("Starting Year Level Increment for Student | Student ID: $studentId | Current School Year: $currentSchoolYear");

        $student = Student::where('student_id', $studentId)->firstOrFail();

        // Skip students who are not enrolled
        if ($student->enrolled == 0) {
            Log::info("Skipping Student: {$student->student_id} | Not Enrolled");
            return response()->json(['error' => 'Student is not enrolled.'], 400);
        }

        Log::info("Processing Student: {$student->student_id} | Current Year Level: {$student->year_level}");

        // Get all passed subjects
        $passedSubjects = Grade::where('student_id', $student->student_id)
            ->where('grade', '>=', 75)
            ->pluck('subject_id')
            ->toArray();

        // Get failed subjects
        $failedSubjects = Grade::where('student_id', $student->student_id)
            ->where(function ($query) {
                $query->where('grade', '<', 75)
                    ->orWhereNull('grade');
            })
            ->whereNotIn('subject_id', $passedSubjects)
            ->get();

        // Check if student has passed all subjects (Graduate Condition)
        $totalSubjects = Subject::where('department_id', $student->department_id)->count();
        $passedCount = count($passedSubjects);

        if ($passedCount == $totalSubjects) {
            // If student is eligible for graduation, mark as graduated and skip further promotion
            Graduate::create([
                'student_id' => $student->student_id,
                'name' => $student->last_name . ', ' . $student->first_name . ' ' . $student->middle_name,
                'department_id' => $student->department_id,
                'graduation_year' => $currentSchoolYear,
            ]);

            Log::info("Student Graduated | Student ID: {$student->student_id}");
            return response()->json(['message' => 'Student has graduated.'], 200);
        }

        // Promote student only if they passed at least one subject
        if (!empty($passedSubjects)) {
            $student->year_level += 1;
            $student->semester = 1;
            $student->save();
            Log::info("Student Promoted | Student ID: {$student->student_id} | New Year Level: {$student->year_level}");
        } else {
            Log::info("Student: {$student->student_id} not promoted due to failing all subjects.");
            return response()->json(['error' => 'Student cannot be promoted due to failing all subjects.'], 400);
        }

        // Re-enroll failed subjects in the same semester but next year level
        foreach ($failedSubjects as $failedSubject) {
            $newYearLevel = $student->year_level;
            $newSemester = $failedSubject->semester;

            $existingGrade = Grade::where('student_id', $student->student_id)
                ->where('subject_id', $failedSubject->subject_id)
                ->where('year_level', $newYearLevel)
                ->where('school_year', $currentSchoolYear) // Using the current school year
                ->exists();

            if (!$existingGrade) {
                Grade::create([
                    'student_id' => $student->student_id,
                    'department_id' => $student->department_id,
                    'subject_id' => $failedSubject->subject_id,
                    'semester' => $newSemester,
                    'year_level' => $newYearLevel,
                    'school_year' => $currentSchoolYear, // Using the current school year
                    'grade' => null,
                ]);
                Log::info("Re-enrolled Failed Subject | Student ID: {$student->student_id} | Subject ID: {$failedSubject->subject_id} | Year Level: $newYearLevel | Semester: $newSemester");
            }
        }

        // Assign new subjects for the next year level
        $semesters = [1, 2];

        foreach ($semesters as $semester) {
            $newSubjects = Subject::where('year', $student->year_level)
                ->where('semester', $semester)
                ->where('department_id', $student->department_id)
                ->get();

            foreach ($newSubjects as $subject) {
                // Check if prerequisite is met
                if ($subject->prerequisite_id) {
                    $prerequisiteGrade = Grade::where('student_id', $student->student_id)
                        ->where('subject_id', $subject->prerequisite_id)
                        ->where('grade', '>=', 75)
                        ->exists();

                    if (!$prerequisiteGrade) {
                        Log::info("Skipping Subject: {$subject->id} for Student: {$student->student_id} due to failed prerequisite.");
                        continue;
                    }
                }

                // Avoid duplicate subject enrollment
                $existingGrade = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('school_year', $currentSchoolYear) // Using the current school year
                    ->exists();

                if (!$existingGrade) {
                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $semester,
                        'year_level' => $student->year_level,
                        'school_year' => $currentSchoolYear, // Using the current school year
                        'grade' => null,
                    ]);
                    Log::info("Assigned New Subject | Student ID: {$student->student_id} | Subject ID: {$subject->id}");
                }
            }
        }

        return response()->json(['message' => 'Student promoted successfully.'], 200);
    }


    public function shiftDepartment(Request $request, $student_id, $new_department_id)
    {
        // Find the student by ID
        $student = Student::findOrFail($student_id);

        // Ensure the department ID is valid
        $department = Department::find($new_department_id);
        if (!$department) {
            return response()->json(['message' => 'Invalid department'], 400);
        }

        // Store the old department ID
        $oldDepartmentId = $student->department_id;

        // Get the subjects the student has taken in the current department
        $currentSubjects = $student->subjects;

        // Retrieve all the subjects in the new department (with matching codes)
        $newDepartmentSubjects = Subject::where('department_id', $new_department_id)->get();

        // Get the current school year from the settings table using the getSchoolYear method
        $currentSchoolYear = Setting::getSchoolYear();

        // Credit the subjects with matching codes in the new department
        foreach ($currentSubjects as $currentSubject) {
            // Find the matching subject in the new department by code
            $matchingSubject = $newDepartmentSubjects->firstWhere('code', $currentSubject->code);

            if ($matchingSubject) {
                // Check if the subject is already taken in the new department and assign the grade
                $existingGrade = $student->grades()->where('subject_id', $matchingSubject->id)->first();
                $schoolYear = $existingGrade ? $existingGrade->school_year : $currentSchoolYear;

                if (!$existingGrade) {
                    // Get the grade from the grades table for the current (old) subject
                    $originalGrade = $student->grades()->where('subject_id', $currentSubject->id)->first();

                    // Attach the subject to the new department (if not already attached)
                    $student->subjects()->attach($matchingSubject->id, [
                        'grade' => $originalGrade ? $originalGrade->grade : null,          // ✅ Copy grade from grades table
                        'year_level' => 1,                                                 // Set year level to 1 (first year)
                        'semester' => $matchingSubject->semester,                           // Set semester from the subject
                        'department_id' => $new_department_id,                              // Set the department_id for new department
                        'school_year' => $schoolYear,                                       // Use the school year from grades table
                    ]);
                }
            }
        }

        // Assign first-year subjects for the new department
        // Retrieve all first-year subjects (assuming 'year' is stored in the Subject table)
        $firstYearSubjects = $newDepartmentSubjects->where('year', 1);  // Filter subjects for year 1

        foreach ($firstYearSubjects as $subject) {
            // Check if the student already has the subject in the new department
            $existingGrade = $student->grades()->where('subject_id', $subject->id)->first();
            if (!$existingGrade) {
                // Attach the first-year subjects to the student (if they haven't taken them already)
                $student->subjects()->attach($subject->id, [
                    'grade' => null,                                       // New subjects should have no grade initially
                    'year_level' => 1,                                     // Set year level to 1 (first year)
                    'semester' => $subject->semester,                       // Set semester from the subject
                    'department_id' => $new_department_id,                 // Set the department_id for new department
                    'school_year' => $currentSchoolYear,                    // Set the current school year for new subjects
                ]);
            }
        }

        // Optionally, reset year level and semester if needed
        $student->year_level = 1;  // Reset year level to 1 (first year)
        $student->semester = 1;    // Reset semester to 1 (or adjust based on your needs)

        // Update the student's department ID to the new department
        $student->department_id = $new_department_id;
        $student->save();

        return response()->json(['message' => 'Department shifted and subjects credited successfully!']);
    }








    // public function updateGrades(Request $request, $studentId)
    // {
    //     $student = Student::findOrFail($studentId);

    //     // Update grades for existing subjects
    //     foreach ($request->subjects as $subjectId => $data) {
    //         Grade::where('student_id', $student->student_id)
    //             ->where('subject_id', $subjectId)
    //             ->update(['grade' => $data['grade']]);
    //     }

    //     // Fetch all subjects the student has taken with grades
    //     $grades = Grade::where('student_id', $student->student_id)->get();
    //     $passedSubjects = $grades->where('grade', '>=', 75)->pluck('subject_id')->toArray();

    //     // Find new subjects that have passed subjects as prerequisites
    //     $newSubjects = Subject::whereNotIn('id', $grades->pluck('subject_id')) // Avoid duplicates
    //         ->whereNotNull('prerequisite_id') // Ensure the subject has a prerequisite
    //         ->where('year', $student->year_level) // Only subjects for the student's current year level
    //         ->get();


    //     foreach ($newSubjects as $subject) {
    //         // Get the prerequisite subject's grade
    //         $prerequisiteGrade = $grades->where('subject_id', $subject->prerequisite_id)->first();

    //         // Only add the subject if the prerequisite exists **and** has a passing grade (>= 75)
    //         if ($prerequisiteGrade && $prerequisiteGrade->grade >= 75) {
    //             Log::info("Adding subject: {$subject->id} for student: {$student->student_id}");

    //             Grade::create([
    //                 'student_id' => $student->student_id,
    //                 'department_id' => $student->department_id,
    //                 'subject_id' => $subject->id,
    //                 'semester' => $subject->semester,
    //                 'year_level' => $student->year_level, // Keep current year
    //                 'grade' => null, // Not yet graded
    //             ]);
    //         }
    //     }

    //     // Check if student has any failing grades
    //     $hasFailingGrade = $grades->where('grade', '<', 75)->isNotEmpty();

    //     // Update student's regular status
    //     $student->update(['regular' => $hasFailingGrade ? 0 : 1]);

    //     return redirect()->back()->with('success', 'Grades updated successfully.');
    // }






// Update the specified resource in storage.
public function update(Request $request, Student $student)
{
    // Validate the incoming data
    $validated = $request->validate([
        'student_id' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:255',
        'age' => 'required|integer|between:1,150',
        'sex' => 'required|string|max:255',
        'bdate' => 'required|date',
        'bplace' => 'required|string|max:255',
        'civil_status' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'region' => 'required|string|max:255',
        'province' => 'string|max:255',
        'city' => 'required|string|max:255',
        'barangay' => 'required|string|max:255',
        'cell_no' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
        'email' => 'required|email',
        'father_last_name' => 'required|string|max:255',
        'father_first_name' => 'required|string|max:255',
        'father_middle_name' => 'required|string|max:255',
        'mother_last_name' => 'required|string|max:255',
        'mother_first_name' => 'required|string|max:255',
        'mother_middle_name' => 'required|string|max:255',
        'elem_school_name' => 'required|string|max:255',
        'hs_school_name' => 'required|string|max:255',
        'tertiary_school_name' => 'string|max:255',
        'elem_grad_year' => 'required|integer|between:1900,' . date('Y'),
        'hs_grad_year' => 'required|integer|between:1900,' . date('Y'),
        'tertiary_grad_year' =>'nullable|integer|between:1900,' . date('Y'),
        'password' => 'nullable|string|min:6', // password is now optional
        'section' => 'nullable|string|max:255',
    ]);

    // Hash the password if it's provided
    if ($request->filled('password')) {
        $validated['password'] = bcrypt($request->password); // Hash the password if provided
    } else {
        unset($validated['password']); // Don't update the password if it's not provided
    }

    // Update the student record with the validated data
    $student->update($validated);

    // Redirect back to the student index with a success message
    return redirect()->route('students.index')->with('success', 'Student updated successfully.');
}



public function toggleEnrollment(Student $student)
    {
        $student->enrolled = !$student->enrolled; // Toggle the value (1 to 0, or 0 to 1)
        $student->save();

        return response()->json([
            'success' => true,
            'enrolled' => $student->enrolled
        ]);
    }




// Remove the specified resource from storage.
public function destroy(Student $student)
    {
        $student->delete();  // Delete the student from database
        return redirect()->route('students.index')->with('deleted','Student deleted successfully');  // Redirect to the student index page
    }
}
