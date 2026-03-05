@extends('HTML.layout')
@section('title', 'Add Teacher')
@section('page-title', 'Add Teacher')

@section('content')
<div class="card max-w-3xl">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">New Teacher</h5>
        @include('HTML.partials.errors')
        <form action="{{ route('teachers.store') }}" method="POST">
            @csrf
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">First Name *</label>
                    <input class="form-input" name="first_name" type="text" value="{{ old('first_name') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name *</label>
                    <input class="form-input" name="last_name" type="text" value="{{ old('last_name') }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email *</label>
                    <input class="form-input" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Password *</label>
                    <input class="form-input" name="password" type="password" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Employee ID</label>
                    <input class="form-input" name="employee_id" type="text" value="{{ old('employee_id') }}" placeholder="e.g. TCH-001">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Qualification</label>
                    <input class="form-input" name="qualification" type="text" value="{{ old('qualification') }}" placeholder="e.g. M.Ed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Specialization</label>
                    <input class="form-input" name="specialization" type="text" value="{{ old('specialization') }}" placeholder="e.g. Mathematics">
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="btn bg-primary text-white">Save Teacher</button>
                <a href="{{ route('teachers.index') }}" class="btn bg-default-150">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
