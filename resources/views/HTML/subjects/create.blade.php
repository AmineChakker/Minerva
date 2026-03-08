@extends('HTML.layout')
@section('title', 'Add Subject')
@section('page-title', 'Add Subject')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('subjects.index') }}">Subjects</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">New Subject</span>
@endsection

@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-book text-lg text-primary"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">New Subject</h5>
            <p class="text-xs text-default-400 mt-0.5">Add a new subject to the curriculum</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Mathematics">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject Code</label>
                    <input class="form-input w-full" name="code" type="text" value="{{ old('code') }}" placeholder="e.g. MATH-101">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Description</label>
                <textarea class="form-input w-full" name="description" rows="3" placeholder="Optional description">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Save Subject
                </button>
                <a href="{{ route('subjects.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
