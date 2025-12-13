@extends('layouts.admin')

@section('title', 'Ajukan Pinjaman')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Ajukan Pinjaman Baru</h5>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.loans.store') }}">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Informasi Pinjaman -->
                            <div class="col-12">
                                <h6 class="mb-3">Informasi Pinjaman</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Anggota *</label>
                                <select name="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->full_name }} ({{ $member->member_number }})
                                            - Limit: {{ number_format($member->loan_limit, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Pinjaman *</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount') }}" min="100000" step="10000" required>
                                </div>
                                <small class="form-text text-muted">Minimal Rp 100.000</small>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Suku Bunga (%) *</label>
                                <div class="input-group">
                                    <input type="number" name="interest_rate" class="form-control @error('interest_rate') is-invalid @enderror" 
                                           value="{{ old('interest_rate', 1) }}" min="0" max="30" step="0.1" required>
                                    <span class="input-group-text">% per tahun</span>
                                </div>
                                <small class="form-text text-muted">0-30% per tahun</small>
                                @error('interest_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Jangka Waktu *</label>
                                <div class="input-group">
                                    <input type="number" name="tenure_months" class="form-control @error('tenure_months') is-invalid @enderror" 
                                           value="{{ old('tenure_months') }}" min="1" max="60" required>
                                    <span class="input-group-text">bulan</span>
                                </div>
                                <small class="form-text text-muted">1-60 bulan</small>
                                @error('tenure_months')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Angsuran Per Bulan (Estimasi)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="monthly-installment" readonly>
                                </div>
                                <small class="form-text text-muted">Akan dihitung otomatis</small>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Tujuan Pinjaman *</label>
                                <select name="purpose" class="form-select @error('purpose') is-invalid @enderror" required>
                                    <option value="">Pilih Tujuan</option>
                                    <option value="Modal Usaha" {{ old('purpose') == 'Modal Usaha' ? 'selected' : '' }}>Modal Usaha</option>
                                    <option value="Konsumsi" {{ old('purpose') == 'Konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                                    <option value="Pendidikan" {{ old('purpose') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="Kesehatan" {{ old('purpose') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="Renovasi" {{ old('purpose') == 'Renovasi' ? 'selected' : '' }}>Renovasi Rumah</option>
                                    <option value="Lainnya" {{ old('purpose') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" placeholder="Informasi tambahan mengenai pinjaman...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Perhitungan -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Rincian Pinjaman</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">Total Pinjaman</h6>
                                                <h4 class="text-primary" id="total-amount">Rp 0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">Total Pembayaran</h6>
                                                <h4 class="text-info" id="total-payment">Rp 0</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-send me-1"></i> Ajukan Pinjaman
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    const rateInput = document.querySelector('input[name="interest_rate"]');
    const tenureInput = document.querySelector('input[name="tenure_months"]');
    const monthlyInstallmentInput = document.getElementById('monthly-installment');
    const totalAmountEl = document.getElementById('total-amount');
    const totalPaymentEl = document.getElementById('total-payment');
    
    function calculateInstallment() {
        const principal = parseFloat(amountInput.value) || 0;
        const annualRate = parseFloat(rateInput.value) || 0;
        const months = parseInt(tenureInput.value) || 0;
        
        if (principal > 0 && months > 0) {
            const monthlyRate = annualRate / 100 / 12;
            let monthlyInstallment;
            
            if (monthlyRate === 0) {
                monthlyInstallment = principal / months;
            } else {
                monthlyInstallment = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);
            }
            
            const totalPayment = monthlyInstallment * months;
            
            monthlyInstallmentInput.value = monthlyInstallment.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            
            totalAmountEl.textContent = 'Rp ' + principal.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            
            totalPaymentEl.textContent = 'Rp ' + totalPayment.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        } else {
            monthlyInstallmentInput.value = '';
            totalAmountEl.textContent = 'Rp 0';
            totalPaymentEl.textContent = 'Rp 0';
        }
    }
    
    amountInput.addEventListener('input', calculateInstallment);
    rateInput.addEventListener('input', calculateInstallment);
    tenureInput.addEventListener('input', calculateInstallment);
});
</script>
@endsection
