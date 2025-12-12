@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Anggota -->
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
                            <h6 class="mb-1">Total Anggota</h6>
                            <h3 class="mb-2">{{ number_format($stats['total_members'], 0, ',', '.') }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> {{ number_format($stats['new_members_this_month'], 0, ',', '.') }} baru bulan ini
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Simpanan -->
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
                                <i class="ti ti-arrow-up"></i> +{{ number_format($stats['savings_growth'], 0, ',', '.') }} bulan ini
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pinjaman -->
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
                            <h6 class="mb-1">Portfolio Pinjaman</h6>
                            <h3 class="mb-2">Rp {{ number_format($stats['active_loan_portfolio'], 0, ',', '.') }}</h3>
                            <small class="text-danger">
                                <i class="ti ti-alert-triangle"></i> {{ $stats['overdue_loans'] }} overdue
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Usaha -->
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-building-store ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Unit Usaha Aktif</h6>
                            <h3 class="mb-2">{{ $stats['active_units'] }}</h3>
                            <small class="text-success">
                                <i class="ti ti-arrow-up"></i> Rp {{ number_format($stats['monthly_unit_revenue'], 0, ',', '.') }} pendapatan
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Performa 6 Bulan Terakhir</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active">Pendapatan</button>
                        <button type="button" class="btn btn-outline-primary">Pengeluaran</button>
                        <button type="button" class="btn btn-outline-primary">Profit</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Transaksi</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <div class="avatar-initial rounded-circle bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                        <i class="ti ti-{{ $transaction->type == 'income' ? 'arrow-down' : 'arrow-up' }}"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $transaction->description }}</h6>
                                                    <small class="text-muted">{{ $transaction->transaction_date->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
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

        <!-- Pending Loans -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pinjaman Pending</h5>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($pendingLoans->count() > 0)
                        @foreach($pendingLoans as $loan)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">{{ $loan->member->full_name }}</h6>
                                    <small class="text-muted">Rp {{ number_format($loan->amount, 0, ',', '.') }}</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-outline-primary">Detail</a>
                                    <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-checklist ti-3x text-success mb-3"></i>
                            <h6>Tidak ada pinjaman pending</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Business Sector Distribution -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribusi Sektor Usaha Anggota</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($sectorDistribution as $sector)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $sector->business_sector }}</span>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-3" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-primary" style="width: {{ ($sector->count / $stats['total_members']) * 100 }}%"></div>
                                        </div>
                                        <span class="badge bg-primary">{{ $sector->count }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Performance -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performa Unit Usaha</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitPerformance as $unit)
                                    <tr>
                                        <td>{{ $unit->name }}</td>
                                        <td class="text-end">Rp {{ number_format($unit->monthly_revenue, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Performance Chart
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($monthlyChart['labels']),
        datasets: [{
            label: 'Pendapatan',
            data: @json($monthlyChart['data']),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
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
</script>
@endsection
