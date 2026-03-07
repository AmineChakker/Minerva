@extends('HTML.layout')
@section('title', $student->user->full_name)
@section('page-title', 'Student Profile')

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body text-center">
            <div class="relative inline-block mb-3">
                @if($student->user->profile_photo)
                    <img src="{{ Storage::url($student->user->profile_photo) }}"
                         class="size-24 rounded-full object-cover mx-auto ring-4 ring-primary/20"
                         alt="{{ $student->user->full_name }}">
                @else
                    <div class="size-24 rounded-full bg-primary/10 flex items-center justify-center text-3xl font-bold text-primary mx-auto ring-4 ring-primary/20">
                        {{ strtoupper(substr($student->user->first_name,0,1)) }}{{ strtoupper(substr($student->user->last_name,0,1)) }}
                    </div>
                @endif
                <a href="{{ route('students.edit', $student) }}"
                   class="absolute bottom-0 end-0 size-7 bg-primary rounded-full flex items-center justify-center ring-2 ring-white"
                   title="Edit photo">
                    <i class="ti ti-camera text-white text-xs"></i>
                </a>
            </div>
            <h5 class="text-lg font-semibold text-default-800">{{ $student->user->full_name }}</h5>
            <p class="text-default-500 text-sm mb-1">{{ $student->user->email }}</p>
            <span class="badge bg-primary/10 text-primary capitalize">Student</span>
            <div class="flex justify-center gap-2 mt-4 flex-wrap">
                <a href="{{ route('students.edit', $student) }}" class="btn bg-primary text-white btn-sm gap-1">
                    <i class="ti ti-edit text-sm"></i> Edit
                </a>
                <a href="{{ route('report-card.show', $student) }}" class="btn bg-success/10 text-success btn-sm gap-1">
                    <i class="ti ti-file-type-pdf text-sm"></i> Report Card
                </a>
                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
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
            <h5 class="text-base font-semibold text-default-700 mb-4">Student Details</h5>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Student ID</p>
                    <p class="text-sm text-default-700">{{ $student->student_id_number ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Class</p>
                    <p class="text-sm text-default-700">{{ $student->classRoom->full_name ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Gender</p>
                    <p class="text-sm text-default-700 capitalize">{{ $student->user->gender ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Date of Birth</p>
                    <p class="text-sm text-default-700">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Address</p>
                    <p class="text-sm text-default-700">{{ $student->address ?? '&mdash;' }}</p>
                </div>
                <div>
                    <p class="text-xs text-default-400 uppercase font-semibold mb-1">Academic Year</p>
                    <p class="text-sm text-default-700">{{ $student->academicYear->name ?? '&mdash;' }}</p>
                </div>
            </div>
            @if($student->parents->count())
            <div class="mt-6">
                <h6 class="text-sm font-semibold text-default-700 mb-3">Parents / Guardians</h6>
                <div class="space-y-2">
                    @foreach($student->parents as $parent)
                    <div class="flex items-center gap-3 p-3 bg-default-50 rounded-lg">
                        @include('HTML.partials.avatar', ['user' => $parent->user, 'size' => 'size-8', 'textSize' => 'text-xs', 'color' => 'info'])
                        <div>
                            <p class="text-sm font-medium text-default-700">{{ $parent->user->full_name }}</p>
                            <p class="text-xs text-default-400">{{ $parent->user->email }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
