@if($errors->any())
<div class="mb-5 bg-danger/10 border border-danger/20 rounded-lg p-4">
    <div class="flex items-center gap-2 mb-2">
        <i class="ti ti-circle-x text-danger text-lg flex-shrink-0"></i>
        <span class="text-sm font-medium text-danger">Please fix the following errors:</span>
    </div>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li class="text-sm text-danger/80">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
