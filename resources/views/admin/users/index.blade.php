@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Manage Users</h5>
                        <small class="text-muted">View and manage all system users</small>
                    </div>
                    @can('create users')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Create User
                    </a>
                    @endcan
                </div>
                <div class="table-responsive text-nowrap">
<table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Store</th>
                                <th>Status</th>
                                @if(auth()->user()->can('edit users') || auth()->user()->can('delete users'))
                                <th class="text-end">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600;">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-label-info me-1">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span>{{ $user->store->store_name ?? $user->store->name ?? 'All Stores' }}</span>
                                    @if($user->branch)
                                        <br><small class="text-muted"><i class="bx bx-git-branch me-1"></i>{{ $user->branch->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge bg-label-success">Active</span>
                                    @elseif($user->status === 'inactive')
                                        <span class="badge bg-label-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-label-danger">Suspended</span>
                                    @endif
                                </td>
                                @if(auth()->user()->can('edit users') || auth()->user()->can('delete users'))
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('edit users')
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            </li>
                                            @endcan
                                            @can('delete users')
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
