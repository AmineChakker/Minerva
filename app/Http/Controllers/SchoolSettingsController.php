<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class SchoolSettingsController extends Controller {
    public function edit() { $school = Auth::user()->school; return view('HTML.school.settings', compact('school')); }
    public function update(Request $request) {
        $school = Auth::user()->school;
        $request->validate(['name' => 'required|string|max:255', 'email' => 'nullable|email|max:255', 'phone' => 'nullable|string|max:30', 'address' => 'nullable|string|max:255', 'city' => 'nullable|string|max:100', 'country' => 'nullable|string|max:100', 'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048']);
        $data = $request->only(['name', 'email', 'phone', 'address', 'city', 'country']);
        if ($request->hasFile('logo')) { if ($school->logo) Storage::disk('public')->delete($school->logo); $data['logo'] = $request->file('logo')->store('logos', 'public'); }
        $school->update($data);
        return redirect()->route('school.settings')->with('success', 'School settings updated successfully.');
    }
}
