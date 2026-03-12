<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = Subject::where('school_id', $schoolId)->withCount('classes');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('name', 'like', $s)->orWhere('code', 'like', $s)->orWhere('description', 'like', $s));
        }

        if ($request->filled('class')) {
            $query->whereHas('classes', fn($q) => $q->where('classes.id', $request->class));
        }

        match($request->input('sort', 'name_asc')) {
            'name_desc' => $query->orderByDesc('name'),
            'newest'    => $query->latest(),
            default     => $query->orderBy('name'),
        };

        $subjects = $query->paginate(15)->withQueryString();

        $classes = ClassRoom::where('school_id', $schoolId)->orderBy('name')->get();

        // Stats
        $totalSubjects      = Subject::where('school_id', $schoolId)->count();
        $assignedSubjects   = Subject::where('school_id', $schoolId)->has('classes')->count();
        $unassignedSubjects = $totalSubjects - $assignedSubjects;
        $totalClasses       = $classes->count();

        // Chart: subjects per class
        $subjectsPerClass = ClassRoom::where('school_id', $schoolId)
            ->withCount('subjects')
            ->orderBy('name')
            ->get();

        return view('HTML.subjects.index', compact(
            'subjects', 'classes',
            'totalSubjects', 'assignedSubjects', 'unassignedSubjects', 'totalClasses',
            'subjectsPerClass'
        ));
    }

    public function create()
    {
        $classes = ClassRoom::where('school_id', $this->schoolId())->orderBy('name')->get();
        return view('HTML.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'class_ids'   => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $subject = Subject::create([
            'school_id'   => $this->schoolId(),
            'name'        => $request->name,
            'code'        => $request->code,
            'description' => $request->description,
        ]);

        if ($request->filled('class_ids')) {
            $subject->classes()->sync($request->class_ids);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load('classes.academicYear', 'exams.classRoom');
        return view('HTML.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $classes         = ClassRoom::where('school_id', $this->schoolId())->orderBy('name')->get();
        $selectedClasses = $subject->classes->pluck('id')->toArray();
        return view('HTML.subjects.edit', compact('subject', 'classes', 'selectedClasses'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'class_ids'   => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $subject->update($request->only(['name', 'code', 'description']));
        $subject->classes()->sync($request->class_ids ?? []);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
