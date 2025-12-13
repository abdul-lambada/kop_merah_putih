@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- System Settings Header -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengaturan Sistem</h5>
                    <small class="text-muted">Konfigurasi sistem dan pengaturan aplikasi</small>
                </div>
            </div>
        </div>
        
        <!-- System Information -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Versi Aplikasi</small>
                        <span class="fw-medium">v1.0.0</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Laravel Framework</small>
                        <span class="fw-medium">{{ app()->version() }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">PHP Version</small>
                        <span class="fw-medium">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Environment</small>
                        <span class="badge bg-{{ app()->environment('production') ? 'danger' : 'success' }}">
                            {{ app()->environment() }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Debug Mode</small>
                        <span class="badge bg-{{ config('app.debug') ? 'warning' : 'success' }}">
                            {{ config('app.debug') ? 'ON' : 'OFF' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Timezone</small>
                        <span class="fw-medium">{{ config('app.timezone') }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Database</small>
                        <span class="fw-medium">{{ config('database.default') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- System Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Status Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-success rounded me-2">
                            <i class="ti ti-database ti-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">Database Connection</div>
                            <small class="text-success">Connected</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-success rounded me-2">
                            <i class="ti ti-file ti-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">File Storage</div>
                            <small class="text-success">Available</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-label-warning rounded me-2">
                            <i class="ti ti-mail ti-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">Mail Service</div>
                            <small class="text-warning">Not Configured</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-label-success rounded me-2">
                            <i class="ti ti-bell ti-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">Notifications</div>
                            <small class="text-success">Active</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Configuration -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                <i class="ti ti-settings me-1"></i> Umum
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">
                                <i class="ti ti-mail me-1"></i> Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#backup" role="tab" aria-controls="backup" aria-selected="false">
                                <i class="ti ti-archive me-1"></i> Backup
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#maintenance" role="tab" aria-controls="maintenance" aria-selected="false">
                                <i class="ti ti-tools me-1"></i> Maintenance
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.system.general.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Aplikasi</label>
                                        <input type="text" class="form-control" name="app_name" value="{{ config('app.name') }}" placeholder="Masukkan nama aplikasi">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">URL Aplikasi</label>
                                        <input type="url" class="form-control" name="app_url" value="{{ config('app.url') }}" placeholder="https://example.com">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Zona Waktu</label>
                                        <select class="form-select" name="timezone">
                                            <option value="Asia/Jakarta" {{ config('app.timezone') === 'Asia/Jakarta' ? 'selected' : '' }}>WIB (UTC+7)</option>
                                            <option value="Asia/Makassar" {{ config('app.timezone') === 'Asia/Makassar' ? 'selected' : '' }}>WITA (UTC+8)</option>
                                            <option value="Asia/Jayapura" {{ config('app.timezone') === 'Asia/Jayapura' ? 'selected' : '' }}>WIT (UTC+9)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Bahasa Default</label>
                                        <select class="form-select" name="locale">
                                            <option value="id" {{ config('app.locale') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                            <option value="en" {{ config('app.locale') === 'en' ? 'selected' : '' }}>English</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="debug_mode" id="debugMode" {{ config('app.debug') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="debugMode">
                                                Debug Mode
                                            </label>
                                        </div>
                                        <small class="text-muted">Aktifkan mode debug untuk development</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenanceMode">
                                            <label class="form-check-label" for="maintenanceMode">
                                                Maintenance Mode
                                            </label>
                                        </div>
                                        <small class="text-muted">Aktifkan mode maintenance untuk sistem maintenance</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Email Tab -->
                        <div class="tab-pane fade" id="email" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.system.email.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Mail Driver</label>
                                        <select class="form-select" name="mail_driver">
                                            <option value="smtp" {{ config('mail.default') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="mail" {{ config('mail.default') === 'mail' ? 'selected' : '' }}>PHP Mail</option>
                                            <option value="sendmail" {{ config('mail.default') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Mail Host</label>
                                        <input type="text" class="form-control" name="mail_host" value="{{ config('mail.mailers.smtp.host') }}" placeholder="smtp.gmail.com">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Mail Port</label>
                                        <input type="number" class="form-control" name="mail_port" value="{{ config('mail.mailers.smtp.port') }}" placeholder="587">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Mail Username</label>
                                        <input type="email" class="form-control" name="mail_username" value="{{ config('mail.mailers.smtp.username') }}" placeholder="email@example.com">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Mail Password</label>
                                        <input type="password" class="form-control" name="mail_password" placeholder="••••••••">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Encryption</label>
                                        <select class="form-select" name="mail_encryption">
                                            <option value="tls" {{ config('mail.mailers.smtp.encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ config('mail.mailers.smtp.encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="" {{ !config('mail.mailers.smtp.encryption') ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">From Address</label>
                                        <input type="email" class="form-control" name="mail_from_address" value="{{ config('mail.from.address') }}" placeholder="noreply@example.com">
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">From Name</label>
                                        <input type="text" class="form-control" name="mail_from_name" value="{{ config('mail.from.name') }}" placeholder="Application Name">
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan Email
                                        </button>
                                        <button type="button" class="btn btn-outline-info ms-2" onclick="testEmailConnection()">
                                            <i class="ti ti-send me-1"></i> Test Koneksi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Backup Tab -->
                        <div class="tab-pane fade" id="backup" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Backup Database</h6>
                                    <p class="text-muted">Buat backup database secara manual atau otomatis</p>
                                    
                                    <div class="d-flex gap-2 mb-4">
                                        <button type="button" class="btn btn-primary" onclick="createBackup()">
                                            <i class="ti ti-download me-1"></i> Backup Sekarang
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="downloadBackup()">
                                            <i class="ti ti-file-download me-1"></i> Download Backup
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <h6 class="mb-3">Jadwal Backup Otomatis</h6>
                                    <form method="POST" action="{{ route('admin.settings.system.backup.schedule') }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Frekuensi Backup</label>
                                                <select class="form-select" name="backup_frequency">
                                                    <option value="daily">Harian</option>
                                                    <option value="weekly">Mingguan</option>
                                                    <option value="monthly">Bulanan</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Waktu Backup</label>
                                                <input type="time" class="form-control" name="backup_time" value="02:00">
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="auto_backup" id="autoBackup">
                                                    <label class="form-check-label" for="autoBackup">
                                                        Aktifkan Backup Otomatis
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-device-floppy me-1"></i> Simpan Jadwal
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="col-12">
                                    <h6 class="mb-3">Riwayat Backup</h6>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Tipe</th>
                                                    <th>Ukuran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ now()->format('d M Y H:i') }}</td>
                                                    <td>Manual</td>
                                                    <td>2.5 MB</td>
                                                    <td><span class="badge bg-success">Success</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-download"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Maintenance Tab -->
                        <div class="tab-pane fade" id="maintenance" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Maintenance Mode</h6>
                                    <p class="text-muted">Aktifkan maintenance mode untuk melakukan pemeliharaan sistem</p>
                                    
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <div>
                                            Saat maintenance mode aktif, pengguna biasa tidak dapat mengakses aplikasi. Hanya administrator yang dapat mengakses.
                                        </div>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="maintenance_mode_switch" id="maintenanceModeSwitch">
                                        <label class="form-check-label" for="maintenanceModeSwitch">
                                            Aktifkan Maintenance Mode
                                        </label>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Pesan Maintenance</label>
                                        <textarea class="form-control" name="maintenance_message" rows="3" placeholder="Sistem sedang dalam maintenance. Silakan coba lagi beberapa saat.">Sistem sedang dalam maintenance. Silakan coba lagi beberapa saat.</textarea>
                                    </div>
                                    
                                    <button type="button" class="btn btn-warning" onclick="toggleMaintenance()">
                                        <i class="ti ti-tools me-1"></i> Update Maintenance Mode
                                    </button>
                                </div>
                                
                                <div class="col-12">
                                    <h6 class="mb-3">Clear Cache</h6>
                                    <p class="text-muted">Bersihkan cache untuk meningkatkan performa sistem</p>
                                    
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="clearCache('config')">
                                            <i class="ti ti-refresh me-1"></i> Clear Config Cache
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" onclick="clearCache('route')">
                                            <i class="ti ti-route me-1"></i> Clear Route Cache
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" onclick="clearCache('view')">
                                            <i class="ti ti-eye me-1"></i> Clear View Cache
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" onclick="clearCache('application')">
                                            <i class="ti ti-app me-1"></i> Clear Application Cache
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <h6 class="mb-3">System Logs</h6>
                                    <div class="d-flex gap-2 mb-3">
                                        <button type="button" class="btn btn-outline-info" onclick="viewLogs()">
                                            <i class="ti ti-file-text me-1"></i> View Logs
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" onclick="downloadLogs()">
                                            <i class="ti ti-download me-1"></i> Download Logs
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="clearLogs()">
                                            <i class="ti ti-trash me-1"></i> Clear Logs
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testEmailConnection() {
    // Implement email connection test
    alert('Testing email connection...');
}

function createBackup() {
    // Implement backup creation
    alert('Creating backup...');
}

function downloadBackup() {
    // Implement backup download
    alert('Downloading backup...');
}

function toggleMaintenance() {
    // Implement maintenance mode toggle
    alert('Toggling maintenance mode...');
}

function clearCache(type) {
    // Implement cache clearing
    alert('Clearing ' + type + ' cache...');
}

function viewLogs() {
    // Implement log viewing
    alert('Viewing system logs...');
}

function downloadLogs() {
    // Implement log download
    alert('Downloading system logs...');
}

function clearLogs() {
    // Implement log clearing
    alert('Clearing system logs...');
}
</script>
@endsection
