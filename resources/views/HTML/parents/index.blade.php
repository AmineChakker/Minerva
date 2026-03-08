@extends('HTML.layout')
@section('title', 'Parents')
@section('page-title', 'Parents')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Parents</span>
@endsection

@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('parents.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-default-400 text-sm"></i>
                    <input class="form-input w-full pl-9" name="search" value="{{ request('search') }}" placeholder="Name or email...">
                </div>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Relation</label>
                <select class="form-input w-full" name="relation">
                    <option value="">All Relations</option>
                    <option value="father"   {{ request('relation') == 'father'   ? 'selected' : '' }}>Father</option>
                    <option value="mother"   {{ request('relation') == 'mother'   ? 'selected' : '' }}>Mother</option>
                    <option value="guardian" {{ request('relation') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                    <option value="other"    {{ request('relation') == 'other'    ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="newest"    {{ request('sort','newest') == 'newest'    ? 'selected' : '' }}>Newest First</option>
                    <option value="name_asc"  {{ request('sort') == 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','relation','sort']))
                <a href="{{ route('parents.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Parents</h5>
            <a href="{{ route('parents.create') }}" class="btn bg-primary text-white btn-sm gap-1.5">
                <i class="ti ti-plus"></i> Add Parent
            </a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $parents->total() }} parent(s) found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Email</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Phone</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Children</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($parents as $parent)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                @include('HTML.partials.avatar', ['user' => $parent->user, 'size' => 'size-9', 'textSize' => 'text-sm', 'color' => 'info'])
                                <p class="text-sm font-medium text-default-800">{{ $parent->user->full_name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $parent->user->email }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $parent->user->phone ?? '&mdash;' }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $parent->students->count() }} student(s)</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('parents.show', $parent) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <a href="{{ route('parents.edit', $parent) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('parents.destroy', $parent) }}', 'Delete Parent', 'Are you sure you want to delete {{ addslashes($parent->user->full_name) }}? This will also remove their account.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-default-400">No parents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($parents->hasPages())
        <div class="mt-4">{{ $parents->links() }}</div>
        @endif
    </div>
</div>
@endsection
