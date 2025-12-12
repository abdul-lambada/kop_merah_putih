@extends('layouts.admin')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Manajemen Anggota</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Tambah Anggota
                        </a>
                        <a href="{{ route('admin.members.register') }}" class="btn btn-success">
                            <i class="ti ti-user-plus me-1"></i> Registrasi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.members.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nama atau Nomor Anggota" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sektor</label>
                            <select name="business_sector" class="form-select">
                                <option value="">Semua</option>
                                <option value="Pertanian" {{ request('business_sector') == 'Pertanian' ? 'selected' : '' }}>Pertanian</option>
                                <option value="Perdagangan" {{ request('business_sector') == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                <option value="Jasa" {{ request('business_sector') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                <option value="Industri" {{ request('business_sector') == 'Industri' ? 'selected' : '' }}>Industri</option>
                                <option value="Lainnya" {{ request('business_sector') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pengalaman</label>
                            <select name="experience" class="form-select">
                                <option value="">Semua</option>
                                <option value="Pemula" {{ request('experience') == 'Pemula' ? 'selected' : '' }}>Pemula</option>
                                <option value="Menengah" {{ request('experience') == 'Menengah' ? 'selected' : '' }}>Menengah</option>
                                <option value="Ahli" {{ request('experience') == 'Ahli' ? 'selected' : '' }}>Ahli</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="ti ti-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Members Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor Anggota</th>
                                    <th>Nama Lengkap</th>
                                    <th>Kontak</th>
                                    <th>Sektor Usaha</th>
                                    <th>Status</th>
                                    <th>Limit Pinjaman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $member->member_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $member->full_name }}</h6>
                                                    <small class="text-muted">{{ $member->join_date->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="d-block">{{ $member->phone }}</small>
                                                <small class="text-muted">{{ $member->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $member->business_sector }}</span>
                                            <br><small>{{ $member->experience }}</small>
                                        </td>
                                        <td>
                                            @if($member->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($member->status == 'inactive')
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                            @if($member->verified_at)
                                                <br><small class="text-success"><i class="ti ti-check"></i> Terverifikasi</small>
                                            @else
                                                <br><small class="text-muted"><i class="ti ti-clock"></i> Belum verifikasi</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>Rp {{ number_format($member->loan_limit, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.members.show', $member) }}" class="dropdown-item">
                                                            <i class="ti ti-eye me-1"></i> Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.members.edit', $member) }}" class="dropdown-item">
                                                            <i class="ti ti-edit me-1"></i> Edit
                                                        </a>
                                                    </li>
                                                    @if(!$member->verified_at)
                                                        <li>
                                                            <form action="{{ route('admin.members.verify', $member) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-success w-100 text-start">
                                                                    <i class="ti ti-check me-1"></i> Verifikasi
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.members.update-status', $member) }}" method="POST" class="dropdown-item p-0">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="{{ $member->status == 'active' ? 'inactive' : 'active' }}">
                                                            <button type="submit" class="btn btn-link w-100 text-start">
                                                                <i class="ti ti-{{ $member->status == 'active' ? 'player-stop' : 'player-play' }} me-1"></i>
                                                                {{ $member->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="dropdown-item p-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger w-100 text-start" onclick="return confirm('Hapus anggota ini?')">
                                                                <i class="ti ti-trash me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="ti ti-users ti-3x text-muted mb-3"></i>
                                            <h6>Tidak ada data anggota</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($members->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $members->firstItem() }} - {{ $members->lastItem() }} dari {{ $members->total() }} data
                            </small>
                            {{ $members->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
