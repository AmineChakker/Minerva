@extends('HTML.layout')
@section('title', 'Subjects')
@section('page-title', 'Subjects')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Subjects</span>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 grid-cols-1 gap-5 mb-5">
    <div class="card border-l-4 border-l-info">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Subjects</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $totalSubjects }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-info/10 flex items-center justify-center">
                    <i class="size-6 text-info" data-lucide="book-open"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Subjects in curriculum</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-success">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Assigned</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $assignedSubjects }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-success/10 flex items-center justify-center">
                    <i class="size-6 text-success" data-lucide="check-circle"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Linked to at least one class</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-warning">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Unassigned</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $unassignedSubjects }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-warning/10 flex items-center justify-center">
                    <i class="size-6 text-warning" data-lucide="alert-circle"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Not linked to any class</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-primary">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Classes</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $totalClasses }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="size-6 text-primary" data-lucide="school"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Classes in school</p>
        </div>
    </div>
</div>

{{-- Charts Row --}}
@if($subjectsPerClass->isNotEmpty())
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mb-5">
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-base font-semibold text-default-700">Subjects per Class</h5>
                <span class="badge bg-info/10 text-info text-xs">All classes</span>
            </div>
            <div id="subjectsPerClassChart"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Assignment Status</h5>
            <div id="assignmentDonutChart"></div>
            <div class="flex justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <span class="size-2.5 rounded-full bg-success inline-block"></span>
                    <span class="text-sm text-default-500">Assigned ({{ $assignedSubjects }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="size-2.5 rounded-full bg-warning inline-block"></span>
                    <span class="text-sm text-default-500">Unassigned ({{ $unassignedSubjects }})</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Filters --}}
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('subjects.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>
                    <input class="form-input w-full" name="search" value="{{ request('search') }}" placeholder="Subject name or code...">
                </div>
            </div>
            <div class="min-w-44">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Filter by Class</label>
                <select class="form-input w-full" name="class">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class') == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="name_asc"  {{ request('sort','name_asc') == 'name_asc'  ? 'selected' : '' }}>Name A–Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                    <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>Newest First</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','sort','class']))
                <a href="{{ route('subjects.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Subjects</h5>
            <a href="{{ route('subjects.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="ti ti-plus mr-1"></i> Add Subject
            </a>
        </div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-default-500">{{ $subjects->total() }} subject(s) found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Subject Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Code</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Classes</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Description</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded bg-info/10 flex items-center justify-center">
                                    <i class="size-4 text-info" data-lucide="book-open"></i>
                                </div>
                                <p class="text-sm font-medium text-default-800">{{ $subject->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-3"><span class="badge bg-info/10 text-info">{{ $subject->code ?? '&mdash;' }}</span></td>
                        <td class="px-6 py-3">
                            @if($subject->classes_count > 0)
                                <span class="badge bg-success/10 text-success">{{ $subject->classes_count }} {{ Str::plural('class', $subject->classes_count) }}</span>
                            @else
                                <span class="badge bg-warning/10 text-warning">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-default-500 max-w-xs truncate">{{ $subject->description ?? '&mdash;' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="View">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>
                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="Edit">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('subjects.destroy', $subject) }}', 'Delete Subject', 'Are you sure you want to delete {{ addslashes($subject->name) }}? All exams for this subject will also be removed.')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Delete"><i class="ti ti-trash text-sm"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-default-400">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subjects->hasPages())
        <div class="mt-4">{{ $subjects->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($subjectsPerClass->isNotEmpty())
<script type="module">
import { u as ApexCharts } from '/assets/apexcharts.esm-DPbJ6jlt.js';

// Subjects per class bar chart
const classNames   = @json($subjectsPerClass->pluck('full_name'));
const subjectCounts = @json($subjectsPerClass->pluck('subjects_count'));

new ApexCharts(document.querySelector('#subjectsPerClassChart'), {
    chart: { type: 'bar', height: 260, toolbar: { show: false } },
    plotOptions: { bar: { horizontal: true, borderRadius: 4, distributed: true } },
    colors: ['#3b82f6','#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#06b6d4','#f97316'],
    series: [{ name: 'Subjects', data: subjectCounts }],
    xaxis: { categories: classNames, labels: { style: { fontSize: '12px' } } },
    yaxis: { labels: { style: { fontSize: '12px' } } },
    dataLabels: { enabled: true, style: { fontSize: '11px' } },
    legend: { show: false },
    tooltip: { y: { formatter: v => v + ' subject(s)' } },
    grid: { borderColor: '#e5e7eb', strokeDashArray: 4 },
}).render();

// Donut chart
new ApexCharts(document.querySelector('#assignmentDonutChart'), {
    chart: { type: 'donut', height: 200 },
    series: [{{ $assignedSubjects }}, {{ $unassignedSubjects }}],
    labels: ['Assigned', 'Unassigned'],
    colors: ['#10b981', '#f59e0b'],
    plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: () => '{{ $totalSubjects }}' } } } } },
    legend: { show: false },
    dataLabels: { enabled: false },
}).render();
</script>
@endif
@endpush
