@extends('layouts.admin')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Tambah Transaksi</h5>
                        <small class="text-muted">Tambah transaksi baru</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.transactions.store') }}">
                        @csrf
                        
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
                                                <select name="type" id="transactionType" class="form-select" required>
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pendapatan</option>
                                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Kategori *</label>
                                                <select name="category" class="form-select" required>
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach($categories as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Jumlah *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" name="amount" class="form-control" min="0" step="1000" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Transaksi *</label>
                                                <input type="date" name="transaction_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">Deskripsi *</label>
                                                <input type="text" name="description" class="form-control" placeholder="Masukkan deskripsi transaksi" required>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Metode Pembayaran</label>
                                                <select name="payment_method" class="form-select">
                                                    <option value="cash">Tunai</option>
                                                    <option value="transfer">Transfer</option>
                                                    <option value="debit">Kartu Debit</option>
                                                    <option value="credit">Kartu Kredit</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Nomor Referensi</label>
                                                <input type="text" name="reference_number" class="form-control" placeholder="Opsional">
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">Catatan</label>
                                                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
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
                                                    <option value="{{ $member->id }}">
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
                                                    <option value="{{ $unit->id }}">
                                                        {{ $unit->name }} ({{ ucfirst($unit->type) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Actions -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Akses Cepat</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-success" onclick="quickFill('income')">
                                                <i class="ti ti-trending-up me-1"></i> Pendapatan Cepat
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="quickFill('expense')">
                                                <i class="ti ti-trending-down me-1"></i> Pengeluaran Cepat
                                            </button>
                                            <button type="button" class="btn btn-outline-info" onclick="clearForm()">
                                                <i class="ti ti-refresh me-1"></i> Reset Form
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recent Transactions -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Transaksi Terkini</h6>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">5 transaksi terakhir</small>
                                        <div class="mt-2">
                                            <!-- This would show recent transactions - can be implemented later -->
                                            <p class="text-muted">Belum ada transaksi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Batal
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-outline-warning">
                                            <i class="ti ti-refresh me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i> Simpan Transaksi
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

<script>
function quickFill(type) {
    document.getElementById('transactionType').value = type;
    
    if (type === 'income') {
        document.querySelector('select[name="category"]').value = 'unit_revenue';
        document.querySelector('input[name="description"]').placeholder = 'Pendapatan dari penjualan/layanan';
    } else {
        document.querySelector('select[name="category"]').value = 'operational_cost';
        document.querySelector('input[name="description"]').placeholder = 'Biaya operasional';
    }
}

function clearForm() {
    document.querySelector('form').reset();
    document.querySelector('input[name="transaction_date"]').value = '{{ now()->format('Y-m-d') }}';
}

// Auto-update description placeholder based on category
document.querySelector('select[name="category"]').addEventListener('change', function() {
    const category = this.value;
    const descriptionInput = document.querySelector('input[name="description"]');
    
    const placeholders = {
        'savings_deposit': 'Simpanan dari anggota',
        'savings_withdrawal': 'Penarikan simpanan anggota',
        'loan_disbursement': 'Pencairan pinjaman kepada anggota',
        'loan_payment': 'Pembayaran pinjaman dari anggota',
        'unit_revenue': 'Pendapatan dari unit usaha',
        'unit_expense': 'Pengeluaran unit usaha',
        'operational_cost': 'Biaya operasional kantor',
        'other': 'Transaksi lainnya'
    };
    
    descriptionInput.placeholder = placeholders[category] || 'Masukkan deskripsi transaksi';
});
</script>
@endsection
