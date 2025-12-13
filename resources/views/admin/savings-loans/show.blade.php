@extends('layouts.admin')

@section('title', 'Detail Simpan Pinjam')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail {{ $savingsLoan->type == 'savings' ? 'Simpanan' : 'Pinjaman' }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.savings-loans.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Utama</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Transaksi</label>
                                <p class="form-control-plaintext fw-bold">{{ $savingsLoan->transaction_number }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <p>
                                    <span class="badge bg-{{ $savingsLoan->type == 'savings' ? 'success' : 'warning' }}">
                                        {{ $savingsLoan->type == 'savings' ? 'Simpanan' : 'Pinjaman' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <p class="form-control-plaintext fw-bold fs-5">
                                    Rp {{ number_format($savingsLoan->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <p>
                                    <span class="badge bg-{{ $savingsLoan->status == 'completed' ? 'success' : ($savingsLoan->status == 'active' ? 'primary' : ($savingsLoan->status == 'pending' ? 'warning' : 'danger')) }}">
                                        {{ $savingsLoan->status == 'completed' ? 'Selesai' : ($savingsLoan->status == 'active' ? 'Aktif' : ($savingsLoan->status == 'pending' ? 'Pending' : 'Ditolak')) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                <p class="form-control-plaintext">{{ $savingsLoan->purpose }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Detail Tambahan</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Anggota</label>
                                <p class="form-control-plaintext">
                                    <a href="{{ route('admin.members.show', $savingsLoan->member) }}">
                                        {{ $savingsLoan->member->full_name }} ({{ $savingsLoan->member->member_number }})
                                    </a>
                                </p>
                            </div>
                            
                            @if($savingsLoan->type == 'loan')
                            <div class="mb-3">
                                <label class="form-label">Suku Bunga</label>
                                <p class="form-control-plaintext">{{ $savingsLoan->interest_rate }}%</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jangka Waktu</label>
                                <p class="form-control-plaintext">{{ $savingsLoan->tenure_months }} bulan</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Cicilan Bulanan</label>
                                <p class="form-control-plaintext fw-bold">
                                    Rp {{ number_format($savingsLoan->monthly_installment, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jatuh Tempo</label>
                                <p class="form-control-plaintext">{{ $savingsLoan->due_date->format('d F Y') }}</p>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label">Disetujui Oleh</label>
                                <p class="form-control-plaintext">
                                    @if($savingsLoan->approvedBy)
                                        {{ $savingsLoan->approvedBy->name }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Persetujuan</label>
                                <p class="form-control-plaintext">
                                    @if($savingsLoan->approved_at)
                                        {{ $savingsLoan->approved_at->format('d F Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dibuat Pada</label>
                                <p class="form-control-plaintext">{{ $savingsLoan->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Catatan</h6>
                            <p class="form-control-plaintext">{{ $savingsLoan->notes ?? '-' }}</p>
                        </div>
                    </div>
                    
                    @if($savingsLoan->transactions && $savingsLoan->transactions->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Transaksi Terkait</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($savingsLoan->transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->transaction_number }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                        {{ $transaction->type == 'income' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">
                                                    {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                </td>
                                                <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
