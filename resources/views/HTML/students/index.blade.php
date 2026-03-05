@extends('HTML.layout')
@section('title', 'Students')
@section('page-title', 'Students')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Students</h5>
            <a href="{{ route('students.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="iconify tabler--plus mr-1"></i> Add Student
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Email</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Class</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Student ID</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($students as $student)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-sm font-bold text-primary">
                                    {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-default-800">{{ $student->user->full_name }}</p>
                                    <p class="text-xs text-default-500">{{ $student->user->gender ?? '&mdash;' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $student->user->email }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $student->classRoom->name ?? '&mdash;' }}</td>
                        <td class="px-6 py-3"><span class="badge bg-primary/10 text-primary">{{ $student->student_id_number ?? '&mdash;' }}</span></td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="View">
                                    <i class="iconify tabler--eye text-sm"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="Edit">
                                    <i class="iconify tabler--edit text-sm"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete">
                                        <i class="iconify tabler--trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-default-400">
                            <i class="size-8 mb-2 block mx-auto" data-lucide="graduation-cap"></i>
                            No students found. <a href="{{ route('students.create') }}" class="text-primary">Add one now.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="mt-4">{{ $students->links() }}</div>
        @endif
    </div>
</div>
@endsection
