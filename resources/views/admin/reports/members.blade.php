@extends('layouts.admin')

@section('title', 'Laporan Anggota')

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
                        <h5 class="card-title mb-0">Laporan Anggota</h5>
                        <small class="text-muted">Analisis keanggotaan koperasi</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.reports.members') }}" class="d-flex gap-2">
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
        
        <!-- Member Statistics Cards -->
        <div class="col-12">
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-info rounded p-2">
                                        <i class="ti ti-users ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Anggota</h6>
                                    <h3 class="mb-2">{{ $totalMembers }}</h3>
                                    <small class="text-info">Semua waktu</small>
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
                                        <i class="ti ti-user-check ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Anggota Aktif</h6>
                                    <h3 class="mb-2">{{ $activeMembers }}</h3>
                                    <small class="text-success">{{ number_format($activeMembers / $totalMembers * 100, 1) }}% dari total</small>
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
                                        <i class="ti ti-user-plus ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Anggota Baru</h6>
                                    <h3 class="mb-2">{{ $newMembers }}</h3>
                                    <small class="text-primary">Periode ini</small>
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
                                        <i class="ti ti-user-check ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Terverifikasi</h6>
                                    <h3 class="mb-2">{{ $verifiedMembers }}</h3>
                                    <small class="text-warning">{{ number_format($verifiedMembers / $totalMembers * 100, 1) }}% dari total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Members by Business Sector -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Anggota per Sektor Usaha</h5>
                    <small class="text-muted">Distribusi anggota berdasarkan sektor usaha</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sektor Usaha</th>
                                    <th class="text-right">Jumlah Anggota</th>
                                    <th class="text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membersBySector as $sector)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-primary rounded me-2">
                                                    <i class="ti ti-building-factory-2 ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $sector->business_sector ?: 'Tidak Diketahui' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">{{ $sector->count }}</td>
                                        <td class="text-right">
                                            <span class="badge bg-{{ $sector->count / $activeMembers * 100 >= 20 ? 'success' : ($sector->count / $activeMembers * 100 >= 10 ? 'warning' : 'secondary') }}">
                                                {{ number_format($sector->count / $activeMembers * 100, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-right">{{ $membersBySector->sum('count') }}</td>
                                    <td class="text-right">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Members by Experience -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Anggota per Pengalaman</h5>
                    <small class="text-muted">Distribusi anggota berdasarkan pengalaman</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pengalaman</th>
                                    <th class="text-right">Jumlah Anggota</th>
                                    <th class="text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membersByExperience as $experience)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-info rounded me-2">
                                                    <i class="ti ti-award ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $experience->experience ?: 'Tidak Diketahui' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">{{ $experience->count }}</td>
                                        <td class="text-right">
                                            <span class="badge bg-{{ $experience->count / $activeMembers * 100 >= 20 ? 'success' : ($experience->count / $activeMembers * 100 >= 10 ? 'warning' : 'secondary') }}">
                                                {{ number_format($experience->count / $activeMembers * 100, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-right">{{ $membersByExperience->sum('count') }}</td>
                                    <td class="text-right">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Member Growth Chart -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tren Pertumbuhan Anggota</h5>
                    <small class="text-muted">Grafik pertumbuhan anggota periode ini</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="memberGrowthChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Rata-rata Pertumbuhan</span>
                                    <span class="fw-bold text-primary">{{ $memberGrowth['average_growth'] ?? 0 }}%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Pertumbuhan Tertinggi</span>
                                    <span class="fw-bold text-success">{{ $memberGrowth['highest_growth'] ?? 0 }}%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Hari Pendaftaran Terbanyak</span>
                                    <span class="fw-bold text-info">{{ $memberGrowth['peak_day'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Savers -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">10 Penabung Terbaik</h5>
                    <small class="text-muted">Anggota dengan total simpanan tertinggi</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Anggota</th>
                                    <th>Nomor Anggota</th>
                                    <th>Sektor Usaha</th>
                                    <th class="text-right">Total Simpanan</th>
                                    <th class="text-right">Jumlah Pinjaman</th>
                                    <th class="text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSavers as $saver)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-success rounded me-2">
                                                    <i class="ti ti-user ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $saver['member']->full_name }}</div>
                                                    <small class="text-muted">{{ $saver['member']->join_date->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $saver['member']->member_number }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $saver['member']->business_sector ?: 'Tidak Diketahui' }}</span>
                                        </td>
                                        <td class="text-right text-success">
                                            Rp {{ number_format($saver['total_savings'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-warning">
                                            {{ $saver['loan_count'] }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $saver['member']->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ $saver['member']->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
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
        
        <!-- Members with Active Loans -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Anggota dengan Pinjaman Aktif</h5>
                    <small class="text-muted">Daftar anggota yang memiliki pinjaman aktif</small>
                </div>
                <div class="card-body">
                    @if($membersWithLoans->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Anggota</th>
                                        <th>Nomor Anggota</th>
                                        <th class="text-right">Total Pinjaman</th>
                                        <th class="text-right">Jumlah Pinjaman</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($membersWithLoans as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-label-warning rounded me-2">
                                                        <i class="ti ti-user ti-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $member->full_name }}</div>
                                                        <small class="text-muted">{{ $member->join_date->format('d M Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $member->member_number }}</td>
                                            <td class="text-right text-danger">
                                                Rp {{ number_format($member->savingsLoans->where('type', 'loan')->sum('amount'), 0, ',', '.') }}
                                            </td>
                                            <td class="text-right text-info">
                                                {{ $member->savingsLoans->where('type', 'loan')->count() }}
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Aktif</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ti ti-wallet ti-3x text-muted mb-3"></i>
                            </div>
                            <h5 class="text-muted">Tidak ada anggota dengan pinjaman aktif</h5>
                            <p class="text-muted">Semua pinjaman telah dilunasi atau tidak ada pinjaman aktif</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('memberGrowthChart').getContext('2d');
    
    const growthData = @json($memberGrowth);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: growthData.labels || [],
            datasets: [{
                label: 'Pertumbuhan Anggota',
                data: growthData.growth || [],
                borderColor: '#409EFF',
                backgroundColor: 'rgba(64, 158, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
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
                            return value + ' anggota';
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
