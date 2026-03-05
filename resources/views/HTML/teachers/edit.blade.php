@extends('HTML.layout')
@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher')

@section('content')
<div class="card max-w-3xl">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">Edit: {{ $teacher->user->full_name }}</h5>
        @include('HTML.partials.errors')
        <form action="{{ route('teachers.update', $teacher) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">First Name *</label>
                    <input class="form-input" name="first_name" type="text" value="{{ old('first_name', $teacher->user->first_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name *</label>
                    <input class="form-input" name="last_name" type="text" value="{{ old('last_name', $teacher->user->last_name) }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email *</label>
                    <input class="form-input" name="email" type="email" value="{{ old('email', $teacher->user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">New Password <span class="text-default-400">(leave blank to keep)</span></label>
                    <input class="form-input" name="password" type="password">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $teacher->user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $teacher->user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Employee ID</label>
                    <input class="form-input" name="employee_id" type="text" value="{{ old('employee_id', $teacher->employee_id) }}">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Qualification</label>
                    <input class="form-input" name="qualification" type="text" value="{{ old('qualification', $teacher->qualification) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Specialization</label>
                    <input class="form-input" name="specialization" type="text" value="{{ old('specialization', $teacher->specialization) }}">
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="btn bg-primary text-white">Update Teacher</button>
                <a href="{{ route('teachers.show', $teacher) }}" class="btn bg-default-150">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
