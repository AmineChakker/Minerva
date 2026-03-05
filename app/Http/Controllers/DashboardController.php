<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        $totalStudents = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $totalTeachers = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();
        $totalClasses  = ClassRoom::where('school_id', $schoolId)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->count();
        $totalParents  = ParentProfile::whereHas('user', fn($q) => $q->where('school_id', $schoolId))->count();

        $stats = [
            'students' => $totalStudents,
            'teachers' => $totalTeachers,
            'classes'  => $totalClasses,
            'subjects' => $totalSubjects,
            'parents'  => $totalParents,
        ];

        // Chart data: students per class
        $classEnrollment = ClassRoom::where('school_id', $schoolId)
            ->withCount('students')
            ->get()
            ->map(fn($c) => ['name' => $c->full_name, 'count' => $c->students_count]);

        // Chart data: gender distribution of students
        $maleCount   = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId)->where('gender', 'male'))->count();
        $femaleCount = Student::whereHas('user', fn($q) => $q->where('school_id', $schoolId)->where('gender', 'female'))->count();
        $otherCount  = $totalStudents - $maleCount - $femaleCount;

        // Chart data: monthly enrollment (last 6 months)
        $monthlyEnrollment = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyEnrollment[] = [
                'month' => $date->format('M'),
                'count' => Student::whereHas('user', fn($q) => $q
                    ->where('school_id', $schoolId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                )->count(),
            ];
        }

        $recentStudents = Student::with('user', 'classRoom')
            ->whereHas('user', fn($q) => $q->where('school_id', $schoolId))
            ->latest()->limit(5)->get();

        return view('HTML.dashboard', compact(
            'user', 'stats', 'classEnrollment',
            'maleCount', 'femaleCount', 'otherCount',
            'monthlyEnrollment', 'recentStudents'
        ));
    }
}
