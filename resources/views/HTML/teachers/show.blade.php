@extends('HTML.layout')
@section('title', $teacher->user->full_name)
@section('page-title', 'Teacher Profile')

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body text-center">
            <div class="relative inline-block mb-3">
                @if($teacher->user->profile_photo)
                    <img src="{{ Storage::url($teacher->user->profile_photo) }}"
                         class="size-24 rounded-full object-cover mx-auto ring-4 ring-success/20"
                         alt="{{ $teacher->user->full_name }}">
                @else
                    <div class="size-24 rounded-full bg-success/10 flex items-center justify-center text-3xl font-bold text-success mx-auto ring-4 ring-success/20">
                        {{ strtoupper(substr($teacher->user->first_name,0,1)) }}{{ strtoupper(substr($teacher->user->last_name,0,1)) }}
                    </div>
                @endif
                <a href="{{ route('teachers.edit', $teacher) }}"
                   class="absolute bottom-0 end-0 size-7 bg-primary rounded-full flex items-center justify-center ring-2 ring-white"
                   title="Edit photo">
                    <i class="ti ti-camera text-white text-xs"></i>
                </a>
            </div>
            <h5 class="text-lg font-semibold text-default-800">{{ $teacher->user->full_name }}</h5>
            <p class="text-default-500 text-sm mb-1">{{ $teacher->user->email }}</p>
            <span class="badge bg-success/10 text-success">Teacher</span>
            <div class="flex justify-center gap-2 mt-4">
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn bg-primary text-white btn-sm gap-1">
                    <i class="ti ti-edit text-sm"></i> Edit
                </a>
                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Delete this teacher?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn bg-danger text-white btn-sm gap-1">
                        <i class="ti ti-trash text-sm"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Teacher Details</h5>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Employee ID</p>
                    <p class="text-sm text-default-700">{{ $teacher->employee_id ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Gender</p>
                    <p class="text-sm text-default-700 capitalize">{{ $teacher->user->gender ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Qualification</p>
                    <p class="text-sm text-default-700">{{ $teacher->qualification ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Specialization</p>
                    <p class="text-sm text-default-700">{{ $teacher->specialization ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Status</p>
                    <p class="text-sm text-default-700 capitalize">{{ str_replace('_', ' ', $teacher->status) }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Joined</p>
                    <p class="text-sm text-default-700">{{ $teacher->user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
