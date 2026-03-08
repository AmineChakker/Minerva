<?php
namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ExamController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index(Request $request) {
        $query = Exam::with(['classRoom', 'subject', 'academicYear'])
            ->where('school_id', $this->schoolId());

        if ($request->filled('search'))           $query->where('name', 'like', '%'.$request->search.'%');
        if ($request->filled('class_id'))         $query->where('class_room_id', $request->class_id);
        if ($request->filled('subject_id'))       $query->where('subject_id', $request->subject_id);
        if ($request->filled('academic_year_id')) $query->where('academic_year_id', $request->academic_year_id);

        match($request->input('sort', 'date_desc')) {
            'date_asc'   => $query->orderBy('exam_date'),
            'name_asc'   => $query->orderBy('name'),
            'marks_desc' => $query->orderByDesc('total_marks'),
            default      => $query->orderByDesc('exam_date'),
        };

        $exams         = $query->paginate(15)->withQueryString();
        $classes       = ClassRoom::where('school_id', $this->schoolId())->orderBy('name')->get();
        $subjects      = Subject::where('school_id', $this->schoolId())->orderBy('name')->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();

        return view('HTML.exams.index', compact('exams', 'classes', 'subjects', 'academicYears'));
    }
    public function create() {
        $classes = ClassRoom::where('school_id', $this->schoolId())->orderBy('name')->get();
        $subjects = Subject::where('school_id', $this->schoolId())->orderBy('name')->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();
        return view('HTML.exams.create', compact('classes', 'subjects', 'academicYears'));
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255', 'class_room_id' => 'required|exists:classes,id', 'subject_id' => 'required|exists:subjects,id', 'academic_year_id' => 'required|exists:academic_years,id', 'exam_date' => 'required|date', 'total_marks' => 'required|integer|min:1']);
        Exam::create(['school_id' => $this->schoolId(), 'name' => $request->name, 'class_room_id' => $request->class_room_id, 'subject_id' => $request->subject_id, 'academic_year_id' => $request->academic_year_id, 'exam_date' => $request->exam_date, 'total_marks' => $request->total_marks]);
        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }
    public function show(Exam $exam) {
        $exam->load(['classRoom', 'subject', 'academicYear', 'results.student.user']);
        $students = $exam->classRoom->students()->with('user')->get();
        return view('HTML.exams.show', compact('exam', 'students'));
    }
    public function edit(Exam $exam) {
        $classes = ClassRoom::where('school_id', $this->schoolId())->orderBy('name')->get();
        $subjects = Subject::where('school_id', $this->schoolId())->orderBy('name')->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();
        return view('HTML.exams.edit', compact('exam', 'classes', 'subjects', 'academicYears'));
    }
    public function update(Request $request, Exam $exam) {
        $request->validate(['name' => 'required|string|max:255', 'class_room_id' => 'required|exists:classes,id', 'subject_id' => 'required|exists:subjects,id', 'academic_year_id' => 'required|exists:academic_years,id', 'exam_date' => 'required|date', 'total_marks' => 'required|integer|min:1']);
        $exam->update($request->only(['name', 'class_room_id', 'subject_id', 'academic_year_id', 'exam_date', 'total_marks']));
        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }
    public function destroy(Exam $exam) { $exam->delete(); return redirect()->route('exams.index')->with('success', 'Exam deleted.'); }
}
