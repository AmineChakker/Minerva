@extends('HTML.layout')
@section('title', 'Report Card — ' . $student->user->full_name)
@section('page-title', 'Report Card')
@section('content')

{{-- Header row --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        @include('HTML.partials.avatar', ['user' => $student->user, 'size' => 'size-11', 'textSize' => 'text-sm', 'color' => 'primary'])
        <div>
            <h5 class="text-base font-semibold text-default-800">{{ $student->user->full_name }}</h5>
            <p class="text-xs text-default-400">{{ $student->classRoom->full_name ?? '—' }} &bull; {{ $student->admission_number ?? 'No ID' }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        {{-- Year selector --}}
        <form method="GET" action="{{ route('report-card.show', $student) }}" class="flex items-center gap-2">
            <select name="year" class="form-input text-sm h-9 min-w-44" onchange="this.form.submit()">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYear?->id == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}{{ $year->is_current ? ' (Current)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
        @if($selectedYear)
        <a href="{{ route('report-card.download', [$student, $selectedYear]) }}"
           class="btn bg-primary text-white gap-2">
            <i class="ti ti-file-type-pdf text-base"></i> Download PDF
        </a>
        @endif
        <a href="{{ route('students.show', $student) }}" class="btn bg-default-150 text-default-600 gap-1.5">
            <i class="ti ti-arrow-left text-sm"></i> Back
        </a>
    </div>
</div>

@if(!$selectedYear)
    <div class="card"><div class="card-body text-center py-12 text-default-400">No academic years found. Create one first.</div></div>
@elseif(!$reportData)
    <div class="card"><div class="card-body text-center py-12 text-default-400">No data available for this year.</div></div>
@else

{{-- ── Stats row ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card border-l-4 border-l-primary">
        <div class="card-body py-4">
            <p class="text-xs text-default-400 mb-1">Overall Score</p>
            <h4 class="text-xl font-bold text-default-800">
                {{ $reportData['overallPct'] !== null ? $reportData['overallPct'].'%' : 'N/A' }}
            </h4>
            <p class="text-xs text-default-400 mt-1">{{ $reportData['obtainedMarks'] }} / {{ $reportData['totalMarks'] }} marks</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-{{ in_array($reportData['overallGrade'],['A+','A','B+','B']) ? 'success' : (in_array($reportData['overallGrade'],['C+','C']) ? 'warning' : 'danger') }}">
        <div class="card-body py-4">
            <p class="text-xs text-default-400 mb-1">Overall Grade</p>
            <h4 class="text-xl font-bold text-default-800">{{ $reportData['overallGrade'] }}</h4>
            <p class="text-xs mt-1 {{ $reportData['overallPct'] >= 60 ? 'text-success' : 'text-danger' }}">
                {{ $reportData['overallPct'] !== null ? ($reportData['overallPct'] >= 60 ? 'Pass' : 'Fail') : 'Pending' }}
            </p>
        </div>
    </div>
    <div class="card border-l-4 border-l-{{ $reportData['attendancePct'] >= 75 ? 'success' : ($reportData['attendancePct'] >= 50 ? 'warning' : 'danger') }}">
        <div class="card-body py-4">
            <p class="text-xs text-default-400 mb-1">Attendance</p>
            <h4 class="text-xl font-bold text-default-800">{{ $reportData['attendancePct'] }}%</h4>
            <p class="text-xs text-default-400 mt-1">{{ $reportData['presentCount'] }} / {{ $reportData['totalDays'] }} days</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-info">
        <div class="card-body py-4">
            <p class="text-xs text-default-400 mb-1">Exams Taken</p>
            <h4 class="text-xl font-bold text-default-800">{{ $reportData['exams']->count() }}</h4>
            <p class="text-xs text-default-400 mt-1">{{ count($reportData['subjectRows']) }} subjects</p>
        </div>
    </div>
</div>

{{-- ── Results table ── --}}
<div class="card mb-5">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-4">Academic Performance — {{ $selectedYear->name }}</h5>
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-start">Subject</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-start">Exam</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-start">Date</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-center">Out Of</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-center">Obtained</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-start min-w-32">Score</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-center">Grade</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase text-default-500 text-start">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                @forelse($reportData['subjectRows'] as $subject => $rows)
                    @foreach($rows as $i => $row)
                    <tr class="hover:bg-default-50">
                        @if($i === 0)
                        <td class="px-4 py-3 font-semibold text-primary text-sm" rowspan="{{ count($rows) }}" style="vertical-align: top; padding-top: 14px;">
                            {{ $subject }}
                        </td>
                        @endif
                        <td class="px-4 py-3 text-sm text-default-700">{{ $row['exam'] }}</td>
                        <td class="px-4 py-3 text-sm text-default-500 whitespace-nowrap">{{ $row['date'] }}</td>
                        <td class="px-4 py-3 text-sm text-default-600 text-center">{{ $row['total_marks'] }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-default-800 text-center">
                            {{ $row['marks_obtained'] ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($row['pct'] !== null)
                            <div class="flex items-center gap-2">
                                <div class="bg-default-100 rounded-full h-1.5 flex-1">
                                    <div class="h-1.5 rounded-full {{ $row['pct'] >= 75 ? 'bg-success' : ($row['pct'] >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                         style="width:{{ min($row['pct'], 100) }}%"></div>
                                </div>
                                <span class="text-xs text-default-500 w-10">{{ $row['pct'] }}%</span>
                            </div>
                            @else
                            <span class="text-default-400 text-xs">Not taken</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($row['grade'])
                            @php
                                $gc = match(true) {
                                    str_starts_with($row['grade'],'A') => 'bg-success/10 text-success',
                                    str_starts_with($row['grade'],'B') => 'bg-info/10 text-info',
                                    str_starts_with($row['grade'],'C') => 'bg-warning/10 text-warning',
                                    str_starts_with($row['grade'],'D') => 'bg-orange-500/10 text-orange-500',
                                    default                            => 'bg-danger/10 text-danger',
                                };
                            @endphp
                            <span class="badge {{ $gc }} font-bold">{{ $row['grade'] }}</span>
                            @else
                            <span class="text-default-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-default-500 italic">{{ $row['remarks'] ?? '' }}</td>
                    </tr>
                    @endforeach
                @empty
                    <tr><td colspan="8" class="px-4 py-10 text-center text-default-400">No exam results for this academic year.</td></tr>
                @endforelse
                </tbody>
                @if(!empty($reportData['subjectRows']) && $reportData['totalMarks'] > 0)
                <tfoot class="bg-primary/5">
                    <tr>
                        <td colspan="3" class="px-4 py-3 font-bold text-sm text-default-700">Total</td>
                        <td class="px-4 py-3 font-bold text-sm text-default-800 text-center">{{ $reportData['totalMarks'] }}</td>
                        <td class="px-4 py-3 font-bold text-sm text-default-800 text-center">{{ $reportData['obtainedMarks'] }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="bg-default-100 rounded-full h-1.5 flex-1">
                                    <div class="h-1.5 rounded-full bg-primary" style="width:{{ min($reportData['overallPct'] ?? 0, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-primary w-10">{{ $reportData['overallPct'] }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $ogc = match(true) {
                                    str_starts_with($reportData['overallGrade'],'A') => 'bg-success/10 text-success',
                                    str_starts_with($reportData['overallGrade'],'B') => 'bg-info/10 text-info',
                                    str_starts_with($reportData['overallGrade'],'C') => 'bg-warning/10 text-warning',
                                    str_starts_with($reportData['overallGrade'],'D') => 'bg-orange-500/10 text-orange-500',
                                    default                                          => 'bg-danger/10 text-danger',
                                };
                            @endphp
                            <span class="badge {{ $ogc }} font-bold">{{ $reportData['overallGrade'] }}</span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- ── Attendance + Grade scale ── --}}
<div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
    <div class="card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Attendance — {{ $selectedYear->name }}</h5>
            <div class="grid grid-cols-2 gap-3 mb-4">
                @foreach([
                    ['label'=>'Total Days',  'val'=>$reportData['totalDays'],    'color'=>'default-200', 'text'=>'default-700'],
                    ['label'=>'Present',     'val'=>$reportData['presentCount'], 'color'=>'success/10',  'text'=>'success'],
                    ['label'=>'Absent',      'val'=>$reportData['absentCount'],  'color'=>'danger/10',   'text'=>'danger'],
                    ['label'=>'Late',        'val'=>$reportData['lateCount'],    'color'=>'warning/10',  'text'=>'warning'],
                ] as $stat)
                <div class="p-3 bg-{{ $stat['color'] }} rounded-lg text-center">
                    <p class="text-2xl font-bold text-{{ $stat['text'] }}">{{ $stat['val'] }}</p>
                    <p class="text-xs text-default-500 mt-0.5">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
            <div>
                <div class="flex justify-between text-xs text-default-500 mb-1">
                    <span>Attendance Rate</span>
                    <span class="font-semibold {{ $reportData['attendancePct'] >= 75 ? 'text-success' : ($reportData['attendancePct'] >= 50 ? 'text-warning' : 'text-danger') }}">
                        {{ $reportData['attendancePct'] }}%
                    </span>
                </div>
                <div class="bg-default-100 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $reportData['attendancePct'] >= 75 ? 'bg-success' : ($reportData['attendancePct'] >= 50 ? 'bg-warning' : 'bg-danger') }}"
                         style="width:{{ min($reportData['attendancePct'],100) }}%"></div>
                </div>
                @if($reportData['attendancePct'] < 75)
                <p class="text-xs text-danger mt-2 flex items-center gap-1"><i class="ti ti-alert-triangle"></i> Attendance below 75% threshold</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="text-base font-semibold text-default-700 mb-4">Grade Scale Reference</h5>
            <div class="space-y-2">
                @foreach([
                    ['grade'=>'A+','range'=>'95 – 100%','color'=>'success'],
                    ['grade'=>'A', 'range'=>'90 – 94%', 'color'=>'success'],
                    ['grade'=>'B+','range'=>'85 – 89%', 'color'=>'info'],
                    ['grade'=>'B', 'range'=>'80 – 84%', 'color'=>'info'],
                    ['grade'=>'C+','range'=>'75 – 79%', 'color'=>'warning'],
                    ['grade'=>'C', 'range'=>'70 – 74%', 'color'=>'warning'],
                    ['grade'=>'D', 'range'=>'60 – 69%', 'color'=>'orange-500'],
                    ['grade'=>'F', 'range'=>'Below 60%','color'=>'danger'],
                ] as $g)
                <div class="flex items-center justify-between py-1.5 border-b border-default-100 last:border-0">
                    <span class="badge bg-{{ $g['color'] }}/10 text-{{ $g['color'] }} font-bold w-10 text-center">{{ $g['grade'] }}</span>
                    <span class="text-sm text-default-600">{{ $g['range'] }}</span>
                    @if($reportData['overallGrade'] === $g['grade'])
                    <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">Current</span>
                    @else
                    <span></span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endif
@endsection
