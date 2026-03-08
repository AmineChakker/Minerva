<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function index(Request $request)
    {
        $query = Teacher::with('user')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->where('users.school_id', $this->schoolId())
            ->select('teachers.*');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q
                ->where('users.first_name', 'like', $s)
                ->orWhere('users.last_name', 'like', $s)
                ->orWhere('users.email', 'like', $s)
                ->orWhere('teachers.employee_id', 'like', $s)
                ->orWhere('teachers.specialization', 'like', $s)
            );
        }
        if ($request->filled('status'))         $query->where('teachers.status', $request->status);
        if ($request->filled('specialization')) $query->where('teachers.specialization', 'like', '%'.$request->specialization.'%');

        match($request->input('sort', 'newest')) {
            'name_asc'    => $query->orderBy('users.first_name')->orderBy('users.last_name'),
            'name_desc'   => $query->orderByDesc('users.first_name'),
            'hire_asc'    => $query->orderBy('teachers.hire_date'),
            'hire_desc'   => $query->orderByDesc('teachers.hire_date'),
            'exp_desc'    => $query->orderByDesc('teachers.experience_years'),
            default       => $query->orderByDesc('teachers.created_at'),
        };

        $teachers = $query->paginate(15)->withQueryString();
        return view('HTML.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('HTML.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8',
            'gender'           => 'nullable|in:male,female,other',
            'phone'            => 'nullable|string|max:20',
            'employee_id'      => 'required|unique:teachers,employee_id',
            'hire_date'        => 'nullable|date',
            'qualification'    => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'specialization'   => 'nullable|string|max:255',
            'salary'           => 'nullable|numeric|min:0',
            'status'           => 'required|in:active,on_leave,resigned,terminated',
            'profile_photo'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
                'role'          => 'teacher',
                'gender'        => $request->gender,
                'profile_photo' => $photoPath,
                'is_active'     => true,
            ]);

            Teacher::create([
                'user_id'          => $user->id,
                'employee_id'      => $request->employee_id,
                'hire_date'        => $request->hire_date,
                'qualification'    => $request->qualification,
                'experience_years' => $request->experience_years ?? 0,
                'specialization'   => $request->specialization,
                'salary'           => $request->salary,
                'status'           => $request->status,
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load('user');
        return view('HTML.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('HTML.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,' . $teacher->user_id,
            'employee_id'      => 'required|unique:teachers,employee_id,' . $teacher->id,
            'status'           => 'required|in:active,on_leave,resigned,terminated',
            'profile_photo'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $userData = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'gender'     => $request->gender,
            ];

            if ($request->hasFile('profile_photo')) {
                if ($teacher->user->profile_photo) {
                    Storage::disk('public')->delete($teacher->user->profile_photo);
                }
                $userData['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $teacher->user->update($userData);

            if ($request->filled('password')) {
                $teacher->user->update(['password' => Hash::make($request->password)]);
            }

            $teacher->update([
                'employee_id'      => $request->employee_id,
                'hire_date'        => $request->hire_date,
                'qualification'    => $request->qualification,
                'experience_years' => $request->experience_years ?? 0,
                'specialization'   => $request->specialization,
                'salary'           => $request->salary,
                'status'           => $request->status,
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->user->profile_photo) {
            Storage::disk('public')->delete($teacher->user->profile_photo);
        }
        $teacher->user->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
