@extends('layouts.admin')

@section('title', 'Tambah Anggota')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tambah Anggota Baru</h5>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.members.store') }}">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Informasi Personal -->
                            <div class="col-12">
                                <h6 class="mb-3">Informasi Personal</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                       value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon *</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Usaha -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Informasi Usaha</h6>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Sektor Usaha *</label>
                                <select name="business_sector" class="form-select @error('business_sector') is-invalid @enderror" required>
                                    <option value="">Pilih Sektor</option>
                                    <option value="Pertanian" {{ old('business_sector') == 'Pertanian' ? 'selected' : '' }}>Pertanian</option>
                                    <option value="Perdagangan" {{ old('business_sector') == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                    <option value="Jasa" {{ old('business_sector') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                    <option value="Industri" {{ old('business_sector') == 'Industri' ? 'selected' : '' }}>Industri</option>
                                    <option value="Lainnya" {{ old('business_sector') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('business_sector')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Pengalaman Usaha *</label>
                                <select name="experience" class="form-select @error('experience') is-invalid @enderror" required>
                                    <option value="">Pilih Pengalaman</option>
                                    <option value="Pemula" {{ old('experience') == 'Pemula' ? 'selected' : '' }}>Pemula (0-2 tahun)</option>
                                    <option value="Menengah" {{ old('experience') == 'Menengah' ? 'selected' : '' }}>Menengah (2-5 tahun)</option>
                                    <option value="Ahli" {{ old('experience') == 'Ahli' ? 'selected' : '' }}>Ahli (5+ tahun)</option>
                                </select>
                                @error('experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Pendapatan Bulanan (Opsional)</label>
                                <input type="number" name="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" 
                                       value="{{ old('monthly_income') }}" min="0" step="100000">
                                @error('monthly_income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Koperasi -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Informasi Koperasi</h6>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Bergabung *</label>
                                <input type="date" name="join_date" class="form-control @error('join_date') is-invalid @enderror" 
                                       value="{{ old('join_date', now()->format('Y-m-d')) }}" required>
                                @error('join_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Limit Pinjaman (Opsional)</label>
                                <input type="number" name="loan_limit" class="form-control @error('loan_limit') is-invalid @enderror" 
                                       value="{{ old('loan_limit') }}" min="0" step="100000">
                                <small class="form-text text-muted">Kosongkan untuk menggunakan default berdasarkan pengalaman</small>
                                @error('loan_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Catatan -->
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
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
                                        <i class="ti ti-save me-1"></i> Simpan Anggota
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
