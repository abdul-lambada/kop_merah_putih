@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Transaksi</h5>
                    <div class="d-flex gap-2">
                        @if($transaction->canEdit())
                            <a href="{{ route('admin.transactions.edit', $transaction) }}" class="btn btn-warning">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                        @endif
                        <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="ti ti-trash me-1"></i> Hapus
                            </button>
                        </form>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Transaksi</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Transaksi</label>
                                <p class="form-control-plaintext fw-bold">{{ $transaction->transaction_number }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <p>
                                    <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                        {{ $transaction->type == 'income' ? 'Pendapatan' : 'Pengeluaran' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <p class="form-control-plaintext">{{ $transaction->category }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <p class="form-control-plaintext fw-bold fs-5 text-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                    {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Transaksi</label>
                                <p class="form-control-plaintext">{{ $transaction->transaction_date->format('d F Y') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <p class="form-control-plaintext">{{ $transaction->payment_method }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Detail Tambahan</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <p class="form-control-plaintext">{{ $transaction->description }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Referensi</label>
                                <p class="form-control-plaintext">{{ $transaction->reference_number ?? '-' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <p class="form-control-plaintext">{{ $transaction->notes ?? '-' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dicatat Oleh</label>
                                <p class="form-control-plaintext">{{ $transaction->recordedBy->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dibuat Pada</label>
                                <p class="form-control-plaintext">{{ $transaction->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->member || $transaction->businessUnit || $transaction->savingsLoan)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Terkait Dengan</h6>
                            
                            @if($transaction->member)
                            <div class="mb-3">
                                <label class="form-label">Anggota</label>
                                <p class="form-control-plaintext">
                                    <a href="{{ route('admin.members.show', $transaction->member) }}">
                                        {{ $transaction->member->full_name }} ({{ $transaction->member->member_number }})
                                    </a>
                                </p>
                            </div>
                            @endif
                            
                            @if($transaction->businessUnit)
                            <div class="mb-3">
                                <label class="form-label">Unit Usaha</label>
                                <p class="form-control-plaintext">
                                    <a href="{{ route('admin.units.show', $transaction->businessUnit) }}">
                                        {{ $transaction->businessUnit->name }}
                                    </a>
                                </p>
                            </div>
                            @endif
                            
                            @if($transaction->savingsLoan)
                            <div class="mb-3">
                                <label class="form-label">Simpan Pinjam</label>
                                <p class="form-control-plaintext">
                                    <a href="{{ route('admin.savings-loans.show', $transaction->savingsLoan) }}">
                                        {{ $transaction->savingsLoan->transaction_number }}
                                    </a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    @if($transaction->attachments && count($transaction->attachments) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted mb-3">Lampiran</h6>
                            <div class="row">
                                @foreach($transaction->attachments as $attachment)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="ti ti-file-text fs-3 text-primary mb-2"></i>
                                            <p class="mb-2">{{ basename($attachment) }}</p>
                                            <a href="{{ asset($attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-eye me-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
