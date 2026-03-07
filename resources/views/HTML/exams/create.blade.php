@extends('HTML.layout')
@section('title', 'Create Exam')
@section('page-title', 'Create Exam')
@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-file-plus text-lg text-primary"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">New Exam</h5><p class="text-xs text-default-400 mt-0.5">Schedule a new exam</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('exams.store') }}" method="POST">@csrf
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">Exam Name <span class="text-danger">*</span></label><input class="form-input w-full" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Mid-Term Mathematics"></div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Class <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="class_room_id" required><option value="">Select class</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ old('class_room_id')==$c->id?'selected':'' }}>{{ $c->full_name }}</option>@endforeach</select>
                </div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Subject <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="subject_id" required><option value="">Select subject</option>@foreach($subjects as $s)<option value="{{ $s->id }}" {{ old('subject_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="academic_year_id" required><option value="">Select year</option>@foreach($academicYears as $y)<option value="{{ $y->id }}" {{ old('academic_year_id')==$y->id?'selected':'' }}>{{ $y->name }}</option>@endforeach</select>
                </div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Total Marks <span class="text-danger">*</span></label><input class="form-input w-full" name="total_marks" type="number" value="{{ old('total_marks',100) }}" min="1" required></div>
            </div>
            <div class="mb-6"><label class="block text-sm font-medium text-default-700 mb-1.5">Exam Date <span class="text-danger">*</span></label><input class="form-input w-full sm:w-1/2" name="exam_date" type="date" value="{{ old('exam_date') }}" required></div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Create Exam</button>
                <a href="{{ route('exams.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
