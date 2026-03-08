@extends('HTML.layout')
@section('title', $admin->user->full_name)
@section('page-title', 'Admin Profile')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('admins.index') }}">Admins</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">{{ $admin->user->full_name }}</span>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body text-center">
            <div class="relative inline-block mb-3">
                @if($admin->user->profile_photo)
                    <img src="{{ Storage::url($admin->user->profile_photo) }}"
                         class="size-24 rounded-full object-cover mx-auto ring-4 ring-primary/20"
                         alt="{{ $admin->user->full_name }}">
                @else
                    <div class="size-24 rounded-full bg-primary/10 flex items-center justify-center text-3xl font-bold text-primary mx-auto ring-4 ring-primary/20">
                        {{ strtoupper(substr($admin->user->first_name,0,1)) }}{{ strtoupper(substr($admin->user->last_name,0,1)) }}
                    </div>
                @endif
                <a href="{{ route('admins.edit', $admin) }}"
                   class="absolute bottom-0 end-0 size-7 bg-primary rounded-full flex items-center justify-center ring-2 ring-white"
                   title="Edit">
                    <i class="ti ti-camera text-white text-xs"></i>
                </a>
            </div>
            <h5 class="text-lg font-semibold text-default-800">{{ $admin->user->full_name }}</h5>
            <p class="text-default-500 text-sm mb-1">{{ $admin->user->email }}</p>
            <span class="badge bg-primary/10 text-primary">Administrator</span>
            <div class="flex justify-center gap-2 mt-4">
                <a href="{{ route('admins.edit', $admin) }}" class="btn bg-primary text-white btn-sm gap-1">
                    <i class="ti ti-edit text-sm"></i> Edit
                </a>
                <button type="button" onclick="openDeleteModal('{{ route('admins.destroy', $admin) }}', 'Delete Admin', 'Are you sure you want to delete {{ addslashes($admin->user->full_name) }}? This will also remove their account.')" class="btn bg-danger text-white btn-sm gap-1"><i class="ti ti-trash text-sm"></i> Delete</button>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Admin Details</h5>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Employee ID</p>
                    <p class="text-sm text-default-700">{{ $admin->employee_id ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Department</p>
                    <p class="text-sm text-default-700">{{ $admin->department ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Gender</p>
                    <p class="text-sm text-default-700 capitalize">{{ $admin->user->gender ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Phone</p>
                    <p class="text-sm text-default-700">{{ $admin->user->phone ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Hire Date</p>
                    <p class="text-sm text-default-700">{{ $admin->hire_date ? \Carbon\Carbon::parse($admin->hire_date)->format('M d, Y') : '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Account Created</p>
                    <p class="text-sm text-default-700">{{ $admin->user->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Status</p>
                    @if($admin->user->is_active)
                        <span class="badge bg-success/10 text-success">Active</span>
                    @else
                        <span class="badge bg-danger/10 text-danger">Inactive</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Last Login</p>
                    <p class="text-sm text-default-700">{{ $admin->user->last_login_at ? \Carbon\Carbon::parse($admin->user->last_login_at)->format('M d, Y H:i') : '&mdash;' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
