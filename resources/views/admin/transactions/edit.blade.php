@extends('layouts.admin')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Edit Transaksi</h5>
                        <small class="text-muted">Perbarui data transaksi #{{ $transaction->transaction_number }}</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Informasi Dasar</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tipe Transaksi *</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Pendapatan</option>
                                                    <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Kategori *</label>
                                                <select name="category" class="form-select" required>
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach($categories as $key => $label)
                                                        <option value="{{ $key }}" {{ $transaction->category == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Jumlah *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" name="amount" class="form-control" min="0" step="1000" value="{{ $transaction->amount }}" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Transaksi *</label>
                                                <input type="date" name="transaction_date" class="form-control" value="{{ $transaction->transaction_date->format('Y-m-d') }}" required>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">Deskripsi *</label>
                                                <input type="text" name="description" class="form-control" value="{{ $transaction->description }}" required>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Metode Pembayaran</label>
                                                <select name="payment_method" class="form-select">
                                                    <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Tunai</option>
                                                    <option value="transfer" {{ $transaction->payment_method == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                                    <option value="debit" {{ $transaction->payment_method == 'debit' ? 'selected' : '' }}>Kartu Debit</option>
                                                    <option value="credit" {{ $transaction->payment_method == 'credit' ? 'selected' : '' }}>Kartu Kredit</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Nomor Referensi</label>
                                                <input type="text" name="reference_number" class="form-control" value="{{ $transaction->reference_number ?? '' }}">
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">Catatan</label>
                                                <textarea name="notes" class="form-control" rows="3">{{ $transaction->notes ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Related Information -->
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Informasi Terkait</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Anggota</label>
                                            <select name="member_id" class="form-select">
                                                <option value="">-- Tidak ada --</option>
                                                @foreach($members as $member)
                                                    <option value="{{ $member->id }}" {{ $transaction->member_id == $member->id ? 'selected' : '' }}>
                                                        {{ $member->full_name }} ({{ $member->member_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Unit Usaha</label>
                                            <select name="business_unit_id" class="form-select">
                                                <option value="">-- Tidak ada --</option>
                                                @foreach($businessUnits as $unit)
                                                    <option value="{{ $unit->id }}" {{ $transaction->business_unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }} ({{ ucfirst($unit->type) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Transaction Info -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Info Transaksi</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="text-muted">Nomor Transaksi</small>
                                            <small class="fw-medium">{{ $transaction->transaction_number }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="text-muted">Dibuat</small>
                                            <small class="fw-medium">{{ $transaction->created_at->format('d M Y H:i') }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="text-muted">Diubah</small>
                                            <small class="fw-medium">{{ $transaction->updated_at->format('d M Y H:i') }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Dibuat oleh</small>
                                            <small class="fw-medium">{{ $transaction->recorded_by_name ?? 'System' }}</small>
                                        </div>
                                        
                                        @if($transaction->created_at->diffInHours(now()) > 24)
                                        <div class="alert alert-warning mt-3">
                                            <i class="ti ti-alert-triangle me-1"></i>
                                            <small>Transaksi ini sudah lebih dari 24 jam dan hanya dapat dilihat</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Batal
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-outline-warning">
                                            <i class="ti ti-refresh me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary" 
                                                @if($transaction->created_at->diffInHours(now()) > 24) disabled
                                                @endif>
                                            <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
