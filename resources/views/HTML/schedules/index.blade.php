@extends('HTML.layout')
@section('title', 'Schedule')
@section('page-title', 'Schedule')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Schedule</span>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 grid-cols-1 gap-5 mb-5">
    <div class="card border-l-4 border-l-primary">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Total Slots</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $totalSlots }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="size-6 text-primary" data-lucide="calendar-days"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Weekly schedule entries</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-info">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Classes Scheduled</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $scheduledClasses }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-info/10 flex items-center justify-center">
                    <i class="size-6 text-info" data-lucide="school"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Classes with at least 1 slot</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-success">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Teachers Active</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $scheduledTeachers }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-success/10 flex items-center justify-center">
                    <i class="size-6 text-success" data-lucide="user-check"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Teachers with scheduled lessons</p>
        </div>
    </div>
    <div class="card border-l-4 border-l-warning">
        <div class="card-body">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-default-500 font-medium text-sm">Today's Lessons</p>
                    <h4 class="text-2xl font-bold text-default-800 mt-1">{{ $todaySlots }}</h4>
                </div>
                <div class="size-12 rounded-xl bg-warning/10 flex items-center justify-center">
                    <i class="size-6 text-warning" data-lucide="clock"></i>
                </div>
            </div>
            <p class="text-xs text-default-400">Slots for {{ now()->format('l') }}</p>
        </div>
    </div>
</div>

{{-- Filter & Action Bar --}}
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('schedules.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="min-w-56">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">View by Class</label>
                <select class="form-input w-full" name="class" onchange="this.form.submit()">
                    <option value="">— Select a class —</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $filterClass == $class->id ? 'selected' : '' }}>{{ $class->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <span class="text-sm text-default-400 mb-2.5">or</span>
            </div>
            <div class="min-w-56">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">View by Teacher</label>
                <select class="form-input w-full" name="teacher" onchange="this.form.submit()">
                    <option value="">— Select a teacher —</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->user_id }}" {{ $filterTeacher == $teacher->user_id ? 'selected' : '' }}>
                        {{ $teacher->user->full_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if($filterClass || $filterTeacher)
            <a href="{{ route('schedules.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25">
                <i class="ti ti-x text-sm"></i> Clear
            </a>
            @endif
            <div class="ml-auto">
                <a href="{{ route('schedules.create') }}" class="btn bg-primary text-white gap-1.5 h-9.25">
                    <i class="ti ti-plus text-sm"></i> Add Slot
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Timetable Grid --}}
@if($timetable !== null)
@php
    $days     = \App\Models\Schedule::$dayNames;
    $periods  = \App\Models\Schedule::$periods;
    $subjectColors = ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899','#f97316','#6366f1','#14b8a6','#84cc16','#a855f7','#0ea5e9','#d946ef','#22c55e'];
    // Build subject → color map
    $subjectColorMap = [];
    $colorIdx = 0;
    foreach ($timetable as $slots) {
        foreach ($slots as $slot) {
            if ($slot && !isset($subjectColorMap[$slot->subject_id])) {
                $subjectColorMap[$slot->subject_id] = $subjectColors[$colorIdx % count($subjectColors)];
                $colorIdx++;
            }
        }
    }
@endphp

<div class="card mb-5">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            @if($filterClass)
            @php $cls = $classes->firstWhere('id', $filterClass); @endphp
            <div class="flex items-center gap-3">
                <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i class="size-5 text-primary" data-lucide="school"></i>
                </div>
                <div>
                    <h5 class="text-base font-semibold text-default-800">{{ $cls?->full_name }}</h5>
                    <p class="text-xs text-default-400">Weekly timetable</p>
                </div>
            </div>
            @elseif($filterTeacher)
            @php $tchr = $teachers->firstWhere('user_id', $filterTeacher); @endphp
            <div class="flex items-center gap-3">
                <div class="size-9 rounded-lg bg-success/10 flex items-center justify-center">
                    <i class="size-5 text-success" data-lucide="user-check"></i>
                </div>
                <div>
                    <h5 class="text-base font-semibold text-default-800">{{ $tchr?->user?->full_name }}</h5>
                    <p class="text-xs text-default-400">Weekly timetable</p>
                </div>
            </div>
            @endif
            <span class="badge bg-primary/10 text-primary text-xs">
                {{ $currentYear?->name ?? 'No active year' }}
            </span>
        </div>

        {{-- Desktop timetable --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="w-32 py-3 px-4 text-xs font-semibold uppercase text-default-500 bg-default-50 border border-default-200 rounded-tl-lg">Time</th>
                        @foreach($days as $dayNum => $dayName)
                        <th class="py-3 px-4 text-xs font-semibold uppercase text-default-500 bg-default-50 border border-default-200 {{ $dayNum == 5 ? 'rounded-tr-lg' : '' }} {{ now()->dayOfWeekIso == $dayNum ? 'bg-primary/5 text-primary' : '' }}">
                            <div class="flex flex-col items-center gap-0.5">
                                <span>{{ $dayName }}</span>
                                @if(now()->dayOfWeekIso == $dayNum && now()->dayOfWeekIso <= 5)
                                <span class="size-1.5 rounded-full bg-primary inline-block"></span>
                                @endif
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($periods as $startTime => $label)
                    @php
                        $isMorning = in_array($startTime, ['08:30', '10:15']);
                        $isAfternoon = in_array($startTime, ['14:00', '15:45']);
                    @endphp
                    @if($loop->index == 2)
                    <tr>
                        <td colspan="6" class="py-1.5 px-4 bg-default-50 border border-default-200 text-center">
                            <span class="text-xs text-default-400 font-medium">— Lunch Break (11:45 – 14:00) —</span>
                        </td>
                    </tr>
                    @endif
                    <tr class="{{ $loop->last ? '' : '' }}">
                        <td class="py-3 px-4 border border-default-200 bg-default-50">
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-bold text-default-700">{{ $startTime }}</span>
                                <span class="text-xs text-default-400">{{ explode(' – ', $label)[1] }}</span>
                                <span class="mt-1 text-xs px-1.5 py-0.5 rounded {{ $isMorning ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $isMorning ? 'AM' : 'PM' }}
                                </span>
                            </div>
                        </td>
                        @foreach($days as $dayNum => $dayName)
                        @php $slot = $timetable[$startTime][$dayNum] ?? null; @endphp
                        <td class="py-2 px-2 border border-default-200 align-top min-w-36 {{ now()->dayOfWeekIso == $dayNum ? 'bg-primary/5' : '' }}">
                            @if($slot)
                            @php $color = $subjectColorMap[$slot->subject_id] ?? '#3b82f6'; @endphp
                            <div class="rounded-lg p-2.5 h-full" style="background-color: {{ $color }}18; border-left: 3px solid {{ $color }};">
                                <p class="text-xs font-bold text-default-800 leading-tight mb-1">{{ $slot->subject->name }}</p>
                                @if($filterClass)
                                <div class="flex items-center gap-1 mt-1">
                                    <div class="size-4 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background-color: {{ $color }}; font-size: 9px;">
                                        {{ strtoupper(substr($slot->teacher->first_name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-default-600 truncate">{{ $slot->teacher->last_name }}</span>
                                </div>
                                @elseif($filterTeacher)
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="size-3 text-default-400" data-lucide="school"></i>
                                    <span class="text-xs text-default-600 truncate">{{ $slot->classRoom->full_name }}</span>
                                </div>
                                @endif
                                @if($slot->room)
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="size-3 text-default-400" data-lucide="map-pin"></i>
                                    <span class="text-xs text-default-400">{{ $slot->room }}</span>
                                </div>
                                @endif
                                <div class="flex gap-1 mt-2">
                                    <a href="{{ route('schedules.edit', $slot) }}" class="inline-flex items-center justify-center size-5 rounded bg-white/70 hover:bg-white transition-colors" title="Edit">
                                        <i class="ti ti-edit text-xs text-default-600"></i>
                                    </a>
                                    <button type="button" onclick="openDeleteModal('{{ route('schedules.destroy', $slot) }}', 'Remove Slot', 'Remove {{ addslashes($slot->subject->name) }} from {{ $slot->day_name }} {{ $slot->start_time_short }}?')" class="inline-flex items-center justify-center size-5 rounded bg-white/70 hover:bg-danger/10 transition-colors" title="Remove">
                                        <i class="ti ti-trash text-xs text-danger"></i>
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="rounded-lg p-2 h-16 border-2 border-dashed border-default-100 flex items-center justify-center group">
                                <a href="{{ route('schedules.create', ['class' => $filterClass, 'teacher' => $filterTeacher, 'day' => $dayNum, 'start' => $startTime]) }}" class="text-xs text-default-300 group-hover:text-primary transition-colors">
                                    <i class="ti ti-plus text-xs"></i>
                                </a>
                            </div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile: list view --}}
        <div class="md:hidden space-y-3">
            @foreach($days as $dayNum => $dayName)
            @php
                $daySlots = collect($timetable)->map(fn($row) => $row[$dayNum] ?? null)->filter()->sortBy('start_time');
            @endphp
            @if($daySlots->isNotEmpty())
            <div class="border border-default-200 rounded-lg overflow-hidden">
                <div class="px-4 py-2 bg-default-50 flex items-center gap-2">
                    <span class="text-sm font-semibold text-default-700">{{ $dayName }}</span>
                    @if(now()->dayOfWeekIso == $dayNum && now()->dayOfWeekIso <= 5)
                    <span class="badge bg-primary/10 text-primary text-xs">Today</span>
                    @endif
                </div>
                @foreach($daySlots as $slot)
                @php $color = $subjectColorMap[$slot->subject_id] ?? '#3b82f6'; @endphp
                <div class="px-4 py-3 flex items-start gap-3 border-t border-default-100">
                    <div class="text-center min-w-14">
                        <span class="text-xs font-bold text-default-700 block">{{ $slot->start_time_short }}</span>
                        <span class="text-xs text-default-400">{{ $slot->end_time_short }}</span>
                    </div>
                    <div class="flex-1 rounded-lg p-2.5" style="background-color: {{ $color }}15; border-left: 3px solid {{ $color }};">
                        <p class="text-sm font-semibold text-default-800">{{ $slot->subject->name }}</p>
                        <p class="text-xs text-default-500">{{ $slot->teacher->full_name }} @if($slot->room)· {{ $slot->room }}@endif</p>
                    </div>
                    <a href="{{ route('schedules.edit', $slot) }}" class="btn btn-sm bg-default-150 size-7 p-0 flex items-center justify-center mt-1">
                        <i class="ti ti-edit text-xs"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
            @endforeach
        </div>

        {{-- Legend --}}
        @if(count($subjectColorMap) > 0)
        <div class="mt-5 pt-4 border-t border-default-100">
            <p class="text-xs font-semibold text-default-500 uppercase mb-2">Legend</p>
            <div class="flex flex-wrap gap-3">
                @foreach($timetable as $slots)
                    @foreach($slots as $slot)
                        @if($slot && isset($subjectColorMap[$slot->subject_id]))
                        @php
                            $color = $subjectColorMap[$slot->subject_id];
                            $sid = $slot->subject_id;
                        @endphp
                        @if(!isset($legendShown[$sid]))
                        @php $legendShown[$sid] = true; @endphp
                        <div class="flex items-center gap-1.5">
                            <span class="size-2.5 rounded-full inline-block flex-shrink-0" style="background-color: {{ $color }};"></span>
                            <span class="text-xs text-default-600">{{ $slot->subject->code ?? $slot->subject->name }}</span>
                        </div>
                        @endif
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@else
{{-- Empty state: no filter selected --}}
<div class="card mb-5">
    <div class="card-body py-14 flex flex-col items-center text-center">
        <div class="size-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-4">
            <i class="size-8 text-primary" data-lucide="calendar-days"></i>
        </div>
        <h5 class="text-base font-semibold text-default-700 mb-1">Select a class or teacher to view the timetable</h5>
        <p class="text-sm text-default-400 mb-5">Choose from the filters above to display the weekly schedule grid.</p>
        <a href="{{ route('schedules.create') }}" class="btn bg-primary text-white gap-1.5">
            <i class="ti ti-plus text-sm"></i> Add Schedule Slot
        </a>
    </div>
</div>
@endif

{{-- All Schedules Table --}}
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <h5 class="text-base font-semibold text-default-700">All Schedule Slots</h5>
            <a href="{{ route('schedules.create') }}" class="btn bg-primary text-white btn-sm">
                <i class="ti ti-plus mr-1"></i> Add Slot
            </a>
        </div>
        @php
            $allSlots = \App\Models\Schedule::where('school_id', auth()->user()->school_id)
                ->with(['classRoom', 'subject', 'teacher'])
                ->orderBy('day_of_week')->orderBy('start_time')->orderBy('class_id')
                ->get();
        @endphp
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Day</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Time</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Class</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Subject</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Teacher</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Room</th>
                        <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wider text-default-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($allSlots as $slot)
                    @php
                        $dayColors = [1=>'primary',2=>'info',3=>'success',4=>'warning',5=>'danger'];
                        $dc = $dayColors[$slot->day_of_week] ?? 'default';
                    @endphp
                    <tr class="hover:bg-default-50 {{ now()->dayOfWeekIso == $slot->day_of_week && now()->dayOfWeekIso <= 5 ? 'bg-primary/5' : '' }}">
                        <td class="px-4 py-3">
                            <span class="badge bg-{{ $dc }}/10 text-{{ $dc }} font-medium">{{ $slot->day_name }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm font-mono text-default-600">{{ $slot->time_range }}</td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-medium text-default-800">{{ $slot->classRoom->full_name }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="size-6 rounded bg-info/10 flex items-center justify-center flex-shrink-0">
                                    <i class="size-3.5 text-info" data-lucide="book-open"></i>
                                </div>
                                <span class="text-sm text-default-700">{{ $slot->subject->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @include('HTML.partials.avatar', ['user' => $slot->teacher, 'size' => 'size-7', 'textSize' => 'text-xs', 'color' => 'success'])
                            <span class="text-sm text-default-700 ml-2">{{ $slot->teacher->full_name }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-default-500">{{ $slot->room ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('schedules.edit', $slot) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center" title="Edit">
                                    <i class="ti ti-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('schedules.destroy', $slot) }}', 'Remove Slot', 'Remove {{ addslashes($slot->subject->name) }} on {{ $slot->day_name }} at {{ $slot->start_time_short }}?')" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center" title="Remove">
                                    <i class="ti ti-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-default-400">No schedule slots found. <a href="{{ route('schedules.create') }}" class="text-primary font-medium">Add the first one →</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
