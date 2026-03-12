@extends('HTML.layout')
@section('title', 'Edit Schedule Slot')
@section('page-title', 'Edit Schedule Slot')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('schedules.index') }}">Schedule</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Edit Slot</span>
@endsection

@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-calendar-event text-lg text-warning"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">Edit Schedule Slot</h5>
            <p class="text-xs text-default-400 mt-0.5">{{ $schedule->subject->name }} — {{ $schedule->day_name }} {{ $schedule->time_range }}</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('schedules.update', $schedule) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label>
                <select class="form-input w-full" name="academic_year_id" required>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ old('academic_year_id', $schedule->academic_year_id) == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="class_id" required>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="subject_id" required>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}{{ $subject->code ? ' ('.$subject->code.')' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Teacher <span class="text-danger">*</span></label>
                <select class="form-input w-full" name="teacher_id" required>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->user_id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
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
                        @foreach(\App\Models\Schedule::$dayNames as $num => $name)
                        <option value="{{ $num }}" {{ old('day_of_week', $schedule->day_of_week) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Period <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="start_time" required>
                        @foreach(\App\Models\Schedule::$periods as $start => $label)
                        <option value="{{ $start }}" {{ old('start_time', $schedule->start_time_short) == $start ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Room / Location</label>
                <input class="form-input w-full" name="room" type="text" value="{{ old('room', $schedule->room) }}" placeholder="e.g. Salle A101, Lab Info-1">
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Update Slot
                </button>
                <a href="{{ route('schedules.index', ['class' => $schedule->class_id]) }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
