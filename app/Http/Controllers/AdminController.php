<?php
namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class AdminController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index() {
        $admins = Admin::with('user')->whereHas('user', fn($q) => $q->where('school_id', $this->schoolId()))->latest()->paginate(15);
        return view('HTML.admins.index', compact('admins'));
    }
    public function create() { return view('HTML.admins.create'); }
    public function store(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email', 'password' => 'required|min:8',
            'gender' => 'nullable|in:male,female,other', 'phone' => 'nullable|string|max:20',
            'employee_id' => 'required|unique:admins,employee_id', 'department' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date', 'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        $photoPath = $request->hasFile('profile_photo') ? $request->file('profile_photo')->store('profile-photos', 'public') : null;
        DB::transaction(function () use ($request, $photoPath) {
            $user = User::create(['school_id' => $this->schoolId(), 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'phone' => $request->phone, 'password' => Hash::make($request->password), 'role' => 'admin', 'gender' => $request->gender, 'profile_photo' => $photoPath, 'is_active' => true]);
            Admin::create(['user_id' => $user->id, 'employee_id' => $request->employee_id, 'department' => $request->department, 'hire_date' => $request->hire_date]);
        });
        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }
    public function show(Admin $admin) { $admin->load('user'); return view('HTML.admins.show', compact('admin')); }
    public function edit(Admin $admin) { $admin->load('user'); return view('HTML.admins.edit', compact('admin')); }
    public function update(Request $request, Admin $admin) {
        $request->validate(['first_name' => 'required|string|max:100', 'last_name' => 'required|string|max:100', 'email' => 'required|email|unique:users,email,'.$admin->user_id, 'employee_id' => 'required|unique:admins,employee_id,'.$admin->id, 'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048']);
        DB::transaction(function () use ($request, $admin) {
            $userData = ['first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'phone' => $request->phone, 'gender' => $request->gender];
            if ($request->hasFile('profile_photo')) { if ($admin->user->profile_photo) Storage::disk('public')->delete($admin->user->profile_photo); $userData['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public'); }
            $admin->user->update($userData);
            if ($request->filled('password')) $admin->user->update(['password' => Hash::make($request->password)]);
            $admin->update(['employee_id' => $request->employee_id, 'department' => $request->department, 'hire_date' => $request->hire_date]);
        });
        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }
    public function destroy(Admin $admin) {
        if ($admin->user->profile_photo) Storage::disk('public')->delete($admin->user->profile_photo);
        $admin->user->delete();
        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }
}
