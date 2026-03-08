@extends('HTML.layout')
@section('title', 'Parents')
@section('page-title', 'Parents')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Parents</span>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Parents</h5>
            <a href="{{ route('parents.create') }}" class="btn bg-primary text-white btn-sm gap-1.5">
                <i class="ti ti-plus"></i> Add Parent
            </a>
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
                                <form action="{{ route('parents.destroy', $parent) }}" method="POST" onsubmit="return confirm('Delete this parent?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center">
                                        <i class="ti ti-trash text-sm"></i>
                                    </button>
                                </form>
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
