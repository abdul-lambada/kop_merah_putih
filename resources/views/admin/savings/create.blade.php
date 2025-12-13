@extends('layouts.admin')

@section('title', 'Tambah Simpanan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Simpanan Baru</h5>
                    <a href="{{ route('admin.savings.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.savings.store') }}">
                        @csrf
                        
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
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
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
                                    <option value="pokok" {{ old('savings_type') == 'pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                                    <option value="wajib" {{ old('savings_type') == 'wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                                    <option value="sukarela" {{ old('savings_type') == 'sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
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
                                           value="{{ old('amount') }}" min="10000" step="1000" required>
                                </div>
                                <small class="form-text text-muted">Minimal Rp 10.000</small>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Simpanan *</label>
                                <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                       value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description') }}</textarea>
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
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="debit" {{ old('payment_method') == 'debit' ? 'selected' : '' }}>Kartu Debit</option>
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
                                        <option value="{{ $unit->id }}" {{ old('business_unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.savings.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i> Simpan
                                    </button>
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
                    <h6 class="card-title mb-0">Informasi Simpanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Simpanan Pokok</small>
                        <h6 class="mb-0">Rp 100.000</h6>
                        <small class="text-success">Satu kali</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Simpanan Wajib</small>
                        <h6 class="mb-0">Rp 50.000/bulan</h6>
                        <small class="text-success">Bulanan</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Simpanan Sukarela</small>
                        <h6 class="mb-0">Bebas</h6>
                        <small class="text-info">Kapan saja</small>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <i class="ti ti-info-circle me-1"></i>
                        Simpanan akan diproses setelah verifikasi oleh bendahara.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
