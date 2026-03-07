@extends('HTML.layout')
@section('title', 'Add Parent')
@section('page-title', 'Add Parent')

@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-user-plus text-lg text-primary"></i>
        </div>
        <div>
            <h5 class="text-base font-semibold text-default-800">New Parent</h5>
            <p class="text-xs text-default-400 mt-0.5">Fill in the details to register a new parent</p>
        </div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('parents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-3">Profile Photo</label>
                <div class="flex items-center gap-4">
                    <div id="photo-preview" class="size-16 rounded-full bg-info/10 flex items-center justify-center overflow-hidden flex-shrink-0 ring-2 ring-default-200">
                        <span class="text-xl font-bold text-info" id="photo-initials">?</span>
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
                    <input class="form-input w-full" name="email" type="email" value="{{ old('email') }}" required placeholder="parent@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Password <span class="text-danger">*</span></label>
                    <input class="form-input w-full" name="password" type="password" placeholder="Min 8 characters" required>
                </div>
            </div>

            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Personal Details</p>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Phone</label>
                    <input class="form-input w-full" name="phone" type="text" value="{{ old('phone') }}" placeholder="+1 234 567 890">
                </div>
                <div>
                    <label class="block text-sm font-medium text-default-700 mb-1.5">Occupation</label>
                    <input class="form-input w-full" name="occupation" type="text" value="{{ old('occupation') }}" placeholder="e.g. Engineer">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Relation to Student <span class="text-danger">*</span></label>
                <select class="form-select w-full" name="relation_to_student" required>
                    <option value="">Select relation</option>
                    <option value="father" {{ old('relation_to_student') == 'father' ? 'selected' : '' }}>Father</option>
                    <option value="mother" {{ old('relation_to_student') == 'mother' ? 'selected' : '' }}>Mother</option>
                    <option value="guardian" {{ old('relation_to_student') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                    <option value="other" {{ old('relation_to_student') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            @if($students->count())
            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Link to Students</p>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-2 mb-6">
                @foreach($students as $student)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-default-200 hover:bg-default-50 cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                    <input class="form-checkbox flex-shrink-0" type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                           {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}>
                    <span class="text-sm text-default-700">
                        <span class="font-medium">{{ $student->user->full_name }}</span>
                        <span class="text-default-400 text-xs block">{{ $student->classRoom->name ?? 'No class' }}</span>
                    </span>
                </label>
                @endforeach
            </div>
            @endif

            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Save Parent
                </button>
                <a href="{{ route('parents.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
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
function updateInitials() {
    const fn = document.querySelector('[name=first_name]').value;
    const ln = document.querySelector('[name=last_name]').value;
    const el = document.getElementById('photo-initials');
    if (el) el.textContent = ((fn[0] || '') + (ln[0] || '')).toUpperCase() || '?';
}
</script>
@endpush
@endsection
