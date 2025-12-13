@extends('layouts.admin')

@section('title', 'Laporan Unit Usaha')

@php
use Carbon\Carbon;

// Calculate period dates
if ($period === 'monthly') {
    $startDate = Carbon::create($year, $month, 1);
    $endDate = $startDate->copy()->endOfMonth();
} elseif ($period === 'quarterly') {
    $quarter = ceil($month / 3);
    $startMonth = ($quarter - 1) * 3 + 1;
    $startDate = Carbon::create($year, $startMonth, 1);
    $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
} else { // annual
    $startDate = Carbon::create($year, 1, 1);
    $endDate = $startDate->copy()->endOfYear();
}
@endphp

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Laporan Unit Usaha</h5>
                        <small class="text-muted">Analisis performa unit usaha koperasi</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.reports.units') }}" class="d-flex gap-2">
                            <select name="period" class="form-select" style="width: 120px;">
                                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="quarterly" {{ $period == 'quarterly' ? 'selected' : '' }}>Triwulan</option>
                                <option value="annual" {{ $period == 'annual' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                            <select name="month" class="form-select" style="width: 150px;" {{ $period == 'annual' ? 'disabled' : '' }}>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                            <select name="year" class="form-select" style="width: 100px;">
                                @for($y = Carbon::now()->year; $y >= Carbon::now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="ti ti-search me-1"></i> Filter
                            </button>
                        </form>
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Period Information -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Periode Laporan</h6>
                            <h5 class="mb-0">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</h5>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Periode</small>
                            <h6 class="mb-0">
                                {{ $period == 'monthly' ? 'Bulanan' : ($period == 'quarterly' ? 'Triwulan' : 'Tahunan') }}
                                {{ $period == 'quarterly' ? 'Q' . ceil($month/3) : '' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Units Overview Cards -->
        <div class="col-12">
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-primary rounded p-2">
                                        <i class="ti ti-building-factory-2 ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Unit</h6>
                                    <h3 class="mb-2">{{ $totalUnits }}</h3>
                                    <small class="text-primary">Semua unit aktif</small>
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
                                    <h3 class="mb-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                                    <small class="text-success">Periode ini</small>
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
                                        <i class="ti ti-trending-down ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Pengeluaran</h6>
                                    <h3 class="mb-2">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                                    <small class="text-warning">Periode ini</small>
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
                                        <i class="ti ti-chart-line ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Laba</h6>
                                    <h3 class="mb-2">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
                                    <small class="text-{{ $totalProfit >= 0 ? 'success' : 'danger' }}">
                                        {{ $totalProfit >= 0 ? 'Untung' : 'Rugi' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Units by Type -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Unit per Jenis Usaha</h5>
                    <small class="text-muted">Distribusi unit berdasarkan jenis usaha</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Jenis Unit</th>
                                    <th class="text-right">Jumlah Unit</th>
                                    <th class="text-right">Total Pendapatan</th>
                                    <th class="text-right">Total Pengeluaran</th>
                                    <th class="text-right">Total Laba</th>
                                    <th class="text-right">Rata-rata Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitsByType as $type => $data)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-primary rounded me-2">
                                                    <i class="ti ti-{{ $type == 'sembako' ? 'shopping-cart' : ($type == 'apotek' ? 'pill' : ($type == 'klinik' ? 'first-aid-kit' : 'truck')) }} ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ ucfirst($type) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">{{ $data['count'] }}</td>
                                        <td class="text-right text-success">
                                            Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-danger">
                                            Rp {{ number_format($data['expenses'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right {{ $data['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($data['profit'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            <span class="badge bg-{{ $data['revenue'] > 0 ? ($data['profit'] / $data['revenue'] * 100 >= 10 ? 'success' : 'warning') : 'secondary' }}">
                                                {{ $data['revenue'] > 0 ? number_format($data['profit'] / $data['revenue'] * 100, 1) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-right">{{ $unitsByType->sum('count') }}</td>
                                    <td class="text-right text-success">Rp {{ number_format($unitsByType->sum('revenue'), 0, ',', '.') }}</td>
                                    <td class="text-right text-danger">Rp {{ number_format($unitsByType->sum('expenses'), 0, ',', '.') }}</td>
                                    <td class="text-right {{ $unitsByType->sum('profit') >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($unitsByType->sum('profit'), 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ $unitsByType->sum('revenue') > 0 ? number_format($unitsByType->sum('profit') / $unitsByType->sum('revenue') * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Individual Unit Performance -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performa Individual Unit</h5>
                    <small class="text-muted">Detail performa setiap unit usaha</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Unit</th>
                                    <th>Jenis</th>
                                    <th class="text-right">Pendapatan</th>
                                    <th class="text-right">Pengeluaran</th>
                                    <th class="text-right">Laba</th>
                                    <th class="text-right">Margin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitDetails as $unit)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-primary rounded me-2">
                                                    <i class="ti ti-{{ $unit->type == 'sembako' ? 'shopping-cart' : ($unit->type == 'apotek' ? 'pill' : ($unit->type == 'klinik' ? 'first-aid-kit' : 'truck')) }} ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $unit->name }}</div>
                                                    <small class="text-muted">{{ $unit->location ?? 'Tidak ada lokasi' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($unit->type) }}</span>
                                        </td>
                                        <td class="text-right text-success">
                                            Rp {{ number_format($unit->revenue ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-danger">
                                            Rp {{ number_format($unit->expenses ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-right {{ ($unit->revenue - $unit->expenses) >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format(($unit->revenue ?? 0) - ($unit->expenses ?? 0), 0, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            <span class="badge bg-{{ ($unit->revenue ?? 0) > 0 ? ((($unit->revenue - $unit->expenses) / $unit->revenue * 100) >= 10 ? 'success' : 'warning') : 'secondary' }}">
                                                {{ ($unit->revenue ?? 0) > 0 ? number_format((($unit->revenue - $unit->expenses) / $unit->revenue * 100), 1) : 0 }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $unit->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ $unit->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Performing Units -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">5 Unit Terbaik</h5>
                    <small class="text-muted">Unit dengan laba tertinggi</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Unit</th>
                                    <th>Jenis</th>
                                    <th class="text-right">Pendapatan</th>
                                    <th class="text-right">Pengeluaran</th>
                                    <th class="text-right">Laba</th>
                                    <th class="text-right">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUnits as $unit)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-success rounded me-2">
                                                    <i class="ti ti-trophy ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $unit['name'] }}</div>
                                                    <small class="text-muted">{{ $unit['location'] ?? 'Tidak ada lokasi' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($unit['type']) }}</span>
                                        </td>
                                        <td class="text-right text-success">
                                            Rp {{ number_format($unit['revenue'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-danger">
                                            Rp {{ number_format($unit['expenses'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-success">
                                            Rp {{ number_format($unit['profit'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            <span class="badge bg-success">
                                                {{ $unit['revenue'] > 0 ? number_format($unit['profit'] / $unit['revenue'] * 100, 1) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Units Performance Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tren Performa Unit</h5>
                    <small class="text-muted">Grafik performa unit periode ini</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="unitPerformanceChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Rata-rata Pendapatan</span>
                                    <span class="fw-bold text-success">Rp {{ number_format($unitStats['average_revenue'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Rata-rata Pengeluaran</span>
                                    <span class="fw-bold text-danger">Rp {{ number_format($unitStats['average_expenses'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Unit Terbaik</span>
                                    <span class="fw-bold text-primary">{{ $unitStats['best_unit'] ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Margin Rata-rata</span>
                                    <span class="fw-bold text-info">{{ $unitStats['average_margin'] ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('unitPerformanceChart').getContext('2d');
    
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels || [],
            datasets: [
                {
                    label: 'Pendapatan',
                    data: chartData.revenue || [],
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: '#28a745',
                    borderWidth: 1
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.expenses || [],
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: '#dc3545',
                    borderWidth: 1
                },
                {
                    label: 'Laba',
                    data: chartData.profit || [],
                    backgroundColor: 'rgba(64, 158, 255, 0.8)',
                    borderColor: '#409EFF',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});

// Period change handler
document.querySelector('select[name="period"]').addEventListener('change', function() {
    const monthSelect = document.querySelector('select[name="month"]');
    if (this.value === 'annual') {
        monthSelect.disabled = true;
    } else {
        monthSelect.disabled = false;
    }
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .card-header .d-flex,
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
