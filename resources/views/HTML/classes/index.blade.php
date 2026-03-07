@extends('HTML.layout')
@section('title', 'Classes')
@section('page-title', 'Classes')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Classes</h5>
            <a href="{{ route('classes.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="ti ti-plus mr-1"></i> Add Class
            </a>
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
                                <form action="{{ route('classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Delete this class?')">
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
                        <td colspan="7" class="px-6 py-10 text-center text-default-400">No classes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
