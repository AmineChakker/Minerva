@extends('HTML.layout')
@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-edit text-lg text-warning"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">Edit Student</h5>
            <p class="text-xs text-default-400 mt-0.5">{{ $student->user->full_name }}</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-3">Profile Photo</label>
                <div class="flex items-center gap-4">
                    <div id="photo-preview" class="size-16 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-default-200">
                        @if($student->user->profile_photo)
                            <img src="{{ Storage::url($student->user->profile_photo) }}" class="size-full object-cover">
                        @else
                            <div class="size-full bg-primary/10 flex items-center justify-center text-xl font-bold text-primary">
                                {{ strtoupper(substr($student->user->first_name,0,1)) }}{{ strtoupper(substr($student->user->last_name,0,1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewPhoto(this)">
                        <label for="profile_photo" class="btn bg-default-150 text-sm cursor-pointer gap-1.5">
                            <i class="ti ti-upload text-base"></i> Change Photo
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
                    <input class="form-input w-full" name="first_name" type="text" value="{{ old('first_name', $student->user->first_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="last_name" type="text" value="{{ old('last_name', $student->user->last_name) }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="email" type="email" value="{{ old('email', $student->user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">New Password <span class="text-default-400 font-normal text-xs">(leave blank to keep)</span></label>
                    <input class="form-input w-full" name="password" type="password" placeholder="New password">
                </div>
            </div>

            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Student Details</p>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Gender</label>
                    <select class="form-select w-full" name="gender">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $student->user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $student->user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Class</label>
                    <select class="form-select w-full" name="class_id">
                        <option value="">Select class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Admission Number <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="admission_number" type="text" value="{{ old('admission_number', $student->admission_number) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="academic_year_id" required>
                        <option value="">Select academic year</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id', $student->academic_year_id) == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Enrollment Date <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="enrollment_date" type="date" value="{{ old('enrollment_date', $student->enrollment_date?->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Status <span class="text-danger">*</span></label>
                    <select class="form-input w-full" name="status" required>
                        <option value="active"    {{ old('status', $student->status) == 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="inactive"  {{ old('status', $student->status) == 'inactive'  ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Date of Birth</label>
                    <input class="form-input w-full" name="date_of_birth" type="date" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Blood Group</label>
                    <select class="form-input w-full" name="blood_group">
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Address</label>
                <textarea class="form-input w-full" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Update Student
                </button>
                <a href="{{ route('students.show', $student) }}" class="btn bg-default-150 text-default-600">Cancel</a>
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
            document.getElementById('photo-preview').innerHTML = '<img src="' + e.target.result + '" class="size-full object-cover">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
