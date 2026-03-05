@extends('HTML.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-6">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Students</p>
                    <h4 class="text-2xl font-semibold text-default-800 mt-1">{{ $stats['students'] }}</h4>
                </div>
                <div class="size-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i class="size-6 text-primary" data-lucide="graduation-cap"></i>
                </div>
            </div>
            <p class="text-sm text-default-500">Enrolled this year</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Teachers</p>
                    <h4 class="text-2xl font-semibold text-default-800 mt-1">{{ $stats['teachers'] }}</h4>
                </div>
                <div class="size-12 rounded-lg bg-success/10 flex items-center justify-center">
                    <i class="size-6 text-success" data-lucide="user-check"></i>
                </div>
            </div>
            <p class="text-sm text-default-500">Active staff members</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Classes</p>
                    <h4 class="text-2xl font-semibold text-default-800 mt-1">{{ $stats['classes'] }}</h4>
                </div>
                <div class="size-12 rounded-lg bg-warning/10 flex items-center justify-center">
                    <i class="size-6 text-warning" data-lucide="school"></i>
                </div>
            </div>
            <p class="text-sm text-default-500">Academic classes</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Subjects</p>
                    <h4 class="text-2xl font-semibold text-default-800 mt-1">{{ $stats['subjects'] }}</h4>
                </div>
                <div class="size-12 rounded-lg bg-info/10 flex items-center justify-center">
                    <i class="size-6 text-info" data-lucide="book-open"></i>
                </div>
            </div>
            <p class="text-sm text-default-500">Available subjects</p>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mb-6">

    {{-- Monthly Enrollment Trend --}}
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-base font-semibold text-default-700">Enrollment Trend</h5>
                <span class="badge bg-primary/10 text-primary text-xs">Last 6 months</span>
            </div>
            <div id="enrollmentTrendChart"></div>
        </div>
    </div>

    {{-- Gender Distribution --}}
    <div class="card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Gender Distribution</h5>
            <div id="genderDonutChart"></div>
            <div class="flex justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <span class="size-2.5 rounded-full bg-primary inline-block"></span>
                    <span class="text-sm text-default-500">Male ({{ $maleCount }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="size-2.5 rounded-full bg-pink-400 inline-block"></span>
                    <span class="text-sm text-default-500">Female ({{ $femaleCount }})</span>
                </div>
                @if($otherCount > 0)
                <div class="flex items-center gap-2">
                    <span class="size-2.5 rounded-full bg-default-300 inline-block"></span>
                    <span class="text-sm text-default-500">Other ({{ $otherCount }})</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Class Enrollment + Recent Students --}}
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">

    {{-- Students per Class --}}
    <div class="card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Students per Class</h5>
            <div id="classBarChart"></div>
        </div>
    </div>

    {{-- Recent Students --}}
    <div class="lg:col-span-2 card">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-base font-semibold text-default-700">Recent Students</h5>
                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                <a href="{{ route('students.index') }}" class="text-sm text-primary font-medium">View all</a>
                @endif
            </div>
            <div class="space-y-3">
                @forelse($recentStudents as $student)
                <div class="flex items-center gap-3 p-3 hover:bg-default-50 rounded-lg transition-colors">
                    <div class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-sm font-bold text-primary flex-shrink-0">
                        {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-default-800 truncate">{{ $student->user->full_name }}</p>
                        <p class="text-xs text-default-400">{{ $student->classRoom->full_name ?? 'No class' }}</p>
                    </div>
                    <span class="badge bg-default-100 text-default-500 text-xs flex-shrink-0">
                        {{ $student->user->created_at->diffForHumans() }}
                    </span>
                </div>
                @empty
                <p class="text-default-400 text-sm text-center py-4">No students yet.</p>
                @endforelse
            </div>

            {{-- Quick actions --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
            <div class="grid grid-cols-3 gap-3 mt-6 pt-4 border-t border-default-100">
                <a href="{{ route('students.create') }}" class="flex flex-col items-center gap-2 p-3 bg-primary/5 hover:bg-primary/10 rounded-lg transition-colors text-center">
                    <i class="size-5 text-primary" data-lucide="user-plus"></i>
                    <span class="text-xs font-medium text-default-600">Add Student</span>
                </a>
                <a href="{{ route('teachers.create') }}" class="flex flex-col items-center gap-2 p-3 bg-success/5 hover:bg-success/10 rounded-lg transition-colors text-center">
                    <i class="size-5 text-success" data-lucide="user-check"></i>
                    <span class="text-xs font-medium text-default-600">Add Teacher</span>
                </a>
                <a href="{{ route('classes.create') }}" class="flex flex-col items-center gap-2 p-3 bg-warning/5 hover:bg-warning/10 rounded-lg transition-colors text-center">
                    <i class="size-5 text-warning" data-lucide="school"></i>
                    <span class="text-xs font-medium text-default-600">Add Class</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
import { u as ApexCharts } from '/assets/apexcharts.esm-DPbJ6jlt.js';

function getCSSVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(`--color-${name}`).trim();
}

document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Enrollment Trend (Area Chart) ──────────────────────────────────
    const monthlyData = @json($monthlyEnrollment);
    const enrollmentChart = new ApexCharts(document.querySelector('#enrollmentTrendChart'), {
        series: [{ name: 'New Students', data: monthlyData.map(d => d.count) }],
        chart: { type: 'area', height: 250, toolbar: { show: false }, parentHeightOffset: 0 },
        xaxis: { categories: monthlyData.map(d => d.month), axisBorder: { show: false } },
        yaxis: { labels: { formatter: v => Math.round(v) }, min: 0 },
        colors: ['#2b7fff'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2 },
        dataLabels: { enabled: false },
        grid: { show: true, padding: { top: -10, right: 0, bottom: -10 } },
        tooltip: { y: { formatter: v => v + ' student' + (v !== 1 ? 's' : '') } },
    });
    enrollmentChart.render();

    // ── 2. Gender Donut Chart ──────────────────────────────────────────────
    const male   = {{ $maleCount }};
    const female = {{ $femaleCount }};
    const other  = {{ $otherCount }};
    const genderSeries = other > 0 ? [male, female, other] : [male, female];
    const genderLabels = other > 0 ? ['Male', 'Female', 'Other'] : ['Male', 'Female'];
    const genderColors = other > 0 ? ['#2b7fff', '#f472b6', '#94a3b8'] : ['#2b7fff', '#f472b6'];

    const genderChart = new ApexCharts(document.querySelector('#genderDonutChart'), {
        series: genderSeries,
        chart: { type: 'donut', height: 200, parentHeightOffset: 0 },
        labels: genderLabels,
        colors: genderColors,
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '70%', labels: {
            show: true,
            total: { show: true, label: 'Students', formatter: () => {{ $stats['students'] }} }
        }}}},
        stroke: { width: 0 },
    });
    genderChart.render();

    // ── 3. Students per Class (Horizontal Bar Chart) ───────────────────────
    const classData = @json($classEnrollment);
    if (classData.length > 0) {
        const classChart = new ApexCharts(document.querySelector('#classBarChart'), {
            series: [{ name: 'Students', data: classData.map(c => c.count) }],
            chart: { type: 'bar', height: Math.max(200, classData.length * 48), toolbar: { show: false }, parentHeightOffset: 0 },
            plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '60%' } },
            xaxis: { categories: classData.map(c => c.name), labels: { formatter: v => Math.round(v) } },
            colors: ['#2b7fff'],
            dataLabels: { enabled: true },
            grid: { show: true, padding: { top: -10, bottom: -10 } },
            tooltip: { y: { formatter: v => v + ' student' + (v !== 1 ? 's' : '') } },
        });
        classChart.render();
    } else {
        document.querySelector('#classBarChart').innerHTML =
            '<p class="text-default-400 text-sm text-center py-8">No class data yet.</p>';
    }

});
</script>
@endpush
