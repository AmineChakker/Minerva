<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ParentController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function index()
    {
        $parents = ParentProfile::with(['user', 'students.user'])
            ->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))
            ->latest()->paginate(15);

        return view('HTML.parents.index', compact('parents'));
    }

    public function create()
    {
        $students = Student::with('user')
            ->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))
            ->where('status', 'active')->get();
        return view('HTML.parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|min:8',
            'phone'               => 'nullable|string|max:20',
            'occupation'          => 'nullable|string|max:255',
            'relation_to_student' => 'required|in:father,mother,guardian,other',
            'student_ids'         => 'nullable|array',
            'student_ids.*'       => 'exists:students,id',
            'profile_photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('profile-photos', 'public')
            : null;

        DB::transaction(function () use ($request, $photoPath) {
            $user = User::create([
                'school_id'     => $this->schoolId(),
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'password'      => Hash::make($request->password),
                'role'          => 'parent',
                'profile_photo' => $photoPath,
                'is_active'     => true,
            ]);

            $parent = ParentProfile::create([
                'user_id'             => $user->id,
                'occupation'          => $request->occupation,
                'relation_to_student' => $request->relation_to_student,
            ]);

            if ($request->student_ids) {
                $parent->students()->attach($request->student_ids);
            }
        });

        return redirect()->route('parents.index')->with('success', 'Parent created successfully.');
    }

    public function show(ParentProfile $parent)
    {
        $parent->load(['user', 'students.user', 'students.classRoom']);
        return view('HTML.parents.show', compact('parent'));
    }

    public function edit(ParentProfile $parent)
    {
        $parent->load('user');
        $students = Student::with('user')
            ->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))
            ->where('status', 'active')->get();
        $linkedStudentIds = $parent->students()->pluck('students.id')->toArray();
        return view('HTML.parents.edit', compact('parent', 'students', 'linkedStudentIds'));
    }

    public function update(Request $request, ParentProfile $parent)
    {
        $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email|unique:users,email,' . $parent->user_id,
            'relation_to_student' => 'required|in:father,mother,guardian,other',
            'student_ids'         => 'nullable|array',
            'student_ids.*'       => 'exists:students,id',
            'profile_photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $parent) {
            $userData = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
            ];

            if ($request->hasFile('profile_photo')) {
                if ($parent->user->profile_photo) {
                    Storage::disk('public')->delete($parent->user->profile_photo);
                }
                $userData['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $parent->user->update($userData);

            if ($request->filled('password')) {
                $parent->user->update(['password' => Hash::make($request->password)]);
            }

            $parent->update([
                'occupation'          => $request->occupation,
                'relation_to_student' => $request->relation_to_student,
            ]);

            $parent->students()->sync($request->student_ids ?? []);
        });

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');
    }

    public function destroy(ParentProfile $parent)
    {
        if ($parent->user->profile_photo) {
            Storage::disk('public')->delete($parent->user->profile_photo);
        }
        $parent->user->delete();
        return redirect()->route('parents.index')->with('success', 'Parent deleted successfully.');
    }
}
