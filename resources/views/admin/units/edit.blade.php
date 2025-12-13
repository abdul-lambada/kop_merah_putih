@extends('layouts.admin')

@section('title', 'Edit Unit Usaha')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Unit Usaha: {{ $unit->name }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-outline-info">
                            <i class="ti ti-eye me-1"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.units.update', $unit) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="col-12">
                                <h6 class="mb-3">Informasi Dasar</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nama Unit *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $unit->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tipe Unit *</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="sembako" {{ old('type', $unit->type) == 'sembako' ? 'selected' : '' }}>Sembako</option>
                                    <option value="apotek" {{ old('type', $unit->type) == 'apotek' ? 'selected' : '' }}>Apotek</option>
                                    <option value="klinik" {{ old('type', $unit->type) == 'klinik' ? 'selected' : '' }}>Klinik</option>
                                    <option value="logistik" {{ old('type', $unit->type) == 'logistik' ? 'selected' : '' }}>Logistik</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description', $unit->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Lokasi -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Informasi Lokasi</h6>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Alamat *</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" required>{{ old('address', $unit->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Telepon</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $unit->phone) }}" maxlength="20">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Manajemen -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Informasi Manajemen</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nama Manajer *</label>
                                <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" 
                                       value="{{ old('manager_name', $unit->manager_name) }}" required>
                                @error('manager_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', $unit->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status', $unit->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="maintenance" {{ old('status', $unit->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informasi Keuangan (Read-only) -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Informasi Keuangan</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Modal Awal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" value="{{ number_format($unit->initial_capital, 0, ',', '.') }}" readonly>
                                </div>
                                <small class="form-text text-muted">Modal awal tidak dapat diubah</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Saldo Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" value="{{ number_format($unit->current_balance, 0, ',', '.') }}" readonly>
                                </div>
                                <small class="form-text text-muted">Saldo akan diperbarui otomatis</small>
                            </div>
                            
                            <!-- Jam Operasional -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Jam Operasional (Opsional)</h6>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Senin - Jumat</label>
                                    <input type="text" name="operating_hours[weekday]" class="form-control" 
                                           value="{{ old('operating_hours.weekday', $unit->operating_hours['weekday'] ?? '08:00 - 17:00') }}" placeholder="08:00 - 17:00">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sabtu</label>
                                    <input type="text" name="operating_hours[saturday]" class="form-control" 
                                           value="{{ old('operating_hours.saturday', $unit->operating_hours['saturday'] ?? '08:00 - 15:00') }}" placeholder="08:00 - 15:00">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Minggu</label>
                                    <input type="text" name="operating_hours[sunday]" class="form-control" 
                                           value="{{ old('operating_hours.sunday', $unit->operating_hours['sunday'] ?? 'Tutup') }}" placeholder="Tutup">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Hari Libur</label>
                                    <input type="text" name="operating_hours[holiday]" class="form-control" 
                                           value="{{ old('operating_hours.holiday', $unit->operating_hours['holiday'] ?? 'Tutup') }}" placeholder="Tutup">
                                </div>
                            </div>
                            
                            <!-- Catatan -->
                            <div class="col-12">
                                <h6 class="mb-3 mt-4">Catatan</h6>
                                <label class="form-label">Informasi Tambahan</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="2" placeholder="Catatan penting mengenai unit usaha...">{{ old('notes', $unit->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-save me-1"></i> Simpan Perubahan
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
