@extends('HTML.layout')
@section('title', 'Students')
@section('page-title', 'Students')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Students</span>
@endsection

@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>
                    <input class="form-input w-full" name="search" value="{{ request('search') }}" placeholder="Name, email or admission no...">
                </div>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Class</label>
                <select class="form-input w-full" name="class_id">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Academic Year</label>
                <select class="form-input w-full" name="academic_year_id">
                    <option value="">All Years</option>
                    @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Status</label>
                <select class="form-input w-full" name="status">
                    <option value="">All Statuses</option>
                    <option value="active"    {{ request('status') == 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="inactive"  {{ request('status') == 'inactive'  ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="newest"         {{ request('sort','newest') == 'newest'         ? 'selected' : '' }}>Newest First</option>
                    <option value="name_asc"        {{ request('sort') == 'name_asc'        ? 'selected' : '' }}>Name A–Z</option>
                    <option value="name_desc"       {{ request('sort') == 'name_desc'       ? 'selected' : '' }}>Name Z–A</option>
                    <option value="enrollment_asc"  {{ request('sort') == 'enrollment_asc'  ? 'selected' : '' }}>Enrollment ↑</option>
                    <option value="enrollment_desc" {{ request('sort') == 'enrollment_desc' ? 'selected' : '' }}>Enrollment ↓</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','class_id','academic_year_id','status','sort']))
                <a href="{{ route('students.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Students</h5>
            <a href="{{ route('students.create') }}" class="btn bg-primary text-white btn-sm gap-1.5">
                <i class="ti ti-plus"></i> Add Student
            </a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $students->total() }} student(s) found</p>
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
                                @include('HTML.partials.avatar', ['user' => $student->user, 'size' => 'size-9', 'textSize' => 'text-sm', 'color' => 'primary'])
                                <div>
                                    <p class="text-sm font-medium text-default-800">{{ $student->user->full_name }}</p>
                                    <p class="text-xs text-default-500 capitalize">{{ $student->user->gender ?? '&mdash;' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $student->user->email }}</td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $student->classRoom->name ?? '&mdash;' }}</td>
                        <td class="px-6 py-3"><span class="badge bg-primary/10 text-primary">{{ $student->student_id_number ?? '&mdash;' }}</span></td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="View">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="Edit">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <a href="{{ route('report-card.show', $student) }}" class="btn btn-sm bg-success/10 text-success size-8 p-0 flex items-center justify-center" title="Report Card">
                                    <i class="ti ti-file-type-pdf text-sm"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('students.destroy', $student) }}', 'Delete Student', 'Are you sure you want to delete {{ addslashes($student->user->full_name) }}? All their attendance, exam results and fee records will also be removed.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-default-400">
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
