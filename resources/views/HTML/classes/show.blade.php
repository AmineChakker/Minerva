@extends('HTML.layout')
@section('title', $class->full_name)
@section('page-title', 'Class Details')

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">{{ $class->full_name }}</h5>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-default-100">
                    <span class="text-sm text-default-500">Section</span>
                    <span class="text-sm font-medium text-default-700">{{ $class->section ?? '&mdash;' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-default-100">
                    <span class="text-sm text-default-500">Teacher</span>
                    <span class="text-sm font-medium text-default-700">{{ $class->classTeacher->full_name ?? '&mdash;' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-default-100">
                    <span class="text-sm text-default-500">Capacity</span>
                    <span class="text-sm font-medium text-default-700">{{ $class->capacity ?? '&mdash;' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-default-500">Students</span>
                    <span class="badge bg-primary/10 text-primary">{{ $class->students->count() }}</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <a href="{{ route('classes.edit', $class) }}" class="btn bg-primary text-white btn-sm">Edit</a>
                <form action="{{ route('classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Delete this class?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn bg-danger text-white btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Students in this class</h5>
            @forelse($class->students as $student)
            <div class="flex items-center gap-3 p-3 hover:bg-default-50 rounded-lg">
                <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-xs font-bold text-primary">
                    {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-default-700">{{ $student->user->full_name }}</p>
                    <p class="text-xs text-default-400">{{ $student->student_id_number ?? '&mdash;' }}</p>
                </div>
                <a href="{{ route('students.show', $student) }}" class="btn btn-sm bg-default-150">View</a>
            </div>
            @empty
            <p class="text-default-400 text-sm">No students in this class yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
