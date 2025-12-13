@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Edit User</h5>
                            <small class="text-muted">Modify user details and roles</small>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Users
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
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- User Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Leave blank to keep current password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters. Leave blank to keep current password.</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                                   placeholder="Confirm new password">
                        </div>

                        <!-- Role Assignments -->
                        <div class="mb-4">
                            <label class="form-label">Assign Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" id="role_{{ $role->id }}" name="roles[]" 
                                                   value="{{ $role->id }}" class="form-check-input"
                                                   {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="form-check-label">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Select which roles this user should have</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Info Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Avatar</label>
                        <div class="text-center">
                            <img src="{{ $user->avatar ? asset('avatars/' . $user->avatar) : asset('sneat-1.0.0/assets/img/avatars/1.png') }}" 
                                 alt="User Avatar" class="rounded-circle" width="80" height="80">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Name</label>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Email</label>
                        <div class="text-muted">{{ $user->email }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Roles</label>
                        @if($user->roles && $user->roles->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-label-info">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">No roles assigned</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Status</label>
                        <div>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                {{ $user->status ?? 'active' }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Created At</label>
                        <div class="text-muted">{{ $user->created_at->format('d M Y H:i') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Updated</label>
                        <div class="text-muted">{{ $user->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-{{ $user->status === 'active' ? 'warning' : 'success' }}">
                                <i class="ti ti-{{ $user->status === 'active' ? 'player-pause' : 'player-play' }} me-1"></i>
                                {{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                            <i class="ti ti-eye me-1"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
