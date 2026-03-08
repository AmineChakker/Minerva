@extends('HTML.layout')
@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Announcements</span>
@endsection
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('announcements.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Search</label>
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>
                    <input class="form-input w-full" name="search" value="{{ request('search') }}" placeholder="Title or content...">
                </div>
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Type</label>
                <select class="form-input w-full" name="type">
                    <option value="">All Types</option>
                    <option value="info"    {{ request('type') == 'info'    ? 'selected' : '' }}>Info</option>
                    <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="danger"  {{ request('type') == 'danger'  ? 'selected' : '' }}>Danger</option>
                </select>
            </div>
            <div class="min-w-36">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Status</label>
                <select class="form-input w-full" name="is_published">
                    <option value="">All</option>
                    <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="min-w-40">
                <label class="block text-xs font-semibold text-default-500 uppercase mb-1.5">Sort By</label>
                <select class="form-input w-full" name="sort">
                    <option value="newest" {{ request('sort','newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="title"  {{ request('sort') == 'title'  ? 'selected' : '' }}>Title A–Z</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn bg-primary text-white gap-1.5 h-9.25"><i class="ti ti-filter text-sm"></i> Filter</button>
                @if(request()->hasAny(['search','type','is_published','sort']))
                <a href="{{ route('announcements.index') }}" class="btn bg-default-150 text-default-600 gap-1.5 h-9.25"><i class="ti ti-x text-sm"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('announcements.create') }}" class="btn bg-primary text-white btn-sm gap-1.5"><i class="ti ti-plus"></i> New Announcement</a>
</div>
@php
$borderMap=['info'=>'border-l-info','warning'=>'border-l-warning','success'=>'border-l-success','danger'=>'border-l-danger'];
$badgeMap=['info'=>'bg-info/10 text-info','warning'=>'bg-warning/10 text-warning','success'=>'bg-success/10 text-success','danger'=>'bg-danger/10 text-danger'];
$iconMap=['info'=>'ti-info-circle','warning'=>'ti-alert-triangle','success'=>'ti-circle-check','danger'=>'ti-alert-circle'];
@endphp
<p class="text-sm text-default-500 mb-4">{{ $announcements->total() }} announcement(s) found</p>
@if($announcements->isEmpty())
<div class="card"><div class="card-body py-16 text-center">
    <div class="size-14 rounded-full bg-default-100 flex items-center justify-center mx-auto mb-4"><i class="size-7 text-default-400" data-lucide="megaphone"></i></div>
    <p class="text-default-500">No announcements yet.</p>
    <a href="{{ route('announcements.create') }}" class="btn bg-primary text-white btn-sm mt-4 gap-1.5"><i class="ti ti-plus"></i> Create first announcement</a>
</div></div>
@else
<div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
@foreach($announcements as $ann)
<div class="card border-l-4 {{ $borderMap[$ann->type] ?? 'border-l-primary' }}">
    <div class="card-body">
        <div class="flex items-start gap-3 mb-3">
            <div class="size-9 rounded-lg {{ $badgeMap[$ann->type] ?? 'bg-primary/10 text-primary' }} flex items-center justify-center flex-shrink-0">
                <i class="ti {{ $iconMap[$ann->type] ?? 'ti-bell' }} text-base"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h6 class="text-sm font-semibold text-default-800">{{ $ann->title }}</h6>
                    <span class="badge text-xs {{ $badgeMap[$ann->type] ?? 'bg-primary/10 text-primary' }} capitalize">{{ $ann->type }}</span>
                    @if(!$ann->is_published)<span class="badge bg-default-100 text-default-500 text-xs">Draft</span>@endif
                </div>
                <p class="text-sm text-default-500 line-clamp-2">{{ $ann->content }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-default-100">
            <div class="flex items-center gap-2">
                @include('HTML.partials.avatar', ['user'=>$ann->user,'size'=>'size-6','textSize'=>'text-xs','color'=>'primary'])
                <span class="text-xs text-default-400">{{ $ann->user->full_name }} · {{ $ann->created_at->diffForHumans() }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('announcements.edit', $ann) }}" class="btn btn-sm bg-default-150 size-7 p-0 flex items-center justify-center"><i class="ti ti-edit text-xs"></i></a>
                <button type="button" onclick="openDeleteModal('{{ route('announcements.destroy', $ann) }}', 'Delete Announcement', 'Are you sure you want to delete this announcement? This cannot be undone.')" class="btn btn-sm bg-danger/10 text-danger gap-1"><i class="ti ti-trash text-sm"></i> Delete</button>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>
@if($announcements->hasPages())<div class="mt-5">{{ $announcements->links() }}</div>@endif
@endif
@endsection
