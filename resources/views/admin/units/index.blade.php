@extends('layouts.admin')

@section('title', 'Manajemen Unit Usaha')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Manajemen Unit Usaha</h5>
                    <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Tambah Unit
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.units.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nama Unit" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-select">
                                <option value="">Semua</option>
                                <option value="sembako" {{ request('type') == 'sembako' ? 'selected' : '' }}>Sembako</option>
                                <option value="apotek" {{ request('type') == 'apotek' ? 'selected' : '' }}>Apotek</option>
                                <option value="klinik" {{ request('type') == 'klinik' ? 'selected' : '' }}>Klinik</option>
                                <option value="logistik" {{ request('type') == 'logistik' ? 'selected' : '' }}>Logistik</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="ti ti-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
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
                            <div class="avatar bg-label-primary rounded p-2">
                                <i class="ti ti-building-store ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Unit</h6>
                            <h3 class="mb-2">{{ $stats['total_units'] }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> {{ $stats['active_units'] }} aktif
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
                                <i class="ti ti-trending-up ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Revenue</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> Bulan ini
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
                                <i class="ti ti-cash ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Modal</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['total_capital'], 0, ',', '.') }}</h3>
                            <small class="text-info">
                                <i class="ti ti-info-circle"></i> Investasi
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
                                <i class="ti ti-percentage ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Avg ROI</h6>
                            <h3 class="mb-2">{{ number_format($stats['avg_roi'], 1) }}%</h3>
                            <small class="text-primary">
                                <i class="ti ti-chart-line"></i> Performa
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Units Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Tipe</th>
                                    <th>Manager</th>
                                    <th>Modal</th>
                                    <th>Revenue Bulan Ini</th>
                                    <th>Profit</th>
                                    <th>ROI</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($units as $unit)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-{{ $unit->type == 'sembako' ? 'success' : ($unit->type == 'apotek' ? 'primary' : ($unit->type == 'klinik' ? 'warning' : 'info')) }}">
                                                        <i class="ti ti-{{ $unit->type == 'sembako' ? 'shopping-cart' : ($unit->type == 'apotek' ? 'pill' : ($unit->type == 'klinik' ? 'first-aid-kit' : 'truck')) }}"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $unit->name }}</h6>
                                                    <small class="text-muted">{{ $unit->location }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $unit->type == 'sembako' ? 'success' : ($unit->type == 'apotek' ? 'primary' : ($unit->type == 'klinik' ? 'warning' : 'info')) }}">
                                                {{ ucfirst($unit->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($unit->manager)
                                                <small>{{ $unit->manager->name }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>Rp {{ number_format($unit->initial_capital, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">Rp {{ number_format($unit->monthlyRevenue, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-{{ $unit->monthlyProfit >= 0 ? 'success' : 'danger' }} fw-bold">
                                                Rp {{ number_format($unit->monthlyProfit, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $unit->roi >= 10 ? 'success' : ($unit->roi >= 0 ? 'warning' : 'danger') }}">
                                                {{ number_format($unit->roi, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if($unit->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($unit->status == 'inactive')
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Maintenance</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.units.show', $unit) }}" class="dropdown-item">
                                                            <i class="ti ti-eye me-1"></i> Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.units.edit', $unit) }}" class="dropdown-item">
                                                            <i class="ti ti-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#transactionModal{{ $unit->id }}">
                                                            <i class="ti ti-cash ti-sm me-1"></i> Transaksi
                                                        </button>
                                                    </li>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.units.report', $unit) }}" class="dropdown-item">
                                                            <i class="ti ti-chart-bar me-1"></i> Laporan
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="dropdown-item p-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger w-100 text-start" onclick="return confirm('Hapus unit ini?')">
                                                                <i class="ti ti-trash me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="ti ti-building-store ti-3x text-muted mb-3"></i>
                                            <h6>Tidak ada data unit usaha</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($units->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $units->firstItem() }} - {{ $units->lastItem() }} dari {{ $units->total() }} data
                            </small>
                            {{ $units->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div> 
    </div>

    <!-- Unit Type Quick Access -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Akses Cepat per Tipe Unit</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.units.sembako') }}" class="btn btn-success w-100 mb-2">
                                <i class="ti ti-shopping-cart me-1"></i> Unit Sembako
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.units.apotek') }}" class="btn btn-primary w-100 mb-2">
                                <i class="ti ti-pill me-1"></i> Unit Apotek
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.units.klinik') }}" class="btn btn-warning w-100 mb-2">
                                <i class="ti ti-first-aid-kit me-1"></i> Unit Klinik
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.units.logistik') }}" class="btn btn-info w-100 mb-2">
                                <i class="ti ti-truck me-1"></i> Unit Logistik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Modals for each unit -->
@foreach($units as $unit)
<div class="modal fade" id="transactionModal{{ $unit->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.units.transaction', $unit) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Transaksi - {{ $unit->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi *</label>
                        <select name="type" class="form-select" required>
                            <option value="">Pilih Tipe</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" min="0" step="1000" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan *</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi *</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="credit">Kartu Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
