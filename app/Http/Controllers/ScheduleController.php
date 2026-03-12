<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function index(Request $request)
    {
        $schoolId    = $this->schoolId();
        $classes     = ClassRoom::where('school_id', $schoolId)->orderBy('name')->orderBy('section')->get();
        $teachers    = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))
                              ->with('user')->get()->sortBy('user.last_name');
        $currentYear = AcademicYear::where('school_id', $schoolId)->where('is_current', true)->first();

        $filterClass   = $request->input('class');
        $filterTeacher = $request->input('teacher');

        // Timetable grid data
        $timetable = null;
        if ($filterClass || $filterTeacher) {
            $query = Schedule::where('school_id', $schoolId)
                ->with(['subject', 'teacher', 'classRoom']);

            if ($filterClass)   $query->where('class_id', $filterClass);
            if ($filterTeacher) $query->where('teacher_id', $filterTeacher);

            $slots    = $query->get();
            $timetable = [];
            foreach (array_keys(Schedule::$periods) as $startTime) {
                for ($day = 1; $day <= 5; $day++) {
                    $timetable[$startTime][$day] = null;
                }
            }
            foreach ($slots as $slot) {
                $key = substr($slot->start_time, 0, 5);
                if (isset($timetable[$key])) {
                    $timetable[$key][$slot->day_of_week] = $slot;
                }
            }
        }

        // Stats
        $totalSlots   = Schedule::where('school_id', $schoolId)->count();
        $scheduledClasses   = Schedule::where('school_id', $schoolId)->distinct('class_id')->count('class_id');
        $scheduledTeachers  = Schedule::where('school_id', $schoolId)->distinct('teacher_id')->count('teacher_id');
        $todaySlots   = Schedule::where('school_id', $schoolId)
                            ->where('day_of_week', now()->dayOfWeekIso <= 5 ? now()->dayOfWeekIso : 1)
                            ->count();

        return view('HTML.schedules.index', compact(
            'classes', 'teachers', 'timetable',
            'filterClass', 'filterTeacher',
            'totalSlots', 'scheduledClasses', 'scheduledTeachers', 'todaySlots',
            'currentYear'
        ));
    }

    public function create()
    {
        $schoolId    = $this->schoolId();
        $classes     = ClassRoom::where('school_id', $schoolId)->orderBy('name')->orderBy('section')->get();
        $subjects    = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $teachers    = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))
                              ->with('user')->get()->sortBy('user.last_name');
        $academicYears = AcademicYear::where('school_id', $schoolId)->orderByDesc('is_current')->orderByDesc('start_date')->get();

        return view('HTML.schedules.create', compact('classes', 'subjects', 'teachers', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id'         => 'required|exists:classes,id',
            'subject_id'       => 'required|exists:subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'day_of_week'      => 'required|integer|between:1,5',
            'start_time'       => ['required', Rule::in(array_keys(Schedule::$periods))],
            'room'             => 'nullable|string|max:100',
        ]);

        // Check class slot conflict
        $classConflict = Schedule::where('class_id', $request->class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('start_time', $request->start_time . ':00')
            ->exists();

        if ($classConflict) {
            return back()->withInput()->withErrors(['start_time' => 'This class already has a lesson at this time slot.']);
        }

        // Check teacher slot conflict
        $teacherConflict = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('start_time', $request->start_time . ':00')
            ->exists();

        if ($teacherConflict) {
            return back()->withInput()->withErrors(['teacher_id' => 'This teacher is already scheduled at this time slot.']);
        }

        Schedule::create([
            'school_id'        => $this->schoolId(),
            'academic_year_id' => $request->academic_year_id,
            'class_id'         => $request->class_id,
            'subject_id'       => $request->subject_id,
            'teacher_id'       => $request->teacher_id,
            'day_of_week'      => $request->day_of_week,
            'start_time'       => $request->start_time . ':00',
            'end_time'         => Schedule::$periodEnds[$request->start_time] . ':00',
            'room'             => $request->room,
        ]);

        return redirect()->route('schedules.index', ['class' => $request->class_id])
                         ->with('success', 'Schedule slot added successfully.');
    }

    public function edit(Schedule $schedule)
    {
        $schoolId      = $this->schoolId();
        $classes       = ClassRoom::where('school_id', $schoolId)->orderBy('name')->orderBy('section')->get();
        $subjects      = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $teachers      = Teacher::whereHas('user', fn($q) => $q->where('school_id', $schoolId))
                                ->with('user')->get()->sortBy('user.last_name');
        $academicYears = AcademicYear::where('school_id', $schoolId)->orderByDesc('is_current')->orderByDesc('start_date')->get();

        return view('HTML.schedules.edit', compact('schedule', 'classes', 'subjects', 'teachers', 'academicYears'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id'         => 'required|exists:classes,id',
            'subject_id'       => 'required|exists:subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'day_of_week'      => 'required|integer|between:1,5',
            'start_time'       => ['required', Rule::in(array_keys(Schedule::$periods))],
            'room'             => 'nullable|string|max:100',
        ]);

        // Check class slot conflict (excluding self)
        $classConflict = Schedule::where('class_id', $request->class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('start_time', $request->start_time . ':00')
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($classConflict) {
            return back()->withInput()->withErrors(['start_time' => 'This class already has a lesson at this time slot.']);
        }

        $teacherConflict = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('start_time', $request->start_time . ':00')
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($teacherConflict) {
            return back()->withInput()->withErrors(['teacher_id' => 'This teacher is already scheduled at this time slot.']);
        }

        $schedule->update([
            'academic_year_id' => $request->academic_year_id,
            'class_id'         => $request->class_id,
            'subject_id'       => $request->subject_id,
            'teacher_id'       => $request->teacher_id,
            'day_of_week'      => $request->day_of_week,
            'start_time'       => $request->start_time . ':00',
            'end_time'         => Schedule::$periodEnds[$request->start_time] . ':00',
            'room'             => $request->room,
        ]);

        return redirect()->route('schedules.index', ['class' => $request->class_id])
                         ->with('success', 'Schedule slot updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $classId = $schedule->class_id;
        $schedule->delete();
        return redirect()->route('schedules.index', ['class' => $classId])
                         ->with('success', 'Schedule slot removed.');
    }
}
