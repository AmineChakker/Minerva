@extends('HTML.layout')
@section('title', 'Subjects')
@section('page-title', 'Subjects')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Subjects</span>
@endsection

@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('subjects.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-default-400 text-sm"></i>
                    <input class="form-input w-full pl-9" name="search" value="{{ request('search') }}" placeholder="Subject name or code...">
                </div>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="name_asc"  {{ request('sort','name_asc') == 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                    <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>Newest First</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','sort']))
                <a href="{{ route('subjects.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Subjects</h5>
            <a href="{{ route('subjects.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="ti ti-plus mr-1"></i> Add Subject
            </a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $subjects->total() }} subject(s) found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Subject Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Code</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Description</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded bg-info/10 flex items-center justify-center">
                                    <i class="size-4 text-info" data-lucide="book-open"></i>
                                </div>
                                <p class="text-sm font-medium text-default-800">{{ $subject->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3"><span class="badge bg-info/10 text-info">{{ $subject->code ?? '&mdash;' }}</span></td>
                        <td class="px-6 py-3 text-sm text-default-500 max-w-xs truncate">{{ $subject->description ?? '&mdash;' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('subjects.destroy', $subject) }}', 'Delete Subject', 'Are you sure you want to delete {{ addslashes($subject->name) }}? All exams for this subject will also be removed.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-default-400">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subjects->hasPages())
        <div class="mt-4">{{ $subjects->links() }}</div>
        @endif
    </div>
</div>
@endsection
