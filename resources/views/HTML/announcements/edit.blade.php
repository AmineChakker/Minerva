@extends('HTML.layout')
@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')
@section('breadcrumbs')
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<a class="font-medium text-default-500 hover:text-default-700" href="{{ route('announcements.index') }}">Announcements</a>
<i class="ti ti-chevron-right text-xs flex-shrink-0 text-default-400 rtl:rotate-180"></i>
<span class="font-medium text-default-700">Edit Announcement</span>
@endsection
@section('content')
<div class="card max-w-2xl">
    <div class="card-header border-b border-default-200 pb-4 mb-6 flex items-center gap-3">
        <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center flex-shrink-0"><i class="ti ti-speakerphone text-lg text-warning"></i></div>
        <div><h5 class="text-base font-semibold text-default-800">Edit Announcement</h5><p class="text-xs text-default-400 mt-0.5">{{ $announcement->title }}</p></div>
    </div>
    <div class="card-body pt-0">
        @include('HTML.partials.errors')
        <form action="{{ route('announcements.update', $announcement) }}" method="POST">@csrf @method('PUT')
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">Title <span class="text-danger">*</span></label><input class="form-input w-full" name="title" type="text" value="{{ old('title', $announcement->title) }}" required></div>
            <div class="mb-4"><label class="block text-sm font-medium text-default-700 mb-1.5">Content <span class="text-danger">*</span></label><textarea class="form-input w-full" name="content" rows="5" required>{{ old('content', $announcement->content) }}</textarea></div>
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mb-6">
                <div><label class="block text-sm font-medium text-default-700 mb-1.5">Type <span class="text-danger">*</span></label>
                    <select class="form-select w-full" name="type" required>
                        <option value="info" {{ old('type',$announcement->type)=='info'?'selected':'' }}>Info</option>
                        <option value="success" {{ old('type',$announcement->type)=='success'?'selected':'' }}>Success</option>
                        <option value="warning" {{ old('type',$announcement->type)=='warning'?'selected':'' }}>Warning</option>
                        <option value="danger" {{ old('type',$announcement->type)=='danger'?'selected':'' }}>Danger / Urgent</option>
                    </select>
                </div>
                <div class="flex flex-col justify-end"><label class="flex items-center gap-3 cursor-pointer pb-2"><input type="hidden" name="is_published" value="0"><input type="checkbox" name="is_published" value="1" class="form-checkbox" {{ old('is_published',$announcement->is_published)?'checked':'' }}><span class="text-sm text-default-700">Published</span></label></div>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-default-100">
                <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-device-floppy text-base"></i> Update</button>
                <a href="{{ route('announcements.index') }}" class="btn bg-default-150 text-default-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
