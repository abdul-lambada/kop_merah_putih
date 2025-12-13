@extends('layouts.admin')

@section('title', 'Edit Simpanan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Simpanan</h5>
                    <a href="{{ route('admin.savings.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.savings.update', $savingsLoan) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Informasi Simpanan -->
                            <div class="col-12">
                                <h6 class="mb-3">Informasi Simpanan</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Anggota *</label>
                                <select name="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id', $savingsLoan->member_id) == $member->id ? 'selected' : '' }}>
                                            {{ $member->full_name }} ({{ $member->member_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Jenis Simpanan *</label>
                                <select name="savings_type" class="form-select @error('savings_type') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="pokok" {{ old('savings_type', $savingsLoan->savings_type) == 'pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                                    <option value="wajib" {{ old('savings_type', $savingsLoan->savings_type) == 'wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                                    <option value="sukarela" {{ old('savings_type', $savingsLoan->savings_type) == 'sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
                                </select>
                                @error('savings_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Simpanan *</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount', $savingsLoan->amount) }}" min="10000" step="1000" required>
                                </div>
                                <small class="form-text text-muted">Minimal Rp 10.000</small>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Simpanan *</label>
                                <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                       value="{{ old('transaction_date', $savingsLoan->transaction_date->format('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description', $savingsLoan->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Pembayaran -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Informasi Pembayaran</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Metode Pembayaran *</label>
                                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="cash" {{ old('payment_method', $savingsLoan->payment_method) == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="transfer" {{ old('payment_method', $savingsLoan->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="debit" {{ old('payment_method', $savingsLoan->payment_method) == 'debit' ? 'selected' : '' }}>Kartu Debit</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Unit Usaha</label>
                                <select name="business_unit_id" class="form-select @error('business_unit_id') is-invalid @enderror">
                                    <option value="">Pilih Unit (Opsional)</option>
                                    @foreach($businessUnits as $unit)
                                        <option value="{{ $unit->id }}" {{ old('business_unit_id', $savingsLoan->business_unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Status -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Status Simpanan</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $savingsLoan->status) == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="approved" {{ old('status', $savingsLoan->status) == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ old('status', $savingsLoan->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Catatan Approval</label>
                                <textarea name="approval_notes" class="form-control @error('approval_notes') is-invalid @enderror" 
                                          rows="2">{{ old('approval_notes', $savingsLoan->approval_notes) }}</textarea>
                                @error('approval_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        @can('savings.delete')
                                        <form method="POST" action="{{ route('admin.savings.destroy', $savingsLoan) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus simpanan ini?')">
                                                <i class="ti ti-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.savings.index') }}" class="btn btn-outline-secondary">
                                            <i class="ti ti-x me-1"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-check me-1"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Side Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Detail Simpanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">No. Transaksi</small>
                        <h6 class="mb-0">{{ $savingsLoan->transaction_number }}</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Dibuat</small>
                        <h6 class="mb-0">{{ $savingsLoan->created_at->format('d M Y H:i') }}</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Status Saat Ini</small>
                        <h6 class="mb-0">
                            @if($savingsLoan->status == 'pending')
                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                            @elseif($savingsLoan->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </h6>
                    </div>
                    @if($savingsLoan->approved_by)
                    <div class="mb-3">
                        <small class="text-muted">Disetujui Oleh</small>
                        <h6 class="mb-0">{{ $savingsLoan->approvedBy->name }}</h6>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
