<?php
namespace App\Http\Controllers;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AnnouncementController extends Controller {
    private function schoolId(): int { return Auth::user()->school_id; }
    public function index(Request $request) {
        $query = Announcement::with('user')->where('school_id', $this->schoolId());

        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('title', 'like', '%'.$request->search.'%')->orWhere('content', 'like', '%'.$request->search.'%'));
        }
        if ($request->filled('type'))         $query->where('type', $request->type);
        if ($request->filled('is_published')) $query->where('is_published', $request->is_published === '1');

        match($request->input('sort', 'newest')) {
            'oldest'   => $query->orderBy('created_at'),
            'title'    => $query->orderBy('title'),
            default    => $query->latest(),
        };

        $announcements = $query->paginate(12)->withQueryString();
        return view('HTML.announcements.index', compact('announcements'));
    }
    public function create() { return view('HTML.announcements.create'); }
    public function store(Request $request) {
        $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string', 'type' => 'required|in:info,warning,success,danger', 'is_published' => 'nullable|boolean']);
        Announcement::create(['school_id' => $this->schoolId(), 'user_id' => Auth::id(), 'title' => $request->title, 'content' => $request->content, 'type' => $request->type, 'is_published' => $request->boolean('is_published')]);
        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }
    public function edit(Announcement $announcement) { return view('HTML.announcements.edit', compact('announcement')); }
    public function update(Request $request, Announcement $announcement) {
        $request->validate(['title' => 'required|string|max:255', 'content' => 'required|string', 'type' => 'required|in:info,warning,success,danger', 'is_published' => 'nullable|boolean']);
        $announcement->update(['title' => $request->title, 'content' => $request->content, 'type' => $request->type, 'is_published' => $request->boolean('is_published')]);
        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }
    public function destroy(Announcement $announcement) { $announcement->delete(); return redirect()->route('announcements.index')->with('success', 'Announcement deleted.'); }
}
