@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Create Role</h5>
                            <small class="text-muted">Add a new system role</small>
                        </div>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf
                        
                        <!-- Role Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="e.g., Manager, Staff" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Use a descriptive name for the role (e.g., Manager Keuangan, Staff Administrasi)
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" 
                                      placeholder="Optional description of what this role does">{{ old('description') }}</textarea>
                        </div>

                        <!-- Permission Assignments -->
                        <div class="mb-4">
                            <label class="form-label">Assign Permissions</label>
                            <div class="row">
                                @foreach($permissionGroups as $group)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header py-2">
                                                <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                            </div>
                                            <div class="card-body">
                                                @foreach($permissions->where('name', 'like', $group . '.%') as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" 
                                                               value="{{ $permission->id }}" class="form-check-input"
                                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label for="permission_{{ $permission->id }}" class="form-check-label">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Select which permissions this role should have</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Create Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Common Role Templates</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    onclick="selectRoleTemplate('admin')">
                                <i class="ti ti-shield me-1"></i> Admin Role
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    onclick="selectRoleTemplate('manager')">
                                <i class="ti ti-user me-1"></i> Manager Role
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    onclick="selectRoleTemplate('staff')">
                                <i class="ti ti-users me-1"></i> Staff Role
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    onclick="selectRoleTemplate('viewer')">
                                <i class="ti ti-eye me-1"></i> Viewer Role
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i> Tips</h6>
                        <ul class="mb-0">
                            <li>Group permissions by functionality</li>
                            <li>Assign minimum necessary permissions</li>
                            <li>Use descriptive role names</li>
                            <li>Test roles with different user accounts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectRoleTemplate(type) {
    // Clear all checkboxes first
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
    
    const templates = {
        admin: ['dashboard.view', 'users.view', 'users.create', 'users.edit', 'users.delete', 'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete'],
        manager: ['dashboard.view', 'members.view', 'members.create', 'members.edit', 'savings.view', 'savings.approve', 'loans.view', 'loans.approve', 'transactions.view', 'reports.view'],
        staff: ['dashboard.view', 'members.view', 'members.create', 'savings.view', 'savings.create', 'transactions.view', 'transactions.create'],
        viewer: ['dashboard.view', 'members.view', 'savings.view', 'loans.view', 'transactions.view', 'reports.view']
    };
    
    const permissions = templates[type] || [];
    
    // Check the appropriate permissions
    permissions.forEach(perm => {
        const checkbox = document.querySelector(`input[name="permissions[]"][value="${perm}"]`);
        if (checkbox) checkbox.checked = true;
    });
    
    // Set role name suggestion
    const nameField = document.getElementById('name');
    if (!nameField.value) {
        nameField.value = type.charAt(0).toUpperCase() + type.slice(1);
    }
}
</script>
@endpush
