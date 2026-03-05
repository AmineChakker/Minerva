@extends('HTML.layout')
@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
<div class="card max-w-3xl">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">Edit: {{ $student->user->full_name }}</h5>
        @include('HTML.partials.errors')
        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">First Name *</label>
                    <input class="form-input" name="first_name" type="text" value="{{ old('first_name', $student->user->first_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name *</label>
                    <input class="form-input" name="last_name" type="text" value="{{ old('last_name', $student->user->last_name) }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email *</label>
                    <input class="form-input" name="email" type="email" value="{{ old('email', $student->user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">New Password <span class="text-default-400">(leave blank to keep)</span></label>
                    <input class="form-input" name="password" type="password" placeholder="New password">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $student->user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $student->user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class</label>
                    <select class="form-select" name="class_id">
                        <option value="">Select class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Student ID Number</label>
                    <input class="form-input" name="student_id_number" type="text" value="{{ old('student_id_number', $student->student_id_number) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Date of Birth</label>
                    <input class="form-input" name="date_of_birth" type="date" value="{{ old('date_of_birth', $student->date_of_birth) }}">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Address</label>
                <textarea class="form-control" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="btn bg-primary text-white">Update Student</button>
                <a href="{{ route('students.show', $student) }}" class="btn bg-default-150">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
