@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Create User</h5>
                            <small class="text-muted">Add a new system user</small>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Validation Errors!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <!-- User Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="Enter full name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="Enter email address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Enter password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                                   placeholder="Confirm password" required>
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
                                                   {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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
                            <button type="submit" class="btn btn-primary" onclick="console.log('Form submitted');">
                                <i class="ti ti-check me-1"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i> Tips</h6>
                        <ul class="mb-0">
                            <li>Use a strong password (8+ characters)</li>
                            <li>Assign appropriate roles based on user responsibilities</li>
                            <li>Email will be used for login</li>
                            <li>User can change password later</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default Avatar</label>
                        <div class="text-center">
                            <img src="{{ asset('sneat-1.0.0/assets/img/avatars/1.png') }}" 
                                 alt="Default Avatar" class="rounded-circle" width="80" height="80">
                        </div>
                        <small class="text-muted">User can upload custom avatar later</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Status</label>
                        <div>
                            <span class="badge bg-success">Active</span>
                            <small class="text-muted">New accounts are active by default</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        console.log('Form submission detected');
        console.log('Form data:', new FormData(form));
    });
});
</script>
@endsection
