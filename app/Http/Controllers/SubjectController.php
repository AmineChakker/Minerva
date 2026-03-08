<?php

namespace App\Http\Controllers;

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
        $query = Subject::where('school_id', $this->schoolId());

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('name', 'like', $s)->orWhere('code', 'like', $s)->orWhere('description', 'like', $s));
        }

        match($request->input('sort', 'name_asc')) {
            'name_desc' => $query->orderByDesc('name'),
            'newest'    => $query->latest(),
            default     => $query->orderBy('name'),
        };

        $subjects = $query->paginate(15)->withQueryString();
        return view('HTML.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('HTML.subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        Subject::create([
            'school_id'   => $this->schoolId(),
            'name'        => $request->name,
            'code'        => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('HTML.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'code'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $subject->update($request->only(['name', 'code', 'description']));

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
