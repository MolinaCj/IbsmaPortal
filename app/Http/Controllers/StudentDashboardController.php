<?php

namespace App\Http\Controllers;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\Student;

use App\Models\Subject;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\SchoolCalendar;
use App\Models\SpecialSubject;
use App\Services\AddressService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    public function dashboard()
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student_login.homepage')->with('error', 'Please log in first.');
        }

       // Check if the student is enrolled
        $isEnrolled = $student->enrolled == 1;

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

        // FOR GRADES CURRENT AND PREVIOUS
        $currentSemester = Setting::getSemester(); // Get the current semester
        $yearLevel = $student->year_level;

        // Check if the student has taken a summer class (semester 3)
        $hasSummerClass = Grade::where('student_id', $student->student_id)
            ->where('semester', '3')
            ->exists();

        // Determine the previous semester
        if ($yearLevel == 1 && $currentSemester == '1') {
            // No previous semester for first-year, first-semester students
            $previousSemester = null;
        } elseif ($currentSemester === '3') {
            // If the current semester is 3 (Summer), the previous should be 2
            $previousSemester = '2';
        } elseif ($currentSemester === '2') {
            // If the current semester is 2, the previous should be 1
            $previousSemester = '1';
        } else {
            // If the student has taken a summer class and is at year 2 or higher, previous should be 3
            // Otherwise, previous should be 2
            $previousSemester = ($hasSummerClass && $yearLevel >= 2) ? '3' : '2';
        }

        // Fetch grades for the current semester and year level
        $grades = Grade::where('student_id', $student->student_id)
            ->where('semester', $currentSemester)
            ->where('year_level', $yearLevel)
            ->with(['subject:id,code,name,units'])
            ->get();

        // Fetch grades for the previous semester only if applicable
        $previousGrades = ($previousSemester) ? Grade::where('student_id', $student->student_id)
            ->where('semester', $previousSemester)
            ->where('year_level', ($currentSemester == '1') ? $yearLevel - 1 : $yearLevel)
            ->with(['subject:id,code,name,units'])
            ->get() : collect();

        // FOR SCHEDULE CURRENT SEMESTER AND YEAR
        $currentSchoolYear = Setting::getSchoolYear();

        // Fetch subjects for the current school year and year level (current year)
        $subjects = $student->subjects()
            ->whereHas('grades', function($query) use ($currentSchoolYear, $yearLevel) {
                $query->where('school_year', $currentSchoolYear) // Match the current school year
                      ->where('year_level', $yearLevel); // Filter by year level
            })
            ->with('teacher') // Eager load the teacher
            ->get()
            ->groupBy('semester'); // Group by semester

        // Fetch special subjects assigned to this student
        $specialSubjects = SpecialSubject::whereHas('subject.grades', function ($query) use ($student, $yearLevel) {
            $query->where('student_id', $student->student_id)
                  ->where('special', 1)
                  ->where('year_level', $yearLevel); // Filter by year level
        })
        ->with(['teacher', 'subject'])
        ->get()
        ->groupBy('semester');

        // Merge special subjects with regular subjects
        foreach ($specialSubjects as $semester => $specialClasses) {
            foreach ($specialClasses as $special) {
                // Override subject properties if it's a special subject
                $special->subject->special_subject = $special->subject->code;
                $special->subject->day = $special->day ?? $special->subject->day;
                $special->subject->time = $special->time ?? $special->subject->time;
                $special->subject->room = $special->room ?? $special->subject->room;
            }

            // Merge special subjects into regular subjects
            if (isset($subjects[$semester])) {
                $subjects[$semester] = $subjects[$semester]->merge($specialClasses->pluck('subject'));
            } else {
                $subjects[$semester] = $specialClasses->pluck('subject');
            }
        }

        // Get failed subjects (grade < 75) from the current school year
        $failedSubjects = Grade::where('student_id', $student->id) // Fetch grades by student
            ->where('grade', '<', 75) // Filter for failed subjects
            ->where('school_year', $currentSchoolYear) // Match the current school year
            ->where('year_level', $yearLevel) // Ensure year level matches
            ->get();

        // Retrieve the corresponding subjects for failed grades (retakes)
        $failedSubjects = $failedSubjects->map(function ($grade) {
            return $grade->subject; // Map to get the subject associated with the grade
        });

        // Merge the failed subjects (retakes) with the regular subjects
        $currentSemesterSubjects = isset($subjects[$currentSemester]) ? $subjects[$currentSemester] : collect([]);
        $currentSemesterSubjects = $currentSemesterSubjects->merge($failedSubjects); // Add failed subjects to the current semester

        // FOR SCHOOL CALENDAR
        $schoolCalendars = SchoolCalendar::latest()->first();
        // FOR BULLETIN BOARD
        $announcements = Announcement::latest()->take(10)->get();

        return view('student.studentDashboard', compact('student', 'grades', 'schoolCalendars', 'announcements', 'previousGrades', 'currentSemester', 'previousSemester', 'currentSemesterSubjects', 'quote','isEnrolled'));
    }


    ///////FUNCTIONS FOR STUDENT PROFILE///////////////////////
    public function profile(AddressService $addressService)
{
    $student = Auth::guard('student')->user();

    if (!$student) {
        return redirect()->route('student_login.homepage')->with('error', 'Please log in first');
    }

    $regionName = $this->getRegionName($student->region);
    $provinceName = $this->getProvinceName($student->province);
    $cityName = $this->getCityName($student->city);
    $barangayName = $this->getBarangayName($student->barangay);

    return view('student.profile', compact('student', 'regionName', 'provinceName', 'cityName', 'barangayName'));
}

    private function getRegionName($regionCode)
    {
        $response = Http::get("https://psgc.gitlab.io/api/regions/{$regionCode}.json");
        return $response->ok() ? $response->json()['name'] : 'N/A';
    }
    
    private function getProvinceName($provinceCode)
    {
        $response = Http::get("https://psgc.gitlab.io/api/provinces/{$provinceCode}.json");
        return $response->ok() ? $response->json()['name'] : 'N/A';
    }
    
    private function getCityName($cityCode)
    {
        $response = Http::get("https://psgc.gitlab.io/api/cities-municipalities/{$cityCode}.json");
        return $response->ok() ? $response->json()['name'] : 'N/A';
    }
    
    private function getBarangayName($barangayCode)
    {
        $response = Http::get("https://psgc.gitlab.io/api/barangays/{$barangayCode}.json");
        return $response->ok() ? $response->json()['name'] : 'N/A';
    }

    //Function for uploading profile picture
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        // Ensure we're getting an instance of the Student model
        $student = Student::where('student_id', Auth::guard('student')->id())->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        if (!$request->hasFile('profile_picture')) {
            return redirect()->back()->with('error', 'No file was uploaded.');
        }

        // Store the uploaded file in 'storage/app/public/profile_pictures'
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        if (!$path) {
            return redirect()->back()->with('error', 'File upload failed.');
        }

        // Update the student's profile_picture field
        $student->profile_picture = $path;
        $student->save(); // This should now work

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }


    ///////END OF FUNCTIONS FOR STUDENT PROFILE///////////////////////
    public function grades()
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student_login.homepage')->with('error', 'You must log in first.');
        }

        // FOR GRADES CURRENT AND PREVIOUS
        $currentSemester = Setting::getSemester(); // Get the current semester
        $yearLevel = $student->year_level;

        // Check if the student has taken a summer class (semester 3)
        $hasSummerClass = Grade::where('student_id', $student->student_id)
            ->where('semester', '3')
            ->exists();

            
        // Determine the previous semester
        if ($yearLevel == 1 && $currentSemester == '1') {
            // No previous semester for first-year, first-semester students
            $previousSemester = null;
            $previousYearLevel = null;
        } elseif ($currentSemester == '1') {
            // For semester 1, previous is semester 2 of previous year
            $previousSemester = '2';
            $previousYearLevel = $yearLevel - 1;
            
            // Check if student had summer classes in between
            $hasSummerClass = Grade::where('student_id', $student->student_id)
                ->where('semester', '3')
                ->where('year_level', $yearLevel - 1)
                ->exists();
                
            if ($hasSummerClass) {
                $previousSemester = '3';
            }
        } elseif ($currentSemester == '2') {
            // For semester 2, previous is semester 1 of same year
            $previousSemester = '1';
            $previousYearLevel = $yearLevel;
        } elseif ($currentSemester == '3') {
            // For summer, previous is semester 2 of same year
            $previousSemester = '2';
            $previousYearLevel = $yearLevel;
        }

        // Fetch grades for the current semester and year level
        $grades = Grade::where('student_id', $student->student_id)
            ->where('semester', $currentSemester)
            ->where('year_level', $yearLevel)
            ->with(['subject:id,code,name,units'])
            ->get();

        // Fetch grades for the previous semester only if applicable
        $previousGrades = ($previousSemester) ? Grade::where('student_id', $student->student_id)
            ->where('semester', $previousSemester)
            ->where('year_level', ($currentSemester == '1') ? $yearLevel - 1 : $yearLevel)
            ->with(['subject:id,code,name,units'])
            ->get() : collect();

        $gradingLocked = Setting::latest('id')->value('grading_locked') == 1;

        // FIRST VERSION
        // $currentSemester = Setting::getSemester(); // Get the current semester

        // // Fetch grades with subject details for the student's current semester
        // $grades = Grade::where('student_id', $student->student_id)
        //     ->where('semester', $currentSemester) // Only current semester
        //     ->with(['subject:id,code,name,units']) // Load only necessary subject details
        //     ->get();

        return view('student.grades', compact('student', 'grades', 'previousGrades', 'currentSemester', 'previousSemester','gradingLocked'));
    }



    public function schedule()
    {
        $student = Auth::guard('student')->user();
    
        if (!$student) {
            return redirect()->route('student_login.homepage')->with('error', 'You must log in first.');
        }
    
        // Get the current semester and school year
        $currentSemester = Setting::getSemester();
        $currentSchoolYear = Setting::getSchoolYear();
        $yearLevel = $student->year_level;
    
        // Fetch subjects for the current school year, year level, and semester
        $subjects = $student->subjects()
            ->whereHas('grades', function ($query) use ($currentSchoolYear, $yearLevel, $currentSemester) {
                $query->where('school_year', $currentSchoolYear)
                    ->where('year_level', $yearLevel)
                    ->where('semester', $currentSemester);
            })
            ->with('teacher')
            ->get();
    
        // Decode the schedule JSON for each subject if it's a string
        $subjects = $subjects->map(function ($subject) {
            if (is_string($subject->schedule)) {
                $subject->schedule = json_decode($subject->schedule, true); // Decode schedule into an array
            }
            return $subject;
        });
    
       // Fetch special subjects assigned to this student (filtered by year_level and semester)
$specialSubjects = SpecialSubject::whereHas('subject.grades', function ($query) use ($student, $yearLevel, $currentSemester) {
    $query->where('student_id', $student->student_id)
        ->where('special', 1)
        ->where('year_level', $yearLevel)
        ->where('semester', $currentSemester);
})
->with(['teacher', 'subject'])
->get();

// Decode the schedule for special subjects as well if it's a string
$specialSubjects = $specialSubjects->map(function ($specialSubject) {
    if (is_string($specialSubject->subject->schedule)) {
        $specialSubject->subject->schedule = json_decode($specialSubject->subject->schedule, true); // Decode schedule into an array
    }

    return $specialSubject;
});


    
        // Get failed subjects (grade < 75) for the current school year, year level, and semester
        $failedSubjects = Grade::where('student_id', $student->id)
            ->where('grade', '<', 75)
            ->where('school_year', $currentSchoolYear)
            ->where('year_level', $yearLevel)
            ->where('semester', $currentSemester)
            ->get()
            ->map(function ($grade) {
                return $grade->subject;
            });
    
        // Merge failed subjects with the current semester subjects
        $currentSemesterSubjects = $subjects->merge($failedSubjects);
        $currentSemesterSpecialSubjects = $specialSubjects;
    
        // Combine all subjects together
        $allSubjects = $currentSemesterSubjects->merge($currentSemesterSpecialSubjects);
    
        // Make sure the subjects are unique based on subject ID
        $uniqueSubjects = $allSubjects->unique('subject_name');
    
        // Pass only unique subjects to the view
        return view('student.schedule', compact('student', 'uniqueSubjects', 'currentSemester', 'currentSemesterSpecialSubjects'));
    }
    
    
    
    
    
    
    

    
    
    














    // FOR SCHOOL CALENDAR
    public function schoolCalendar()
    {
        $schoolCalendars = SchoolCalendar::latest()->first();
        return view('student.schoolCalendar', compact('schoolCalendars'));
    }
    public function announcements()
    {
        $announcements = Announcement::latest()->take(10)->get(); // Fetch the latest 9 announcements
        return view('student/announcements', compact('announcements'));
    }
}
