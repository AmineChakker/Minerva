<?php
namespace App\Http\Controllers;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
class ExamResultController extends Controller {
    public function store(Request $request, Exam $exam) {
        $request->validate(['results' => 'required|array', 'results.*.marks_obtained' => 'required|integer|min:0', 'results.*.grade' => 'nullable|string|max:5', 'results.*.remarks' => 'nullable|string|max:255']);
        foreach ($request->results as $studentId => $data) {
            ExamResult::updateOrCreate(['exam_id' => $exam->id, 'student_id' => $studentId], ['marks_obtained' => $data['marks_obtained'], 'grade' => $data['grade'] ?? null, 'remarks' => $data['remarks'] ?? null]);
        }
        return redirect()->route('exams.show', $exam)->with('success', 'Results saved successfully.');
    }
}
