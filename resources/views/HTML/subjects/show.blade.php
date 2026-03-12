@extends('HTML.layout')
@section('title', $subject->name)
@section('page-title', 'Subject Details')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('subjects.index') }}">Subjects</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">{{ $subject->name }}</span>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">

    {{-- Left: Subject Info --}}
    <div class="space-y-5">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-5">
                    <div class="size-12 rounded-xl bg-info/10 flex items-center justify-center flex-shrink-0">
                        <i class="size-6 text-info" data-lucide="book-open"></i>
                    </div>
                    <div>
                        <h5 class="text-base font-semibold text-default-800">{{ $subject->name }}</h5>
                        @if($subject->code)
                        <span class="badge bg-info/10 text-info text-xs">{{ $subject->code }}</span>
                        @endif
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-default-100">
                        <span class="text-sm text-default-500">Classes Assigned</span>
                        <span class="badge bg-primary/10 text-primary">{{ $subject->classes->count() }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-default-100">
                        <span class="text-sm text-default-500">Total Exams</span>
                        <span class="badge bg-violet-500/10 text-violet-500">{{ $subject->exams->count() }}</span>
                    </div>
                    <div class="py-2">
                        <span class="text-sm text-default-500 block mb-1">Description</span>
                        <p class="text-sm text-default-700">{{ $subject->description ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex gap-2 mt-5">
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn bg-primary text-white btn-sm">Edit</a>
                    <button type="button" onclick="openDeleteModal('{{ route('subjects.destroy', $subject) }}', 'Delete Subject', 'Are you sure you want to delete {{ addslashes($subject->name) }}?')" class="btn bg-danger text-white btn-sm gap-1"><i class="ti ti-trash text-sm"></i> Delete</button>
                </div>
            </div>
        </div>

        {{-- Exams list --}}
        @if($subject->exams->isNotEmpty())
        <div class="card">
            <div class="card-body">
                <h5 class="text-sm font-semibold text-default-700 mb-3">Recent Exams</h5>
                <div class="space-y-2">
                    @foreach($subject->exams->take(5) as $exam)
                    <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-default-50 border border-default-100">
                        <div>
                            <p class="text-sm font-medium text-default-700">{{ $exam->name }}</p>
                            <p class="text-xs text-default-400">{{ $exam->classRoom->full_name ?? '—' }} · {{ $exam->exam_date->format('d M Y') }}</p>
                        </div>
                        <span class="badge bg-violet-500/10 text-violet-500 text-xs">{{ $exam->total_marks }} pts</span>
                    </div>
                    @endforeach
                    @if($subject->exams->count() > 5)
                    <p class="text-xs text-default-400 text-center pt-1">+{{ $subject->exams->count() - 5 }} more exams</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Classes assigned --}}
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-base font-semibold text-default-700">Assigned Classes</h5>
                <span class="badge bg-primary/10 text-primary">{{ $subject->classes->count() }} {{ Str::plural('class', $subject->classes->count()) }}</span>
            </div>
            @forelse($subject->classes as $class)
            <div class="flex items-center gap-4 p-3.5 rounded-lg hover:bg-default-50 border border-default-100 mb-2">
                <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <i class="size-5 text-primary" data-lucide="school"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-default-800">{{ $class->full_name }}</p>
                    <p class="text-xs text-default-400">
                        {{ $class->academicYear->name ?? '—' }}
                        @if($class->students_count ?? $class->students->count())
                        · {{ $class->students->count() }} student(s)
                        @endif
                    </p>
                </div>
                <a href="{{ route('classes.show', $class) }}" class="btn btn-sm bg-default-150 text-default-600">View Class</a>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="size-14 rounded-full bg-default-100 flex items-center justify-center mb-3">
                    <i class="size-7 text-default-300" data-lucide="school"></i>
                </div>
                <p class="text-sm text-default-400">No classes assigned yet.</p>
                <a href="{{ route('subjects.edit', $subject) }}" class="mt-3 text-xs text-primary font-medium">Assign to classes →</a>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
