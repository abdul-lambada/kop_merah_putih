@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Role-based Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            Dashboard - {{ $priorityRole ?? 'User' }}
                        </h5>
                        <small class="text-muted">
                            Selamat datang, {{ auth()->user()->full_name ?? auth()->user()->name }}!
                        </small>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ti ti-settings me-1"></i> Quick Actions
                        </button>
                        <ul class="dropdown-menu">
                            @can('profile.edit')
                            <li><a class="dropdown-item" href="{{ route('admin.settings.profile') }}">
                                <i class="ti ti-user me-2"></i>Edit Profile
                            </a></li>
                            @endcan
                            @can('members.create')
                            <li><a class="dropdown-item" href="{{ route('admin.members.create') }}">
                                <i class="ti ti-user-plus me-2"></i>Tambah Anggota
                            </a></li>
                            @endcan
                            @can('transactions.create')
                            <li><a class="dropdown-item" href="{{ route('admin.transactions.create') }}">
                                <i class="ti ti-cash me-2"></i>Transaksi Baru
                            </a></li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Super Admin Dashboard - Priority 1 -->
    @if(auth()->user()->roles->contains('name', 'Super Admin'))
    <div class="row g-4 mb-4">
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
                            <h6 class="mb-1">Total Users</h6>
                            <h3 class="mb-2">{{ number_format($data['overview']['total_users'], 0, ',', '.') }}</h3>
                            <small class="text-success">All users</small>
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
                                <i class="ti ti-pig-money ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Simpanan</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['financial']['total_savings'], 0, ',', '.') }}</h3>
                            <small class="text-success">All savings</small>
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
                                <i class="ti ti-building ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Units</h6>
                            <h3 class="mb-2">{{ number_format($data['overview']['total_units'], 0, ',', '.') }}</h3>
                            <small class="text-success">All units</small>
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
                                <i class="ti ti-credit-card ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Pinjaman</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['financial']['total_loans'], 0, ',', '.') }}</h3>
                            <small class="text-success">All loans</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_activities'] ?? [] as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $activity->description ?? 'Transaction' }}</td>
                                    <td>Rp {{ number_format($activity->amount ?? 0, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success">{{ $activity->status ?? 'Completed' }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent activities found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ketua Koperasi Dashboard - Priority 2 -->
    @elseif(auth()->user()->roles->contains('name', 'Ketua Koperasi'))
    <div class="row g-4 mb-4">
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
                            <h3 class="mb-2">{{ number_format($data['overview']['total_members'], 0, ',', '.') }}</h3>
                            <small class="text-success">+{{ number_format($data['overview']['new_members_this_month'], 0, ',', '.') }} baru</small>
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
                                <i class="ti ti-pig-money ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Simpanan</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['financial']['total_savings'], 0, ',', '.') }}</h3>
                            <small class="text-muted">All member savings</small>
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
                                <i class="ti ti-building-store ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Unit Usaha</h6>
                            <h3 class="mb-2">{{ number_format($data['overview']['total_units'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Active units</small>
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
                                <i class="ti ti-credit-card ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Portfolio Pinjaman</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['financial']['total_loans'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Active loans</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pending Approvals</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Pinjaman Besar (>50jt)</h6>
                            <small class="text-muted">Menunggu persetujuan</small>
                        </div>
                        <h4 class="mb-0">{{ $data['pending_approvals']['large_loans'] }}</h4>
                    </div>
                    @can('loans.approve')
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-primary">Review</a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">New Members</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Pendaftar Baru</h6>
                            <small class="text-muted">Menunggu verifikasi</small>
                        </div>
                        <h4 class="mb-0">{{ $data['pending_approvals']['new_members'] }}</h4>
                    </div>
                    @can('members.verify')
                    <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-primary">Verify</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- Manager Keuangan Dashboard - Priority 3 -->
    @elseif(auth()->user()->roles->contains('name', 'Manager Keuangan'))
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-4">
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
                            @if(isset($data['savings']['total_savings']))
                                <h3 class="mb-2">Rp {{ number_format($data['savings']['total_savings'], 0, ',', '.') }}</h3>
                                <small class="text-success">+{{ number_format($data['savings']['new_savings_this_month'] ?? $data['savings']['savings_this_month'] ?? 0, 0, ',', '.') }} bulan ini</small>
                            @else
                                <h3 class="mb-2">Rp 0</h3>
                                <small class="text-muted">Data tidak tersedia</small>
                            @endif
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
                            <h6 class="mb-1">Portfolio Pinjaman</h6>
                            @if(isset($data['loans']['loan_portfolio']))
                                <h3 class="mb-2">Rp {{ number_format($data['loans']['loan_portfolio'], 0, ',', '.') }}</h3>
                                <small class="text-warning">{{ $data['loans']['overdue_loans'] ?? 0 }} overdue</small>
                            @else
                                <h3 class="mb-2">Rp 0</h3>
                                <small class="text-muted">Data tidak tersedia</small>
                            @endif
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
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-cash ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Transaksi Hari Ini</h6>
                            <h3 class="mb-2">{{ number_format($data['transactions']['daily_transactions'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Transactions today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pinjaman Aktif</h5>
                </div>
                <div class="card-body">
                    @if(isset($data['loans']['active_loans']))
                        <h3 class="mb-2">{{ number_format($data['loans']['active_loans'], 0, ',', '.') }}</h3>
                    @else
                        <h3 class="mb-2">0</h3>
                    @endif
                    <small class="text-muted">Active loans</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pinjaman Overdue</h5>
                </div>
                <div class="card-body">
                    @if(isset($data['loans']['overdue_loans']))
                        <h3 class="mb-2 text-danger">{{ number_format($data['loans']['overdue_loans'], 0, ',', '.') }}</h3>
                    @else
                        <h3 class="mb-2 text-danger">0</h3>
                    @endif
                    <small class="text-muted">Need attention</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Total Bulanan</h5>
                </div>
                <div class="card-body">
                    @if(isset($data['transactions']['monthly_total']))
                        <h3 class="mb-2">Rp {{ number_format($data['transactions']['monthly_total'], 0, ',', '.') }}</h3>
                    @else
                        <h3 class="mb-2">Rp 0</h3>
                    @endif
                    <small class="text-muted">This month</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Manager Unit Dashboard - Priority 4 -->
    @elseif(auth()->user()->roles->contains('name', 'Manager Unit'))
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-building-store ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Units</h6>
                            <h3 class="mb-2">{{ number_format($data['units']['total_units'], 0, ',', '.') }}</h3>
                            <small class="text-success">{{ number_format($data['units']['active_units'], 0, ',', '.') }} aktif</small>
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
                            <div class="avatar bg-label-primary rounded p-2">
                                <i class="ti ti-users ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Total Staff</h6>
                            <h3 class="mb-2">{{ number_format($data['staff']['total_staff'], 0, ',', '.') }}</h3>
                            <small class="text-success">{{ number_format($data['staff']['active_staff'], 0, ',', '.') }} aktif</small>
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
                            <div class="avatar bg-label-success rounded p-2">
                                <i class="ti ti-cash ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Revenue Bulanan</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['transactions']['monthly_revenue'], 0, ',', '.') }}</h3>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Performance -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Unit Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Unit Name</th>
                                    <th>Today Revenue</th>
                                    <th>Monthly Revenue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['units']['unit_performance'] ?? [] as $unit)
                                <tr>
                                    <td>{{ $unit->name }}</td>
                                    <td>Rp {{ number_format($unit->today_revenue ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($unit->monthly_revenue ?? 0, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Staff Administrasi Dashboard - Priority 5 -->
    @elseif(auth()->user()->roles->contains('name', 'Staff Administrasi'))
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-4">
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
                            <h3 class="mb-2">{{ number_format($data['members']['total_members'], 0, ',', '.') }}</h3>
                            <small class="text-success">{{ number_format($data['members']['new_members_today'], 0, ',', '.') }} hari ini</small>
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
                                <i class="ti ti-user-check ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Pending Verification</h6>
                            <h3 class="mb-2">{{ number_format($data['members']['pending_verifications'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Need verification</small>
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
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-file-text ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Applications</h6>
                            <h3 class="mb-2">{{ number_format($data['tasks']['applications_pending'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Pending applications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Today -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registrations Today</h5>
                </div>
                <div class="card-body">
                    <h3 class="mb-2">{{ number_format($data['tasks']['registrations_today'], 0, ',', '.') }}</h3>
                    <small class="text-muted">New registrations</small>
                    @can('members.create')
                    <div class="mt-3">
                        <a href="{{ route('admin.members.create') }}" class="btn btn-sm btn-primary">Add New Member</a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    @can('members.verify')
                    <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-primary me-2">Verify Members</a>
                    @endcan
                    @can('savings.create')
                    <a href="{{ route('admin.savings.create') }}" class="btn btn-sm btn-outline-success">Add Savings</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- Bendahara Unit Dashboard - Priority 6 -->
    @elseif(auth()->user()->roles->contains('name', 'Bendahara Unit'))
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-success rounded p-2">
                                <i class="ti ti-cash ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Kas Hari Ini</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['cash']['today_cash'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Today's transactions</small>
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
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-calendar ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Kas Bulanan</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['cash']['monthly_cash'], 0, ',', '.') }}</h3>
                            <small class="text-muted">This month</small>
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
                                <i class="ti ti-wallet ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Saldo Kas</h6>
                            <h3 class="mb-2">Rp {{ number_format($data['cash']['cash_balance'], 0, ',', '.') }}</h3>
                            <small class="text-muted">Current balance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['transactions']['recent_transactions'] ?? [] as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('H:i') }}</td>
                                    <td>{{ $transaction->description ?? 'Transaction' }}</td>
                                    <td>Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success">{{ $transaction->status ?? 'Completed' }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent transactions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @can('transactions.create')
                    <div class="mt-3">
                        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">New Transaction</a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Anggota Dashboard - Priority 7 (Fallback) -->
    @else
    <div class="row g-4 mb-4 py-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar avatar-xl mb-3">
                            <img src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : asset('sneat-1.0.0/assets/img/avatars/1.png') }}" alt="Profile" class="rounded-circle">
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->full_name ?? auth()->user()->name }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-primary">Anggota</span>
                            @if(isset($data['profile']['membership_status']))
                                <span class="badge {{ $data['profile']['membership_status'] === 'active' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $data['profile']['membership_status'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8 mt-4 py-5">
            <div class="row g-4 mb-4">
                <div class="col-sm-6">
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
                                    @if(isset($data['savings']['total_savings']))
                                        <h3 class="mb-2">Rp {{ number_format($data['savings']['total_savings'], 0, ',', '.') }}</h3>
                                        <small class="text-success">+{{ number_format($data['savings']['savings_this_month'] ?? $data['savings']['new_savings_this_month'] ?? 0, 0, ',', '.') }} bulan ini</small>
                                    @else
                                        <h3 class="mb-2">Rp 0</h3>
                                        <small class="text-muted">Data tidak tersedia</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-warning rounded p-2">
                                        <i class="ti ti-credit-card ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Pinjaman Aktif</h6>
                                    @if(isset($data['loans']['active_loans']))
                                        <h3 class="mb-2">{{ $data['loans']['active_loans']->count() }}</h3>
                                        <small class="text-muted">{{ $data['loans']['loan_history'] ?? 0 }} selesai</small>
                                    @else
                                        <h3 class="mb-2">0</h3>
                                        <small class="text-muted">Data tidak tersedia</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Loans -->
    @if(isset($data['loans']['active_loans']) && $data['loans']['active_loans']->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pinjaman Aktif</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Amount</th>
                                    <th>Monthly Payment</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['loans']['active_loans'] as $loan)
                                <tr>
                                    <td>{{ $loan->loan_type ?? 'Personal' }}</td>
                                    <td>Rp {{ number_format($loan->amount, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($loan->monthly_payment ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y') : '-' }}</td>
                                    <td><span class="badge bg-success">{{ $loan->status }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['transactions']['recent_transactions'] ?? [] as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    <td>{{ $transaction->description ?? 'Transaction' }}</td>
                                    <td><span class="badge bg-info">{{ $transaction->type ?? 'General' }}</span></td>
                                    <td>Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent transactions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @can('loans.create')
                        <a href="{{ route('admin.loans.create') }}" class="btn btn-primary me-2">Apply for Loan</a>
                        @endcan
                        @can('savings.create')
                        <a href="{{ route('admin.savings.create') }}" class="btn btn-success">Add Savings</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
