@extends('HTML.layout')
@section('title', 'Add Student')
@section('page-title', 'Add Student')

@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-user-plus text-lg text-primary"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">New Student</h5>
            <p class="text-xs text-default-400 mt-0.5">Fill in the details to register a new student</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Photo Upload --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-3">Profile Photo</label>
                <div class="flex items-center gap-4">
                    <div id="photo-preview" class="size-16 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden flex-shrink-0 ring-2 ring-default-200">
                        <span class="text-xl font-bold text-primary" id="photo-initials">?</span>
                    </div>
                    <div>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewPhoto(this)">
                        <label for="profile_photo" class="btn bg-default-150 text-sm cursor-pointer gap-1.5">
                            <i class="ti ti-upload text-base"></i> Choose Photo
                        </label>
                        <p class="text-xs text-default-400 mt-1.5">JPG, PNG or WebP — max 2MB</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Account Info</p>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">First Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="first_name" type="text" value="{{ old('first_name') }}" required placeholder="First name" oninput="updateInitials()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="last_name" type="text" value="{{ old('last_name') }}" required placeholder="Last name" oninput="updateInitials()">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="email" type="email" value="{{ old('email') }}" required placeholder="student@school.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Password <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="password" type="password" placeholder="Min 8 characters" required>
                </div>
            </div>

            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Student Details</p>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Gender</label>
                    <select class="form-select w-full" name="gender">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class</label>
                    <select class="form-select w-full" name="class_id">
                        <option value="">Select class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Student ID Number</label>
                    <input class="form-input w-full" name="student_id_number" type="text" value="{{ old('student_id_number') }}" placeholder="e.g. STU-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Date of Birth</label>
                    <input class="form-input w-full" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Address</label>
                <textarea class="form-input w-full" name="address" rows="2" placeholder="Student address">{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Save Student
                </button>
                <a href="{{ route('students.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('photo-preview').innerHTML =
                `<img src="${e.target.result}" class="size-full object-cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function updateInitials() {
    const fn = document.querySelector('[name=first_name]').value;
    const ln = document.querySelector('[name=last_name]').value;
    const el = document.getElementById('photo-initials');
    if (el) el.textContent = ((fn[0] || '') + (ln[0] || '')).toUpperCase() || '?';
}
</script>
@endpush
@endsection
