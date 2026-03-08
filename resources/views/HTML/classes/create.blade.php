@extends('HTML.layout')
@section('title', 'Add Class')
@section('page-title', 'Add Class')

@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-school text-lg text-primary"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">New Class</h5>
            <p class="text-xs text-default-400 mt-0.5">Create a new classroom for your school</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('classes.store') }}" method="POST">
            @csrf

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Grade 5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Section</label>
                    <input class="form-input w-full" name="section" type="text" value="{{ old('section') }}" placeholder="e.g. A">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class Teacher</label>
                    <select class="form-select w-full" name="class_teacher_id">
                        <option value="">Select teacher</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('class_teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Capacity</label>
                    <input class="form-input w-full" name="capacity" type="number" value="{{ old('capacity') }}" placeholder="Max students" min="1">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Save Class
                </button>
                <a href="{{ route('classes.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
