@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Role Details</h5>
                            <small class="text-muted">View role information and assigned permissions</small>
                        </div>
                        <div class="d-flex gap-2">
                            @can('roles.edit')
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            @endcan
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Roles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Basic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Role Name</label>
                                <div>
                                    <span class="badge bg-label-primary fs-5">{{ $role->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Guard Name</label>
                                <div>
                                    <span class="badge bg-label-secondary">{{ $role->guard_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Users</label>
                                <div>
                                    <span class="badge bg-label-info">{{ $role->users->count() }} users</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Created At</label>
                                <div class="text-muted">
                                    {{ $role->created_at->format('d M Y H:i:s') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <div class="text-muted">
                                    {{ $role->updated_at->format('d M Y H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Assigned Permissions</h6>
                    @can('roles.edit')
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                            data-bs-target="#assignPermissionsModal">
                        <i class="ti ti-plus me-1"></i> Assign Permissions
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @php
                                $groupedPermissions = $role->permissions->groupBy(function($permission) {
                                    $parts = explode('.', $permission->name);
                                    return $parts[0] ?? 'general';
                                });
                            @endphp
                            
                            @foreach($groupedPermissions as $group => $permissions)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($permissions as $permission)
                                                    <span class="badge bg-label-info">{{ $permission->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-shield-off ti-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No permissions assigned</h6>
                            <p class="text-muted mb-0">This role doesn't have any permissions assigned.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Permissions</span>
                        <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Users</span>
                        <span class="badge bg-info">{{ $role->users->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Status</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
            </div>

            <!-- Users with this Role -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Users with this Role</h6>
                </div>
                <div class="card-body">
                    @if($role->users->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($role->users->take(5) as $user)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                </div>
                            @endforeach
                            @if($role->users->count() > 5)
                                <div class="list-group-item text-center">
                                    <small class="text-muted">+{{ $role->users->count() - 5 }} more users</small>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-0">No users assigned to this role</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('roles.edit')
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i> Edit Role
                        </a>
                        @endcan
                        
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                data-bs-target="#assignPermissionsModal">
                            <i class="ti ti-shield me-1"></i> Assign Permissions
                        </button>
                        
                        @can('roles.delete')
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="ti ti-trash me-1"></i> Delete Role
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Permissions Modal -->
@if(auth()->user()->can('roles.edit'))
<div class="modal fade" id="assignPermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Permissions to Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.assign-permissions', $role) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        @php
                            $allPermissions = \Spatie\Permission\Models\Permission::orderBy('name')->get();
                            $permissionGroups = $allPermissions->groupBy(function($permission) {
                                $parts = explode('.', $permission->name);
                                return $parts[0] ?? 'general';
                            });
                        @endphp
                        
                        @foreach($permissionGroups as $group => $permissions)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" id="modal_permission_{{ $permission->id }}" name="permissions[]" 
                                                       value="{{ $permission->id }}" class="form-check-input"
                                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                <label for="modal_permission_{{ $permission->id }}" class="form-check-label">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
