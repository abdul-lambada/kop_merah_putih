@extends('layouts.admin')

@section('title', 'Permission Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Permission Details</h5>
                            <small class="text-muted">View permission information and assignments</small>
                        </div>
                        <div class="d-flex gap-2">
                            @can('permissions.edit')
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            @endcan
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Permissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Details -->
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
                                <label class="form-label text-muted">Permission Name</label>
                                <div>
                                    <code class="text-primary fs-5">{{ $permission->name }}</code>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Permission Group</label>
                                @php
                                    $parts = explode('.', $permission->name);
                                    $group = $parts[0] ?? 'general';
                                    $action = $parts[1] ?? 'unknown';
                                @endphp
                                <div>
                                    <span class="badge bg-label-primary fs-6">{{ ucfirst($group) }}</span>
                                    <span class="badge bg-label-info fs-6">{{ ucfirst($action) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Guard Name</label>
                                <div>
                                    <span class="badge bg-label-secondary">{{ $permission->guard_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Created At</label>
                                <div class="text-muted">
                                    {{ $permission->created_at->format('d M Y H:i:s') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <div class="text-muted">
                                    {{ $permission->updated_at->format('d M Y H:i:s') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Assignments -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Role Assignments</h6>
                    @can('permissions.edit')
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                            data-bs-target="#assignRolesModal">
                        <i class="ti ti-plus me-1"></i> Assign Roles
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    @if($permission->roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Users Count</th>
                                        <th>Assigned At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission->roles as $role)
                                        <tr>
                                            <td>
                                                <span class="badge bg-label-info">{{ $role->name }}</span>
                                            </td>
                                            <td>{{ $role->users()->count() }} users</td>
                                            <td>{{ $role->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                @can('permissions.edit')
                                                <form action="{{ route('admin.permissions.remove-role', [$permission, $role]) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Remove this role from permission?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-user-off ti-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No roles assigned</h6>
                            <p class="text-muted mb-0">This permission is not currently assigned to any roles.</p>
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
                        <span>Assigned Roles</span>
                        <span class="badge bg-primary">{{ $permission->roles->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Users</span>
                        <span class="badge bg-info">{{ $permission->roles->sum(function($role) { return $role->users()->count(); }) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Permission Group</span>
                        <span class="badge bg-secondary">{{ ucfirst($group) }}</span>
                    </div>
                </div>
            </div>

            <!-- Related Permissions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Related Permissions</h6>
                </div>
                <div class="card-body">
                    @php
                        $relatedPermissions = \Spatie\Permission\Models\Permission::where('name', 'like', $group . '.%')
                            ->where('id', '!=', $permission->id)
                            ->limit(8)
                            ->get();
                    @endphp
                    
                    @if($relatedPermissions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatedPermissions as $related)
                                <a href="{{ route('admin.permissions.show', $related) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span class="text-truncate">
                                        <code class="text-primary">{{ $related->name }}</code>
                                    </span>
                                    <i class="ti ti-chevron-right text-muted"></i>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No related permissions found</p>
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
                        @can('permissions.edit')
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i> Edit Permission
                        </a>
                        @endcan
                        
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                data-bs-target="#assignRolesModal">
                            <i class="ti ti-user-plus me-1"></i> Assign Roles
                        </button>
                        
                        @can('permissions.delete')
                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this permission? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="ti ti-trash me-1"></i> Delete Permission
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Roles Modal -->
@if(auth()->user()->can('permissions.edit'))
<div class="modal fade" id="assignRolesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Roles to Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.permissions.assign-roles', $permission) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Roles</label>
                        @php
                            $allRoles = \Spatie\Permission\Models\Role::orderBy('name')->get();
                        @endphp
                        @foreach($allRoles as $role)
                            <div class="form-check">
                                <input type="checkbox" id="modal_role_{{ $role->id }}" name="roles[]" 
                                       value="{{ $role->id }}" class="form-check-input"
                                       {{ $permission->roles->contains($role->id) ? 'checked' : '' }}>
                                <label for="modal_role_{{ $role->id }}" class="form-check-label">
                                    {{ $role->name }}
                                    <small class="text-muted">({{ $role->users()->count() }} users)</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Roles</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
