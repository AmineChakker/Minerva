@extends('HTML.layout')
@section('title', 'Exam Results')
@section('page-title', 'Exam Results')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('exams.index') }}">Exams</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">{{ $exam->name }}</span>
@endsection
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h5 class="text-lg font-bold text-default-800">{{ $exam->name }}</h5>
                <div class="flex flex-wrap gap-4 mt-2">
                    <span class="text-sm text-default-500"><i class="ti ti-school mr-1"></i>{{ $exam->classRoom->full_name }}</span>
                    <span class="text-sm text-default-500"><i class="ti ti-book mr-1"></i>{{ $exam->subject->name }}</span>
                    <span class="text-sm text-default-500"><i class="ti ti-calendar mr-1"></i>{{ $exam->exam_date->format('M d, Y') }}</span>
                    <span class="text-sm text-default-500"><i class="ti ti-star mr-1"></i>Total: {{ $exam->total_marks }} marks</span>
                </div>
            </div>
            <a href="{{ route('exams.index') }}" class="btn bg-default-150 btn-sm gap-1.5"><i class="ti ti-arrow-left text-sm"></i> Back</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-5">Enter Results</h5>
        @if($students->isEmpty())
        <p class="text-default-400 text-sm text-center py-8">No students in this class.</p>
        @else
        <form action="{{ route('exam-results.store', $exam) }}" method="POST">@csrf
            <div class="overflow-x-auto">
                <table class="table min-w-full">
                    <thead class="bg-default-100"><tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">#</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Student</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Marks (/ {{ $exam->total_marks }})</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Grade</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Progress</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Remarks</th>
                    </tr></thead>
                    <tbody class="divide-y divide-default-200">
                        @foreach($students as $student)
                        @php $result=$exam->results->firstWhere('student_id',$student->id); $pct=$result?round(($result->marks_obtained/$exam->total_marks)*100):0; @endphp
                        <tr class="hover:bg-default-50">
                            <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3"><div class="flex items-center gap-3">@include('HTML.partials.avatar',['user'=>$student->user,'size'=>'size-8','textSize'=>'text-xs','color'=>'primary'])<p class="text-sm font-medium text-default-800">{{ $student->user->full_name }}</p></div></td>
                            <td class="px-6 py-3"><input type="number" name="results[{{ $student->id }}][marks_obtained]" class="form-input w-24 text-sm" data-total="{{ $exam->total_marks }}" data-student="{{ $student->id }}" value="{{ $result?->marks_obtained??'' }}" min="0" max="{{ $exam->total_marks }}" oninput="updateProgress(this)"></td>
                            <td class="px-6 py-3"><input type="text" name="results[{{ $student->id }}][grade]" class="form-input w-16 text-sm text-center uppercase grade-input-{{ $student->id }}" value="{{ $result?->grade??'' }}" maxlength="3" placeholder="A+"></td>
                            <td class="px-6 py-3 w-32"><div class="flex items-center gap-2"><div class="flex-1 bg-default-100 rounded-full h-2"><div class="progress-bar-{{ $student->id }} h-2 rounded-full {{ $pct>=75?'bg-success':($pct>=50?'bg-warning':'bg-danger') }}" style="width:{{ $pct }}%"></div></div><span class="text-xs text-default-500 w-8 progress-pct-{{ $student->id }}">{{ $pct }}%</span></div></td>
                            <td class="px-6 py-3"><input type="text" name="results[{{ $student->id }}][remarks]" class="form-input w-32 text-sm" value="{{ $result?->remarks??'' }}" placeholder="Optional"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5 pt-4 border-t border-default-100"><button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Save Results</button></div>
        </form>
        @endif
    </div>
</div>
@push('scripts')
<script>
function updateProgress(input){
    const total=parseInt(input.dataset.total),sid=input.dataset.student,val=parseInt(input.value)||0;
    const pct=total>0?Math.min(100,Math.round((val/total)*100)):0;
    const bar=document.querySelector(`.progress-bar-${sid}`),pctEl=document.querySelector(`.progress-pct-${sid}`);
    if(bar){bar.style.width=pct+'%';bar.className=bar.className.replace(/bg-(success|warning|danger)/,'');bar.classList.add(pct>=75?'bg-success':pct>=50?'bg-warning':'bg-danger');}
    if(pctEl)pctEl.textContent=pct+'%';
    const gi=document.querySelector(`.grade-input-${sid}`);
    if(gi&&!gi.value)gi.value=pct>=90?'A+':pct>=80?'A':pct>=70?'B':pct>=60?'C':pct>=50?'D':'F';
}
</script>
@endpush
@endsection
