<?php
namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FeeController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index(Request $request) {
        $query = Fee::with(['student.user', 'academicYear'])->where('school_id', $this->schoolId());
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('academic_year_id')) $query->where('academic_year_id', $request->academic_year_id);
        $fees = $query->latest()->paginate(15);
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();
        return view('HTML.fees.index', compact('fees', 'academicYears'));
    }
    public function create() {
        $students = Student::with('user')->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();
        return view('HTML.fees.create', compact('students', 'academicYears'));
    }
    public function store(Request $request) {
        $request->validate(['student_id' => 'required|exists:students,id', 'academic_year_id' => 'required|exists:academic_years,id', 'title' => 'required|string|max:255', 'amount' => 'required|numeric|min:0', 'due_date' => 'required|date', 'status' => 'required|in:unpaid,paid,partial,waived']);
        Fee::create(['school_id' => $this->schoolId(), 'student_id' => $request->student_id, 'academic_year_id' => $request->academic_year_id, 'title' => $request->title, 'amount' => $request->amount, 'due_date' => $request->due_date, 'status' => $request->status, 'paid_at' => $request->status === 'paid' ? now() : null]);
        return redirect()->route('fees.index')->with('success', 'Fee record created successfully.');
    }
    public function edit(Fee $fee) {
        $students = Student::with('user')->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();
        return view('HTML.fees.edit', compact('fee', 'students', 'academicYears'));
    }
    public function update(Request $request, Fee $fee) {
        $request->validate(['title' => 'required|string|max:255', 'amount' => 'required|numeric|min:0', 'due_date' => 'required|date', 'status' => 'required|in:unpaid,paid,partial,waived']);
        $fee->update(['title' => $request->title, 'amount' => $request->amount, 'due_date' => $request->due_date, 'status' => $request->status, 'paid_at' => $request->status === 'paid' && !$fee->paid_at ? now() : $fee->paid_at]);
        return redirect()->route('fees.index')->with('success', 'Fee updated successfully.');
    }
    public function destroy(Fee $fee) { $fee->delete(); return redirect()->route('fees.index')->with('success', 'Fee record deleted.'); }
}
