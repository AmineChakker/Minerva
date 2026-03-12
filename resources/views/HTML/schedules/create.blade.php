@extends('HTML.layout')
@section('title', 'Add Schedule Slot')
@section('page-title', 'Add Schedule Slot')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('schedules.index') }}">Schedule</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Add Slot</span>
@endsection

@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-calendar-plus text-lg text-primary"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">New Schedule Slot</h5>
            <p class="text-xs text-default-400 mt-0.5">Add a lesson to the weekly timetable</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label>
                <select class="form-input w-full" name="academic_year_id" required>
                    <option value="">Select academic year</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ old('academic_year_id', $year->is_current ? $year->id : '') == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="class_id" id="classSelect" required>
                        <option value="">Select class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', request('class')) == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="subject_id" required>
                        <option value="">Select subject</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}{{ $subject->code ? ' ('.$subject->code.')' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Teacher <span class="text-danger">*</span></label>
                <select class="form-input w-full" name="teacher_id" required>
                    <option value="">Select teacher</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->user_id }}" {{ old('teacher_id', request('teacher')) == $teacher->user_id ? 'selected' : '' }}>
                        {{ $teacher->user->full_name }}
                        @if($teacher->specialization) — {{ $teacher->specialization }}@endif
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Day <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="day_of_week" required>
                        <option value="">Select day</option>
                        @foreach(\App\Models\Schedule::$dayNames as $num => $name)
                        <option value="{{ $num }}" {{ old('day_of_week', request('day')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Period <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="start_time" required>
                        <option value="">Select period</option>
                        @foreach(\App\Models\Schedule::$periods as $start => $label)
                        <option value="{{ $start }}" {{ old('start_time', request('start')) == $start ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Room / Location</label>
                <input class="form-input w-full" name="room" type="text" value="{{ old('room') }}" placeholder="e.g. Salle A101, Lab Info-1">
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Save Slot
                </button>
                <a href="{{ route('schedules.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
