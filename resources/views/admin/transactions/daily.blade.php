@extends('layouts.admin')

@section('title', 'Laporan Harian')

@php
use Carbon\Carbon;
@endphp

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Laporan Harian</h5>
                        <small class="text-muted">Laporan transaksi harian</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.transactions.daily') }}" class="d-flex gap-2">
                            <input type="date" name="date" class="form-control" value="{{ $date }}" max="{{ now()->format('Y-m-d') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </form>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Semua Transaksi
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
                                    <div class="avatar bg-label-success rounded p-2">
                                        <i class="ti ti-trending-up ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Pendapatan</h6>
                                    <h3 class="mb-2">Rp {{ number_format($dailyStats['income'], 0, ',', '.') }}</h3>
                                    <small class="text-success">
                                        <i class="ti ti-arrow-up"></i> {{ $transactions->where('type', 'income')->count() }} transaksi
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
                                    <h3 class="mb-2">Rp {{ number_format($dailyStats['expense'], 0, ',', '.') }}</h3>
                                    <small class="text-danger">
                                        <i class="ti ti-arrow-down"></i> {{ $transactions->where('type', 'expense')->count() }} transaksi
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
                                    <h6 class="mb-1">Laba Bersih</h6>
                                    <h3 class="mb-2">Rp {{ number_format($dailyStats['profit'], 0, ',', '.') }}</h3>
                                    <small class="text-{{ $dailyStats['profit'] >= 0 ? 'success' : 'danger' }}">
                                        <i class="ti ti-{{ $dailyStats['profit'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> 
                                        {{ $dailyStats['profit'] >= 0 ? 'Untung' : 'Rugi' }}
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
                                    <h6 class="mb-1">Total Transaksi</h6>
                                    <h3 class="mb-2">{{ $dailyStats['count'] }}</h3>
                                    <small class="text-info">
                                        <i class="ti ti-receipt"></i> {{ Carbon::parse($date)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transactions Table -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Transaksi Hari Ini</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <form action="{{ route('admin.transactions.export') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-download me-1"></i> Export CSV
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Waktu</th>
                                        <th>Deskripsi</th>
                                        <th>Kategori</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
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
                                                        @endif
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
                                <i class="ti ti-receipt ti-3x text-muted mb-3"></i>
                            </div>
                            <h5 class="text-muted">Tidak ada transaksi pada tanggal {{ Carbon::parse($date)->format('d M Y') }}</h5>
                            <p class="text-muted">Pilih tanggal lain atau tambahkan transaksi baru</p>
                            <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Tambah Transaksi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .card-header .d-flex,
    .dropdown,
    .btn {
        display: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
