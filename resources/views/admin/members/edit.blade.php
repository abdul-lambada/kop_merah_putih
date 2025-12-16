@extends('layouts.admin')

@section('title', 'Edit Anggota')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Anggota: {{ $member->full_name }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline-info">
                            <i class="ti ti-eye me-1"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.members.update', $member) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Informasi Personal -->
                            <div class="col-12">
                                <h6 class="mb-3">Informasi Personal</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                       value="{{ old('full_name', $member->full_name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">NIK *</label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                       value="{{ old('nik', $member->nik) }}" required maxlength="16">
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $member->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Telepon *</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $member->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Alamat *</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" required>{{ old('address', $member->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Usaha -->
                            <div class="col-12 mt-4">
                                <h6 class="mb-3">Informasi Usaha</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Sektor Usaha *</label>
                                <select name="business_sector" class="form-select @error('business_sector') is-invalid @enderror" required>
                                    <option value="">Pilih Sektor</option>
                                    <option value="pertanian" {{ old('business_sector', $member->business_sector) == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                    <option value="peternakan" {{ old('business_sector', $member->business_sector) == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
                                    <option value="perikanan" {{ old('business_sector', $member->business_sector) == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                                    <option value="umkm" {{ old('business_sector', $member->business_sector) == 'umkm' ? 'selected' : '' }}>UMKM</option>
                                </select>
                                @error('business_sector')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Pengalaman *</label>
                                <select name="experience" class="form-select @error('experience') is-invalid @enderror" required>
                                    <option value="">Pilih Pengalaman</option>
                                    <option value="baru" {{ old('experience', $member->experience) == 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="2-5_tahun" {{ old('experience', $member->experience) == '2-5_tahun' ? 'selected' : '' }}>2-5 Tahun</option>
                                    <option value="5+_tahun" {{ old('experience', $member->experience) == '5+_tahun' ? 'selected' : '' }}>5+ Tahun</option>
                                </select>
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Keuangan -->
                            <div class="col-12 mt-4">
                                <h6 class="mb-3">Informasi Keuangan</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Batas Pinjaman (Rp)</label>
                                <input type="number" name="loan_limit" class="form-control @error('loan_limit') is-invalid @enderror" 
                                       value="{{ old('loan_limit', $member->loan_limit) }}" min="0" step="100000">
                                @error('loan_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="suspended" {{ old('status', $member->status) == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-save me-1"></i> Perbarui Data
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
@endsection
