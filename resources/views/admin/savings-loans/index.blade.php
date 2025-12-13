@extends('layouts.admin')

@section('title', 'Simpan Pinjam')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Simpan Pinjam</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.savings.create') }}" class="btn btn-success">
                            <i class="ti ti-plus me-1"></i> Tambah Simpanan
                        </a>
                        <a href="{{ route('admin.loans.create') }}" class="btn btn-warning">
                            <i class="ti ti-plus me-1"></i> Ajukan Pinjaman
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Anggota</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($savingsLoans as $savingsLoan)
                                    <tr>
                                        <td>
                                            <span class="text-primary fw-bold">{{ $savingsLoan->transaction_number }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.members.show', $savingsLoan->member) }}">
                                                {{ $savingsLoan->member->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $savingsLoan->type == 'savings' ? 'success' : 'warning' }}">
                                                {{ $savingsLoan->type == 'savings' ? 'Simpanan' : 'Pinjaman' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">
                                            Rp {{ number_format($savingsLoan->amount, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $savingsLoan->status == 'completed' ? 'success' : ($savingsLoan->status == 'active' ? 'primary' : ($savingsLoan->status == 'pending' ? 'warning' : 'danger')) }}">
                                                {{ $savingsLoan->status == 'completed' ? 'Selesai' : ($savingsLoan->status == 'active' ? 'Aktif' : ($savingsLoan->status == 'pending' ? 'Pending' : 'Ditolak')) }}
                                            </span>
                                        </td>
                                        <td>{{ $savingsLoan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.savings-loans.show', $savingsLoan) }}" class="btn btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                @if($savingsLoan->type == 'loan' && $savingsLoan->status == 'pending')
                                                    <form action="{{ route('admin.loans.approve', $savingsLoan) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $savingsLoans->firstItem() }} - {{ $savingsLoans->lastItem() }} dari {{ $savingsLoans->total() }} data
                        </div>
                        {{ $savingsLoans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
