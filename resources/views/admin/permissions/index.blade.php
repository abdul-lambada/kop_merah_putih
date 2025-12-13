@extends('layouts.admin')

@section('title', 'Manajemen Permissions')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Manajemen Permissions</h5>
                        <small class="text-muted">Kelola permission sistem dan penugasannya</small>
                    </div>
                    @can('permissions.create')
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah Permission
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
                    <form method="GET" action="{{ route('admin.permissions.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Cari Permission</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari berdasarkan nama permission..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Grup</label>
                                <select name="group" class="form-select">
                                    <option value="">Semua Grup</option>
                                    <option value="dashboard" {{ request('group') == 'dashboard' ? 'selected' : '' }}>Dashboard</option>
                                    <option value="members" {{ request('group') == 'members' ? 'selected' : '' }}>Anggota</option>
                                    <option value="savings" {{ request('group') == 'savings' ? 'selected' : '' }}>Simpanan</option>
                                    <option value="loans" {{ request('group') == 'loans' ? 'selected' : '' }}>Pinjaman</option>
                                    <option value="transactions" {{ request('group') == 'transactions' ? 'selected' : '' }}>Transaksi</option>
                                    <option value="reports" {{ request('group') == 'reports' ? 'selected' : '' }}>Laporan</option>
                                    <option value="settings" {{ request('group') == 'settings' ? 'selected' : '' }}>Pengaturan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Permission</th>
                                    <th>Grup</th>
                                    <th>Roles</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                    <tr>
                                        <td>
                                            <code class="text-primary">{{ $permission->name }}</code>
                                        </td>
                                        <td>
                                            @php
                                                $parts = explode('.', $permission->name);
                                                $group = $parts[0] ?? 'general';
                                            @endphp
                                            <span class="badge bg-label-info">{{ ucfirst($group) }}</span>
                                        </td>
                                        <td>
                                            @if($permission->roles->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($permission->roles->take(3) as $role)
                                                        <span class="badge bg-label-secondary">{{ $role->name }}</span>
                                                    @endforeach
                                                    @if($permission->roles->count() > 3)
                                                        <span class="badge bg-label-secondary">+{{ $permission->roles->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada role</span>
                                            @endif
                                        </td>
                                        <td>{{ $permission->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.permissions.show', $permission) }}" 
                                                           class="dropdown-item">
                                                            <i class="ti ti-eye me-2"></i> Lihat
                                                        </a>
                                                    </li>
                                                    @can('permissions.edit')
                                                    <li>
                                                        <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                                           class="dropdown-item">
                                                            <i class="ti ti-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    @endcan
                                                    @can('permissions.delete')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                        <form action="{{ route('admin.permissions.destroy', $permission) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus permission ini?')">
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
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="ti ti-shield ti-3x mb-3"></i>
                                            <h6>Tidak ada permission ditemukan</h6>
                                            <p class="mb-0">Mulai dengan membuat permission pertama Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($permissions->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Menampilkan {{ $permissions->firstItem() }} hingga {{ $permissions->lastItem() }} 
                                dari {{ $permissions->total() }} permissions
                            </div>
                            {{ $permissions->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
