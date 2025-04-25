<?php

use App\Models\Setting;
//Admin Dashboard Controller Import
use App\Exports\StudentsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
//Instructor Dashboard Controller Import
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AdminLoginController;
//Student Dashboard Controller Import
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InstructorAuthController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\InstructorLoginController;
// Address Controller Import
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\InstructorDashboardController;


Route::get('/', function () {
    return view('/student_login/studentLogin');
});
// instructor_login/instructorLogin
///login_admin/login


// admin routes

// Dashboard route
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::resource('students', StudentController::class);  // Register the resource route
Route::resource('teachers', TeacherController::class);
Route::resource('subjects', SubjectController::class);
// Route::resource('subjects', SubjectController::class);

Route::resource('classes', ClassController::class);
Route::get('/admin/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
Route::post('/admin/update-semester', [AdminDashboardController::class, 'updateSemester'])->name('admin.updateSemester');
Route::post('/admin/increment-year', [AdminDashboardController::class, 'incrementYearLevel'])->name('admin.incrementYear');

Route::put('/admin/students/{student}/grades', [StudentController::class, 'updateGrades'])->name('admin.update.student.grades');
//enrollment toggle
Route::patch('/students/{student}/toggle-enrollment', [StudentController::class, 'toggleEnrollment'])
    ->name('students.toggleEnrollment');
//school year setup
Route::post('/admin/settings/update-school-year', [AdminDashboardController::class, 'updateSchoolYear'])
    ->name('admin.settings.updateSchoolYear');

    //bulk enrollment
Route::post('/admin/students/enroll-bulk', [StudentController::class, 'bulkEnroll'])->name('admin.students.enroll.bulk');

//incomplete modal to SPECIAL SUBJECTS
Route::get('/admin/students/incomplete', [StudentController::class, 'getIncompleteStudents'])->name('admin.students.incomplete');
Route::post('/admin/students/mark-special', [StudentController::class, 'markSpecial'])->name('admin.students.markSpecial');
Route::post('/admin/students/assign-special-class', [StudentController::class, 'assignSpecialClass'])
    ->name('admin.students.assignSpecialClass');

//SPECIAL SUBJECTS schedule
Route::get('admin/subject', [SubjectController::class, 'index'])->name('admin.subject');
Route::post('/admin/update-special-subject/{id}', [SubjectController::class, 'updateSpecial'])->name('admin.update-special-subject');
Route::post('/special-subjects/{id}/update-schedule', [SubjectController::class, 'updateSchedule']);

//re-add requisite subjects
Route::post('/admin/students/{studentId}/recheck-subjects', [StudentController::class, 'recheckStudentSubjects']);
//promote single student
Route::post('/promote-student/{studentId}', [StudentController::class, 'incrementYearLevelForStudent'])->name('promote.student');
//shiting
Route::post('/admin/students/{student_id}/shift-department/{new_department_id}', [StudentController::class, 'shiftDepartment']);

// Show the modal with eligible students for graduation (GET request)
Route::get('admin/settings/graduation', [AdminDashboardController::class, 'settings'])->name('admin.settings.graduation');
// Process the selected students for graduation (POST request)
Route::post('admin/settings/graduation/check', [AdminDashboardController::class, 'processGraduation'])->name('admin.graduation.check');
// Check graduation eligibility (GET request)
Route::get('admin/settings/graduation/check', [AdminDashboardController::class, 'checkGraduationEligibility'])->name('admin.graduation.check');
// Optional route for showing the modal via a different method (if needed)
Route::get('admin/settings/graduation/show-modal', [AdminDashboardController::class, 'showGraduationModal'])->name('admin.graduation.showModal');
// Route to process the graduation check and update students' graduation status
Route::post('admin/graduation/update', [AdminDashboardController::class, 'updateGraduationStatus'])->name('admin.graduation.update');


//Route for Announcements
Route::resource('/admin/announcements', AnnouncementsController::class)->names([
    'index' => 'admin.announcements', // Custom name for index route
    // 'create' => 'admin.schoolCalendar.create',
    // 'store' => 'admin.schoolCalendar.store',
    // 'show' => 'admin.schoolCalendar.show',
    // 'edit' => 'admin.schoolCalendar.edit',
    // 'update' => 'admin.schoolCalendar.update',  // Fix: Add update route name
    // 'destroy' => 'admin.schoolCalendar.destroy',
]);
//Route::get('/admin/announcements', [AnnouncementsController::class, 'index'])->name('admin.announcements');
//Route::post('/announcements/store', [AnnouncementsController::class, 'store'])->name('announcements.store');

// Route for School Calendar
Route::resource('/admin/schoolCalendar', SchoolCalendarController::class)->names([
    'index' => 'admin.schoolCalendar', //Custom name for index route
    'create' => 'admin.schoolCalendar.create',
    // 'store' => 'admin.schoolCalendar.store',
    // 'show' => 'admin.schoolCalendar.show',
    // 'edit' => 'admin.schoolCalendar.edit',
    'update' => 'admin.schoolCalendar.update', //custom name for update route
    // 'destroy' => 'admin.schoolCalendar.destroy',
]);
// Route::get('/admin/schoolCalendar', [SchoolCalendarController::class, 'index'])->name('admin.schoolCalendar');

Route::get('/admin/logs', [LogsController::class, 'logs'])->name('admin.logs');
// for checking duplicate name
Route::post('/students/check-duplicate-name', [StudentController::class, 'checkDuplicateName'])->name('students.checkDuplicateName');


// Show login form
Route::get('/login_admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login_admin.login');
// Register admin
Route::get('/admin/register', [AdminLoginController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminLoginController::class, 'adminRegister'])->name('admin.register.submit');
Route::post('/admin/password/reset', [AdminLoginController::class, 'resetPassword'])->name('admin.password.reset');
// Route for resetting the username
Route::post('/admin/reset-username', [AdminLoginController::class, 'resetUsername'])->name('admin.username.reset');
Route::get('/admin/emergency-key', function () {
    if (!session()->has('emergency_key')) {
        return redirect()->route('admin.login'); // Fallback
    }

    return view('admin.emergency-key', [
        'emergencyKey' => session('emergency_key'),
    ]);
})->name('admin.emergency.key');

// Handle login request
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
// Protect Admin Routes
// Route::get('/admin/dashboard', function () {
//     if (!session()->has('admin_logged_in')) {
//         return redirect()->route('admin.login')->with('error', 'You must log in first.');
//     }
//     return view('admin.dashboard');
// })->name('admin.dashboard');
// Logout Route
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

//teacher.Active status
Route::patch('/teachers/{id}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggleStatus');

//locking grade
// Route::post('/admin/toggle-grading-lock', [AdminDashboardController::class, 'toggleGradingLock'])->name('admin.toggleGradingLock');

Route::post('/toggle-grading-lock', [AdminDashboardController::class, 'toggleGradingLock'])->name('toggle.grading.lock');
Route::get('/check-grading-lock', [AdminDashboardController::class, 'checkGradingLock'])->name('check.grading.lock');



// Route for updating admin profile
Route::post('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
// end of admin routes

//==========================================================================================================================================//
// ADDRESS API CONTROLLER
Route::get('/api/regions', [AddressController::class, 'getRegions']);
Route::get('/api/provinces/{regionCode}', [AddressController::class, 'getProvinces']);
Route::get('/api/region-cities-municipalities/{regionCode}', [AddressController::class, 'getRegionCitiesMunicipalities']);
Route::get('/api/cities-municipalities/{provinceCode}', [AddressController::class, 'getCitiesMunicipalities']);
Route::get('/api/barangays/{cityOrMunicipalityCode}', [AddressController::class, 'getBarangays']);
//==========================================================================================================================================//

//ROUTE FOR INSTRUCTOR DASHBOARD
// Instructor login routes
Route::get('/instructor_login/instructorLogin', [InstructorLoginController::class, 'showHomepage'])->name('instructor_login.homepage');
Route::post('/instructor/login', [InstructorLoginController::class, 'instructorLogin'])->name('instructor.login.submit');
Route::post('/instructor/logout', [InstructorLoginController::class, 'logout'])->name('instructor.logout');
Route::post('/instructor/send-otp', [InstructorLoginController::class, 'sendOtp'])->name('instructor.sendOtp');
Route::post('/otp-verify', [InstructorLoginController::class, 'verifyOtp'])->name('instructor.verify-otp');
Route::post('/instructorResend-otp', [InstructorLoginController::class, 'instructorResendOtp'])->name('instructor.resend-otp');
Route::post('/instructor/upload-profile-picture', [InstructorDashboardController::class, 'uploadProfilePicture'])->name('instructor.uploadPp');
// Instructor dashboard route with middleware protection
Route::middleware(['teacher.auth'])->group(function () {
    Route::get('/instructor/instructorDashboard', [InstructorDashboardController::class, 'dashboard'])->name('instructor.instructorDashboard');
    Route::get('/instructor/profile', [InstructorDashboardController::class, 'profile'])->name('instructor.profile');
    Route::get('/instructor/instructorSched', [InstructorDashboardController::class, 'sched'])->name('instructor.instructorSched');
    // Route::get('/instructor/instructorSched', [InstructorDashboardController::class, 'showSchedule'])->name('instructor.instructorSched');
    Route::get('/instructor/instructorSched', [InstructorDashboardController::class, 'showSchedule'])->name('instructor.instructorSched');

    Route::get('/instructor/studGrade', [InstructorDashboardController::class, 'studgrade'])->name('instructor.studGrade');
    Route::post('instructor/studGrade/update', [InstructorDashboardController::class, 'updateGrade'])->name('instructor.updateGrade');
    Route::post('/mark-incomplete', [InstructorDashboardController::class, 'markIncomplete'])->name('mark.incomplete');//incomplete
    Route::get('/instructor/incomplete-students', [InstructorDashboardController::class, 'getIncomplete'])->name('instructor.getIncomplete');
    Route::post('/instructor/update-incomplete-grade', [InstructorDashboardController::class, 'updateIncompleteGrade'])->name('instructor.updateIncompleteGrade');
    Route::get('/instructor/schoolCalendar', [InstructorDashboardController::class, 'schoolCalendar'])->name('instructor.schoolCalendar');
    Route::get('/instructor/announcements', [InstructorDashboardController::class, 'announcements'])->name('instructor.announcements');
//special class
    // Route::get('/instructor/studGrade', [StudentController::class, 'studGrade'])->name('instructor.studGrade');

});

Route::get('/test-auth', function () {
    return Auth::guard('teacher')->user() ?? 'Not authenticated';
});

//==========================================================================================================================================//

//ROUTE FOR STUDENT DASHBOARD
// Instructor login routes
Route::get('/student_login/studentLogin', [StudentLoginController::class, 'showHomepage'])->name('student_login.homepage');
Route::post('/student/login', [StudentLoginController::class, 'studentLogin'])->name('student.login.submit');
Route::post('/student/logout', [StudentLoginController::class, 'logout'])->name('student.logout');
Route::post('/student/send-otp', [StudentLoginController::class, 'sendOtp'])->name('student.sendOtp');
Route::post('/student-otp-verify', [StudentLoginController::class, 'verifyOtp'])->name('student.student-verify-otp');
Route::post('/resend-otp', [StudentLoginController::class, 'resendOtp'])->name('student.resend-otp');
Route::get('/check-student-id', [StudentController::class, 'checkStudentId'])->name('students.checkStudentId');
Route::get('/check-email', [StudentController::class, 'checkEmail'])->name('students.checkEmail');
// Instructor dashboard route with middleware protection
Route::middleware(['student.auth'])->group(function () {
    Route::get('/student/studentDashboard', [StudentDashboardController::class, 'dashboard'])->name('student.studentDashboard');
    Route::get('/student/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::get('/student/grades', [StudentDashboardController::class, 'grades'])->name('student.grades');
    Route::get('/student/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
    Route::get('/student/schoolCalendar', [StudentDashboardController::class, 'schoolCalendar'])->name('student.schoolCalendar');
    Route::get('/student/announcements', [StudentDashboardController::class, 'announcements'])->name('student.announcements');
    Route::post('/student/upload-profile-picture', [StudentDashboardController::class, 'uploadProfilePicture'])->name('student.uploadPp');
});



// ===============================================
//Experimental Section
//Export to EXCEL
Route::get('/export/students/{year?}', [ExportController::class, 'exportStudents'])
    ->name('export.students');

Route::get('/export/students', [ExportController::class, 'exportStudents'])->name('export.students');


Route::get('/export/full-data', [ExportController::class, 'exportFullStudentData'])
    ->name('export.full-data');
