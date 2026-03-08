@extends('HTML.layout')
@section('title', 'Academic Years')
@section('page-title', 'Academic Years')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Academic Years</span>
@endsection
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('academic-years.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-default-400 text-sm"></i>
                    <input class="form-input w-full pl-9" name="search" value="{{ request('search') }}" placeholder="Year name e.g. 2024-2025...">
                </div>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Status</label>
                <select class="form-input w-full" name="is_current">
                    <option value="">All Years</option>
                    <option value="1" {{ request('is_current') === '1' ? 'selected' : '' }}>Current Year</option>
                    <option value="0" {{ request('is_current') === '0' ? 'selected' : '' }}>Past Years</option>
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="newest"   {{ request('sort','newest') == 'newest'   ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest"   {{ request('sort') == 'oldest'   ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','is_current','sort']))
                <a href="{{ route('academic-years.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">Academic Years</h5>
            <a href="{{ route('academic-years.create') }}" class="btn bg-primary text-white btn-sm gap-1.5"><i class="ti ti-plus"></i> Add Year</a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $academicYears->total() }} year(s) found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Start Date</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">End Date</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Status</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($academicYears as $year)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-default-800">{{ $year->name }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $year->start_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $year->end_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3">
                            @if($year->is_current)
                                <span class="badge bg-success/10 text-success">Current</span>
                            @else
                                <span class="badge bg-default-100 text-default-500">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                @if(!$year->is_current)
                                <form action="{{ route('academic-years.set-current', $year) }}" method="POST">@csrf
                                    <button type="submit" class="btn btn-sm bg-success/10 text-success text-xs px-2.5 py-1">Set Current</button>
                                </form>
                                @endif
                                <a href="{{ route('academic-years.edit', $year) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center"><i class="ti ti-edit text-sm"></i></a>
                                <button type="button" onclick="openDeleteModal('{{ route('academic-years.destroy', $year) }}', 'Delete Academic Year', 'Are you sure you want to delete {{ addslashes($year->name) }}? This will also delete all classes, students, exams and fees for this year.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-default-400">No academic years. <a href="{{ route('academic-years.create') }}" class="text-primary">Add one.</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($academicYears->hasPages())<div class="mt-4">{{ $academicYears->links() }}</div>@endif
    </div>
</div>
@endsection
