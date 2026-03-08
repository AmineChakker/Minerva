<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function index(Request $request)
    {
        $query = ClassRoom::with(['academicYear', 'classTeacher', 'students'])
            ->where('school_id', $this->schoolId());

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('name', 'like', $s)->orWhere('section', 'like', $s));
        }
        if ($request->filled('academic_year_id')) $query->where('academic_year_id', $request->academic_year_id);

        match($request->input('sort', 'newest')) {
            'name_asc'  => $query->orderBy('name')->orderBy('section'),
            'name_desc' => $query->orderByDesc('name'),
            'capacity'  => $query->orderByDesc('capacity'),
            default     => $query->latest(),
        };

        $classes       = $query->paginate(15)->withQueryString();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->orderByDesc('start_date')->get();

        return view('HTML.classes.index', compact('classes', 'academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->get();
        $teachers = User::where('school_id', $this->schoolId())->where('role', 'teacher')->get();
        return view('HTML.classes.create', compact('academicYears', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'section'          => 'nullable|string|max:10',
            'capacity'         => 'required|integer|min:1|max:200',
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_teacher_id' => 'nullable|exists:users,id',
        ]);

        ClassRoom::create([
            'school_id'        => $this->schoolId(),
            'academic_year_id' => $request->academic_year_id,
            'name'             => $request->name,
            'section'          => $request->section,
            'capacity'         => $request->capacity,
            'class_teacher_id' => $request->class_teacher_id,
        ]);

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function show(ClassRoom $class)
    {
        $class->load(['academicYear', 'classTeacher', 'students.user']);
        return view('HTML.classes.show', compact('class'));
    }

    public function edit(ClassRoom $class)
    {
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->get();
        $teachers = User::where('school_id', $this->schoolId())->where('role', 'teacher')->get();
        return view('HTML.classes.edit', compact('class', 'academicYears', 'teachers'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'section'          => 'nullable|string|max:10',
            'capacity'         => 'required|integer|min:1|max:200',
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_teacher_id' => 'nullable|exists:users,id',
        ]);

        $class->update($request->only(['name', 'section', 'capacity', 'academic_year_id', 'class_teacher_id']));

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
