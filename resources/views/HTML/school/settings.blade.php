@extends('HTML.layout')
@section('title', 'School Settings')
@section('page-title', 'School Settings')
@section('content')
<div class="card max-w-3xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-school text-lg text-primary"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">School Settings</h5><p class="text-xs text-default-400 mt-0.5">Update your school information</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('school.settings.update') }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="mb-6">
                <label class="block text-sm font-medium text-default-700 mb-3">School Logo</label>
                <div class="flex items-center gap-4">
                    <div class="size-16 rounded-xl overflow-hidden bg-default-100 flex items-center justify-center flex-shrink-0 ring-2 ring-default-200" id="logo-preview-wrap">
                        @if($school->logo)<img src="{{ Storage::url($school->logo) }}" class="size-full object-cover">@else<i class="ti ti-school text-2xl text-default-400"></i>@endif
                    </div>
                    <div>
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                        <label for="logo" class="btn bg-default-150 text-sm cursor-pointer gap-1.5"><i class="ti ti-upload text-base"></i> Upload Logo</label>
                        <p class="text-xs text-default-400 mt-1.5">JPG, PNG or WebP — max 2MB</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Basic Info</p>
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">School Name <span class="text-danger">*</span></label><input class="form-input w-full" name="name" type="text" value="{{ old('name', $school->name) }}" required></div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Email</label><input class="form-input w-full" name="email" type="email" value="{{ old('email', $school->email) }}"></div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Phone</label><input class="form-input w-full" name="phone" type="text" value="{{ old('phone', $school->phone) }}"></div>
            </div>
            <div class="border-t border-default-100 mb-5"></div>
            <p class="text-xs font-semibold uppercase tracking-wider text-default-400 mb-3">Location</p>
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">Address</label><input class="form-input w-full" name="address" type="text" value="{{ old('address', $school->address) }}"></div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">City</label><input class="form-input w-full" name="city" type="text" value="{{ old('city', $school->city) }}"></div>
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Country</label><input class="form-input w-full" name="country" type="text" value="{{ old('country', $school->country) }}"></div>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Save Settings</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')<script>function previewLogo(input){if(input.files&&input.files[0]){const r=new FileReader();r.onload=(e)=>{document.getElementById('logo-preview-wrap').innerHTML='<img src="'+e.target.result+'" class="size-full object-cover">';};r.readAsDataURL(input.files[0]);}}</script>@endpush
@endsection
