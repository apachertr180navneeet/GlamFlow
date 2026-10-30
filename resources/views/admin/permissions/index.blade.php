@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Manage Permissions</h5>
                        <small class="text-muted">View and manage all system permissions</small>
                    </div>
                    @can('create permissions')
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Create Permission
                    </a>
                    @endcan
                </div>
                <div class="table-responsive text-nowrap">
<table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Guard</th>
                                <th>Created</th>
                                @if(auth()->user()->can('edit permissions') || auth()->user()->can('delete permissions'))
                                <th class="text-end">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $permission->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary">{{ $permission->guard_name }}</span>
                                </td>
                                <td>{{ $permission->created_at->format('M d, Y h:i A') }}</td>
                                @if(auth()->user()->can('edit permissions') || auth()->user()->can('delete permissions'))
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('edit permissions')
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.permissions.edit', $permission->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            </li>
                                            @endcan
                                            @can('delete permissions')
                                            <li>
                                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this permission?')">
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
                                <td colspan="4" class="text-center py-4 text-muted">No permissions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
