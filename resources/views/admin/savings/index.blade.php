@extends('layouts.admin')

@section('title', 'Manajemen Simpanan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Manajemen Simpanan</h5>
                    <a href="{{ route('admin.savings.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah Simpanan
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.savings.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nomor atau Nama Anggota" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="ti ti-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('admin.savings.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-success rounded p-2">
                                <i class="ti ti-pig-money ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Simpanan</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['total_savings'], 0, ',', '.') }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> {{ $stats['total_count'] }} transaksi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-warning rounded p-2">
                                <i class="ti ti-clock ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Pending</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['pending_amount'], 0, ',', '.') }}</h3>
                            <small class="text-warning">
                                <i class="ti ti-alert-circle"></i> {{ $stats['pending_count'] }} menunggu
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-check ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Bulan Ini</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['monthly_savings'], 0, ',', '.') }}</h3>
                            <small class="text-info">
                                <i class="ti ti-calendar"></i> {{ $stats['monthly_count'] }} transaksi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-primary rounded p-2">
                                <i class="ti ti-users ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Anggota Aktif</h6>
                            <h3 class="mb-2">{{ $stats['active_members'] }}</h3>
                            <small class="text-primary">
                                <i class="ti ti-trending-up"></i> Menabung rutin
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Savings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor Simpanan</th>
                                    <th>Anggota</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Saldo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($savings as $saving)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $saving->transaction_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($saving->member->full_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $saving->member->full_name }}</h6>
                                                    <small class="text-muted">{{ $saving->member->member_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $saving->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="text-success fw-bold">+Rp {{ number_format($saving->amount, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <small>Rp {{ number_format($saving->member->total_savings, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            @if($saving->status == 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($saving->status == 'approved')
                                                <span class="badge bg-primary">Disetujui</span>
                                            @elseif($saving->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.savings.show', $saving) }}" class="dropdown-item">
                                                            <i class="ti ti-eye me-1"></i> Detail
                                                        </a>
                                                    </li>
                                                    @if($saving->status == 'pending')
                                                        <li>
                                                            <form action="{{ route('admin.savings.approve', $saving) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-success w-100 text-start">
                                                                    <i class="ti ti-check me-1"></i> Setujui
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($saving->status == 'approved' || $saving->status == 'completed')
                                                        <li>
                                                            <form action="{{ route('admin.savings.withdraw', $saving) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-warning w-100 text-start">
                                                                    <i class="ti ti-arrow-up me-1"></i> Tarik
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="ti ti-pig-money ti-3x text-muted mb-3"></i>
                                            <h6>Tidak ada data simpanan</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($savings->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $savings->firstItem() }} - {{ $savings->lastItem() }} dari {{ $savings->total() }} data
                            </small>
                            {{ $savings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
