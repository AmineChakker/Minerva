@extends('HTML.layout')
@section('title', 'Parents')
@section('page-title', 'Parents')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Parents</h5>
            <a href="{{ route('parents.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="iconify tabler--plus mr-1"></i> Add Parent
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
                                <div class="size-9 rounded-full bg-info/10 flex items-center justify-center text-sm font-bold text-info">
                                    {{ strtoupper(substr($parent->user->first_name, 0, 1)) }}{{ strtoupper(substr($parent->user->last_name, 0, 1)) }}
                                </div>
                                <p class="text-sm font-medium text-default-800">{{ $parent->user->full_name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $parent->user->email }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $parent->phone ?? '&mdash;' }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $parent->students->count() }} student(s)</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('parents.show', $parent) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="iconify tabler--eye text-sm"></i>
                                </a>
                                <a href="{{ route('parents.edit', $parent) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="iconify tabler--edit text-sm"></i>
                                </a>
                                <form action="{{ route('parents.destroy', $parent) }}" method="POST" onsubmit="return confirm('Delete this parent?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center">
                                        <i class="iconify tabler--trash text-sm"></i>
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
