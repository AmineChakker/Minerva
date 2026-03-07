<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index(Request $request) {
        $query = User::where('school_id', $this->schoolId());
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('search')) { $search = $request->search; $query->where(fn($q) => $q->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")); }
        $users = $query->latest()->paginate(20);
        return view('HTML.users.index', compact('users'));
    }
    public function toggleActive(User $user) {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$user->full_name} has been {$status}.");
    }
}
