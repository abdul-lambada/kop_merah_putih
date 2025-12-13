@extends('layouts.admin')

@section('title', 'Detail Pinjaman')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Loan Info -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pinjaman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Nomor Pinjaman</small>
                        <h6 class="mb-0">{{ $loan->transaction_number }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Anggota</small>
                        <h6 class="mb-0">{{ $loan->member->full_name }}</h6>
                        <small class="text-muted">{{ $loan->member->member_number }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $loan->status == 'active' ? 'success' : ($loan->status == 'pending' ? 'warning' : ($loan->status == 'completed' ? 'info' : 'danger')) }}">
                                {{ $loan->status == 'active' ? 'Aktif' : ($loan->status == 'pending' ? 'Pending' : ($loan->status == 'completed' ? 'Selesai' : 'Ditolak')) }}
                            </span>
                            @if($isOverdue)
                                <span class="badge bg-danger ms-1">Jatuh Tempo</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Jumlah Pinjaman</small>
                        <h6 class="mb-0 text-primary">Rp {{ number_format($loan->amount, 0, ',', '.') }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Suku Bunga</small>
                        <h6 class="mb-0">{{ $loan->interest_rate }}% per tahun</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Jangka Waktu</small>
                        <h6 class="mb-0">{{ $loan->tenure_months }} bulan</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Angsuran per Bulan</small>
                        <h6 class="mb-0 text-info">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Sisa Pinjaman</small>
                        <h6 class="mb-0 text-warning">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Jatuh Tempo</small>
                        <h6 class="mb-0">{{ $loan->due_date->format('d M Y') }}</h6>
                        @if($isOverdue)
                            <small class="text-danger">Terlambat {{ $loan->due_date->diffInDays(now()) }} hari</small>
                        @endif
                    </div>
                    
                    @if($loan->purpose)
                    <div class="mb-3">
                        <small class="text-muted">Tujuan Pinjaman</small>
                        <p class="mb-0">{{ $loan->purpose }}</p>
                    </div>
                    @endif
                    
                    @if($loan->approved_at)
                    <div class="mb-3">
                        <small class="text-muted">Disetujui oleh</small>
                        <p class="mb-0">{{ $loan->approvedBy->name ?? 'System' }}</p>
                        <small class="text-muted">{{ $loan->approved_at->format('d M Y H:i') }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Aksi Pinjaman</h5>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    @if($loan->status == 'pending')
                        <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pinjaman ini?')">
                                <i class="ti ti-check me-1"></i> Setujui
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="ti ti-x me-1"></i> Tolak
                        </button>
                    @endif
                    
                    @if($loan->status == 'active')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="ti ti-cash me-1"></i> Bayar
                        </button>
                    @endif
                    
                    @if($loan->status != 'rejected')
                        <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="ti ti-list me-1"></i> Daftar Pinjaman
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Payment History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Riwayat Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($paymentHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Catatan</th>
                                        <th>Dibuat oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentHistory as $payment)
                                        <tr>
                                            <td>{{ $payment->transaction_date->format('d M Y') }}</td>
                                            <td class="text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>{{ $payment->notes ?? '-' }}</td>
                                            <td>{{ $payment->recordedBy->name ?? 'System' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th>Total Pembayaran</th>
                                        <th colspan="4" class="text-success">
                                            Rp {{ number_format($paymentHistory->sum('amount'), 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-cash-off text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($loan->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan *</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pinjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Payment Modal -->
@if($loan->status == 'active')
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.loans.payment', $loan) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bayar Pinjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <small>Sisa Pinjaman: <strong>Rp {{ number_format($remainingBalance, 0, ',', '.') }}</strong></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pembayaran *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" 
                                   value="{{ min($loan->monthly_installment, $remainingBalance) }}" 
                                   min="1000" max="{{ $remainingBalance }}" step="1000" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="credit">Kartu Kredit</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan pembayaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
