@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Create Role</h5>
                        <small class="text-muted">Add a new role with permissions</small>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Manager, Stylist" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3">Select the permissions you want to assign to this role.</p>

                            @foreach($permissions as $module => $modulePermissions)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input module-checkbox" type="checkbox" id="module_{{ Str::slug($module) }}" data-module="{{ Str::slug($module) }}">
                                            <label class="form-check-label fw-semibold" for="module_{{ Str::slug($module) }}">
                                                {{ ucfirst($module) }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach($modulePermissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" data-module="{{ Str::slug($module) }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @error('permissions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Create Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
        moduleCheckbox.addEventListener('change', function() {
            var module = this.dataset.module;
            var permissions = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
            permissions.forEach(function(perm) {
                perm.checked = moduleCheckbox.checked;
            });
        });
    });

    document.querySelectorAll('.permission-checkbox').forEach(function(permCheckbox) {
        permCheckbox.addEventListener('change', function() {
            var module = this.dataset.module;
            var moduleCheckbox = document.querySelector('.module-checkbox[data-module="' + module + '"]');
            var permissions = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
            var allChecked = true;
            permissions.forEach(function(perm) {
                if (!perm.checked) {
                    allChecked = false;
                }
            });
            moduleCheckbox.checked = allChecked;
        });
    });
});
</script>
@endsection
