@extends('HTML.layout')
@section('title', 'Fee Management')
@section('page-title', 'Fee Management')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Fee Management</span>
@endsection
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('fees.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-36"><label class="block text-sm font-medium text-default-700 mb-1.5">Status</label>
                <select class="form-select w-full" name="status"><option value="">All</option><option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Unpaid</option><option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option><option value="partial" {{ request('status')=='partial'?'selected':'' }}>Partial</option><option value="waived" {{ request('status')=='waived'?'selected':'' }}>Waived</option></select>
            </div>
            <div class="flex-1 min-w-36"><label class="block text-sm font-medium text-default-700 mb-1.5">Academic Year</label>
                <select class="form-select w-full" name="academic_year_id"><option value="">All Years</option>@foreach($academicYears as $y)<option value="{{ $y->id }}" {{ request('academic_year_id')==$y->id?'selected':'' }}>{{ $y->name }}</option>@endforeach</select>
            </div>
            <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-filter text-sm"></i> Filter</button>
            <a href="{{ route('fees.create') }}" class="btn bg-success text-white gap-1.5"><i class="ti ti-plus text-sm"></i> Add Fee</a>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100"><tr>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">#</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Student</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Fee Title</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Amount</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Due Date</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Status</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($fees as $fee)
                    @php $bc=match($fee->status){'paid'=>'bg-success/10 text-success','partial'=>'bg-warning/10 text-warning','waived'=>'bg-info/10 text-info',default=>'bg-danger/10 text-danger'}; @endphp
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3"><div class="flex items-center gap-3">@include('HTML.partials.avatar',['user'=>$fee->student->user,'size'=>'size-8','textSize'=>'text-xs','color'=>'primary'])<p class="text-sm font-medium text-default-800">{{ $fee->student->user->full_name }}</p></div></td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $fee->title }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-default-800">${{ number_format($fee->amount,2) }}</td>
                        <td class="px-6 py-3 text-sm {{ $fee->due_date->isPast()&&$fee->status!=='paid'?'text-danger font-medium':'text-default-600' }}">{{ $fee->due_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3"><span class="badge {{ $bc }} capitalize">{{ $fee->status }}</span></td>
                        <td class="px-6 py-3"><div class="flex items-center gap-2">
                            <a href="{{ route('fees.edit', $fee) }}" class="btn btn-sm bg-default-150 size-8 p-0 flex items-center justify-center"><i class="ti ti-edit text-sm"></i></a>
                            <form action="{{ route('fees.destroy', $fee) }}" method="POST" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm bg-danger/10 text-danger size-8 p-0 flex items-center justify-center"><i class="ti ti-trash text-sm"></i></button></form>
                        </div></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-default-400">No fee records. <a href="{{ route('fees.create') }}" class="text-primary">Add one.</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($fees->hasPages())<div class="mt-4">{{ $fees->links() }}</div>@endif
    </div>
</div>
@endsection
