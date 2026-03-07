<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AttendanceController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index(Request $request) {
        $classes = ClassRoom::where('school_id', $this->schoolId())->orderBy('name')->get();
        $selectedClass = $request->input('class_id');
        $selectedDate  = $request->input('date', today()->toDateString());
        $attendances = collect(); $students = collect();
        if ($selectedClass) {
            $students = Student::with('user')->where('class_id', $selectedClass)->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))->get();
            $attendances = Attendance::where('class_room_id', $selectedClass)->where('date', $selectedDate)->get()->keyBy('student_id');
        }
        return view('HTML.attendance.index', compact('classes', 'selectedClass', 'selectedDate', 'students', 'attendances'));
    }
    public function store(Request $request) {
        $request->validate(['class_room_id' => 'required|exists:classes,id', 'date' => 'required|date', 'attendance' => 'required|array']);
        $schoolId = $this->schoolId();
        foreach ($request->attendance as $studentId => $data) {
            Attendance::updateOrCreate(['student_id' => $studentId, 'date' => $request->date], ['school_id' => $schoolId, 'class_room_id' => $request->class_room_id, 'status' => $data['status'] ?? 'present', 'note' => $data['note'] ?? null]);
        }
        return redirect()->route('attendance.index', ['class_id' => $request->class_room_id, 'date' => $request->date])->with('success', 'Attendance saved successfully.');
    }
}
