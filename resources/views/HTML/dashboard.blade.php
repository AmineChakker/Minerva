@extends('HTML.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Dashboard</span>
@endsection

@section('content')

{{-- Stats Row 1 --}}
<div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5 mb-5">
    <div class="card border-l-4 border-l-primary"><div class="card-body"><div class="flex items-center justify-between mb-3"><div><p class="text-default-500 font-medium text-sm">Total Students</p><h4 class="text-2xl font-bold text-default-800 mt-1">{{ number_format($stats['students']) }}</h4></div><div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center"><i class="size-6 text-primary" data-lucide="graduation-cap"></i></div></div><p class="text-xs text-default-400">Enrolled this academic year</p></div></div>
    <div class="card border-l-4 border-l-success"><div class="card-body"><div class="flex items-center justify-between mb-3"><div><p class="text-default-500 font-medium text-sm">Total Teachers</p><h4 class="text-2xl font-bold text-default-800 mt-1">{{ number_format($stats['teachers']) }}</h4></div><div class="size-12 rounded-xl bg-success/10 flex items-center justify-center"><i class="size-6 text-success" data-lucide="user-check"></i></div></div><p class="text-xs text-default-400">{{ $teacherStatusData['active'] }} active · {{ $teacherStatusData['on_leave'] }} on leave</p></div></div>
    <div class="card border-l-4 border-l-warning"><div class="card-body"><div class="flex items-center justify-between mb-3"><div><p class="text-default-500 font-medium text-sm">Total Classes</p><h4 class="text-2xl font-bold text-default-800 mt-1">{{ number_format($stats['classes']) }}</h4></div><div class="size-12 rounded-xl bg-warning/10 flex items-center justify-center"><i class="size-6 text-warning" data-lucide="school"></i></div></div><p class="text-xs text-default-400">Academic classes active</p></div></div>
</div>

{{-- Stats Row 2 --}}
<div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5 mb-6">
    <div class="card border-l-4 border-l-info"><div class="card-body"><div class="flex items-center justify-between mb-3"><div><p class="text-default-500 font-medium text-sm">Total Subjects</p><h4 class="text-2xl font-bold text-default-800 mt-1">{{ number_format($stats['subjects']) }}</h4></div><div class="size-12 rounded-xl bg-info/10 flex items-center justify-center"><i class="size-6 text-info" data-lucide="book-open"></i></div></div><p class="text-xs text-default-400">Available subjects</p></div></div>
    <div class="card border-l-4 border-l-violet-500"><div class="card-body"><div class="flex items-center justify-between mb-3"><div><p class="text-default-500 font-medium text-sm">Total Parents</p><h4 class="text-2xl font-bold text-default-800 mt-1">{{ number_format($stats['parents']) }}</h4></div><div class="size-12 rounded-xl bg-violet-500/10 flex items-center justify-center"><i class="size-6 text-violet-500" data-lucide="users"></i></div></div><p class="text-xs text-default-400">Registered guardians</p></div></div>
    <div class="card border-l-4 border-l-{{ $currentYear ? 'success' : 'default-300' }}"><div class="card-body"><div class="flex items-center justify-between mb-3"><div><p class="text-default-500 font-medium text-sm">Academic Year</p><h4 class="text-lg font-bold text-default-800 mt-1 truncate">{{ $currentYear ? $currentYear->name : 'Not Set' }}</h4></div><div class="size-12 rounded-xl bg-{{ $currentYear ? 'success' : 'default-100' }}/10 flex items-center justify-center"><i class="size-6 text-{{ $currentYear ? 'success' : 'default-400' }}" data-lucide="calendar-range"></i></div></div>@if($currentYear)<p class="text-xs text-default-400">{{ $currentYear->start_date->format('M Y') }} – {{ $currentYear->end_date->format('M Y') }}</p>@else<p class="text-xs text-warning">No current year set</p>@endif</div></div>
</div>

{{-- Charts Row 1: Enrollment + Gender --}}
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mb-6">
    <div class="lg:col-span-2 card"><div class="card-body"><div class="flex items-center justify-between mb-4"><h5 class="text-base font-semibold text-default-700">Enrollment Trend</h5><span class="badge bg-primary/10 text-primary text-xs">Last 6 months</span></div><div id="enrollmentTrendChart"></div></div></div>
    <div class="card"><div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-4">Gender Distribution</h5>
        <div id="genderDonutChart"></div>
        <div class="flex justify-center gap-6 mt-4">
            <div class="flex items-center gap-2"><span class="size-2.5 rounded-full bg-primary inline-block"></span><span class="text-sm text-default-500">Male ({{ $maleCount }})</span></div>
            <div class="flex items-center gap-2"><span class="size-2.5 rounded-full bg-pink-400 inline-block"></span><span class="text-sm text-default-500">Female ({{ $femaleCount }})</span></div>
            @if($otherCount > 0)<div class="flex items-center gap-2"><span class="size-2.5 rounded-full bg-default-300 inline-block"></span><span class="text-sm text-default-500">Other ({{ $otherCount }})</span></div>@endif
        </div>
    </div></div>
</div>

{{-- Charts Row 2: Class Bar + Teacher Radial + Attendance --}}
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5 mb-6">
    <div class="card"><div class="card-body"><h5 class="text-base font-semibold text-default-700 mb-4">Students per Class</h5><div id="classBarChart"></div></div></div>
    <div class="card"><div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-4">Teacher Status</h5>
        <div id="teacherRadialChart"></div>
        <div class="space-y-2 mt-2">
            <div class="flex items-center justify-between text-sm"><div class="flex items-center gap-2"><span class="size-2.5 rounded-full bg-success inline-block"></span><span class="text-default-500">Active</span></div><span class="font-semibold text-default-700">{{ $teacherStatusData['active'] }}</span></div>
            <div class="flex items-center justify-between text-sm"><div class="flex items-center gap-2"><span class="size-2.5 rounded-full bg-warning inline-block"></span><span class="text-default-500">On Leave</span></div><span class="font-semibold text-default-700">{{ $teacherStatusData['on_leave'] }}</span></div>
            <div class="flex items-center justify-between text-sm"><div class="flex items-center gap-2"><span class="size-2.5 rounded-full bg-danger inline-block"></span><span class="text-default-500">Inactive</span></div><span class="font-semibold text-default-700">{{ $teacherStatusData['inactive'] }}</span></div>
        </div>
    </div></div>
    <div class="card"><div class="card-body">
        <h5 class="text-base font-semibold text-default-700 mb-4">Attendance This Month</h5>
        @if($attendanceRate !== null)
            <div id="attendanceRadialChart"></div>
            <div class="flex justify-center mt-2"><div class="text-center"><p class="text-lg font-bold text-success">{{ $attendanceRate }}%</p><p class="text-xs text-default-400">Present</p></div></div>
        @else
            <div class="flex flex-col items-center justify-center py-8 text-center"><div class="size-12 rounded-full bg-default-100 flex items-center justify-center mb-3"><i class="size-6 text-default-400" data-lucide="clipboard-check"></i></div><p class="text-sm text-default-400">No attendance data yet</p>@if(auth()->user()->isAdmin()||auth()->user()->isTeacher())<a href="{{ route('attendance.index') }}" class="mt-3 text-xs text-primary font-medium">Mark attendance →</a>@endif</div>
        @endif
    </div></div>
</div>

{{-- Bottom: Recent Students + Announcements --}}
<div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
    <div class="lg:col-span-2 card"><div class="card-body">
        <div class="flex items-center justify-between mb-4"><h5 class="text-base font-semibold text-default-700">Recent Students</h5>@if(auth()->user()->isAdmin()||auth()->user()->isTeacher())<a href="{{ route('students.index') }}" class="text-sm text-primary font-medium">View all</a>@endif</div>
        <div class="space-y-3">
            @forelse($recentStudents as $student)
            <div class="flex items-center gap-3 p-3 hover:bg-default-50 rounded-lg transition-colors">
                @include('HTML.partials.avatar',['user'=>$student->user,'size'=>'size-9','textSize'=>'text-sm','color'=>'primary'])
                <div class="flex-1 min-w-0"><p class="text-sm font-medium text-default-800 truncate">{{ $student->user->full_name }}</p><p class="text-xs text-default-400">{{ $student->classRoom->full_name ?? 'No class' }}</p></div>
                <span class="badge bg-default-100 text-default-500 text-xs flex-shrink-0">{{ $student->user->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-default-400 text-sm text-center py-4">No students yet.</p>
            @endforelse
        </div>
        @if(auth()->user()->isAdmin()||auth()->user()->isTeacher())
        <div class="grid grid-cols-3 gap-3 mt-6 pt-4 border-t border-default-100">
            <a href="{{ route('students.create') }}" class="flex flex-col items-center gap-2 p-3 bg-primary/5 hover:bg-primary/10 rounded-lg transition-colors text-center"><i class="size-5 text-primary" data-lucide="user-plus"></i><span class="text-xs font-medium text-default-600">Add Student</span></a>
            <a href="{{ route('teachers.create') }}" class="flex flex-col items-center gap-2 p-3 bg-success/5 hover:bg-success/10 rounded-lg transition-colors text-center"><i class="size-5 text-success" data-lucide="user-check"></i><span class="text-xs font-medium text-default-600">Add Teacher</span></a>
            <a href="{{ route('classes.create') }}" class="flex flex-col items-center gap-2 p-3 bg-warning/5 hover:bg-warning/10 rounded-lg transition-colors text-center"><i class="size-5 text-warning" data-lucide="school"></i><span class="text-xs font-medium text-default-600">Add Class</span></a>
        </div>
        @endif
    </div></div>

    <div class="card"><div class="card-body">
        <div class="flex items-center justify-between mb-4"><h5 class="text-base font-semibold text-default-700">Announcements</h5>@if(auth()->user()->isAdmin())<a href="{{ route('announcements.index') }}" class="text-sm text-primary font-medium">View all</a>@endif</div>
        <div class="space-y-3">
            @forelse($recentAnnouncements as $ann)
            @php $bm=['info'=>'border-l-info','warning'=>'border-l-warning','success'=>'border-l-success','danger'=>'border-l-danger'];$bgm=['info'=>'bg-info/5','warning'=>'bg-warning/5','success'=>'bg-success/5','danger'=>'bg-danger/5']; @endphp
            <div class="p-3 border-l-4 {{ $bm[$ann->type]??'border-l-primary' }} {{ $bgm[$ann->type]??'bg-primary/5' }} rounded-r-lg">
                <p class="text-sm font-semibold text-default-800 mb-1">{{ $ann->title }}</p>
                <p class="text-xs text-default-500 line-clamp-2">{{ $ann->content }}</p>
                <p class="text-xs text-default-400 mt-1">{{ $ann->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-6 text-center"><div class="size-10 rounded-full bg-default-100 flex items-center justify-center mb-2"><i class="size-5 text-default-400" data-lucide="megaphone"></i></div><p class="text-sm text-default-400">No announcements yet.</p>@if(auth()->user()->isAdmin())<a href="{{ route('announcements.create') }}" class="mt-2 text-xs text-primary font-medium">Create one →</a>@endif</div>
            @endforelse
        </div>
    </div></div>
</div>

@endsection

@push('scripts')
<script type="module">
import { u as ApexCharts } from '/assets/apexcharts.esm-DPbJ6jlt.js';
document.addEventListener('DOMContentLoaded', function () {

    const monthlyData = @json($monthlyEnrollment);
    new ApexCharts(document.querySelector('#enrollmentTrendChart'),{series:[{name:'New Students',data:monthlyData.map(d=>d.count)}],chart:{type:'area',height:250,toolbar:{show:false},parentHeightOffset:0},xaxis:{categories:monthlyData.map(d=>d.month),axisBorder:{show:false}},yaxis:{labels:{formatter:v=>Math.round(v)},min:0},colors:['#2b7fff'],fill:{type:'gradient',gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.05}},stroke:{curve:'smooth',width:2},dataLabels:{enabled:false},grid:{show:true,padding:{top:-10,right:0,bottom:-10}},tooltip:{y:{formatter:v=>v+' student'+(v!==1?'s':'')}}}).render();

    const male={{ $maleCount }},female={{ $femaleCount }},other={{ $otherCount }};
    const gS=other>0?[male,female,other]:[male,female],gL=other>0?['Male','Female','Other']:['Male','Female'],gC=other>0?['#2b7fff','#f472b6','#94a3b8']:['#2b7fff','#f472b6'];
    new ApexCharts(document.querySelector('#genderDonutChart'),{series:gS,chart:{type:'donut',height:200,parentHeightOffset:0},labels:gL,colors:gC,legend:{show:false},dataLabels:{enabled:false},plotOptions:{pie:{donut:{size:'70%',labels:{show:true,total:{show:true,label:'Students',formatter:()=>{{ $stats['students'] }}}}}}},stroke:{width:0}}).render();

    const classData = @json($classEnrollment);
    if(classData.length>0){new ApexCharts(document.querySelector('#classBarChart'),{series:[{name:'Students',data:classData.map(c=>c.count)}],chart:{type:'bar',height:Math.max(200,classData.length*48),toolbar:{show:false},parentHeightOffset:0},plotOptions:{bar:{horizontal:true,borderRadius:4,barHeight:'60%'}},xaxis:{categories:classData.map(c=>c.name),labels:{formatter:v=>Math.round(v)}},colors:['#2b7fff'],dataLabels:{enabled:true},grid:{show:true,padding:{top:-10,bottom:-10}},tooltip:{y:{formatter:v=>v+' student'+(v!==1?'s':'')}}}).render();}
    else{document.querySelector('#classBarChart').innerHTML='<p class="text-default-400 text-sm text-center py-8">No class data yet.</p>';}

    const tA={{ $teacherStatusData['active'] }},tL={{ $teacherStatusData['on_leave'] }},tI={{ $teacherStatusData['inactive'] }},tT=tA+tL+tI;
    if(tT>0){new ApexCharts(document.querySelector('#teacherRadialChart'),{series:[Math.round((tA/tT)*100),Math.round((tL/tT)*100),Math.round((tI/tT)*100)],chart:{type:'radialBar',height:200,parentHeightOffset:0},plotOptions:{radialBar:{offsetY:0,startAngle:-120,endAngle:120,hollow:{size:'40%'},dataLabels:{name:{fontSize:'11px'},value:{fontSize:'13px',formatter:v=>v+'%'},total:{show:true,label:'Teachers',formatter:()=>tT}},track:{background:'rgba(0,0,0,0.05)'}}},labels:['Active','On Leave','Inactive'],colors:['#22c55e','#f59e0b','#ef4444'],legend:{show:false}}).render();}
    else{document.querySelector('#teacherRadialChart').innerHTML='<p class="text-default-400 text-sm text-center py-8">No teacher data.</p>';}

    @if($attendanceRate !== null)
    new ApexCharts(document.querySelector('#attendanceRadialChart'),{series:[{{ $attendanceRate }}],chart:{type:'radialBar',height:180,parentHeightOffset:0},plotOptions:{radialBar:{hollow:{size:'55%'},dataLabels:{name:{show:false},value:{fontSize:'22px',fontWeight:700,formatter:v=>v+'%'}}}},colors:['#22c55e'],stroke:{lineCap:'round'}}).render();
    @endif
});
</script>
@endpush
