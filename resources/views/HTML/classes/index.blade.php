@extends('HTML.layout')
@section('title', 'Classes')
@section('page-title', 'Classes')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Classes</span>
@endsection

@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('classes.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-default-400 text-sm"></i>
                    <input class="form-input w-full pl-9" name="search" value="{{ request('search') }}" placeholder="Class name or section...">
                </div>
            </div>
            <div class="min-w-44">
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
                    <option value="newest"    {{ request('sort','newest') == 'newest'    ? 'selected' : '' }}>Newest First</option>
                    <option value="name_asc"  {{ request('sort') == 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                    <option value="capacity"  {{ request('sort') == 'capacity'  ? 'selected' : '' }}>Largest Capacity</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','academic_year_id','sort']))
                <a href="{{ route('classes.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Classes</h5>
            <a href="{{ route('classes.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="ti ti-plus mr-1"></i> Add Class
            </a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $classes->total() }} class(es) found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Section</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Teacher</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Students</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Capacity</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($classes as $class)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <p class="text-sm font-medium text-default-800">{{ $class->name }}</p>
                            <p class="text-xs text-default-400">{{ $class->academicYear->name ?? '&mdash;' }}</p>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $class->section ?? '&mdash;' }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $class->classTeacher->full_name ?? '&mdash;' }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $class->students->count() }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $class->capacity ?? '&mdash;' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('classes.destroy', $class) }}', 'Delete Class', 'Are you sure you want to delete {{ addslashes($class->full_name) }}? All students will be unassigned and related exams removed.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-default-400">No classes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($classes->hasPages())
        <div class="mt-4">{{ $classes->links() }}</div>
        @endif
    </div>
</div>
@endsection
