<?php

namespace App\Http\Controllers;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Graduate;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;



class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ensure the admin is logged in
        // if (!session()->has('admin_logged_in')) {
        //     return redirect()->route('admin.login')->with('error', 'You must log in first.');
        // }

        // Count students by department, only enrolled and not graduated
        $studentsByDepartment = DB::table('students')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->where('students.enrolled', 1) // Only currently enrolled students
            ->where('students.graduated', 0) // Exclude already graduated students
            ->select(
                'departments.name as department',
                DB::raw('count(case when sex = "Male" then 1 end) as male'),
                DB::raw('count(case when sex = "Female" then 1 end) as female')
            )
            ->groupBy('departments.name')
            ->get();

        // Total male and female students who are enrolled and not graduated
        $totalMale = DB::table('students')
            ->where('sex', 'Male')
            ->where('enrolled', 1)
            ->where('graduated', 0)
            ->count();

        $totalFemale = DB::table('students')
            ->where('sex', 'Female')
            ->where('enrolled', 1)
            ->where('graduated', 0)
            ->count();

        // Count active male and female teachers
        $instructorMale = DB::table('teachers')
            ->where('gender', 'Male')
            ->where('is_active', 1) // Only active instructors
            ->count();

        $instructorFemale = DB::table('teachers')
            ->where('gender', 'Female')
            ->where('is_active', 1) // Only active instructors
            ->count();

        // Total active instructors (male + female)
        $totalInstructors = $instructorMale + $instructorFemale;

        return view('admin.dashboard', compact('studentsByDepartment', 'totalMale', 'totalFemale', 'instructorMale', 'instructorFemale', 'totalInstructors'));
    }


    public function settings()
    {
        $currentSchoolYear = Setting::getSchoolYear();  // Get current school year from the settings table
        $currentSemester = Setting::getSemester();

        // Fetch students who are 4th year and enrolled
        $students = Student::where('year_level', 4)
            ->where('enrolled', 1)
            ->get();

        // Fetch already graduated students for this school year
        $graduatedStudents = Graduate::where('graduation_year', $currentSchoolYear)->get();

        $eligibleStudents = collect();   // ✅ Upcoming graduates (not yet in graduates table)

        foreach ($students as $student) {
            $requiredSubjects = Subject::where('department_id', $student->department_id)
                ->pluck('id')
                ->toArray();

            $passedSubjects = Grade::where('student_id', $student->student_id)
                ->where('grade', '>=', 75)
                ->pluck('subject_id')
                ->toArray();

            if (!array_diff($requiredSubjects, $passedSubjects)) {
                // ✅ Only include if NOT in graduates table
                $alreadyGraduated = $graduatedStudents->contains('student_id', $student->student_id);

                if (!$alreadyGraduated) {
                    $eligibleStudents->push($student);
                }
            }
        }

        // Group both by department
        $groupedByDepartment = $eligibleStudents->groupBy('department_id'); // upcoming grads
        $groupedGraduated = $graduatedStudents->groupBy('department_id'); // already graduated

        return view('admin.settings', compact(
            'currentSemester',
            'groupedByDepartment', // for upcoming graduates
            'groupedGraduated'  ,   // for modal (already graduated)
            'currentSchoolYear'
        ));
    }

    public function updateGraduationStatus(Request $request)
{
    $currentSchoolYear = Setting::getSchoolYear(); // Get the current school year

    // Get the list of selected student IDs from the form submission
    $selectedStudents = $request->input('students', []);

    // Process each selected student
    foreach ($selectedStudents as $studentId) {
        $student = Student::find($studentId);

        if ($student) {
            // Check if the student is already graduated for the current school year
            $existingGraduate = Graduate::where('student_id', $student->student_id)
                ->where('graduation_year', $currentSchoolYear)
                ->first();

            // If the student is being checked for graduation, update their status in the graduates table and students table
            if (!$existingGraduate) {
                Graduate::updateOrCreate(
                    ['student_id' => $student->student_id],
                    [
                        'name' => trim("{$student->last_name}, {$student->first_name} " . ($student->middle_name ?? '')),
                        'department_id' => $student->department_id,
                        'graduation_year' => $currentSchoolYear,
                    ]
                );

                // Mark the student as graduated
                $student->graduated = 1;
                $student->save();
            }
        }
    }

    // Process unselected students (students unchecked from the list)
    $unselectedStudents = Student::whereNotIn('student_id', $selectedStudents)
        ->where('graduated', 1) // Check only students who are currently marked as graduated
        ->get();

    foreach ($unselectedStudents as $student) {
        // Check if the student is graduated for the current school year
        $existingGraduate = Graduate::where('student_id', $student->student_id)
            ->where('graduation_year', $currentSchoolYear)
            ->first();

        if ($existingGraduate) {
            // Remove the student from the graduates table
            Graduate::where('student_id', $student->student_id)
                ->where('graduation_year', $currentSchoolYear)
                ->delete();

            // Set the graduated value back to 0
            $student->graduated = 0;
            $student->save();
        }
    }

    return redirect()->back()->with('success', 'Graduation status updated.');
}



    public function updateSemester(Request $request)
    {
        $request->validate([
            'semester' => 'required|in:1,2,3'
        ]);

        // Update semester setting globally
        Setting::query()->update(['current_semester' => $request->semester]);

        // Update all students' semester and reset enrollment
        Student::query()->update([
            'semester' => $request->semester,
            'enrolled' => 0
        ]);


        // Fetch updated school year from settings
        $currentSchoolYear = Setting::getSchoolYear();

        // Get all students
        $students = Student::all();

        foreach ($students as $student) {
            Log::info("Processing student: {$student->student_id}");

            // Re-Add Failed or Null-Graded Subjects for the Same Semester
            $failedSubjects = Grade::where('student_id', $student->student_id)
                ->where(function ($query) {
                    $query->where('grade', '<', 75)
                        ->orWhereNull('grade'); // Also check ungraded subjects
                })
                ->where('semester', $request->semester) // Only re-enroll for the current semester
                ->pluck('subject_id')
                ->toArray();

            $subjectsToReAdd = Subject::whereIn('id', $failedSubjects)->get();

            foreach ($subjectsToReAdd as $subject) {
                $alreadyReAdded = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('semester', $subject->semester)
                    ->where('school_year', $currentSchoolYear) //
                    ->exists();

                if (!$alreadyReAdded) {
                    Log::info("Re-adding failed/null subject: {$subject->id} for student: {$student->student_id}");

                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $subject->semester, // Same semester
                        'year_level' => $student->year_level, // Stay in current year
                        'school_year' => $currentSchoolYear, //
                        'grade' => null, // Reset grade
                    ]);
                }
            }

            // Add Next Eligible Subjects (After Passing Prerequisites)
            $passedSubjects = Grade::where('student_id', $student->student_id)
                ->where('grade', '>=', 75)
                ->pluck('subject_id')
                ->toArray();

            // Find subjects that require these passed subjects as prerequisites
            $newSubjects = Subject::whereIn('prerequisite_id', $passedSubjects)
                ->where('semester', $request->semester) // Use dynamic semester
                ->where('year', '<=', $student->year_level) //
                ->whereNotIn('id', Grade::where('student_id', $student->student_id)->pluck('subject_id')) // Exclude subjects already taken
                ->get();



            foreach ($newSubjects as $subject) {
                $alreadyTaken = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('school_year', $currentSchoolYear) //
                    ->exists();

                if (!$alreadyTaken) {
                    Log::info("Adding new subject: {$subject->id} (Prerequisite Passed) for student: {$student->student_id}");

                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $subject->semester,
                        'year_level' => $student->year_level, // Keep current year
                        'school_year' => $currentSchoolYear, //
                        'grade' => null, // Not yet graded
                    ]);
                }
            }

            // Special Case: Summer Subjects (Semester 3)
            if ($request->semester == 3) {
                $summerSubjects = Subject::where('semester', 3)
                    ->where('department_id', $student->department_id)
                    ->get();

                foreach ($summerSubjects as $subject) {
                    $alreadyEnrolled = Grade::where('student_id', $student->student_id)
                        ->where('subject_id', $subject->id)
                        ->where('semester', 3)
                        ->where('school_year', $currentSchoolYear) //
                        ->exists();

                    if (!$alreadyEnrolled) {
                        Log::info("Enrolling Student: {$student->student_id} in Summer Subject: {$subject->id}");

                        Grade::create([
                            'student_id' => $student->student_id,
                            'department_id' => $student->department_id,
                            'subject_id' => $subject->id,
                            'semester' => 3,
                            'year_level' => $student->year_level, // Stay in current year
                            'school_year' => $currentSchoolYear, //
                            'grade' => null, // Not yet graded
                        ]);
                    }
                }
            }
        } // End of foreach ($students as $student)

        return redirect()->back()->with('success', 'Semester updated successfully. Failed/null subjects re-added, prerequisite subjects assigned, and summer subjects enrolled.');
    }



    public function incrementYearLevel()
    {
        // Get the current school year from settings
        $currentSchoolYear = Setting::getSchoolYear();
        $nextSchoolYear = $currentSchoolYear + 1;

        Log::info("Starting Year Level Increment | Current School Year: $currentSchoolYear | Next School Year: $nextSchoolYear");

        $students = Student::where('year_level', '<', 4)->get();

        foreach ($students as $student) {
            // Skip students who are not enrolled
            if ($student->enrolled == 0) {
                Log::info("Skipping Student: {$student->student_id} | Not Enrolled");
                continue;
            }

            Log::info("Processing Student: {$student->student_id} | Current Year Level: {$student->year_level}");

            // Get all passed subjects FOR CURRENT YEAR ONLY
            $passedSubjects = Grade::where('student_id', $student->student_id)
                ->where('grade', '>=', 75)
                ->where('school_year', $currentSchoolYear)
                ->pluck('subject_id')
                ->toArray();

            // Get only the subjects that are still failed and were never passed (current year)
            $failedSubjects = Grade::where('student_id', $student->student_id)
                ->where('school_year', $currentSchoolYear)
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

                // $student->update(['graduated' => 1]); // Mark as graduated

                Log::info("Student Graduated | Student ID: {$student->student_id}");
                continue; // Skip the rest of the processing for graduates
            }

            // NEW: Calculate total enrolled subjects FOR CURRENT SCHOOL YEAR ONLY
            $totalEnrolledSubjects = Grade::where('student_id', $student->student_id)
                ->where('school_year', $currentSchoolYear)
                ->count();

            // CURRENT YEAR LEVEL ONLY
            // $totalEnrolledSubjects = Grade::where('student_id', $student->student_id)
            //     ->where('school_year', $currentSchoolYear)
            //     ->where('year_level', $student->year_level)
            //     ->count();

            // NEW: Count passed major subjects FOR CURRENT SCHOOL YEAR ONLY
            $passedMajorSubjects = Grade::where('student_id', $student->student_id)
                ->where('grade', '>=', 75)
                ->where('school_year', $currentSchoolYear)
                ->whereHas('subject', function ($query) {
                    $query->where('major', 1); // Check major subjects
                })
                ->count();

            // NEW: Only check promotion rules if student has enrolled subjects
            if ($totalEnrolledSubjects > 0) {
                // Calculate the percentage of passed subjects
                $passedPercentage = ($passedCount / $totalEnrolledSubjects) * 100;

                // Promote if passed 60% of current year's subjects OR (2+ major subjects AND 30% of all subjects)
                if ($passedPercentage >= 60 || ($passedMajorSubjects >= 2 && $passedPercentage >= 30)) {
                    $student->year_level += 1;
                    $student->semester = 1;
                    $student->school_year = $nextSchoolYear;
                    $student->save();
                    Log::info("Student Promoted | Student ID: {$student->student_id} | New Year Level: {$student->year_level} | Passed: {$passedPercentage}%");
                } else {
                    Log::info("Student: {$student->student_id} not promoted (failed promotion criteria). Passed: {$passedPercentage}%, Major subjects: {$passedMajorSubjects}");
                    continue;
                }
            } else {
                Log::info("Student: {$student->student_id} not promoted (no enrolled subjects for current year).");
                continue;
            }

            // Re-enroll failed subjects in the same semester but next year level
            foreach ($failedSubjects as $failedSubject) {
                $newYearLevel = $student->year_level;
                $newSemester = $failedSubject->semester;

                $existingGrade = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $failedSubject->subject_id)
                    ->where('year_level', $newYearLevel)
                    ->where('school_year', $nextSchoolYear)
                    ->exists();

                if (!$existingGrade) {
                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $failedSubject->subject_id,
                        'semester' => $newSemester,
                        'year_level' => $newYearLevel,
                        'school_year' => $nextSchoolYear,
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
                        ->where('school_year', $nextSchoolYear)
                        ->exists();

                    if (!$existingGrade) {
                        Grade::create([
                            'student_id' => $student->student_id,
                            'department_id' => $student->department_id,
                            'subject_id' => $subject->id,
                            'semester' => $semester,
                            'year_level' => $student->year_level,
                            'school_year' => $nextSchoolYear,
                            'grade' => null,
                        ]);
                        Log::info("Assigned New Subject | Student ID: {$student->student_id} | Subject ID: {$subject->id}");
                    }
                }
            }

            // Add subjects that became available after passing prerequisites
            $nextSubjects = Subject::whereIn('prerequisite_id', $passedSubjects)
                ->where('year', $student->year_level)
                ->get();

            foreach ($nextSubjects as $subject) {
                $alreadyEnrolled = Grade::where('student_id', $student->student_id)
                    ->where('subject_id', $subject->id)
                    ->where('school_year', $nextSchoolYear)
                    ->exists();

                if (!$alreadyEnrolled) {
                    Grade::create([
                        'student_id' => $student->student_id,
                        'department_id' => $student->department_id,
                        'subject_id' => $subject->id,
                        'semester' => $subject->semester,
                        'year_level' => $student->year_level,
                        'school_year' => $nextSchoolYear,
                        'grade' => null,
                    ]);
                    Log::info("Enrolled Subject After Prerequisite Passed | Student ID: {$student->student_id} | Subject ID: {$subject->id}");
                }
            }
        }

        // Update school year and reset semester in settings
        Setting::incrementSchoolYear();
        Setting::query()->update(['current_semester' => 1]);

        // 🔥 Reset enrollment status for all students
        // Student::query()->update(['enrolled' => 0]);

        // Fetch the updated school year AFTER incrementing it
        $nextSchoolYear = Setting::getSchoolYear();

        Log::info("Year Level Increment Completed.");

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Students promoted, semester reset, failed subjects re-enrolled, prerequisite subjects assigned, and graduates recorded.');
    }



    public function updateSchoolYear(Request $request)
    {
        $request->validate([
            'current_school_year' => 'required|integer|min:2024',
        ]);

        $setting = Setting::first();
        if ($setting) {
            $setting->update(['current_school_year' => $request->current_school_year]);
        } else {
            Setting::create(['current_school_year' => $request->current_school_year, 'current_semester' => 1]);
        }

        return back()->with('success', 'School Year updated successfully!');
    }

    public function toggleGradingLock(Request $request)
    {
        // Get the settings row (assuming only one row exists)
        $settings = Setting::first();

        if (!$settings) {
            return response()->json(['error' => 'Settings not found'], 404);
        }

        // Toggle the grading lock
        $settings->grading_locked = !$settings->grading_locked;
        $settings->save();

        // Log the action
        Log::info('Grading lock toggled by admin', ['status' => $settings->grading_locked]);

        return response()->json([
            'success' => true,
            'locked' => $settings->grading_locked
        ]);
    }

    public function checkGradingLock()
    {
        $locked = Setting::latest('id')->value('grading_locked') == 1;

        return response()->json([
            'success' => true,
            'locked' => $locked
        ]);
    }

    //     public function showGraduationModal()
    // {
    //     $currentSchoolYear = Setting::getSchoolYear();


    //     return view('admin.settings', compact('eligibleStudents'));
    // }

//     public function processGraduation(Request $request)
// {
//     $selectedStudents = $request->input('students', []); // Get selected students
//     $currentSchoolYear = Setting::getSchoolYear();

//     if (empty($selectedStudents)) {
//         return redirect()->back()->with('error', 'No students selected for graduation.');
//     }

//     foreach ($selectedStudents as $studentId) {
//         $student = Student::find($studentId);

//         if ($student) {
//             // Log the values for debugging
//             Log::info("Processing Student: {$student->student_id}");
//             Log::info("First Name: " . $student->first_name);
//             Log::info("Last Name: " . $student->last_name);
//             Log::info("Middle Name: " . ($student->middle_name ?? 'N/A'));

//             // Handle missing first or last name gracefully
//             $firstName = $student->first_name ?? 'N/A';  // Use 'N/A' or other placeholder if missing
//             $lastName = $student->last_name ?? 'N/A';    // Same for last name
//             $middleName = $student->middle_name ?? '';   // Empty string if middle name is missing

//             // Construct the full name properly
//             $fullName = trim("{$lastName}, {$firstName} {$middleName}");
//             Log::info("Full Name: " . $fullName); // Log the constructed full name

//             // Update or create the graduation record
//             Graduate::updateOrCreate(
//                 ['student_id' => $student->student_id],
//                 [
//                     'name' => $fullName, // Ensure valid name is set
//                     'department_id' => $student->department_id,
//                     'graduation_year' => $currentSchoolYear, // Set graduation year
//                 ]
//             );

//             // Update student as graduated
//             $student->update(['graduated' => 1]);

//             Log::info("Student Graduated: {$student->student_id}");
//         }
//     }

//     return redirect()->back()->with('success', 'Graduation process completed.');
// }








// public function checkGraduationEligibility(Request $request)
// {
//     $currentSchoolYear = Setting::getSchoolYear();

//     // Fetch all 4th-year enrolled students
//     $students = Student::where('year_level', 4)
//         ->where('enrolled', 1)
//         ->get();

//     if ($students->isEmpty()) {
//         return redirect()->back()->with('success', 'No students found for graduation check.');
//     }

//     $graduatedStudents = [];
//     $notGraduatedStudents = [];

//     foreach ($students as $student) {
//         // Fetch all subjects for the student's department
//         $requiredSubjects = Subject::where('department_id', $student->department_id)
//             ->pluck('id')
//             ->toArray();

//         // Get all subjects the student has passed
//         $passedSubjects = Grade::where('student_id', $student->student_id)
//             ->where('grade', '>=', 75)
//             ->pluck('subject_id')
//             ->toArray();

//         // Check if the student has passed ALL required subjects
//         if (!array_diff($requiredSubjects, $passedSubjects)) {
//             Graduate::updateOrCreate(
//                 ['student_id' => $student->student_id], // Prevent duplicates
//                 [
//                     'name' => trim("{$student->last_name}, {$student->first_name} " . ($student->middle_name ?? '')),
//                     'department_id' => $student->department_id,
//                     'graduation_year' => $currentSchoolYear,
//                 ]
//             );

//             // Mark student as graduated in the students table
//             $student->update(['graduated' => 1]);

//             $graduatedStudents[] = $student->student_id;
//         } else {
//             $notGraduatedStudents[] = $student->student_id;
//         }
//     }

//     Log::info('Graduation Check Results:', [
//         'graduated_students' => $graduatedStudents,
//         'not_graduated_students' => $notGraduatedStudents
//     ]);

//     return redirect()->back()->with('success', 'Graduation check completed. Check the logs for results.');
// }










}
