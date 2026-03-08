@extends('HTML.layout')
@section('title', 'Edit Class')
@section('page-title', 'Edit Class')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('classes.index') }}">Classes</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Edit Class</span>
@endsection

@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-edit text-lg text-warning"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">Edit Class</h5>
            <p class="text-xs text-default-400 mt-0.5">{{ $class->full_name }}</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('classes.update', $class) }}" method="POST">
            @csrf @method('PUT')

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="name" type="text" value="{{ old('name', $class->name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Section</label>
                    <input class="form-input w-full" name="section" type="text" value="{{ old('section', $class->section) }}">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="academic_year_id" required>
                        <option value="">Select academic year</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id', $class->academic_year_id) == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Capacity <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="capacity" type="number" value="{{ old('capacity', $class->capacity) }}" min="1" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class Teacher</label>
                    <select class="form-input w-full" name="class_teacher_id">
                        <option value="">Select teacher</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('class_teacher_id', $class->class_teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Update Class
                </button>
                <a href="{{ route('classes.show', $class) }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
