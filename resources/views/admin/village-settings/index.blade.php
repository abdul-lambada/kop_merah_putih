@extends('admin.layouts.app')

@section('title', 'Pengaturan Desa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i>
                        Pengaturan Desa
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.village-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Logo Preview -->
                            <div class="col-md-3">
                                <div class="text-center">
                                    <img src="{{ $settings->logo_url }}" alt="Logo" class="img-fluid mb-3" style="max-height: 150px; border: 1px solid #ddd; padding: 10px;">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Logo Desa</label>
                                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                        @error('logo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($settings->logo_path)
                                        <a href="{{ route('admin.village-settings.remove-logo') }}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus logo?')">
                                            <i class="fas fa-trash"></i> Hapus Logo
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Basic Information -->
                            <div class="col-md-9">
                                <h5>Informasi Dasar</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="village_name" class="form-label">Nama Desa *</label>
                                            <input type="text" class="form-control" id="village_name" name="village_name" value="{{ $settings->village_name }}" required>
                                            @error('village_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="village_code" class="form-label">Kode Desa</label>
                                            <input type="text" class="form-control" id="village_code" name="village_code" value="{{ $settings->village_code }}">
                                            @error('village_code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $settings->description }}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="village_head" class="form-label">Kepala Desa</label>
                                            <input type="text" class="form-control" id="village_head" name="village_head" value="{{ $settings->village_head }}">
                                            @error('village_head')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="village_phone" class="form-label">Telepon</label>
                                            <input type="text" class="form-control" id="village_phone" name="village_phone" value="{{ $settings->village_phone }}">
                                            @error('village_phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Address Information -->
                        <h5>Alamat</h5>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="village_address" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" id="village_address" name="village_address" rows="2">{{ $settings->village_address }}</textarea>
                                    @error('village_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="province" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" id="province" name="province" value="{{ $settings->province }}">
                                    @error('province')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="regency" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" class="form-control" id="regency" name="regency" value="{{ $settings->regency }}">
                                    @error('regency')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="district" class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control" id="district" name="district" value="{{ $settings->district }}">
                                    @error('district')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="village_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="village_email" name="village_email" value="{{ $settings->village_email }}">
                                    @error('village_email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Coordinates -->
                        <h5>Koordinat GPS</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ $settings->latitude }}">
                                    @error('latitude')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ $settings->longitude }}">
                                    @error('longitude')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Social Media -->
                        <h5>Media Sosial</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $settings->social_media['facebook'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $settings->social_media['instagram'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="twitter" class="form-label">Twitter</label>
                                    <input type="text" class="form-control" id="twitter" name="twitter" value="{{ $settings->social_media['twitter'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="youtube" class="form-label">YouTube</label>
                                    <input type="text" class="form-control" id="youtube" name="youtube" value="{{ $settings->social_media['youtube'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="website" name="website" value="{{ $settings->social_media['website'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
