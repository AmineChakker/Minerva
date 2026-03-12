<?php
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolSettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('role:admin,teacher')->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('parents', ParentController::class);
        Route::resource('classes', ClassRoomController::class);
        Route::resource('subjects', SubjectController::class);
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::resource('schedules', ScheduleController::class)->except(['show']);
        Route::resource('exams', ExamController::class);
        Route::post('exams/{exam}/results', [ExamResultController::class, 'store'])->name('exam-results.store');
        Route::get('students/{student}/report-card', [ReportCardController::class, 'show'])->name('report-card.show');
        Route::get('students/{student}/report-card/{academicYear}/download', [ReportCardController::class, 'download'])->name('report-card.download');
    });
    Route::middleware('role:admin')->group(function () {
        Route::resource('admins', AdminController::class);
        Route::resource('academic-years', AcademicYearController::class);
        Route::post('academic-years/{academicYear}/set-current', [AcademicYearController::class, 'setCurrent'])->name('academic-years.set-current');
        Route::get('school-settings', [SchoolSettingsController::class, 'edit'])->name('school.settings');
        Route::put('school-settings', [SchoolSettingsController::class, 'update'])->name('school.settings.update');
        Route::resource('announcements', AnnouncementController::class);
        Route::resource('fees', FeeController::class);
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
