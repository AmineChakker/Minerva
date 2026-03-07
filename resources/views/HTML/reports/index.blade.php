@extends('HTML.layout')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('content')
<div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-6">
    <div class="card border-l-4 border-l-primary"><div class="card-body"><p class="text-xs text-default-400 mb-1">Total Students</p><h4 class="text-2xl font-bold text-default-800">{{ number_format($totalStudents) }}</h4><p class="text-xs mt-1"><span class="text-success">{{ $activeStudents }}</span> active</p></div></div>
    <div class="card border-l-4 border-l-success"><div class="card-body"><p class="text-xs text-default-400 mb-1">Total Teachers</p><h4 class="text-2xl font-bold text-default-800">{{ number_format($totalTeachers) }}</h4><p class="text-xs mt-1"><span class="text-success">{{ $activeTeachers }}</span> active</p></div></div>
    <div class="card border-l-4 border-l-warning"><div class="card-body"><p class="text-xs text-default-400 mb-1">Fee Collection</p><h4 class="text-2xl font-bold text-default-800">${{ number_format($collectedFees,0) }}</h4><p class="text-xs mt-1 text-default-400">of ${{ number_format($totalFees,0) }}</p></div></div>
    <div class="card border-l-4 border-l-{{ $attendanceRate>=80?'success':($attendanceRate>=60?'warning':'danger') }}"><div class="card-body"><p class="text-xs text-default-400 mb-1">Attendance Rate</p><h4 class="text-2xl font-bold text-default-800">{{ $attendanceRate }}%</h4><p class="text-xs mt-1 text-default-400">{{ $presentCount }} present · {{ $absentCount }} absent</p></div></div>
</div>
<div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-6">
    <div class="card"><div class="card-body"><h5 class="text-base font-semibold text-default-700 mb-4">Students per Class</h5><div id="reportClassChart"></div></div></div>
    <div class="card"><div class="card-body"><h5 class="text-base font-semibold text-default-700 mb-4">Fee Collection Trend</h5><div id="feeCollectionChart"></div></div></div>
</div>
<div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
    <div class="card"><div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-4">Class Enrollment Details</h5>
        <div class="space-y-3">
            @php $max=$classStats->max('students_count'); @endphp
            @foreach($classStats->take(8) as $class)
            <div><div class="flex items-center justify-between mb-1"><p class="text-sm text-default-700 truncate">{{ $class->full_name }}</p><span class="text-sm font-semibold text-default-800 ml-2">{{ $class->students_count }}</span></div><div class="bg-default-100 rounded-full h-1.5"><div class="bg-primary h-1.5 rounded-full" style="width:{{ $max>0?round(($class->students_count/$max)*100):0 }}%"></div></div></div>
            @endforeach
            @if($classStats->isEmpty())<p class="text-default-400 text-sm text-center py-4">No class data.</p>@endif
        </div>
    </div></div>
    <div class="card"><div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-4">Fee Summary</h5>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-success/5 rounded-lg"><div class="flex items-center gap-3"><div class="size-9 rounded-lg bg-success/10 flex items-center justify-center"><i class="ti ti-circle-check text-success text-base"></i></div><p class="text-sm font-medium text-default-700">Collected</p></div><p class="text-base font-bold text-success">${{ number_format($collectedFees,2) }}</p></div>
            <div class="flex items-center justify-between p-3 bg-danger/5 rounded-lg"><div class="flex items-center gap-3"><div class="size-9 rounded-lg bg-danger/10 flex items-center justify-center"><i class="ti ti-alert-circle text-danger text-base"></i></div><p class="text-sm font-medium text-default-700">Pending</p></div><p class="text-base font-bold text-danger">${{ number_format($pendingFees,2) }}</p></div>
            <div class="flex items-center justify-between p-3 bg-default-50 rounded-lg"><div class="flex items-center gap-3"><div class="size-9 rounded-lg bg-default-100 flex items-center justify-center"><i class="ti ti-report-money text-default-500 text-base"></i></div><p class="text-sm font-medium text-default-700">Total Issued</p></div><p class="text-base font-bold text-default-700">${{ number_format($totalFees,2) }}</p></div>
            @if($totalFees>0)<div class="pt-2"><div class="flex justify-between text-xs text-default-400 mb-1"><span>Collection rate</span><span>{{ round(($collectedFees/$totalFees)*100,1) }}%</span></div><div class="bg-default-100 rounded-full h-2"><div class="bg-success h-2 rounded-full" style="width:{{ round(($collectedFees/$totalFees)*100) }}%"></div></div></div>@endif
        </div>
    </div></div>
</div>
@push('scripts')
<script type="module">
import { u as ApexCharts } from '/assets/apexcharts.esm-DPbJ6jlt.js';
document.addEventListener('DOMContentLoaded', function () {
    const classData = @json($classStats->map(fn($c)=>['name'=>$c->full_name,'count'=>$c->students_count]));
    if(classData.length>0){new ApexCharts(document.querySelector('#reportClassChart'),{series:[{name:'Students',data:classData.map(c=>c.count)}],chart:{type:'bar',height:250,toolbar:{show:false}},plotOptions:{bar:{horizontal:false,borderRadius:4,columnWidth:'55%'}},xaxis:{categories:classData.map(c=>c.name)},colors:['#2b7fff'],dataLabels:{enabled:false},grid:{show:true}}).render();}
    else{document.querySelector('#reportClassChart').innerHTML='<p class="text-default-400 text-sm text-center py-8">No data yet.</p>';}
    const feeData = @json($feeCollection);
    new ApexCharts(document.querySelector('#feeCollectionChart'),{series:[{name:'Collected ($)',data:feeData.map(f=>f.amount)}],chart:{type:'line',height:250,toolbar:{show:false}},xaxis:{categories:feeData.map(f=>f.month)},colors:['#22c55e'],stroke:{curve:'smooth',width:2},fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.3,opacityTo:0.05}},dataLabels:{enabled:false},grid:{show:true},tooltip:{y:{formatter:v=>'$'+v.toLocaleString()}}}).render();
});
</script>
@endpush
@endsection
