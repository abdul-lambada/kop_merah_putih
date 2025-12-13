@extends('layouts.admin')

@section('title', 'Laporan Koperasi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Laporan Koperasi</h5>
                    <a href="{{ route('admin.reports.generate') }}" class="btn btn-primary">
                        <i class="ti ti-file-plus me-1"></i> Buat Laporan Baru
                    </a>
                </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tipe Laporan</label>
                                <select name="type" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="quarterly" {{ request('type') == 'quarterly' ? 'selected' : '' }}>Kuartalan</option>
                                    <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Tahunan</option>
                                    <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Kustom</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tahun</label>
                                <select name="year" class="form-select">
                                    <option value="">Semua</option>
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="ti ti-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Report Access -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.reports.financial') }}" class="card text-decoration-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-success rounded p-2">
                                <i class="ti ti-chart-line ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Laporan Keuangan</h6>
                            <small class="text-muted">Analisis pendapatan & pengeluaran</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('admin.reports.members') }}" class="card text-decoration-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-primary rounded p-2">
                                <i class="ti ti-users ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Laporan Anggota</h6>
                            <small class="text-muted">Statistik & pertumbuhan anggota</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('admin.reports.units') }}" class="card text-decoration-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-info rounded p-2">
                                <i class="ti ti-building-store ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Laporan Unit Usaha</h6>
                            <small class="text-muted">Performa & ROI unit</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('admin.reports.generate') }}" class="card text-decoration-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar bg-label-warning rounded p-2">
                                <i class="ti ti-file-plus ti-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-dark">Buat Laporan Kustom</h6>
                            <small class="text-muted">Generate laporan baru</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Reports List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Laporan Tersimpan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor Laporan</th>
                                    <th>Judul</th>
                                    <th>Tipe</th>
                                    <th>Periode</th>
                                    <th>Net Profit</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $report->report_number }}</span>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ $report->title }}</h6>
                                            @if($report->summary)
                                                <small class="text-muted">{{ Str::limit($report->summary, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $report->type == 'monthly' ? 'success' : ($report->type == 'quarterly' ? 'warning' : ($report->type == 'annual' ? 'primary' : 'info')) }}">
                                                {{ ucfirst($report->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $report->period_start->format('d M Y') }} - {{ $report->period_end->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-{{ $report->net_profit >= 0 ? 'success' : 'danger' }}">
                                                Rp {{ number_format($report->net_profit, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $report->generatedBy->name }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $report->generated_at->format('d M Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.reports.show', $report) }}" class="dropdown-item">
                                                            <i class="ti ti-eye me-1"></i> Lihat Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item" onclick="window.print()">
                                                            <i class="ti ti-printer me-1"></i> Cetak
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="ti ti-file-text ti-3x text-muted mb-3"></i>
                                            <h6>Belum ada laporan tersimpan</h6>
                                            <p class="text-muted">Buat laporan pertama untuk memulai</p>
                                            <a href="{{ route('admin.reports.generate') }}" class="btn btn-primary">
                                                <i class="ti ti-file-plus me-1"></i> Buat Laporan Baru
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($reports->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} data
                            </small>
                            {{ $reports->links('pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
