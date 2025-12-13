@extends('layouts.admin')

@section('title', 'Manajemen Users')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Manajemen Users</h5>
                        <small class="text-muted">Kelola pengguna sistem dan role mereka</small>
                    </div>
                    @can('users.create')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah User
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.users.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Cari User</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari berdasarkan nama atau email..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Role</label>
                                <select name="role" class="form-select">
                                    <option value="">Semua Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <img src="{{ $user->avatar ?? asset('sneat-1.0.0/assets/img/avatars/1.png') }}" 
                                                         alt class="rounded-circle">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $user->name }}</div>
                                                    <small class="text-muted">@{{ $user->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->roles && $user->roles->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-label-info">{{ $role->name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada role</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                                {{ $user->status ?? 'active' }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.users.show', $user) }}" 
                                                           class="dropdown-item">
                                                            <i class="ti ti-eye me-2"></i> Lihat
                                                        </a>
                                                    </li>
                                                    @can('users.edit')
                                                    <li>
                                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                                           class="dropdown-item">
                                                            <i class="ti ti-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.users.toggle-status', $user) }}" 
                                                              method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ti ti-{{ $user->status === 'active' ? 'player-pause' : 'player-play' }} me-2"></i>
                                                                {{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endcan
                                                    @can('users.delete')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-trash me-2"></i> Hapus
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
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="ti ti-users ti-3x mb-3"></i>
                                            <h6>Tidak ada user ditemukan</h6>
                                            <p class="mb-0">Mulai dengan membuat user pertama Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} 
                                dari {{ $users->total() }} users
                            </div>
                            {{ $users->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
