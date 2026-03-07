@extends('HTML.layout')
@section('title', $parent->user->full_name)
@section('page-title', 'Parent Profile')

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body text-center">
            <div class="relative inline-block mb-3">
                @if($parent->user->profile_photo)
                    <img src="{{ Storage::url($parent->user->profile_photo) }}"
                         class="size-24 rounded-full object-cover mx-auto ring-4 ring-info/20"
                         alt="{{ $parent->user->full_name }}">
                @else
                    <div class="size-24 rounded-full bg-info/10 flex items-center justify-center text-3xl font-bold text-info mx-auto ring-4 ring-info/20">
                        {{ strtoupper(substr($parent->user->first_name,0,1)) }}{{ strtoupper(substr($parent->user->last_name,0,1)) }}
                    </div>
                @endif
                <a href="{{ route('parents.edit', $parent) }}"
                   class="absolute bottom-0 end-0 size-7 bg-primary rounded-full flex items-center justify-center ring-2 ring-white"
                   title="Edit photo">
                    <i class="ti ti-camera text-white text-xs"></i>
                </a>
            </div>
            <h5 class="text-lg font-semibold text-default-800">{{ $parent->user->full_name }}</h5>
            <p class="text-default-500 text-sm mb-1">{{ $parent->user->email }}</p>
            <span class="badge bg-info/10 text-info capitalize">{{ $parent->relation_to_student }}</span>
            <div class="flex justify-center gap-2 mt-4">
                <a href="{{ route('parents.edit', $parent) }}" class="btn bg-primary text-white btn-sm gap-1">
                    <i class="ti ti-edit text-sm"></i> Edit
                </a>
                <form action="{{ route('parents.destroy', $parent) }}" method="POST" onsubmit="return confirm('Delete this parent?')">
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
            <h5 class="text-base font-semibold text-default-700 mb-4">Parent Details</h5>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Phone</p>
                    <p class="text-sm text-default-700">{{ $parent->user->phone ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Occupation</p>
                    <p class="text-sm text-default-700">{{ $parent->occupation ?? '&mdash;' }}</p>
                </div>
            </div>
            @if($parent->students->count())
            <h6 class="text-sm font-semibold text-default-700 mb-3">Children</h6>
            <div class="space-y-2">
                @foreach($parent->students as $student)
                <div class="flex items-center gap-3 p-3 bg-default-50 rounded-lg">
                    @include('HTML.partials.avatar', ['user' => $student->user, 'size' => 'size-8', 'textSize' => 'text-xs', 'color' => 'primary'])
                    <div>
                        <p class="text-sm font-medium text-default-700">{{ $student->user->full_name }}</p>
                        <p class="text-xs text-default-400">{{ $student->classRoom->name ?? 'No class' }}</p>
                    </div>
                    <a href="{{ route('students.show', $student) }}" class="ms-auto btn btn-sm bg-default-150">View</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
