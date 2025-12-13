@extends('layouts.admin')

@section('title', 'Unit Klinik')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Unit Klinik</h5>
                        <small class="text-muted">Manajemen unit usaha klinik</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Tambah Unit
                        </a>
                        <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Semua Unit
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="col-12">
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-warning rounded p-2">
                                        <i class="ti ti-first-aid-kit ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Unit</h6>
                                    <h3 class="mb-2">{{ $stats['total_units'] }}</h3>
                                    <small class="text-success">
                                        <i class="ti ti-check"></i> Aktif: {{ $stats['active_units'] }}
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
                                    <h6 class="mb-1">Total Pendapatan</h6>
                                    <h3 class="mb-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                                    <small class="text-success">
                                        <i class="ti ti-arrow-up"></i> Semua unit
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
                                    <h3 class="mb-2">Rp {{ number_format($stats['total_expenses'], 0, ',', '.') }}</h3>
                                    <small class="text-danger">
                                        <i class="ti ti-arrow-down"></i> Semua unit
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
                                        <i class="ti ti-chart-pie ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Laba</h6>
                                    <h3 class="mb-2">Rp {{ number_format($stats['total_profit'], 0, ',', '.') }}</h3>
                                    <small class="text-{{ $stats['total_profit'] >= 0 ? 'success' : 'danger' }}">
                                        <i class="ti ti-{{ $stats['total_profit'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> {{ $stats['total_profit'] >= 0 ? 'Untung' : 'Rugi' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Units List -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Unit Klinik</h5>
                    <div class="card-action">
                        <span class="badge bg-info">{{ $units->count() }} unit</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($units->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Unit</th>
                                        <th>Manajer</th>
                                        <th>Status</th>
                                        <th>Modal Awal</th>
                                        <th>Saldo</th>
                                        <th>Pendapatan</th>
                                        <th>Laba</th>
                                        <th>ROI</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($units as $unit)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-label-warning rounded me-2">
                                                        <i class="ti ti-first-aid-kit ti-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $unit->name }}</div>
                                                        <small class="text-muted">{{ $unit->address }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $unit->manager_name }}</td>
                                            <td>
                                                @if($unit->status == 'active')
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($unit->status == 'inactive')
                                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                                @else
                                                    <span class="badge bg-warning">Maintenance</span>
                                                @endif
                                            </td>
                                            <td class="text-primary">Rp {{ number_format($unit->initial_capital, 0, ',', '.') }}</td>
                                            <td class="text-info">Rp {{ number_format($unit->current_balance, 0, ',', '.') }}</td>
                                            <td class="text-success">Rp {{ number_format($unit->monthlyRevenue, 0, ',', '.') }}</td>
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
                                                        <li>
                                                            <a href="{{ route('admin.units.report', $unit) }}" class="dropdown-item">
                                                                <i class="ti ti-chart-bar me-1"></i> Laporan
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            @if($unit->transactions()->count() == 0)
                                                                <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="dropdown-item p-0">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                                                                        <i class="ti ti-trash me-1"></i> Hapus
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ti ti-first-aid-kit text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">Belum ada unit klinik</h5>
                            <p class="text-muted">Tambahkan unit klinik pertama untuk memulai</p>
                            <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Tambah Unit Klinik
                            </a>
                        </div>
                    @endif
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
