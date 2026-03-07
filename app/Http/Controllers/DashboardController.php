<?php
namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller {
    public function index() {
        $user = Auth::user(); $schoolId = $user->school_id;
        $totalStudents = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $totalTeachers = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $totalClasses  = ClassRoom::where('school_id', $schoolId)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->count();
        $totalParents  = ParentProfile::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $currentYear   = AcademicYear::where('school_id', $schoolId)->where('is_current', true)->first();
        $stats = ['students' => $totalStudents, 'teachers' => $totalTeachers, 'classes' => $totalClasses, 'subjects' => $totalSubjects, 'parents' => $totalParents];
        $classEnrollment = ClassRoom::where('school_id', $schoolId)->withCount('students')->get()->map(fn($c) => ['name' => $c->full_name, 'count' => $c->students_count]);
        $maleCount   = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId)->where('gender', 'male'))->count();
        $femaleCount = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId)->where('gender', 'female'))->count();
        $otherCount  = $totalStudents - $maleCount - $femaleCount;
        $monthlyEnrollment = [];
        for ($i = 5; $i >= 0; $i--) { $date = now()->subMonths($i); $monthlyEnrollment[] = ['month' => $date->format('M'), 'count' => Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId)->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month))->count()]; }
        $teacherStatusData = ['active' => Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->where('status', 'active')->count(), 'on_leave' => Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->where('status', 'on_leave')->count(), 'inactive' => Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->whereIn('status', ['resigned', 'terminated'])->count()];
        $recentAnnouncements = Announcement::where('school_id', $schoolId)->where('is_published', true)->latest()->limit(3)->get();
        $thisMonthAttendance = Attendance::where('school_id', $schoolId)->whereYear('date', now()->year)->whereMonth('date', now()->month)->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();
        $totalAttendance = array_sum($thisMonthAttendance); $presentCount = $thisMonthAttendance['present'] ?? 0;
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : null;
        $recentStudents = Student::with('user', 'classRoom')->whereHas('user', fn($q) => $q->where('school_id', $schoolId))->latest()->limit(5)->get();
        return view('HTML.dashboard', compact('user', 'stats', 'classEnrollment', 'maleCount', 'femaleCount', 'otherCount', 'monthlyEnrollment', 'recentStudents', 'teacherStatusData', 'recentAnnouncements', 'attendanceRate', 'currentYear'));
    }
}
