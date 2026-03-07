@extends('HTML.layout')
@section('title', 'All Users')
@section('page-title', 'All Users')
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-48"><label class="block text-sm font-medium text-default-700 mb-1.5">Search</label><input class="form-input w-full" type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."></div>
            <div class="flex-1 min-w-36"><label class="block text-sm font-medium text-default-700 mb-1.5">Role</label>
                <select class="form-select w-full" name="role"><option value="">All Roles</option><option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option><option value="teacher" {{ request('role')=='teacher'?'selected':'' }}>Teacher</option><option value="student" {{ request('role')=='student'?'selected':'' }}>Student</option><option value="parent" {{ request('role')=='parent'?'selected':'' }}>Parent</option></select>
            </div>
            <button type="submit" class="btn bg-primary text-white gap-1.5"><i class="ti ti-search text-sm"></i> Search</button>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table min-w-full">
                <thead class="bg-default-100"><tr>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">#</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">User</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Email</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Role</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Status</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Last Login</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-default-500">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($users as $user)
                    @php $rc=['admin'=>'bg-primary/10 text-primary','teacher'=>'bg-success/10 text-success','student'=>'bg-info/10 text-info','parent'=>'bg-violet-500/10 text-violet-500']; @endphp
                    <tr class="hover:bg-default-50">
                        <td class="px-6 py-3 text-sm text-default-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3"><div class="flex items-center gap-3">@include('HTML.partials.avatar',['user'=>$user,'size'=>'size-9','textSize'=>'text-sm','color'=>'primary'])<div><p class="text-sm font-medium text-default-800">{{ $user->full_name }}</p><p class="text-xs text-default-400">{{ $user->phone??'&mdash;' }}</p></div></div></td>
                        <td class="px-6 py-3 text-sm text-default-600">{{ $user->email }}</td>
                        <td class="px-6 py-3"><span class="badge capitalize {{ $rc[$user->role]??'bg-default-100 text-default-500' }}">{{ $user->role }}</span></td>
                        <td class="px-6 py-3">@if($user->is_active)<span class="badge bg-success/10 text-success">Active</span>@else<span class="badge bg-danger/10 text-danger">Inactive</span>@endif</td>
                        <td class="px-6 py-3 text-sm text-default-500">{{ $user->last_login_at?$user->last_login_at->diffForHumans():'Never' }}</td>
                        <td class="px-6 py-3">
                            @if($user->id!==auth()->id())
                            <form action="{{ route('users.toggle-active', $user) }}" method="POST">@csrf
                                <button type="submit" class="btn btn-sm {{ $user->is_active?'bg-danger/10 text-danger':'bg-success/10 text-success' }} text-xs px-2.5 py-1 gap-1">
                                    <i class="ti {{ $user->is_active?'ti-user-x':'ti-user-check' }} text-xs"></i> {{ $user->is_active?'Deactivate':'Activate' }}
                                </button>
                            </form>
                            @else<span class="text-xs text-default-400 italic">You</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-default-400">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())<div class="mt-4">{{ $users->withQueryString()->links() }}</div>@endif
    </div>
</div>
@endsection
