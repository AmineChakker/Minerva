@extends('HTML.layout')
@section('title', 'Edit Class')
@section('page-title', 'Edit Class')

@section('content')
<div class="card max-w-3xl">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">Edit: {{ $class->full_name }}</h5>
        @include('HTML.partials.errors')
        <form action="{{ route('classes.update', $class) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class Name *</label>
                    <input class="form-input" name="name" type="text" value="{{ old('name', $class->name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Section</label>
                    <input class="form-input" name="section" type="text" value="{{ old('section', $class->section) }}">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class Teacher</label>
                    <select class="form-select" name="teacher_id">
                        <option value="">Select teacher</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Capacity</label>
                    <input class="form-input" name="capacity" type="number" value="{{ old('capacity', $class->capacity) }}">
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="btn bg-primary text-white">Update Class</button>
                <a href="{{ route('classes.show', $class) }}" class="btn bg-default-150">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
