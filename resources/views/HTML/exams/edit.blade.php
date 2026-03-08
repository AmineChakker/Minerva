@extends('HTML.layout')
@section('title', 'Edit Exam')
@section('page-title', 'Edit Exam')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('exams.index') }}">Exams</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Edit Exam</span>
@endsection
@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-file-pencil text-lg text-warning"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">Edit Exam</h5><p class="text-xs text-default-400 mt-0.5">{{ $exam->name }}</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('exams.update', $exam) }}" method="POST">@csrf @method('PUT')
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">Exam Name <span class="text-danger">*</span></label><input class="form-input w-full" name="name" type="text" value="{{ old('name',$exam->name) }}" required></div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Class <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="class_room_id" required>@foreach($classes as $c)<option value="{{ $c->id }}" {{ old('class_room_id',$exam->class_room_id)==$c->id?'selected':'' }}>{{ $c->full_name }}</option>@endforeach</select>
                </div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Subject <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="subject_id" required>@foreach($subjects as $s)<option value="{{ $s->id }}" {{ old('subject_id',$exam->subject_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach</select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="academic_year_id" required>@foreach($academicYears as $y)<option value="{{ $y->id }}" {{ old('academic_year_id',$exam->academic_year_id)==$y->id?'selected':'' }}>{{ $y->name }}</option>@endforeach</select>
                </div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Total Marks <span class="text-danger">*</span></label><input class="form-input w-full" name="total_marks" type="number" value="{{ old('total_marks',$exam->total_marks) }}" min="1" required></div>
            </div>
            <div class="mb-6"><label class="block text-sm font-medium text-default-700 mb-1.5">Exam Date <span class="text-danger">*</span></label><input class="form-input w-full sm:w-1/2" name="exam_date" type="date" value="{{ old('exam_date',$exam->exam_date->format('Y-m-d')) }}" required></div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Update Exam</button>
                <a href="{{ route('exams.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
