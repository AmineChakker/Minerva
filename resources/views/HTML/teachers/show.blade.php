@extends('HTML.layout')
@section('title', $teacher->user->full_name)
@section('page-title', 'Teacher Profile')

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body text-center">
            <div class="size-20 rounded-full bg-success/10 flex items-center justify-center text-2xl font-bold text-success mx-auto mb-3">
                {{ strtoupper(substr($teacher->user->first_name, 0, 1)) }}{{ strtoupper(substr($teacher->user->last_name, 0, 1)) }}
            </div>
            <h5 class="text-lg font-semibold text-default-800">{{ $teacher->user->full_name }}</h5>
            <p class="text-default-500 text-sm mb-1">{{ $teacher->user->email }}</p>
            <span class="badge bg-success/10 text-success">Teacher</span>
            <div class="flex justify-center gap-2 mt-4">
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn bg-primary text-white btn-sm">Edit</a>
                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Delete this teacher?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn bg-danger text-white btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Teacher Details</h5>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Employee ID</p>
                    <p class="text-sm text-default-700">{{ $teacher->employee_id ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Gender</p>
                    <p class="text-sm text-default-700 capitalize">{{ $teacher->user->gender ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Qualification</p>
                    <p class="text-sm text-default-700">{{ $teacher->qualification ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Specialization</p>
                    <p class="text-sm text-default-700">{{ $teacher->specialization ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Joined</p>
                    <p class="text-sm text-default-700">{{ $teacher->user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
