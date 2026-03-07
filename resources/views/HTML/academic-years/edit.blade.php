@extends('HTML.layout')
@section('title', 'Edit Academic Year')
@section('page-title', 'Edit Academic Year')
@section('content')
<div class="card max-w-xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-calendar-event text-lg text-warning"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">Edit Academic Year</h5><p class="text-xs text-default-400 mt-0.5">{{ $academicYear->name }}</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('academic-years.update', $academicYear) }}" method="POST">@csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Year Name <span class="text-danger">*</span></label>
                <input class="form-input w-full" name="name" type="text" value="{{ old('name', $academicYear->name) }}" required>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Start Date <span class="text-danger">*</span></label><input class="form-input w-full" name="start_date" type="date" value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" required></div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">End Date <span class="text-danger">*</span></label><input class="form-input w-full" name="end_date" type="date" value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" required></div>
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer"><input type="hidden" name="is_current" value="0"><input type="checkbox" name="is_current" value="1" class="form-checkbox" {{ old('is_current', $academicYear->is_current) ? 'checked' : '' }}><span class="text-sm text-default-700">Set as current academic year</span></label>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Update</button>
                <a href="{{ route('academic-years.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
