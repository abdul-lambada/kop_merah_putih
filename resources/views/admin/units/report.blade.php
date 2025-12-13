@extends('layouts.admin')

@section('title', 'Laporan Unit Usaha')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Laporan Unit Usaha: {{ $unit->name }}</h5>
                        <small class="text-muted">{{ ucfirst($unit->type) }} - {{ $unit->manager_name }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" onclick="printReport()">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="button" class="btn btn-success" onclick="downloadPDF()">
                            <i class="ti ti-file-download me-1"></i> Download PDF
                        </button>
                        <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Summary Cards -->
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
                                    <h3 class="mb-2">Rp {{ number_format($unit->revenue, 0, ',', '.') }}</h3>
                                    <small class="text-success">
                                        <i class="ti ti-arrow-up"></i> Semua waktu
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
                                    <h3 class="mb-2">Rp {{ number_format($unit->expenses, 0, ',', '.') }}</h3>
                                    <small class="text-danger">
                                        <i class="ti ti-arrow-down"></i> Semua waktu
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
                                    <h6 class="mb-1">Laba Bersih</h6>
                                    <h3 class="mb-2">Rp {{ number_format($unit->profit, 0, ',', '.') }}</h3>
                                    <small class="text-{{ $unit->profit >= 0 ? 'success' : 'danger' }}">
                                        <i class="ti ti-{{ $unit->profit >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> {{ $unit->profit >= 0 ? 'Untung' : 'Rugi' }}
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
                                        <i class="ti ti-wallet ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Saldo Saat Ini</h6>
                                    <h3 class="mb-2">Rp {{ number_format($unit->current_balance, 0, ',', '.') }}</h3>
                                    <small class="text-info">
                                        <i class="ti ti-pig"></i> Tersedia
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Daily Trend Chart -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tren 30 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pendapatan</th>
                                    <th>Pengeluaran</th>
                                    <th>Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_reverse($dailyTrend) as $data)
                                    <tr>
                                        <td>{{ $data['date'] }}</td>
                                        <td class="text-success">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                                        <td class="text-danger">Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                                        <td class="text-{{ ($data['income'] - $data['expense']) >= 0 ? 'success' : 'danger' }} fw-bold">
                                            Rp {{ number_format($data['income'] - $data['expense'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Categories -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kategori Teratas</h5>
                </div>
                <div class="card-body">
                    @if($topCategories->count() > 0)
                        @foreach($topCategories as $category)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="fw-medium">{{ $category->category }}</div>
                                    <small class="text-muted">{{ $category->type == 'income' ? 'Pendapatan' : 'Pengeluaran' }} ({{ $category->count }} transaksi)</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-{{ $category->type == 'income' ? 'success' : 'danger' }}">
                                        Rp {{ number_format($category->total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-chart-bar text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Belum ada data kategori</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Monthly Performance -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performa Bulanan {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    @if($monthlyData->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Pendapatan</th>
                                        <th>Pengeluaran</th>
                                        <th>Laba</th>
                                        <th>Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyData as $year => $months)
                                        @foreach($months as $month => $data)
                                            <tr>
                                                <td>{{ $month }} {{ $year }}</td>
                                                <td class="text-success">Rp {{ number_format($data->where('type', 'income')->sum('total'), 0, ',', '.') }}</td>
                                                <td class="text-danger">Rp {{ number_format($data->where('type', 'expense')->sum('total'), 0, ',', '.') }}</td>
                                                <td class="text-{{ ($data->where('type', 'income')->sum('total') - $data->where('type', 'expense')->sum('total')) >= 0 ? 'success' : 'danger' }} fw-bold">
                                                    Rp {{ number_format($data->where('type', 'income')->sum('total') - $data->where('type', 'expense')->sum('total'), 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $data->sum('total') > 0 ? $data->count() : 0 }} transaksi
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-calendar text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada data bulanan untuk tahun ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Unit Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Unit</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Nama Unit</strong></td>
                                    <td>{{ $unit->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe</strong></td>
                                    <td><span class="badge bg-primary">{{ ucfirst($unit->type) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $unit->status == 'active' ? 'success' : ($unit->status == 'inactive' ? 'secondary' : 'warning') }}">
                                            {{ $unit->status == 'active' ? 'Aktif' : ($unit->status == 'inactive' ? 'Tidak Aktif' : 'Maintenance') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Manajer</strong></td>
                                    <td>{{ $unit->manager_name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Modal Awal</strong></td>
                                    <td class="text-primary">Rp {{ number_format($unit->initial_capital, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>ROI</strong></td>
                                    <td class="text-{{ $unit->roi >= 10 ? 'success' : ($unit->roi >= 0 ? 'warning' : 'danger') }}">
                                        {{ number_format($unit->roi, 1) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Transaksi</strong></td>
                                    <td>{{ $unit->transactions()->count() }} transaksi</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>{{ $unit->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($unit->description)
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p class="mb-0">{{ $unit->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header .d-flex.gap-2,
    .btn,
    .container-xxl,
    .container-p-y {
        display: none !important;
    }
    
    .card-header {
        background: white !important;
        border-bottom: 2px solid #333 !important;
        margin-bottom: 20px !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 30px !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    .table-responsive {
        overflow: visible !important;
    }
    
    .table {
        font-size: 12px !important;
    }
    
    .badge {
        border: 1px solid #333 !important;
        background: white !important;
        color: #333 !important;
    }
    
    .avatar {
        display: none !important;
    }
    
    .row {
        margin: 0 !important;
    }
    
    .col-12, .col-xl-8, .col-xl-4, .col-md-6, .col-sm-6 {
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
    
    h5 {
        font-size: 16px !important;
        font-weight: bold !important;
        margin-bottom: 15px !important;
    }
    
    .text-success, .text-danger, .text-primary, .text-warning, .text-info {
        color: #333 !important;
    }
    
    .text-muted {
        color: #666 !important;
    }
    
    @page {
        margin: 1cm;
        size: A4;
    }
}
</style>

<script>
function printReport() {
    window.print();
}

function downloadPDF() {
    window.location.href = '{{ route("admin.units.report.pdf", $unit) }}';
}
</script>
@endsection
