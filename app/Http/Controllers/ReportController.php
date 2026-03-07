<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Fee;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
class ReportController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index() {
        $schoolId = $this->schoolId();
        $totalStudents  = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $activeStudents = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->where('status', 'active')->count();
        $totalTeachers  = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $activeTeachers = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->where('status', 'active')->count();
        $totalFees     = Fee::where('school_id', $schoolId)->sum('amount');
        $collectedFees = Fee::where('school_id', $schoolId)->where('status', 'paid')->sum('amount');
        $pendingFees   = Fee::where('school_id', $schoolId)->whereIn('status', ['unpaid', 'partial'])->sum('amount');
        $thisMonthAttendance = Attendance::where('school_id', $schoolId)->whereYear('date', now()->year)->whereMonth('date', now()->month)->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();
        $presentCount = $thisMonthAttendance['present'] ?? 0; $absentCount = $thisMonthAttendance['absent'] ?? 0; $lateCount = $thisMonthAttendance['late'] ?? 0;
        $totalAttendance = array_sum($thisMonthAttendance);
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0;
        $classStats = ClassRoom::where('school_id', $schoolId)->withCount('students')->orderByDesc('students_count')->get();
        $feeCollection = [];
        for ($i = 5; $i >= 0; $i--) { $date = now()->subMonths($i); $feeCollection[] = ['month' => $date->format('M'), 'amount' => Fee::where('school_id', $schoolId)->where('status', 'paid')->whereYear('paid_at', $date->year)->whereMonth('paid_at', $date->month)->sum('amount')]; }
        return view('HTML.reports.index', compact('totalStudents', 'activeStudents', 'totalTeachers', 'activeTeachers', 'totalFees', 'collectedFees', 'pendingFees', 'attendanceRate', 'presentCount', 'absentCount', 'lateCount', 'classStats', 'feeCollection'));
    }
}
