@extends('layouts.admin')

@section('title', 'Roles Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Roles Management</h5>
                        <small class="text-muted">Manage system roles and their permissions</small>
                    </div>
                    @can('roles.create')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Add Role
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.roles.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Search Role</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by role name..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i> Search
                                </button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Permissions Count</th>
                                    <th>Users Count</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            <span class="badge bg-label-primary">{{ $role->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-info">{{ $role->permissions->count() }} permissions</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-success">{{ $role->users->count() }} users</span>
                                        </td>
                                        <td>{{ $role->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.roles.show', $role) }}" 
                                                           class="dropdown-item">
                                                            <i class="ti ti-eye me-2"></i> View
                                                        </a>
                                                    </li>
                                                    @can('roles.edit')
                                                    <li>
                                                        <a href="{{ route('admin.roles.edit', $role) }}" 
                                                           class="dropdown-item">
                                                            <i class="ti ti-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    @endcan
                                                    @can('roles.delete')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                        <form action="{{ route('admin.roles.destroy', $role) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-trash me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="ti ti-user ti-3x mb-3"></i>
                                            <h6>No roles found</h6>
                                            <p class="mb-0">Start by creating your first role.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($roles->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} 
                                of {{ $roles->total() }} roles
                            </div>
                            {{ $roles->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
