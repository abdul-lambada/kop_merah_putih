@extends('layouts.admin')

@section('title', 'Pendaftaran Anggota Baru')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pendaftaran Anggota Baru</h5>
                    <p class="text-muted mb-0">Formulir pendaftaran anggota koperasi baru</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Informasi Pribadi -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Pribadi</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIK *</label>
                                    <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                                    @error('nik')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nomor Telepon *</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat Lengkap *</label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Usaha -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Usaha</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sektor Usaha *</label>
                                    <select name="business_sector" class="form-select" required>
                                        <option value="">Pilih Sektor Usaha</option>
                                        <option value="pertanian" {{ old('business_sector') == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                        <option value="peternakan" {{ old('business_sector') == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
                                        <option value="perikanan" {{ old('business_sector') == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                                        <option value="umkm" {{ old('business_sector') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                                    </select>
                                    @error('business_sector')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pengalaman Usaha *</label>
                                    <select name="experience" class="form-select" required>
                                        <option value="">Pilih Pengalaman</option>
                                        <option value="baru" {{ old('experience') == 'baru' ? 'selected' : '' }}>Baru (kurang dari 2 tahun)</option>
                                        <option value="2-5_tahun" {{ old('experience') == '2-5_tahun' ? 'selected' : '' }}>2-5 Tahun</option>
                                        <option value="5+_tahun" {{ old('experience') == '5+_tahun' ? 'selected' : '' }}>5+ Tahun</option>
                                    </select>
                                    @error('experience')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Bergabung *</label>
                                    <input type="date" name="join_date" class="form-control" value="{{ old('join_date', now()->format('Y-m-d')) }}" required>
                                    @error('join_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Keuangan -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3">Informasi Keuangan</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Saldo Simpanan Awal</label>
                                    <input type="number" name="savings_balance" class="form-control" value="{{ old('savings_balance', 0) }}" min="0" step="1000">
                                    @error('savings_balance')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Limit Pinjaman</label>
                                    <input type="number" name="loan_limit" class="form-control" value="{{ old('loan_limit', 0) }}" min="0" step="1000">
                                    @error('loan_limit')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dokumen Verifikasi -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3">Dokumen Verifikasi</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload KTP</label>
                                    <input type="file" name="ktp_file" class="form-control" accept="image/*,.pdf">
                                    <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                                    @error('ktp_file')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload KK</label>
                                    <input type="file" name="kk_file" class="form-control" accept="image/*,.pdf">
                                    <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                                    @error('kk_file')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Catatan -->
                        <div class="mb-4">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            <small class="text-muted">Catatan tentang anggota, riwayat usaha, dll.</small>
                            @error('notes')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-user-plus me-1"></i> Daftarkan Anggota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
