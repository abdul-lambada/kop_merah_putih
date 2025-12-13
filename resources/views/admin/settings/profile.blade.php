@extends('layouts.admin')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Profile Header -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profil Pengguna</h5>
                    <small class="text-muted">Kelola informasi profil dan pengaturan akun</small>
                </div>
            </div>
        </div>
        
        <!-- Profile Information -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar avatar-xl mb-3">
                            <img src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : asset('sneat-1.0.0/assets/img/avatars/1.png') }}" alt="Profile" class="rounded-circle">
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->full_name ?? auth()->user()->name }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-primary">{{ auth()->user()->role ?? 'User' }}</span>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                            <i class="ti ti-camera me-1"></i> Ganti Foto
                        </button>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="ps-3">
                        <h6 class="mb-3">Informasi Akun</h6>
                        <div class="mb-3">
                            <small class="text-muted d-block">Nomor Anggota</small>
                            <span class="fw-medium">{{ auth()->user()->member_number ?? 'Tidak tersedia' }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Tanggal Bergabung</small>
                            <span class="fw-medium">{{ auth()->user()->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Terakhir Login</small>
                            <span class="fw-medium">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y H:i') : 'Tidak tersedia' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Form -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <i class="ti ti-user me-1"></i> Informasi Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">
                                <i class="ti ti-lock me-1"></i> Keamanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#preferences" role="tab" aria-controls="preferences" aria-selected="false">
                                <i class="ti ti-adjustments me-1"></i> Preferensi
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.profile.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="full_name" value="{{ auth()->user()->full_name ?? old('full_name') }}" placeholder="Masukkan nama lengkap">
                                        @error('full_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" placeholder="Masukkan email">
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" name="phone" value="{{ auth()->user()->phone ?? old('phone') }}" placeholder="Masukkan nomor telepon">
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="birth_date" value="{{ auth()->user()->birth_date?->format('Y-m-d') ?? old('birth_date') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control" name="address" rows="3" placeholder="Masukkan alamat lengkap">{{ auth()->user()->address ?? old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="emailNotifications">
                                                Terima notifikasi email
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                                            <i class="ti ti-refresh me-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <h6 class="mb-4">Ubah Password</h6>
                            <form method="POST" action="{{ route('admin.settings.password.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label">Password Saat Ini</label>
                                        <input type="password" class="form-control" name="current_password" placeholder="Masukkan password saat ini">
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" name="password" placeholder="Masukkan password baru">
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password baru">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-lock me-1"></i> Ubah Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <hr class="my-5">
                            
                            <h6 class="mb-4">Aktivitas Login</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>IP Address</th>
                                            <th>Device</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ now()->format('d M Y H:i') }}</td>
                                            <td>{{ request()->ip() }}</td>
                                            <td>{{ request()->userAgent() ? substr(request()->userAgent(), 0, 50) . '...' : 'Unknown' }}</td>
                                            <td><span class="badge bg-success">Aktif</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Preferences Tab -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.preferences.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <h6 class="mb-3">Tampilan</h6>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="theme" id="lightTheme" value="light" {{ auth()->user()->theme !== 'dark' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="lightTheme">
                                                <i class="ti ti-sun me-1"></i> Light Mode
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="theme" id="darkTheme" value="dark" {{ auth()->user()->theme === 'dark' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="darkTheme">
                                                <i class="ti ti-moon me-1"></i> Dark Mode
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <h6 class="mb-3">Bahasa</h6>
                                        <select class="form-select" name="language">
                                            <option value="id" {{ auth()->user()->language === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                            <option value="en" {{ auth()->user()->language === 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <h6 class="mb-3">Zona Waktu</h6>
                                        <select class="form-select" name="timezone">
                                            <option value="Asia/Jakarta" {{ auth()->user()->timezone === 'Asia/Jakarta' ? 'selected' : '' }}>WIB (UTC+7)</option>
                                            <option value="Asia/Makassar" {{ auth()->user()->timezone === 'Asia/Makassar' ? 'selected' : '' }}>WITA (UTC+8)</option>
                                            <option value="Asia/Jayapura" {{ auth()->user()->timezone === 'Asia/Jayapura' ? 'selected' : '' }}>WIT (UTC+9)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <h6 class="mb-3">Notifikasi</h6>
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="notification_email" value="0">
                                            <input class="form-check-input" type="checkbox" name="notification_email" id="notificationEmail" value="1" {{ auth()->user()->notification_email ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notificationEmail">
                                                Notifikasi Email
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="notification_push" value="0">
                                            <input class="form-check-input" type="checkbox" name="notification_push" id="notificationPush" value="1" {{ auth()->user()->notification_push ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notificationPush">
                                                Notifikasi Push
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="notification_sms" value="0">
                                            <input class="form-check-input" type="checkbox" name="notification_sms" id="notificationSms" value="1" {{ auth()->user()->notification_sms ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notificationSms">
                                                Notifikasi SMS
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i> Simpan Preferensi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Avatar Modal -->
<div class="modal fade" id="changeAvatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="avatarForm" method="POST" action="{{ route('admin.settings.avatar.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Foto</label>
                        <input type="file" class="form-control" name="avatar" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        @error('avatar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="text-center">
                        <img id="avatarPreview" src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : asset('sneat-1.0.0/assets/img/avatars/1.png') }}" alt="Preview" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="avatarForm" class="btn btn-primary">Unggah</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const avatarInput = document.querySelector('input[name="avatar"]');
    const avatarPreview = document.getElementById('avatarPreview');
    
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Theme switcher
    const themeRadios = document.querySelectorAll('input[name="theme"]');
    themeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const htmlElement = document.documentElement;
            if (this.value === 'dark') {
                htmlElement.classList.remove('light-style');
                htmlElement.classList.add('dark-style');
                htmlElement.setAttribute('data-theme', 'theme-dark');
            } else {
                htmlElement.classList.remove('dark-style');
                htmlElement.classList.add('light-style');
                htmlElement.setAttribute('data-theme', 'theme-default');
            }
        });
    });
});
</script>
@endsection
