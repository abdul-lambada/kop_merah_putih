<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('sneat-1.0.0/assets/') }}" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Koperasi Merah Putih') - Admin Panel</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat-1.0.0/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/assets/css/demo.css') }}" />

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])

    <!-- Helpers -->
    <script src="{{ asset('sneat-1.0.0/assets/vendor/js/helpers.js') }}"></script>

    <!-- Template Customizer JS -->
    <script src="{{ asset('sneat-1.0.0/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
                        <span class="app-brand-logo">
                            <svg width="32" height="32" viewBox="0 0 32 32">
                                <rect width="32" height="32" rx="8" fill="#b91c1c"/>
                                <text x="16" y="22" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="white" text-anchor="middle">KM</text>
                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">Koperasi Merah Putih</span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>

                    <!-- Manajemen Anggota -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-group"></i>
                            <div>Manajemen Anggota</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.members.index') }}" class="menu-link">
                                    <div>Data Anggota</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.members.register') ? 'active' : '' }}">
                                <a href="{{ route('admin.members.register') }}" class="menu-link">
                                    <div>Pendaftaran</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Simpan Pinjam -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-dollar"></i>
                            <div>Simpan Pinjam</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('admin.savings.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.savings.index') }}" class="menu-link">
                                    <div>Simpanan</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.loans.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.loans.index') }}" class="menu-link">
                                    <div>Pinjaman</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Unit Usaha -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div>Unit Usaha</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('admin.units.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.units.index') }}" class="menu-link">
                                    <div>Data Unit</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.units.sembako') ? 'active' : '' }}">
                                <a href="{{ route('admin.units.sembako') }}" class="menu-link">
                                    <div>Sembako</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.units.apotek') ? 'active' : '' }}">
                                <a href="{{ route('admin.units.apotek') }}" class="menu-link">
                                    <div>Apotek</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.units.klinik') ? 'active' : '' }}">
                                <a href="{{ route('admin.units.klinik') }}" class="menu-link">
                                    <div>Klinik</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.units.logistik') ? 'active' : '' }}">
                                <a href="{{ route('admin.units.logistik') }}" class="menu-link">
                                    <div>Logistik</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Transaksi -->
                    <li class="menu-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.transactions.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-receipt"></i>
                            <div>Transaksi</div>
                        </a>
                    </li>

                    <!-- Laporan -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div>Laporan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('admin.reports.financial') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.financial') }}" class="menu-link">
                                    <div>Keuangan</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.reports.members') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.members') }}" class="menu-link">
                                    <div>Anggota</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.reports.units') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.units') }}" class="menu-link">
                                    <div>Unit Usaha</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Pengaturan -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div>Pengaturan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('admin.settings.profile') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.profile') }}" class="menu-link">
                                    <div>Profil</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('admin.settings.system') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.system') }}" class="menu-link">
                                    <div>Sistem</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Kembali ke Landing -->
                    <li class="menu-item">
                        <a href="{{ url('/') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-arrow-back"></i>
                            <div>Landing Page</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-2 me-xl-4" href="javascript:void(0);">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Cari..." aria-label="Cari..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('sneat-1.0.0/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('sneat-1.0.0/assets/img/avatars/1.png') }}" alt class="w-32 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'Admin' }}</span>
                                                    <small class="text-muted">Administrator</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.settings.profile') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Profil Saya</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.settings.system') }}">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Pengaturan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Keluar</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-4 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © {{ date('Y') }} Koperasi Merah Putih Desa
                            </div>
                            <div>
                                <a href="#" class="footer-link fw-bolder">Bantuan</a>
                                <a href="#" class="footer-link fw-bolder">Kebijakan Privasi</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('sneat-1.0.0/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/assets/vendor/js/bootstrap.js') }}"></script>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])

    <!-- Template JS -->
    <script src="{{ asset('sneat-1.0.0/assets/js/main.js') }}"></script>

    <!-- Page specific JS -->
    @stack('scripts')
</body>
</html>
