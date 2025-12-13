@extends('layouts.admin')

@section('title', 'Detail Simpanan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Simpanan</h5>
                    <div class="d-flex gap-2">
                        @if($saving->status == 'pending')
                            <form action="{{ route('admin.savings.approve', $saving) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-check me-1"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.savings.reject', $saving) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak simpanan ini?')">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bx bx-x me-1"></i> Tolak
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.savings.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Simpanan</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Transaksi</label>
                                <p class="form-control-plaintext fw-bold">{{ $saving->transaction_number }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah Simpanan</label>
                                <p class="form-control-plaintext fw-bold fs-5 text-success">
                                    +Rp {{ number_format($saving->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <p>
                                    <span class="badge bg-{{ $saving->status == 'completed' ? 'success' : ($saving->status == 'approved' ? 'primary' : ($saving->status == 'pending' ? 'warning' : 'danger')) }}">
                                        {{ $saving->status == 'completed' ? 'Selesai' : ($saving->status == 'approved' ? 'Disetujui' : ($saving->status == 'pending' ? 'Pending' : 'Ditolak')) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                <p class="form-control-plaintext">{{ $saving->purpose }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Suku Bunga</label>
                                <p class="form-control-plaintext">{{ $saving->interest_rate }}% per tahun</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jangka Waktu</label>
                                <p class="form-control-plaintext">{{ $saving->tenure_months }} bulan</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Anggota</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Anggota</label>
                                <p class="form-control-plaintext">
                                    <a href="{{ route('admin.members.show', $saving->member) }}">
                                        {{ $saving->member->full_name }}
                                    </a>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Anggota</label>
                                <p class="form-control-plaintext">{{ $saving->member->member_number }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Total Simpanan Anggota</label>
                                <p class="form-control-plaintext fw-bold">
                                    Rp {{ number_format($saving->member->total_savings, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Disetujui Oleh</label>
                                <p class="form-control-plaintext">
                                    @if($saving->approvedBy)
                                        {{ $saving->approvedBy->name }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Persetujuan</label>
                                <p class="form-control-plaintext">
                                    @if($saving->approved_at)
                                        {{ $saving->approved_at->format('d F Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Jatuh Tempo</label>
                                <p class="form-control-plaintext">{{ $saving->due_date->format('d F Y') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dibuat Pada</label>
                                <p class="form-control-plaintext">{{ $saving->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Catatan</h6>
                            <p class="form-control-plaintext">{{ $saving->notes ?? '-' }}</p>
                        </div>
                    </div>
                    
                    @if($saving->transactions && $saving->transactions->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Transaksi Terkait</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nomor Transaksi</th>
                                            <th>Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($saving->transactions as $transaction)
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
                                                <td>{{ $transaction->description }}</td>
                                                <td>
                                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bx bx-show"></i>
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
                    
                    <!-- Riwayat Simpanan Anggota -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Riwayat Simpanan Anggota</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nomor Transaksi</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($saving->member->savingsLoans()->where('type', 'savings')->orderBy('created_at', 'desc')->get() as $memberSaving)
                                            <tr>
                                                <td>{{ $memberSaving->transaction_number }}</td>
                                                <td class="fw-bold text-success">
                                                    +Rp {{ number_format($memberSaving->amount, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $memberSaving->status == 'completed' ? 'success' : ($memberSaving->status == 'approved' ? 'primary' : ($memberSaving->status == 'pending' ? 'warning' : 'danger')) }}">
                                                        {{ $memberSaving->status == 'completed' ? 'Selesai' : ($memberSaving->status == 'approved' ? 'Disetujui' : ($memberSaving->status == 'pending' ? 'Pending' : 'Ditolak')) }}
                                                    </span>
                                                </td>
                                                <td>{{ $memberSaving->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.savings.show', $memberSaving) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </td>
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
    </div>
</div>
@endsection
