@extends('HTML.layout')
@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('teachers.index') }}">Teachers</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Edit Teacher</span>
@endsection

@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-edit text-lg text-warning"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">Edit Teacher</h5>
            <p class="text-xs text-default-400 mt-0.5">{{ $teacher->user->full_name }}</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-3">Profile Photo</label>
                <div class="flex items-center gap-4">
                    <div id="photo-preview" class="size-16 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-default-200">
                        @if($teacher->user->profile_photo)
                            <img src="{{ Storage::url($teacher->user->profile_photo) }}" class="size-full object-cover">
                        @else
                            <div class="size-full bg-success/10 flex items-center justify-center text-xl font-bold text-success">
                                {{ strtoupper(substr($teacher->user->first_name,0,1)) }}{{ strtoupper(substr($teacher->user->last_name,0,1)) }}
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
                    <input class="form-input w-full" name="first_name" type="text" value="{{ old('first_name', $teacher->user->first_name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Last Name <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="last_name" type="text" value="{{ old('last_name', $teacher->user->last_name) }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Email <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="email" type="email" value="{{ old('email', $teacher->user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">New Password <span class="text-default-400 font-normal text-xs">(leave blank to keep)</span></label>
                    <input class="form-input w-full" name="password" type="password" placeholder="New password">
                </div>
            </div>

            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Professional Details</p>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Gender</label>
                    <select class="form-select w-full" name="gender">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $teacher->user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $teacher->user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Employee ID <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="employee_id" type="text" value="{{ old('employee_id', $teacher->employee_id) }}" required>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Qualification</label>
                    <input class="form-input w-full" name="qualification" type="text" value="{{ old('qualification', $teacher->qualification) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Specialization</label>
                    <input class="form-input w-full" name="specialization" type="text" value="{{ old('specialization', $teacher->specialization) }}">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Hire Date</label>
                    <input class="form-input w-full" name="hire_date" type="date" value="{{ old('hire_date', $teacher->hire_date?->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Status <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="status" required>
                        <option value="active" {{ old('status',$teacher->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="on_leave" {{ old('status',$teacher->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="resigned" {{ old('status',$teacher->status) == 'resigned' ? 'selected' : '' }}>Resigned</option>
                        <option value="terminated" {{ old('status',$teacher->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Update Teacher
                </button>
                <a href="{{ route('teachers.show', $teacher) }}" class="btn bg-default-150 text-default-600">Cancel</a>
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
