<?php
namespace App\Http\Controllers;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AcademicYearController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index() {
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->paginate(15);
        return view('HTML.academic-years.index', compact('academicYears'));
    }
    public function create() { return view('HTML.academic-years.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:100', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date', 'is_current' => 'nullable|boolean']);
        DB::transaction(function () use ($request) {
            if ($request->boolean('is_current')) AcademicYear::where('school_id', $this->schoolId())->update(['is_current' => false]);
            AcademicYear::create(['school_id' => $this->schoolId(), 'name' => $request->name, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'is_current' => $request->boolean('is_current')]);
        });
        return redirect()->route('academic-years.index')->with('success', 'Academic year created successfully.');
    }
    public function edit(AcademicYear $academicYear) { return view('HTML.academic-years.edit', compact('academicYear')); }
    public function update(Request $request, AcademicYear $academicYear) {
        $request->validate(['name' => 'required|string|max:100', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date', 'is_current' => 'nullable|boolean']);
        DB::transaction(function () use ($request, $academicYear) {
            if ($request->boolean('is_current')) AcademicYear::where('school_id', $this->schoolId())->where('id', '!=', $academicYear->id)->update(['is_current' => false]);
            $academicYear->update(['name' => $request->name, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'is_current' => $request->boolean('is_current')]);
        });
        return redirect()->route('academic-years.index')->with('success', 'Academic year updated successfully.');
    }
    public function destroy(AcademicYear $academicYear) { $academicYear->delete(); return redirect()->route('academic-years.index')->with('success', 'Academic year deleted.'); }
    public function setCurrent(AcademicYear $academicYear) {
        DB::transaction(function () use ($academicYear) {
            AcademicYear::where('school_id', $this->schoolId())->update(['is_current' => false]);
            $academicYear->update(['is_current' => true]);
        });
        return redirect()->route('academic-years.index')->with('success', 'Current academic year updated.');
    }
}
