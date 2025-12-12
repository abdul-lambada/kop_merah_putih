@extends('layouts.admin')

@section('title', 'Detail Anggota')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Member Info -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Anggota</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl mb-3">
                            <div class="avatar-initial rounded-circle bg-label-primary" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ strtoupper(substr($member->full_name, 0, 1)) }}
                            </div>
                        </div>
                        <h4>{{ $member->full_name }}</h4>
                        <span class="badge bg-{{ $member->status == 'active' ? 'success' : ($member->status == 'pending' ? 'warning' : 'secondary') }}">
                            {{ $member->status == 'active' ? 'Aktif' : ($member->status == 'pending' ? 'Pending' : 'Tidak Aktif') }}
                        </span>
                        @if($member->verified_at)
                            <br><small class="text-success"><i class="ti ti-check"></i> Terverifikasi {{ $member->verified_at->format('d M Y') }}</small>
                        @else
                            <br><small class="text-muted"><i class="ti ti-clock"></i> Belum verifikasi</small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Nomor Anggota</small>
                        <h6 class="mb-0">{{ $member->member_number }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kontak</small>
                        <div>
                            <i class="ti ti-phone me-1"></i> {{ $member->phone }}
                        </div>
                        <div>
                            <i class="ti ti-mail me-1"></i> {{ $member->email }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Alamat</small>
                        <p class="mb-0">{{ $member->address }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Lahir</small>
                        <p class="mb-0">{{ $member->birth_date ? $member->birth_date->format('d M Y') : '-' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Bergabung</small>
                        <p class="mb-0">{{ $member->join_date->format('d M Y') }}</p>
                    </div>
                    
                    @if($member->notes)
                        <div class="mb-3">
                            <small class="text-muted">Catatan</small>
                            <p class="mb-0">{{ $member->notes }}</p>
                        </div>
                    @endif
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-primary flex-fill">
                            <i class="ti ti-edit me-1"></i> Edit
                        </a>
                        @if(!$member->verified_at)
                            <form action="{{ route('admin.members.verify', $member) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ti ti-check me-1"></i> Verifikasi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Business Info & Statistics -->
        <div class="col-xl-8">
            <!-- Business Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Usaha</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <small class="text-muted">Sektor Usaha</small>
                                <h6 class="mb-0">
                                    <span class="badge bg-info">{{ $member->business_sector }}</span>
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <small class="text-muted">Pengalaman</small>
                                <h6 class="mb-0">{{ $member->experience }}</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <small class="text-muted">Pendapatan Bulanan</small>
                                <h6 class="mb-0">
                                    {{ $member->monthly_income ? 'Rp ' . number_format($member->monthly_income, 0, ',', '.') : '-' }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Financial Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ringkasan Keuangan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success mb-2">Rp {{ number_format($member->totalSavings(), 0, ',', '.') }}</h3>
                                <small class="text-muted">Total Simpanan</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning mb-2">Rp {{ number_format($member->activeLoanAmount(), 0, ',', '.') }}</h3>
                                <small class="text-muted">Pinjaman Aktif</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info mb-2">{{ $member->savingsLoans()->where('type', 'loan')->count() }}</h3>
                                <small class="text-muted">Total Pinjaman</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary mb-2">Rp {{ number_format($member->loan_limit, 0, ',', '.') }}</h3>
                                <small class="text-muted">Limit Pinjaman</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activities -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active" onclick="showTab('savings')">Simpanan</button>
                        <button type="button" class="btn btn-outline-primary" onclick="showTab('loans')">Pinjaman</button>
                        <button type="button" class="btn btn-outline-primary" onclick="showTab('transactions')">Transaksi</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Savings Tab -->
                    <div id="savings-tab" class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($member->savingsLoans()->where('type', 'savings')->latest()->limit(5)->get() as $saving)
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $saving->savings_loan_number }}</span></td>
                                            <td>{{ $saving->created_at->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($saving->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $saving->status == 'completed' ? 'success' : ($saving->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ $saving->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.savings.show', $saving) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada data simpanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.savings.index', ['member' => $member->id]) }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                    
                    <!-- Loans Tab -->
                    <div id="loans-tab" class="tab-content" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Sisa</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($member->savingsLoans()->where('type', 'loan')->latest()->limit(5)->get() as $loan)
                                        <tr>
                                            <td><span class="badge bg-warning">{{ $loan->savings_loan_number }}</span></td>
                                            <td>{{ $loan->created_at->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($loan->amount, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($loan->remaining_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $loan->status == 'completed' ? 'success' : ($loan->status == 'active' ? 'primary' : ($loan->status == 'overdue' ? 'danger' : 'warning')) }}">
                                                    {{ $loan->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada data pinjaman</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.loans.index', ['member' => $member->id]) }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                    
                    <!-- Transactions Tab -->
                    <div id="transactions-tab" class="tab-content" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($member->transactions()->latest()->limit(5)->get() as $transaction)
                                        <tr>
                                            <td><span class="badge bg-info">{{ $transaction->transaction_number }}</span></td>
                                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                            <td>{{ $transaction->description }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                    {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada transaksi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.transactions.index', ['member' => $member->id]) }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').style.display = 'block';
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>
@endsection
