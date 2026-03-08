@extends('HTML.layout')
@section('title', 'Teachers')
@section('page-title', 'Teachers')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Teachers</span>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Teachers</h5>
            <a href="{{ route('teachers.create') }}" class="btn bg-primary text-white btn-sm gap-1.5">
                <i class="ti ti-plus"></i> Add Teacher
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Email</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Employee ID</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Qualification</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                @include('HTML.partials.avatar', ['user' => $teacher->user, 'size' => 'size-9', 'textSize' => 'text-sm', 'color' => 'success'])
                                <div>
                                    <p class="text-sm font-medium text-default-800">{{ $teacher->user->full_name }}</p>
                                    <p class="text-xs text-default-500 capitalize">{{ $teacher->user->gender ?? '&mdash;' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $teacher->user->email }}</td>
                        <td class="px-6 py-3"><span class="badge bg-success/10 text-success">{{ $teacher->employee_id ?? '&mdash;' }}</span></td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $teacher->qualification ?? '&mdash;' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Delete this teacher?')">
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
                        <td colspan="6" class="px-6 py-10 text-center text-default-400">
                            No teachers found. <a href="{{ route('teachers.create') }}" class="text-primary">Add one now.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($teachers->hasPages())
        <div class="mt-4">{{ $teachers->links() }}</div>
        @endif
    </div>
</div>
@endsection
