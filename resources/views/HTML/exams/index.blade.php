@extends('HTML.layout')
@section('title', 'Exams')
@section('page-title', 'Exams')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Exams</h5>
            <a href="{{ route('exams.create') }}" class="btn bg-primary text-white btn-sm gap-1.5"><i class="ti ti-plus"></i> Add Exam</a>
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
                                <form action="{{ route('exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center"><i class="ti ti-trash text-sm"></i></button>
                                </form>
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
