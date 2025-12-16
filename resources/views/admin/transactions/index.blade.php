@extends('layouts.admin')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Manajemen Transaksi</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Tambah Transaksi
                        </a>
                        <form action="{{ route('admin.transactions.export') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-download me-1"></i> Export CSV
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nomor atau Deskripsi" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-select">
                                <option value="">Semua</option>
                                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pendapatan</option>
                                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">Semua</option>
                                <option value="Operasional" {{ request('category') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                <option value="Investasi" {{ request('category') == 'Investasi' ? 'selected' : '' }}>Investasi</option>
                                <option value="Simpanan" {{ request('category') == 'Simpanan' ? 'selected' : '' }}>Simpanan</option>
                                <option value="Pinjaman" {{ request('category') == 'Pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Semua</option>
                                <option value="Tunai" {{ request('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Transfer" {{ request('payment_method') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="E-Wallet" {{ request('payment_method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
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
                                <i class="ti ti-trending-up ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Pendapatan</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> {{ $stats['income_count'] }} transaksi
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
                                <i class="ti ti-trending-down ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Pengeluaran</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</h3>
                            <small class="text-danger">
                                <i class="ti ti-arrow-down"></i> {{ $stats['expense_count'] }} transaksi
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
                                <i class="ti ti-chart-line ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Net Profit</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}</h3>
                            <small class="text-{{ $stats['net_profit'] >= 0 ? 'success' : 'danger' }}">
                                <i class="ti ti-{{ $stats['net_profit'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> 
                                {{ $stats['net_profit'] >= 0 ? 'Profit' : 'Loss' }}
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
                                <i class="ti ti-calendar ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Bulan Ini</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['monthly_income'] - $stats['monthly_expense'], 0, ',', '.') }}</h3>
                            <small class="text-info">
                                <i class="ti ti-receipt"></i> {{ $stats['monthly_count'] }} transaksi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $transaction->transaction_number }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <small>{{ $transaction->transaction_date->format('d M Y') }}</small>
                                                <br><small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $transaction->description }}</h6>
                                                @if($transaction->member)
                                                    <small class="text-muted">{{ $transaction->member->full_name }}</small>
                                                @endif
                                                @if($transaction->businessUnit)
                                                    <small class="text-muted">{{ $transaction->businessUnit->name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $transaction->category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                {{ $transaction->type == 'income' ? 'Pendapatan' : 'Pengeluaran' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $transaction->payment_method }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="dropdown-item">
                                                            <i class="ti ti-eye me-1"></i> Detail
                                                        </a>
                                                    </li>
                                                    @if($transaction->canEdit())
                                                        <li>
                                                            <a href="{{ route('admin.transactions.edit', $transaction) }}" class="dropdown-item">
                                                                <i class="ti ti-edit me-1"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link text-danger w-100 text-start" onclick="return confirm('Hapus transaksi ini?')">
                                                                    <i class="ti ti-trash me-1"></i> Hapus
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
                                        <td colspan="9" class="text-center py-4">
                                            <i class="ti ti-receipt ti-3x text-muted mb-3"></i>
                                            <h6>Tidak ada data transaksi</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($transactions->hasPages())
                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                            <small class="text-muted mb-2 mb-md-0">
                                Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} data
                            </small>
                            {{ $transactions->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.transactions.daily') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="ti ti-calendar me-1"></i> Laporan Harian
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.transactions.monthly') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="ti ti-chart-bar me-1"></i> Laporan Bulanan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.transactions.create') }}?type=income" class="btn btn-success w-100 mb-2">
                                <i class="ti ti-trending-up me-1"></i> Tambah Pendapatan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.transactions.create') }}?type=expense" class="btn btn-danger w-100 mb-2">
                                <i class="ti ti-trending-down me-1"></i> Tambah Pengeluaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
