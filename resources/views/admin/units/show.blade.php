@extends('layouts.admin')

@section('title', 'Detail Unit Usaha')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Unit Info -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Unit</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Nama Unit</small>
                        <h6 class="mb-0">{{ $unit->name }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tipe</small>
                        <div>
                            <span class="badge bg-{{ $unit->type == 'sembako' ? 'success' : ($unit->type == 'apotek' ? 'info' : ($unit->type == 'klinik' ? 'primary' : 'warning')) }}">
                                {{ ucfirst($unit->type) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-{{ $unit->status == 'active' ? 'success' : ($unit->status == 'inactive' ? 'secondary' : 'warning') }}">
                                {{ $unit->status == 'active' ? 'Aktif' : ($unit->status == 'inactive' ? 'Tidak Aktif' : 'Maintenance') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Manajer</small>
                        <h6 class="mb-0">{{ $unit->manager_name }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kontak</small>
                        <div>
                            @if($unit->phone)
                                <div><i class="ti ti-phone me-1"></i> {{ $unit->phone }}</div>
                            @endif
                            <div><i class="ti ti-map-pin me-1"></i> {{ $unit->address }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Modal Awal</small>
                        <h6 class="mb-0 text-primary">Rp {{ number_format($unit->initial_capital, 0, ',', '.') }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Saldo Saat Ini</small>
                        <h6 class="mb-0 text-info">Rp {{ number_format($unit->current_balance, 0, ',', '.') }}</h6>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">ROI</small>
                        <h6 class="mb-0 text-{{ $unit->roi >= 10 ? 'success' : ($unit->roi >= 0 ? 'warning' : 'danger') }}">
                            {{ number_format($unit->roi, 1) }}%
                        </h6>
                    </div>
                    
                    @if($unit->description)
                    <div class="mb-3">
                        <small class="text-muted">Deskripsi</small>
                        <p class="mb-0">{{ $unit->description }}</p>
                    </div>
                    @endif
                    
                    @if($unit->operating_hours && is_array($unit->operating_hours))
                    <div class="mb-3">
                        <small class="text-muted">Jam Operasional</small>
                        @foreach($unit->operating_hours as $day => $hours)
                            <div class="d-flex justify-content-between">
                                <small>{{ ucfirst($day) }}</small>
                                <small>{{ $hours }}</small>
                            </div>
                        @endforeach
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
                    <h5 class="card-title mb-0">Aksi Unit</h5>
                    <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#transactionModal">
                            <i class="ti ti-cash me-1"></i> Transaksi
                        </button>
                        <a href="{{ route('admin.units.report', $unit) }}" class="btn btn-info">
                            <i class="ti ti-chart-bar me-1"></i> Laporan
                        </a>
                        @if($unit->transactions()->count() == 0)
                            <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                                    <i class="ti ti-trash me-1"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Financial Summary -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-success rounded p-2">
                                        <i class="ti ti-trending-up ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Pendapatan</h6>
                                    <h3 class="mb-2">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
                                    <small class="text-success">
                                        <i class="ti ti-arrow-up"></i> Bulan ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-danger rounded p-2">
                                        <i class="ti ti-trending-down ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Total Pengeluaran</h6>
                                    <h3 class="mb-2">Rp {{ number_format($expenses, 0, ',', '.') }}</h3>
                                    <small class="text-danger">
                                        <i class="ti ti-arrow-down"></i> Semua waktu
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-info rounded p-2">
                                        <i class="ti ti-chart-pie ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Laba Bersih</h6>
                                    <h3 class="mb-2">Rp {{ number_format($profit, 0, ',', '.') }}</h3>
                                    <small class="text-{{ $profit >= 0 ? 'success' : 'danger' }}">
                                        <i class="ti ti-{{ $profit >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> {{ $profit >= 0 ? 'Untung' : 'Rugi' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-label-warning rounded p-2">
                                        <i class="ti ti-wallet ti-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Saldo Saat Ini</h6>
                                    <h3 class="mb-2">Rp {{ number_format($unit->current_balance, 0, ',', '.') }}</h3>
                                    <small class="text-info">
                                        <i class="ti ti-pig"></i> Tersedia
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Performance Chart -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performa 6 Bulan Terakhir</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Pendapatan</th>
                                    <th>Pengeluaran</th>
                                    <th>Laba</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyPerformance as $data)
                                    <tr>
                                        <td>{{ $data['month'] }}</td>
                                        <td class="text-success">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                                        <td class="text-danger">Rp {{ number_format($data['expenses'], 0, ',', '.') }}</td>
                                        <td class="text-{{ ($data['revenue'] - $data['expenses']) >= 0 ? 'success' : 'danger' }} fw-bold">
                                            Rp {{ number_format($data['revenue'] - $data['expenses'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Transaksi Terakhir</h5>
                    <span class="badge bg-info">{{ $recentTransactions->count() }} transaksi</span>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                                    {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                                </span>
                                            </td>
                                            <td>{{ $transaction->category }}</td>
                                            <td class="text-{{ $transaction->type == 'income' ? 'success' : 'danger' }} fw-bold">
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $transaction->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-receipt text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.units.transaction', $unit) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi *</label>
                        <select name="type" class="form-select" required>
                            <option value="">Pilih Tipe</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" min="0" step="1000" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan *</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi *</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="credit">Kartu Kredit</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Referensi</label>
                        <input type="text" name="reference_number" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
