@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">User Details</h5>
                            <small class="text-muted">View user information and permissions</small>
                        </div>
                        <div class="d-flex gap-2">
                            @can('users.edit')
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                            @endcan
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Profile Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('sneat-1.0.0/assets/img/avatars/1.png') }}" 
                                 alt="User Avatar" class="rounded-circle mb-3" width="120" height="120">
                            <div class="mb-3">
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }} fs-6">
                                    {{ $user->status ?? 'active' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Full Name</label>
                                        <div class="fw-semibold fs-5">{{ $user->name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email Address</label>
                                        <div>{{ $user->email }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Username</label>
                                        <div>@{{ $user->name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email Verified</label>
                                        <div>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Verified</span>
                                                <small class="text-muted">({{ $user->email_verified_at->format('d M Y') }})</small>
                                            @else
                                                <span class="badge bg-warning">Not Verified</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created At</label>
                                        <div class="text-muted">{{ $user->created_at->format('d M Y H:i:s') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <div class="text-muted">{{ $user->updated_at->format('d M Y H:i:s') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Roles and Permissions</h6>
                    @can('users.edit')
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                            data-bs-target="#assignRolesModal">
                        <i class="ti ti-plus me-1"></i> Assign Roles
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    @if($user->roles && $user->roles->count() > 0)
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Assigned Roles</h6>
                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-label-primary fs-6">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Direct Permissions</h6>
                                @if($user->permissions && $user->permissions->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($user->permissions as $permission)
                                            <span class="badge bg-label-info">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No direct permissions (inherited from roles)</span>
                                @endif
                            </div>
                        </div>

                        <h6 class="mb-3">All Permissions (via roles)</h6>
                        <div class="row">
                            @php
                                $allPermissions = $user->getAllPermissions()->groupBy(function($permission) {
                                    $parts = explode('.', $permission->name);
                                    return $parts[0] ?? 'general';
                                });
                            @endphp
                            
                            @foreach($allPermissions as $group => $permissions)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($permissions as $permission)
                                                    <span class="badge bg-label-secondary">{{ $permission->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-user-off ti-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No roles assigned</h6>
                            <p class="text-muted mb-0">This user doesn't have any roles assigned.</p>
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
                        <span>Roles</span>
                        <span class="badge bg-primary">{{ $user->roles ? $user->roles->count() : 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Permissions</span>
                        <span class="badge bg-info">{{ $user->getAllPermissions() ? $user->getAllPermissions()->count() : 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Status</span>
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                            {{ $user->status ?? 'active' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('users.edit')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i> Edit User
                        </a>
                        
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-{{ $user->status === 'active' ? 'warning' : 'success' }}">
                                <i class="ti ti-{{ $user->status === 'active' ? 'player-pause' : 'player-play' }} me-1"></i>
                                {{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}
                            </button>
                        </form>
                        @endcan
                        
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                data-bs-target="#assignRolesModal">
                            <i class="ti ti-user-plus me-1"></i> Assign Roles
                        </button>
                        
                        @can('users.delete')
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="ti ti-trash me-1"></i> Delete User
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="ti ti-clock ti-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No recent activity</h6>
                        <p class="text-muted mb-0">User activity tracking will be available soon.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Roles Modal -->
@if(auth()->user()->can('users.edit'))
<div class="modal fade" id="assignRolesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Roles to User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.assign-roles', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Roles</label>
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" id="modal_role_{{ $role->id }}" name="roles[]" 
                                       value="{{ $role->id }}" class="form-check-input"
                                       {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                <label for="modal_role_{{ $role->id }}" class="form-check-label">
                                    {{ $role->name }}
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
