@extends('HTML.layout')
@section('title', 'Edit Subject')
@section('page-title', 'Edit Subject')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('subjects.index') }}">Subjects</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Edit Subject</span>
@endsection

@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-edit text-lg text-warning"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">Edit Subject</h5>
            <p class="text-xs text-default-400 mt-0.5">{{ $subject->name }}</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('subjects.update', $subject) }}" method="POST">
            @csrf @method('PUT')

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="name" type="text" value="{{ old('name', $subject->name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject Code</label>
                    <input class="form-input w-full" name="code" type="text" value="{{ old('code', $subject->code) }}">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Description</label>
                <textarea class="form-input w-full" name="description" rows="3">{{ old('description', $subject->description) }}</textarea>
            </div>

            @if($classes->isNotEmpty())
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-2">Assign to Classes</label>
                <div class="grid sm:grid-cols-2 grid-cols-1 gap-2 p-3 border border-default-200 rounded-lg bg-default-50 max-h-48 overflow-y-auto">
                    @foreach($classes as $class)
                    <label class="flex items-center gap-2.5 cursor-pointer p-1.5 rounded hover:bg-default-100">
                        <input type="checkbox" class="form-checkbox" name="class_ids[]" value="{{ $class->id }}"
                            {{ in_array($class->id, old('class_ids', $selectedClasses)) ? 'checked' : '' }}>
                        <span class="text-sm text-default-700">{{ $class->full_name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-default-400 mt-1">Select one or more classes to assign this subject to.</p>
            </div>
            @endif

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Update Subject
                </button>
                <a href="{{ route('subjects.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
