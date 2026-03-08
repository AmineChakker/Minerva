@extends('HTML.layout')
@section('title', 'Add Academic Year')
@section('page-title', 'Add Academic Year')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('academic-years.index') }}">Academic Years</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">New Year</span>
@endsection
@section('content')
<div class="card max-w-xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-calendar-plus text-lg text-primary"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">New Academic Year</h5><p class="text-xs text-default-400 mt-0.5">Define a new school year</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('academic-years.store') }}" method="POST">@csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Year Name <span class="text-danger">*</span></label>
                <input class="form-input w-full" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. 2025 – 2026">
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Start Date <span class="text-danger">*</span></label><input class="form-input w-full" name="start_date" type="date" value="{{ old('start_date') }}" required></div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">End Date <span class="text-danger">*</span></label><input class="form-input w-full" name="end_date" type="date" value="{{ old('end_date') }}" required></div>
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer"><input type="hidden" name="is_current" value="0"><input type="checkbox" name="is_current" value="1" class="form-checkbox" {{ old('is_current') ? 'checked' : '' }}><span class="text-sm text-default-700">Set as current academic year</span></label>
                <p class="text-xs text-default-400 mt-1 ml-6">This will unset any other current year.</p>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Save</button>
                <a href="{{ route('academic-years.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
