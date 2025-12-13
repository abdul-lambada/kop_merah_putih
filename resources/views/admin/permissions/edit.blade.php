@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Edit Permission</h5>
                            <small class="text-muted">Modify permission details and assignments</small>
                        </div>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Permissions
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
                    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Permission Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $permission->name) }}" placeholder="e.g., users.create" required>
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
                                                   {{ in_array($role->id, old('roles', $permission->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="form-check-label">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Select which roles should have this permission</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Update Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Permission Info Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Permission Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Name</label>
                        <div>
                            <code class="text-primary">{{ $permission->name }}</code>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permission Group</label>
                        @php
                            $parts = explode('.', $permission->name);
                            $group = $parts[0] ?? 'general';
                        @endphp
                        <div>
                            <span class="badge bg-label-primary">{{ ucfirst($group) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Currently Assigned To</label>
                        @if($permission->roles->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($permission->roles as $role)
                                    <span class="badge bg-label-info">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">No roles assigned</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Created At</label>
                        <div class="text-muted">
                            {{ $permission->created_at->format('d M Y H:i') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Updated</label>
                        <div class="text-muted">
                            {{ $permission->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Permissions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Related Permissions</h6>
                </div>
                <div class="card-body">
                    @php
                        $parts = explode('.', $permission->name);
                        $group = $parts[0] ?? 'general';
                        $relatedPermissions = \Spatie\Permission\Models\Permission::where('name', 'like', $group . '.%')
                            ->where('id', '!=', $permission->id)
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($relatedPermissions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatedPermissions as $related)
                                <a href="{{ route('admin.permissions.edit', $related) }}" 
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
        </div>
    </div>
</div>
@endsection
