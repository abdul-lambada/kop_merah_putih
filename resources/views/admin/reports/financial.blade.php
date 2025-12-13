@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

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
                        <h5 class="card-title mb-0">Laporan Keuangan</h5>
                        <small class="text-muted">Analisis keuangan koperasi</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.reports.financial') }}" class="d-flex gap-2">
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
        
        <!-- Financial Overview Cards -->
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
                                    <h3 class="mb-2">Rp {{ number_format($income, 0, ',', '.') }}</h3>
                                    <small class="text-success">
                                        @if($previousPeriodData['income'] > 0)
                                            {{ $income > $previousPeriodData['income'] ? '+' : '' }}
                                            {{ number_format(($income - $previousPeriodData['income']) / $previousPeriodData['income'] * 100, 1) }}%
                                        @else
                                            Data tidak tersedia
                                        @endif
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
                                    <h3 class="mb-2">Rp {{ number_format($expense, 0, ',', '.') }}</h3>
                                    <small class="text-danger">
                                        @if($previousPeriodData['expense'] > 0)
                                            {{ $expense > $previousPeriodData['expense'] ? '+' : '' }}
                                            {{ number_format(($expense - $previousPeriodData['expense']) / $previousPeriodData['expense'] * 100, 1) }}%
                                        @else
                                            Data tidak tersedia
                                        @endif
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
                                    <h3 class="mb-2">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                                    <small class="text-{{ $netProfit >= 0 ? 'success' : 'danger' }}">
                                        {{ $netProfit >= 0 ? 'Untung' : 'Rugi' }}
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
                                        <i class="ti ti-users ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Anggota Aktif</h6>
                                    <h3 class="mb-2">{{ $activeMembersCount }}</h3>
                                    <small class="text-info">
                                        +{{ $newMembersCount }} baru
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Savings & Loans Overview -->
        <div class="col-12">
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-success rounded p-2">
                                        <i class="ti ti-pig ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Simpanan</h6>
                                    <h3 class="mb-2">Rp {{ number_format($totalSavings, 0, ',', '.') }}</h3>
                                    <small class="text-success">Periode ini</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-warning rounded p-2">
                                        <i class="ti ti-credit-card ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Pinjaman</h6>
                                    <h3 class="mb-2">Rp {{ number_format($totalLoans, 0, ',', '.') }}</h3>
                                    <small class="text-warning">Periode ini</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-danger rounded p-2">
                                        <i class="ti ti-wallet ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Portofolio Pinjaman Aktif</h6>
                                    <h3 class="mb-2">Rp {{ number_format($activeLoanPortfolio, 0, ',', '.') }}</h3>
                                    <small class="text-danger">Total aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Unit Performance -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performa Unit Usaha</h5>
                    <small class="text-muted">Ringkasan performa setiap unit</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Unit Usaha</th>
                                    <th>Tipe</th>
                                    <th class="text-right">Pendapatan</th>
                                    <th class="text-right">Pengeluaran</th>
                                    <th class="text-right">Laba</th>
                                    <th class="text-right">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitPerformance as $unit)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-primary rounded me-2">
                                                    <i class="ti ti-{{ $unit['type'] == 'sembako' ? 'shopping-cart' : ($unit['type'] == 'apotek' ? 'pill' : ($unit['type'] == 'klinik' ? 'first-aid-kit' : 'truck')) }} ti-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $unit['name'] }}</div>
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
                                        <td class="text-right {{ $unit['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($unit['profit'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            <span class="badge bg-{{ $unit['revenue'] > 0 ? ($unit['profit'] / $unit['revenue'] * 100 >= 10 ? 'success' : 'warning') : 'secondary' }}">
                                                {{ $unit['revenue'] > 0 ? number_format($unit['profit'] / $unit['revenue'] * 100, 1) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="2">Total</td>
                                    <td class="text-right text-success">Rp {{ number_format($unitPerformance->sum('revenue'), 0, ',', '.') }}</td>
                                    <td class="text-right text-danger">Rp {{ number_format($unitPerformance->sum('expenses'), 0, ',', '.') }}</td>
                                    <td class="text-right {{ $unitPerformance->sum('profit') >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($unitPerformance->sum('profit'), 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ $unitPerformance->sum('revenue') > 0 ? number_format($unitPerformance->sum('profit') / $unitPerformance->sum('revenue') * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart Section -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tren Keuangan</h5>
                    <small class="text-muted">Perbandingan pendapatan dan pengeluaran</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="financialChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Rata-rata Pendapatan</span>
                                    <span class="fw-bold text-success">Rp {{ number_format($chartData['average_income'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Rata-rata Pengeluaran</span>
                                    <span class="fw-bold text-danger">Rp {{ number_format($chartData['average_expense'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Hari Profit Terbanyak</span>
                                    <span class="fw-bold text-primary">{{ $chartData['best_day'] ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Hari Rugi Terbesar</span>
                                    <span class="fw-bold text-danger">{{ $chartData['worst_day'] ?? '-' }}</span>
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
    const ctx = document.getElementById('financialChart').getContext('2d');
    
    const chartData = @json($chartData);
    
    // Calculate averages and best/worst days from available data
    const incomeData = chartData.income || [];
    const expenseData = chartData.expense || [];
    
    const averageIncome = incomeData.length > 0 ? incomeData.reduce((a, b) => a + b, 0) / incomeData.length : 0;
    const averageExpense = expenseData.length > 0 ? expenseData.reduce((a, b) => a + b, 0) / expenseData.length : 0;
    
    // Find best and worst days
    let bestDay = '-';
    let worstDay = '-';
    
    if (incomeData.length > 0 && expenseData.length > 0) {
        const profits = incomeData.map((income, index) => income - (expenseData[index] || 0));
        const maxProfit = Math.max(...profits);
        const minProfit = Math.min(...profits);
        
        if (maxProfit > 0) {
            bestDay = 'Tgl ' + (profits.indexOf(maxProfit) + 1);
        }
        if (minProfit < 0) {
            worstDay = 'Tgl ' + (profits.indexOf(minProfit) + 1);
        }
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [
                {
                    label: 'Pendapatan',
                    data: chartData.income || [],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.expense || [],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4
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

// Update statistics display
document.addEventListener('DOMContentLoaded', function() {
    const avgIncomeEl = document.querySelector('.text-success.fw-bold');
    const avgExpenseEl = document.querySelector('.text-danger.fw-bold');
    const bestDayEl = document.querySelector('.text-primary.fw-bold');
    const worstDayEl = document.querySelector('.text-danger:last-of-type .fw-bold');
    
    if (avgIncomeEl) {
        avgIncomeEl.textContent = 'Rp ' + averageIncome.toLocaleString('id-ID');
    }
    if (avgExpenseEl) {
        avgExpenseEl.textContent = 'Rp ' + averageExpense.toLocaleString('id-ID');
    }
    if (bestDayEl) {
        bestDayEl.textContent = bestDay;
    }
    if (worstDayEl) {
        worstDayEl.textContent = worstDay;
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
