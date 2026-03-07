<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    private function schoolId(): int
    {
        return Auth::user()->school_id;
    }

    public function index()
    {
        $students = Student::with(['user', 'classRoom', 'academicYear'])
            ->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))
            ->latest()->paginate(15);

        return view('HTML.students.index', compact('students'));
    }

    public function create()
    {
        $classes = ClassRoom::where('school_id', $this->schoolId())->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->get();
        return view('HTML.students.create', compact('classes', 'academicYears'));
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
            'admission_number' => 'required|unique:students,admission_number',
            'class_id'         => 'nullable|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'date_of_birth'    => 'nullable|date',
            'enrollment_date'  => 'required|date',
            'status'           => 'required|in:active,graduated,suspended,inactive',
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
                'role'          => 'student',
                'gender'        => $request->gender,
                'profile_photo' => $photoPath,
                'is_active'     => true,
            ]);

            Student::create([
                'user_id'                 => $user->id,
                'admission_number'        => $request->admission_number,
                'class_id'                => $request->class_id,
                'academic_year_id'        => $request->academic_year_id,
                'date_of_birth'           => $request->date_of_birth,
                'blood_group'             => $request->blood_group,
                'nationality'             => $request->nationality,
                'address'                 => $request->address,
                'emergency_contact_name'  => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'medical_notes'           => $request->medical_notes,
                'enrollment_date'         => $request->enrollment_date,
                'status'                  => $request->status,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'classRoom', 'academicYear', 'parents.user']);
        return view('HTML.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $classes = ClassRoom::where('school_id', $this->schoolId())->get();
        $academicYears = AcademicYear::where('school_id', $this->schoolId())->get();
        return view('HTML.students.edit', compact('student', 'classes', 'academicYears'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,' . $student->user_id,
            'class_id'         => 'nullable|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'enrollment_date'  => 'required|date',
            'status'           => 'required|in:active,graduated,suspended,inactive',
            'profile_photo'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $student) {
            $userData = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'gender'     => $request->gender,
            ];

            if ($request->hasFile('profile_photo')) {
                if ($student->user->profile_photo) {
                    Storage::disk('public')->delete($student->user->profile_photo);
                }
                $userData['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $student->user->update($userData);

            if ($request->filled('password')) {
                $student->user->update(['password' => Hash::make($request->password)]);
            }

            $student->update([
                'class_id'                => $request->class_id,
                'academic_year_id'        => $request->academic_year_id,
                'date_of_birth'           => $request->date_of_birth,
                'blood_group'             => $request->blood_group,
                'nationality'             => $request->nationality,
                'address'                 => $request->address,
                'emergency_contact_name'  => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'medical_notes'           => $request->medical_notes,
                'enrollment_date'         => $request->enrollment_date,
                'status'                  => $request->status,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->user->profile_photo) {
            Storage::disk('public')->delete($student->user->profile_photo);
        }
        $student->user->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
