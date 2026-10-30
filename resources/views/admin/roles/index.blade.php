@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Manage Roles</h5>
                        <small class="text-muted">View and manage user roles and permissions</small>
                    </div>
                    @can('create roles')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Create Role
                    </a>
                    @endcan
                </div>
                <div class="table-responsive text-nowrap">
<table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Permissions Count</th>
                                @if(auth()->user()->can('edit roles') || auth()->user()->can('delete roles'))
                                <th class="text-end">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $role->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $role->permissions_count }} {{ Str::plural('permission', $role->permissions_count) }}</span>
                                </td>
                                @if(auth()->user()->can('edit roles') || auth()->user()->can('delete roles'))
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('edit roles')
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.roles.edit', $role->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                            </li>
                                            @endcan
                                            @can('delete roles')
                                            <li>
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this role?')">
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
                                <td colspan="3" class="text-center py-4 text-muted">No roles found.</td>
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
