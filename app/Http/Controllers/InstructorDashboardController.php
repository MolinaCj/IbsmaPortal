<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\SchoolCalendar;
use App\Models\SpecialSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class InstructorDashboardController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in teacher
        $teacher = Auth::guard('teacher')->user();

        // Fetch the latest school calendar uploaded
        $calendar = SchoolCalendar::latest()->first();

        // Fetch all announcements (latest first)
        $announcements = Announcement::latest()->get();

        // Fetch the quote of the day from ZenQuotes API
        $response = Http::get('https://zenquotes.io/api/today');

        // Check if the request was successful
        if ($response->successful()) {
            // Decode the JSON response to get the quote
            $quoteData = $response->json();
            $quote = $quoteData[0]['q'] ?? 'No quote available today.'; // Default message if no quote available
        } else {
            $quote = 'Could not retrieve the quote of the day.';
        }

        $currentSemester = Setting::latest('id')->value('current_semester') ?? 1;
        $currentYear = Setting::latest('id')->value('current_school_year') ?? date('Y');

        $subjects = Subject::with([
            'students.grades' => function ($query) use ($currentSemester, $currentYear) {
                $query->where('semester', $currentSemester)
                      ->where('school_year', $currentYear);
            },
            'students' => function ($query) use ($currentSemester, $currentYear) {
                $query->where('graduated', '!=', '1')
                      ->where('enrolled', 1)
                      ->whereHas('grades', function ($gradeQuery) use ($currentSemester, $currentYear) {
                          $gradeQuery->where('semester', $currentSemester)
                                     ->where('school_year', $currentYear);
                      });
            },
            'grades' => function ($query) use ($currentSemester, $currentYear) {
                $query->where('semester', $currentSemester)
                      ->where('school_year', $currentYear);
            }
        ])
        ->where('teacher_id', $teacher->id)
        ->where('semester', $currentSemester) // 🟢 This line filters subjects by semester
        ->get();


        // Pass all data to the view
        return view('instructor.instructorDashboard', compact('teacher', 'calendar', 'announcements', 'quote','subjects'));
    }


    // FOR INSTRUCTOR PROFILE VIEW
    public function profile()
    {
        $instructor = Auth::guard('teacher')->user();

        if (!$instructor){
            return redirect()->route('instructor_login.homepage')->with('error', 'Please log in first');
        }
        return view('instructor/profile', compact('instructor')); // Ensure this matches your Blade file
    }

    // FOR UPLOADING INSTRUCTOR PROFILE PICTURE
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        // Ensure we're getting an instance of the Student model
        $instructor = Teacher::where('id', Auth::guard('teacher')->id())->first();

        if (!$instructor) {
            return redirect()->back()->with('error', 'Instructor not found.');
        }

        if (!$request->hasFile('profile_picture')) {
            return redirect()->back()->with('error', 'No file was uploaded.');
        }

        // Store the uploaded file in 'storage/app/public/profile_pictures'
        $path = $request->file('profile_picture')->store('profile_picture', 'public');

        if (!$path) {
            return redirect()->back()->with('error', 'File upload failed.');
        }

        // Update the student's profile_picture field
        $instructor->profile_picture = $path;
        $instructor->save(); // This should now work

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    //upload schedule section
    // public function sched()
    // {
    //     $teacher = Auth::guard('teacher')->user();

    //     // Fetch schedules where:
    //     // - The instructor is assigned (`instructor_id == $teacher->id`)
    //     // - OR it's open to all (`instructor_id IS NULL`)
    //     // - AND the class belongs to the same department
    //     $scheduleImages = ClassModel::where('department_id', $teacher->department_id)
    //         ->where(function ($query) use ($teacher) {
    //             $query->whereNull('instructor_id')
    //                 ->orWhere('instructor_id', $teacher->id);
    //         })
    //         ->get();

    //     return view('instructor.instructorSched', compact('scheduleImages'));
    // }
    public function showSchedule()
    {
        $teacher = Auth::guard('teacher')->user();

         // Get current semester (and optionally year)
        $currentSemester = Setting::latest('id')->value('current_semester') ?? 1;

        // Fetch the regular subjects for the logged-in teacher
        $subjects = $teacher->subjects()->where('semester', $currentSemester)->get();

        // Fetch the special subjects for the logged-in teacher
        $specialSubjects = SpecialSubject::where('teacher_id', $teacher->id)->where('semester', $currentSemester)->get();

        // Add flags
        $specialSubjects->each(function ($subject) {
            $subject->is_special = true;

            // Decode schedule if it's still a string
            if (is_string($subject->schedule)) {
                $subject->schedule = json_decode($subject->schedule, true);
            }
        });


        $subjects->each(function ($subject) {
            $subject->is_special = false;
        });

        // Merge both into one collection
        $allSubjects = $subjects->merge($specialSubjects);

        // Initialize empty schedule grouped by day
        $weekSchedule = collect([
            'Monday' => collect(),
            'Tuesday' => collect(),
            'Wednesday' => collect(),
            'Thursday' => collect(),
            'Friday' => collect(),
            'Saturday' => collect(),
            'Sunday' => collect(),
        ]);

        // Populate the week schedule
        foreach ($allSubjects as $subject) {
            if (!is_array($subject->schedule)) continue;

            foreach ($subject->schedule as $sched) {
                if (!isset($sched['day'])) continue;

                $day = $sched['day'];
                $filtered = collect($subject->schedule)->filter(fn($s) => $s['day'] === $day)->values();
                $subjectClone = clone $subject;
                $subjectClone->filtered_schedule = $filtered;

                // ✅ Use push to keep it a collection
                $weekSchedule[$day] = $weekSchedule[$day]->push($subjectClone);
            }
        }


        return view('instructor.instructorSched', compact('weekSchedule', 'teacher'));
    }










    public function studGrade(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $currentSemester = Setting::latest('id')->value('current_semester') ?? 1;
        $currentYear = Setting::latest('id')->value('current_school_year') ?? date('Y');
        $gradingLocked = Setting::first()->grading_locked ?? false;
        $isSpecialClass = $request->query('special', 0);

        // Fetch subjects along with students and grades in one optimized query
        $subjects = Subject::with([
            'students' => function ($query) use ($currentSemester, $currentYear, $isSpecialClass) {
                $query->where('graduated', '!=', '1')
                    ->where('enrolled', '=', '1') // Exclude students who are not enrolled
                    ->whereHas('grades', function ($gradeQuery) use ($currentSemester, $currentYear, $isSpecialClass) {
                        $gradeQuery->where('semester', $currentSemester)
                            ->where('school_year', $currentYear)
                            ->where('special', $isSpecialClass)
                            ->when($isSpecialClass, fn($q) => $q->whereNotNull('teacher_id'));
                    });
            },

            'grades' => function ($query) use ($currentSemester, $currentYear, $isSpecialClass) {
                $query->where('semester', $currentSemester)
                    ->where('school_year', $currentYear)
                    ->where('special', $isSpecialClass)
                    ->when($isSpecialClass, fn($q) => $q->whereNotNull('teacher_id'));
            }
        ])
        ->whereHas('grades', function ($query) use ($currentSemester, $currentYear, $isSpecialClass) {
            $query->where('semester', $currentSemester)
                ->where('school_year', $currentYear)
                ->where('special', $isSpecialClass)
                ->when($isSpecialClass, fn($q) => $q->whereNotNull('teacher_id'));
        })
        ->when(!$isSpecialClass, function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id); // Apply teacher filter only for regular subjects
        })
        ->get();

        // Fetch only the most recent grade per student-subject at the DB level
        $editableGrades = Grade::join(
            DB::raw('(SELECT MAX(id) as max_id FROM grades GROUP BY student_id, subject_id) latest_grades'),
            'grades.id', '=', 'latest_grades.max_id'
        )
        ->whereIn('subject_id', $subjects->pluck('id'))
        ->where('semester', $currentSemester)
        ->where('school_year', $currentYear)
        ->where('special', $isSpecialClass)
        ->when($isSpecialClass, fn($q) => $q->whereNotNull('teacher_id')) //Exclude NULL teacher_id for special classes
        ->whereHas('student', function ($query) {
            $query->where('enrolled', 1); // Ensure only enrolled students are included
        })
        ->get();

        return view('instructor.studGrade', compact('subjects', 'editableGrades', 'isSpecialClass', 'gradingLocked'));
    }




    public function updateGrade(Request $request)
    {
        $currentYear = Setting::orderBy('id', 'desc')->value('current_school_year') ?? date('Y');

        // Check if bulk update or single update
        if ($request->has('grades')) {
            // Bulk update mode
            $gradesData = $request->input('grades');

            foreach ($gradesData as $studentId => $subjects) {
                foreach ($subjects as $subjectId => $gradeValue) {
                    $student = Student::where('student_id', $studentId)->first();
                    $subject = Subject::find($subjectId);

                    if (!$student || !$subject) {
                        continue; // Skip invalid records
                    }

                    $departmentId = $subject->department_id;

                    // Find all grades for this student and subject, regardless of school year or semester
                    $grades = Grade::where([
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        // 'school_year' => $currentYear,
                    ])->get();

                    // If no grades found, create a new grade
                    if ($grades->isEmpty()) {
                        Grade::create([
                            'student_id' => $studentId,
                            'subject_id' => $subjectId,
                            'year_level' => $student->year_level,
                            'semester' => $subject->semester,
                            'school_year' => $currentYear,
                            'department_id' => $departmentId,
                            'grade' => $gradeValue,
                            'incomplete' => ($gradeValue >= 75) ? 0 : 1, // Auto-set incomplete
                        ]);
                    } else {
                        // If grades found, update all grades matching this student and subject
                        foreach ($grades as $grade) {
                            $grade->update([
                                'grade' => $gradeValue,
                                'incomplete' => ($gradeValue >= 75) ? 0 : $grade->incomplete, // Update incomplete status
                            ]);
                        }
                    }
                }
            }

            return response()->json(['success' => true]);
        } else {
            // Single grade update mode
            $request->validate([
                'student_id' => 'required',
                'subject_id' => 'required',
                'grade' => 'nullable|numeric|min:0|max:100',
            ]);

            $student = Student::where('student_id', $request->student_id)->first();
            $subject = Subject::find($request->subject_id);

            if (!$student || !$subject) {
                return redirect()->back()->with('error', 'Student or Subject not found.');
            }

            $departmentId = $subject->department_id;

            // Find all grades for this student and subject
            $grades = Grade::where([
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
            ])->get();

            // If no grades found, create a new grade
            if ($grades->isEmpty()) {
                Grade::create([
                    'student_id' => $request->student_id,
                    'subject_id' => $request->subject_id,
                    'year_level' => $student->year_level,
                    'semester' => $subject->semester,
                    'school_year' => $currentYear,
                    'department_id' => $departmentId,
                    'grade' => $request->grade,
                    'incomplete' => ($request->grade >= 75) ? 0 : 1, //  Auto-set incomplete
                ]);
            } else {
                // If grades found, update all grades matching this student and subject
                foreach ($grades as $grade) {
                    $grade->update([
                        'grade' => $request->grade,
                        'incomplete' => ($request->grade >= 75) ? 0 : $grade->incomplete, // Update incomplete status
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Grade updated successfully.');
        }
    }


    public function markIncomplete(Request $request)
    {
        \Log::info('Received request to mark incomplete', [
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id
        ]);

        // Get the current school year with fallback
        $currentYear = DB::table('settings')->value('current_school_year') ?? date('Y');

        // Fetch grade for the student and subject in the current year
        $grade = Grade::where('student_id', $request->student_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('school_year', $currentYear)
                    ->first();

        if (!$grade) {
            \Log::error('Grade not found', [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'school_year' => $currentYear
            ]);
            return response()->json(['success' => false, 'message' => 'Grade record not found'], 404);
        }

        // Toggle `incomplete` status
        $grade->incomplete = !$grade->incomplete;

        //If marking as incomplete, also reset grade to 0
        if ($grade->incomplete) {
            $grade->grade = 0;
        }

        $grade->save();

        \Log::info('Updated incomplete status', [
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'new_status' => $grade->incomplete,
            'new_grade' => $grade->grade //Log the new grade value
        ]);

        return response()->json([
            'success' => true,
            'is_incomplete' => $grade->incomplete
        ]);
    }
    public function getIncomplete()
    {
        $currentYear = Setting::orderBy('id', 'desc')->value('current_school_year') ?? date('Y');
        $teacherId = Auth::guard('teacher')->id();


        // Fetch subjects assigned to the teacher
        $teacherSubjects = Subject::where('teacher_id', $teacherId)->pluck('id');

        // Fetch incomplete grades while excluding special students, filtered by teacher's subjects
        $incompleteGrades = Grade::where('incomplete', 1)
            ->where('school_year', $currentYear)
            ->where('special', '!=', 1) //  Exclude students in special classes
            ->whereIn('subject_id', $teacherSubjects) // Only subjects handled by the teacher
            ->with(['student', 'subject']) // Ensure relationships are eager loaded
            ->get();

        return response()->json($incompleteGrades);
    }




    public function updateIncompleteGrade(Request $request)
    {
        \Log::info('Update Request:', $request->all()); // Log input data

        $request->validate([
            'id' => 'required|exists:grades,id',
            'grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade = Grade::find($request->id);

        if (!$grade) {
            \Log::error('Grade not found for ID: ' . $request->id); // Log if grade is missing
            return response()->json(['success' => false, 'message' => 'Grade not found'], 404);
        }

        $grade->grade = $request->grade;

        // Remove incomplete status if grade is passing
        if ($request->grade >= 75) {
            $grade->incomplete = 0;
        }

        \Log::info('Saving Grade:', ['id' => $grade->id, 'newGrade' => $grade->grade]); //Log before saving

        $grade->save();

        return response()->json(['success' => true, 'message' => 'Grade updated successfully']);
    }

    public function schoolCalendar()
    {
        $schoolCalendars = SchoolCalendar::latest()->first();
        return view('instructor.schoolCalendar', compact('schoolCalendars'));
    }

    public function announcements()
    {
        $announcements = Announcement::latest()->take(10)->get(); // Fetch the latest 9 announcements
        return view('instructor/announcements', compact('announcements'));
    }
}
