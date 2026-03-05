@extends('HTML.layout')
@section('title', 'Add Subject')
@section('page-title', 'Add Subject')

@section('content')
<div class="card max-w-2xl">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">New Subject</h5>
        @include('HTML.partials.errors')
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject Name *</label>
                    <input class="form-input" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Mathematics">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Subject Code</label>
                    <input class="form-input" name="code" type="text" value="{{ old('code') }}" placeholder="e.g. MATH-101">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Description</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Optional description">{{ old('description') }}</textarea>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="btn bg-primary text-white">Save Subject</button>
                <a href="{{ route('subjects.index') }}" class="btn bg-default-150">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
