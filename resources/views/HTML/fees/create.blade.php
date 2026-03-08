@extends('HTML.layout')
@section('title', 'Add Fee')
@section('page-title', 'Add Fee')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('fees.index') }}">Fee Management</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Add Fee</span>
@endsection
@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-success/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-credit-card text-lg text-success"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">Add Fee Record</h5><p class="text-xs text-default-400 mt-0.5">Create a new fee entry</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('fees.store') }}" method="POST">@csrf
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Student <span class="text-danger">*</span></label><select class="form-select w-full" name="student_id" required><option value="">Select student</option>@foreach($students as $s)<option value="{{ $s->id }}" {{ old('student_id')==$s->id?'selected':'' }}>{{ $s->user->full_name }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label><select class="form-select w-full" name="academic_year_id" required><option value="">Select year</option>@foreach($academicYears as $y)<option value="{{ $y->id }}" {{ old('academic_year_id')==$y->id?'selected':'' }}>{{ $y->name }}</option>@endforeach</select></div>
            </div>
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">Fee Title <span class="text-danger">*</span></label><input class="form-input w-full" name="title" type="text" value="{{ old('title') }}" required placeholder="e.g. Tuition Fee – Term 1"></div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Amount ($) <span class="text-danger">*</span></label><input class="form-input w-full" name="amount" type="number" value="{{ old('amount') }}" min="0" step="0.01" required></div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Due Date <span class="text-danger">*</span></label><input class="form-input w-full" name="due_date" type="date" value="{{ old('due_date') }}" required></div>
            </div>
            <div class="mb-6"><label class="block text-sm font-medium text-default-700 mb-1.5">Status <span class="text-danger">*</span></label><select class="form-select w-full sm:w-1/2" name="status" required><option value="unpaid" {{ old('status','unpaid')=='unpaid'?'selected':'' }}>Unpaid</option><option value="paid" {{ old('status')=='paid'?'selected':'' }}>Paid</option><option value="partial" {{ old('status')=='partial'?'selected':'' }}>Partial</option><option value="waived" {{ old('status')=='waived'?'selected':'' }}>Waived</option></select></div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100"><button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Save Fee</button><a href="{{ route('fees.index') }}" class="btn bg-default-150 text-default-600">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
