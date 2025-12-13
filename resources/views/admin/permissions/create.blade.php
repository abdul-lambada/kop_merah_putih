@extends('layouts.admin')

@section('title', 'Create Permission')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Create Permission</h5>
                            <small class="text-muted">Add a new system permission</small>
                        </div>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Permissions
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
                    <form method="POST" action="{{ route('admin.permissions.store') }}">
                        @csrf
                        
                        <!-- Permission Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="e.g., users.create" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Use dot notation: group.action (e.g., users.create, posts.edit)
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" 
                                      placeholder="Optional description of what this permission controls">{{ old('description') }}</textarea>
                        </div>

                        <!-- Role Assignments -->
                        <div class="mb-4">
                            <label class="form-label">Assign to Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" 
                                                   value="{{ $role->id }}" class="form-check-input"
                                                   {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="form-check-label">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Select which roles should have this permission</small>
                        </div>

                        <!-- Permission Groups Helper -->
                        <div class="mb-4">
                            <label class="form-label">Common Permission Groups</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" 
                                            onclick="suggestPermission('users')">
                                        <i class="ti ti-users me-1"></i> Users
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" 
                                            onclick="suggestPermission('members')">
                                        <i class="ti ti-user me-1"></i> Members
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" 
                                            onclick="suggestPermission('loans')">
                                        <i class="ti ti-credit-card me-1"></i> Loans
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" 
                                            onclick="suggestPermission('reports')">
                                        <i class="ti ti-file me-1"></i> Reports
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Click to quickly fill permission names for common groups</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Create Permission
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
                        <label class="form-label">Common Actions</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                    onclick="addAction('view')">
                                <i class="ti ti-eye me-1"></i> Add .view
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                    onclick="addAction('create')">
                                <i class="ti ti-plus me-1"></i> Add .create
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                    onclick="addAction('edit')">
                                <i class="ti ti-edit me-1"></i> Add .edit
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                    onclick="addAction('delete')">
                                <i class="ti ti-trash me-1"></i> Add .delete
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i> Tips</h6>
                        <ul class="mb-0">
                            <li>Use dot notation for better organization</li>
                            <li>Group related permissions together</li>
                            <li>Be descriptive with permission names</li>
                            <li>Assign permissions to roles, not users directly</li>
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
function suggestPermission(group) {
    const nameField = document.getElementById('name');
    const currentName = nameField.value;
    
    if (currentName && !currentName.includes('.')) {
        nameField.value = currentName + '.view';
    } else if (!currentName) {
        nameField.value = group + '.view';
    }
}

function addAction(action) {
    const nameField = document.getElementById('name');
    const currentName = nameField.value;
    
    if (currentName && !currentName.includes('.')) {
        nameField.value = currentName + '.' + action;
    } else if (currentName && currentName.includes('.')) {
        const parts = currentName.split('.');
        parts[parts.length - 1] = action;
        nameField.value = parts.join('.');
    }
}
</script>
@endpush
