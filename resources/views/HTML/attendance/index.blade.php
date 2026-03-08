@extends('HTML.layout')
@section('title', 'Attendance')
@section('page-title', 'Attendance')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Attendance</span>
@endsection
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-36">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Class</label>
                <select class="form-select w-full" name="class_id">
                    <option value="">Select class</option>
                    @foreach($classes as $class)<option value="{{ $class->id }}" {{ $selectedClass==$class->id?'selected':'' }}>{{ $class->full_name }}</option>@endforeach
                </select>
            </div>
            <div class="flex-1 min-w-36">
                <label class="block text-sm font-medium text-default-700 mb-1.5">Date</label>
                <input class="form-input w-full" type="date" name="date" value="{{ $selectedDate }}">
            </div>
            <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-filter text-sm"></i> Load</button>
        </form>
    </div>
</div>
@if($students->isNotEmpty())
<div class="card">
    <div class="card-body">
        <div class="flex items-center justify-between mb-5">
            <div><h5 class="text-base font-semibold text-default-700">Mark Attendance</h5><p class="text-xs text-default-400 mt-0.5">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</p></div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="markAll('present')" class="btn btn-sm bg-success/10 text-success gap-1"><i class="ti ti-check text-sm"></i> All Present</button>
                <button type="button" onclick="markAll('absent')" class="btn btn-sm bg-danger/10 text-danger gap-1"><i class="ti ti-x text-sm"></i> All Absent</button>
            </div>
        </div>
        <form method="POST" action="{{ route('attendance.store') }}">@csrf
            <input type="hidden" name="class_room_id" value="{{ $selectedClass }}">
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <div class="overflow-x-auto">
                <table class="table min-w-full">
                    <thead class="bg-default-100"><tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Student</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Status</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Note</th>
                    </tr></thead>
                    <tbody class="divide-y divide-default-200">
                        @foreach($students as $student)
                        @php $att=$attendances->get($student->id); @endphp
                        <tr class="hover:bg-default-50">
                            <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    @include('HTML.partials.avatar',['user'=>$student->user,'size'=>'size-8','textSize'=>'text-xs','color'=>'primary'])
                                    <p class="text-sm font-medium text-default-800">{{ $student->user->full_name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['present'=>'success','absent'=>'danger','late'=>'warning','excused'=>'info'] as $status=>$color)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]" value="{{ $status }}" class="hidden status-radio-{{ $student->id }}" {{ ($att?->status??'present')==$status?'checked':'' }} onchange="updateBadge({{ $student->id }})">
                                        <span class="status-badge-{{ $student->id }}-{{ $status }} badge text-xs px-3 py-1 cursor-pointer capitalize {{ ($att?->status??'present')==$status ? "bg-{$color}/20 text-{$color} ring-1 ring-{$color}/40" : 'bg-default-100 text-default-500' }}">{{ ucfirst($status) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-3"><input type="text" name="attendance[{{ $student->id }}][note]" class="form-input text-sm w-full" value="{{ $att?->note }}" placeholder="Optional note"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Save Attendance</button>
            </div>
        </form>
    </div>
</div>
@elseif($selectedClass)
<div class="card"><div class="card-body py-12 text-center text-default-400">No students found in this class.</div></div>
@else
<div class="card"><div class="card-body py-12 text-center">
    <div class="size-12 rounded-full bg-default-100 flex items-center justify-center mx-auto mb-3"><i class="size-6 text-default-400" data-lucide="clipboard-check"></i></div>
    <p class="text-default-500">Select a class and date to mark attendance.</p>
</div></div>
@endif
@push('scripts')
<script>
const statusColors={present:'bg-success/20 text-success ring-1 ring-success/40',absent:'bg-danger/20 text-danger ring-1 ring-danger/40',late:'bg-warning/20 text-warning ring-1 ring-warning/40',excused:'bg-info/20 text-info ring-1 ring-info/40'};
function updateBadge(studentId){document.querySelectorAll(`.status-radio-${studentId}`).forEach(r=>{const badge=document.querySelector(`.status-badge-${studentId}-${r.value}`);if(!badge)return;badge.className=`status-badge-${studentId}-${r.value} badge text-xs px-3 py-1 cursor-pointer capitalize ${r.checked?statusColors[r.value]:'bg-default-100 text-default-500'}`;});}
function markAll(status){document.querySelectorAll(`input[type=radio][value=${status}]`).forEach(r=>{r.checked=true;const id=r.name.match(/\[(\d+)\]/)[1];updateBadge(id);});}
</script>
@endpush
@endsection
