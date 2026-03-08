@extends('HTML.layout')
@section('title', 'Admins')
@section('page-title', 'Admins')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Admins</span>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Admins</h5>
            <a href="{{ route('admins.create') }}" class="btn bg-primary text-white btn-sm gap-1.5">
                <i class="ti ti-plus"></i> Add Admin
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
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Department</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Hire Date</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                @include('HTML.partials.avatar', ['user' => $admin->user, 'size' => 'size-9', 'textSize' => 'text-sm', 'color' => 'primary'])
                                <div>
                                    <p class="text-sm font-medium text-default-800">{{ $admin->user->full_name }}</p>
                                    <p class="text-xs text-default-500 capitalize">{{ $admin->user->gender ?? '&mdash;' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $admin->user->email }}</td>
                        <td class="px-6 py-3"><span class="badge bg-primary/10 text-primary">{{ $admin->employee_id ?? '&mdash;' }}</span></td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $admin->department ?? '&mdash;' }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $admin->hire_date ? \Carbon\Carbon::parse($admin->hire_date)->format('M d, Y') : '&mdash;' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admins.show', $admin) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admins.edit', $admin) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Delete this admin?')">
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
                        <td colspan="7" class="px-6 py-10 text-center text-default-400">
                            No admins found. <a href="{{ route('admins.create') }}" class="text-primary">Add one now.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admins->hasPages())
        <div class="mt-4">{{ $admins->links() }}</div>
        @endif
    </div>
</div>
@endsection
