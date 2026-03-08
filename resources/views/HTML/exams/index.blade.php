@extends('HTML.layout')
@section('title', 'Exams')
@section('page-title', 'Exams')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Exams</span>
@endsection
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('exams.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-default-400 text-sm"></i>
                    <input class="form-input w-full pl-9" name="search" value="{{ request('search') }}" placeholder="Exam name...">
                </div>
            </div>
            <div class="min-w-44">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Class</label>
                <select class="form-input w-full" name="class_id">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-44">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Subject</label>
                <select class="form-input w-full" name="subject_id">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Academic Year</label>
                <select class="form-input w-full" name="academic_year_id">
                    <option value="">All Years</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="date_desc" {{ request('sort','date_desc') == 'date_desc' ? 'selected' : '' }}>Date (Newest)</option>
                    <option value="date_asc"  {{ request('sort') == 'date_asc'  ? 'selected' : '' }}>Date (Oldest)</option>
                    <option value="name_asc"  {{ request('sort') == 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                    <option value="marks_desc"{{ request('sort') == 'marks_desc'? 'selected' : '' }}>Highest Marks</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','class_id','subject_id','academic_year_id','sort']))
                <a href="{{ route('exams.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Exams</h5>
            <a href="{{ route('exams.create') }}" class="btn bg-primary text-white btn-sm gap-1.5"><i class="ti ti-plus"></i> Add Exam</a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $exams->total() }} exam(s) found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100"><tr>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">#</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Exam Name</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Class</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Subject</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Date</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Marks</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($exams as $exam)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3"><p class="text-sm font-semibold text-default-800">{{ $exam->name }}</p><p class="text-xs text-default-400">{{ $exam->academicYear->name }}</p></td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $exam->classRoom->full_name }}</td>
                        <td class="px-6 py-3"><span class="badge bg-info/10 text-info">{{ $exam->subject->name }}</span></td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $exam->exam_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-default-700">{{ $exam->total_marks }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm bg-info/10 text-info text-xs px-2.5 py-1 gap-1"><i class="ti ti-table text-xs"></i> Results</a>
                                <a href="{{ route('exams.edit', $exam) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center"><i class="ti ti-edit text-sm"></i></a>
                                <button type="button" onclick="openDeleteModal('{{ route('exams.destroy', $exam) }}', 'Delete Exam', 'Are you sure you want to delete {{ addslashes($exam->name) }}? All student results for this exam will also be removed.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-default-400">No exams found. <a href="{{ route('exams.create') }}" class="text-primary">Create one.</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($exams->hasPages())<div class="mt-4">{{ $exams->links() }}</div>@endif
    </div>
</div>
@endsection
