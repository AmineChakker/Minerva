@extends('HTML.layout')
@section('title', 'Edit Parent')
@section('page-title', 'Edit Parent')

@section('content')
<div class="card max-w-3xl">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">Edit: {{ $parent->user->full_name }}</h5>
        @include('HTML.partials.errors')
        <form action="{{ route('parents.update', $parent) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">First Name *</label>
                    <input class="form-input" name="first_name" type="text" value="{{ old('first_name', $parent->user->first_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name *</label>
                    <input class="form-input" name="last_name" type="text" value="{{ old('last_name', $parent->user->last_name) }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email *</label>
                    <input class="form-input" name="email" type="email" value="{{ old('email', $parent->user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">New Password</label>
                    <input class="form-input" name="password" type="password">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Phone</label>
                    <input class="form-input" name="phone" type="text" value="{{ old('phone', $parent->phone) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Occupation</label>
                    <input class="form-input" name="occupation" type="text" value="{{ old('occupation', $parent->occupation) }}">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Linked Students</label>
                <div class="space-y-2">
                    @foreach($students as $student)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="form-checkbox" type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                               {{ in_array($student->id, old('student_ids', $parent->students->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <span class="text-sm text-default-700">{{ $student->user->full_name }} ({{ $student->classRoom->name ?? 'No class' }})</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="btn bg-primary text-white">Update Parent</button>
                <a href="{{ route('parents.show', $parent) }}" class="btn bg-default-150">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
