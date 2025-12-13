@extends('layouts.admin')

@section('title', 'Manajemen Pinjaman')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Manajemen Pinjaman</h5>
                    <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Ajukan Pinjaman
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.loans.index') }}" class="row g-3">
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
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                                <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary">
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
                            <div class="avatar bg-label-warning rounded p-2">
                                <i class="ti ti-cash ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Portfolio Aktif</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['active_portfolio'], 0, ',', '.') }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> {{ $stats['active_count'] }} pinjaman aktif
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
                            <div class="avatar bg-label-danger rounded p-2">
                                <i class="ti ti-alert-triangle ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Overdue</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['overdue_amount'], 0, ',', '.') }}</h3>
                            <small class="text-danger">
                                <i class="ti ti-alert-circle"></i> {{ $stats['overdue_count'] }} overdue
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
                                <i class="ti ti-clock ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Pending Approval</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['pending_amount'], 0, ',', '.') }}</h3>
                            <small class="text-warning">
                                <i class="ti ti-hourglass"></i> {{ $stats['pending_count'] }} menunggu
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
                            <div class="avatar bg-label-success rounded p-2">
                                <i class="ti ti-calendar ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Bulan Ini</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['monthly_loans'], 0, ',', '.') }}</h3>
                            <small class="text-info">
                                <i class="ti ti-trending-up"></i> {{ $stats['monthly_count'] }} pinjaman
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor Pinjaman</th>
                                    <th>Anggota</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Sisa</th>
                                    <th>Cicilan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                    <tr>
                                        <td>
                                            <span class="badge bg-warning">{{ $loan->transaction_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($loan->member->full_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $loan->member->full_name }}</h6>
                                                    <small class="text-muted">{{ $loan->member->member_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $loan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="text-warning fw-bold">Rp {{ number_format($loan->amount, 0, ',', '.') }}</span>
                                            <br><small class="text-muted">{{ $loan->tenure_months }} bulan</small>
                                        </td>
                                        <td>
                                            <span class="text-{{ $loan->remaining_balance > 0 ? 'danger' : 'success' }} fw-bold">
                                                Rp {{ number_format($loan->remaining_balance, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</small>
                                            @if($loan->next_payment_date)
                                                <br><small class="text-muted">{{ $loan->next_payment_date->format('d M Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($loan->status == 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($loan->status == 'active')
                                                <span class="badge bg-primary">Aktif</span>
                                            @elseif($loan->status == 'overdue')
                                                <span class="badge bg-danger">Overdue</span>
                                            @elseif($loan->status == 'approved')
                                                <span class="badge bg-info">Disetujui</span>
                                            @elseif($loan->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.loans.show', $loan) }}" class="dropdown-item">
                                                            <i class="ti ti-eye me-1"></i> Detail
                                                        </a>
                                                    </li>
                                                    @if($loan->status == 'pending')
                                                        <li>
                                                            <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-success w-100 text-start">
                                                                    <i class="ti ti-check me-1"></i> Setujui
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.loans.reject', $loan) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-danger w-100 text-start">
                                                                    <i class="ti ti-x me-1"></i> Tolak
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($loan->status == 'active' || $loan->status == 'overdue')
                                                        <li>
                                                            <form action="{{ route('admin.loans.payment', $loan) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-primary w-100 text-start">
                                                                    <i class="ti ti-cash ti-sm me-1"></i> Bayar Cicilan
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
                                        <td colspan="8" class="text-center py-4">
                                            <i class="ti ti-cash ti-3x text-muted mb-3"></i>
                                            <h6>Tidak ada data pinjaman</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($loans->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $loans->firstItem() }} - {{ $loans->lastItem() }} dari {{ $loans->total() }} data
                            </small>
                            {{ $loans->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
