@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Edit Role</h5>
                            <small class="text-muted">Modify role details and permissions</small>
                        </div>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Role Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $role->name) }}" placeholder="e.g., Manager, Staff" required>
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
                                                               {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                <i class="ti ti-check me-1"></i> Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role Info Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Role Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Name</label>
                        <div>
                            <span class="badge bg-label-primary">{{ $role->name }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Permissions</label>
                        <div>
                            <span class="badge bg-label-info">{{ $role->permissions->count() }} permissions</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assigned Users</label>
                        <div>
                            <span class="badge bg-label-success">{{ $role->users->count() }} users</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Created At</label>
                        <div class="text-muted">
                            {{ $role->created_at->format('d M Y H:i') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Updated</label>
                        <div class="text-muted">
                            {{ $role->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Permissions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Current Permissions</h6>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($role->permissions as $permission)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-truncate">
                                        <code class="text-primary">{{ $permission->name }}</code>
                                    </span>
                                    <small class="text-muted">{{ $permission->created_at->format('d M Y') }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No permissions assigned</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
