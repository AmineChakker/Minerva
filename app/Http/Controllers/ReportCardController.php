<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function show(Student $student, Request $request)
    {
        $student->load(['user', 'classRoom', 'academicYear']);

        $academicYears = AcademicYear::where('school_id', $this->schoolId())
            ->orderByDesc('start_date')
            ->get();

        $selectedYear = $request->year
            ? AcademicYear::find($request->year)
            : AcademicYear::where('school_id', $this->schoolId())->where('is_current', true)->first()
              ?? $academicYears->first();

        $reportData = $selectedYear ? $this->buildReportData($student, $selectedYear) : null;

        return view('HTML.report-card.show', compact('student', 'academicYears', 'selectedYear', 'reportData'));
    }

    public function download(Student $student, AcademicYear $academicYear)
    {
        $student->load(['user', 'classRoom', 'academicYear']);
        $school   = Auth::user()->school;
        $reportData = $this->buildReportData($student, $academicYear);

        $pdf = Pdf::loadView('HTML.report-card.pdf', compact('student', 'academicYear', 'school', 'reportData'))
            ->setPaper('a4', 'portrait');

        $filename = str_replace(' ', '_', $student->user->full_name) . '_' . str_replace('/', '-', $academicYear->name) . '_Report_Card.pdf';

        return $pdf->download($filename);
    }

    private function buildReportData(Student $student, AcademicYear $academicYear): array
    {
        // All exams for this student's class in this academic year
        $exams = Exam::with([
            'subject',
            'results' => fn($q) => $q->where('student_id', $student->id),
        ])
            ->where('school_id', $this->schoolId())
            ->where('class_room_id', $student->class_id)
            ->where('academic_year_id', $academicYear->id)
            ->orderBy('exam_date')
            ->get();

        // Attendance in the academic year date range
        $attendanceQuery = Attendance::where('student_id', $student->id)
            ->where('school_id', $this->schoolId());

        if ($academicYear->start_date && $academicYear->end_date) {
            $attendanceQuery->whereBetween('date', [$academicYear->start_date, $academicYear->end_date]);
        }

        $attendance = $attendanceQuery->get();

        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount  = $attendance->where('status', 'absent')->count();
        $lateCount    = $attendance->where('status', 'late')->count();
        $excusedCount = $attendance->where('status', 'excused')->count();
        $totalDays    = $attendance->count();
        $attendancePct = $totalDays > 0 ? round(($presentCount / $totalDays) * 100, 1) : 0;

        // Group results by subject for the table
        $subjectRows = [];
        foreach ($exams as $exam) {
            $result = $exam->results->first();
            $subjectName = $exam->subject->name ?? 'N/A';
            if (!isset($subjectRows[$subjectName])) {
                $subjectRows[$subjectName] = [];
            }
            $subjectRows[$subjectName][] = [
                'exam'           => $exam->name,
                'date'           => $exam->exam_date->format('d M Y'),
                'total_marks'    => $exam->total_marks,
                'marks_obtained' => $result?->marks_obtained,
                'grade'          => $result?->grade,
                'remarks'        => $result?->remarks,
                'pct'            => $exam->total_marks > 0 && $result
                    ? round(($result->marks_obtained / $exam->total_marks) * 100, 1)
                    : null,
            ];
        }

        // Overall performance
        $totalMarks    = $exams->sum('total_marks');
        $obtainedMarks = $exams->sum(fn($e) => $e->results->first()?->marks_obtained ?? 0);
        $overallPct    = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 1) : null;
        $overallGrade  = $overallPct !== null ? $this->calcGrade($overallPct) : 'N/A';

        return compact(
            'exams', 'subjectRows',
            'presentCount', 'absentCount', 'lateCount', 'excusedCount',
            'totalDays', 'attendancePct',
            'totalMarks', 'obtainedMarks', 'overallPct', 'overallGrade'
        );
    }

    private function calcGrade(float $pct): string
    {
        return match(true) {
            $pct >= 95 => 'A+',
            $pct >= 90 => 'A',
            $pct >= 85 => 'B+',
            $pct >= 80 => 'B',
            $pct >= 75 => 'C+',
            $pct >= 70 => 'C',
            $pct >= 60 => 'D',
            default    => 'F',
        };
    }
}
