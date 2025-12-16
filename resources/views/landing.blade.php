<!DOCTYPE html>
<html lang="id" x-data="landingApp()" :class="fontSize">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi Merah Putih Desa - Kemitraan Ekonomi Desa</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #fef2f2 0%, #fed7aa 100%);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .tab-active {
            background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);
            color: white;
        }
        
        .counter-animation {
            transition: all 0.5s ease-out;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-md shadow-sm z-50 transition-all duration-300" 
         :class="{ 'py-2': scrolled, 'py-4': !scrolled }"
         x-data="{ scrolled: false }"
         @scroll.window="scrolled = window.pageYOffset > 50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-600 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold">
                        KM
                    </div>
                    <div>
                        <p class="font-bold text-lg gradient-text">Koperasi Merah Putih</p>
                        <p class="text-xs text-gray-600">Desa Sejahtera</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-gray-700 hover:text-red-600 transition-colors">Beranda</a>
                    <a href="#layanan" class="text-gray-700 hover:text-red-600 transition-colors">Layanan</a>
                    <a href="#statistik" class="text-gray-700 hover:text-red-600 transition-colors">Statistik</a>
                    <a href="#aktivitas" class="text-gray-700 hover:text-red-600 transition-colors">Aktivitas</a>
                    <button @click="showRegistration = true" 
                            class="bg-gradient-to-r from-red-600 to-orange-600 text-white px-6 py-2 rounded-full hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        Daftar Sekarang
                    </button>
                </div>
                
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-3">
                <a href="#beranda" class="block text-gray-700 hover:text-red-600">Beranda</a>
                <a href="#layanan" class="block text-gray-700 hover:text-red-600">Layanan</a>
                <a href="#statistik" class="block text-gray-700 hover:text-red-600">Statistik</a>
                <a href="#aktivitas" class="block text-gray-700 hover:text-red-600">Aktivitas</a>
                <button @click="showRegistration = true; mobileMenu = false" 
                        class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white px-6 py-2 rounded-full">
                    Daftar Sekarang
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-gradient min-h-screen flex items-center pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        <span class="gradient-text">Kemitraan Ekonomi</span><br>
                        <span class="text-gray-900">Untuk Desa Sejahtera</span>
                    </h1>
                    <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                        Bergabunglah dengan koperasi kami untuk mengembangkan usaha pertanian, peternakan, perikanan, dan UMKM. 
                        Dapatkan modal, bantuan teknis, dan akses pasar yang lebih baik.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button @click="showRegistration = true" 
                                class="bg-gradient-to-r from-red-600 to-orange-600 text-white px-8 py-4 rounded-full hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold">
                            <i class="fas fa-user-plus mr-2"></i>
                            Daftar Anggota
                        </button>
                        <a href="#layanan" 
                           class="border-2 border-red-600 text-red-600 px-8 py-4 rounded-full hover:bg-red-600 hover:text-white transition-all duration-300 font-semibold">
                            <i class="fas fa-th-large mr-2"></i>
                            Lihat Layanan
                        </a>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-12">
                        <div class="text-center">
                            <div class="text-2xl font-bold gradient-text counter-animation" x-text="counters.members"></div>
                            <div class="text-sm text-gray-600">Anggota Aktif</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold gradient-text counter-animation" x-text="counters.units"></div>
                            <div class="text-sm text-gray-600">Unit Usaha</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold gradient-text counter-animation" x-text="counters.savings"></div>
                            <div class="text-sm text-gray-600">Total Simpanan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold gradient-text">100%</div>
                            <div class="text-sm text-gray-600">Kepatuhan</div>
                        </div>
                    </div>
                </div>
                
                <div class="relative" data-aos="fade-left" data-aos-duration="1000">
                    <div class="float-animation">
                        <div class="bg-white rounded-3xl shadow-2xl p-8">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="text-center p-4 bg-red-50 rounded-xl">
                                    <i class="fas fa-seedling text-3xl text-red-600 mb-2"></i>
                                    <p class="font-semibold">Pertanian</p>
                                </div>
                                <div class="text-center p-4 bg-orange-50 rounded-xl">
                                    <i class="fas fa-cow text-3xl text-orange-600 mb-2"></i>
                                    <p class="font-semibold">Peternakan</p>
                                </div>
                                <div class="text-center p-4 bg-blue-50 rounded-xl">
                                    <i class="fas fa-fish text-3xl text-blue-600 mb-2"></i>
                                    <p class="font-semibold">Perikanan</p>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-xl">
                                    <i class="fas fa-store text-3xl text-green-600 mb-2"></i>
                                    <p class="font-semibold">UMKM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating elements -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-r from-red-400 to-orange-400 rounded-full opacity-20 blur-xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-r from-orange-400 to-yellow-400 rounded-full opacity-20 blur-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="statistik" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">
                    <span class="gradient-text">Statistik Koperasi</span>
                </h2>
                <p class="text-lg text-gray-600">Pertumbuhan koperasi kami dalam angka nyata</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center card-hover bg-gradient-to-br from-red-50 to-orange-50 p-8 rounded-2xl" 
                     data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-600 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="text-3xl font-bold gradient-text mb-2" x-text="stats.total_members"></div>
                    <p class="text-gray-600">Total Anggota</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> +12% bulan ini
                    </p>
                </div>
                
                <div class="text-center card-hover bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl" 
                     data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-piggy-bank text-white text-2xl"></i>
                    </div>
                    <div class="text-3xl font-bold gradient-text mb-2" x-text="formattedStats.savings"></div>
                    <p class="text-gray-600">Total Simpanan</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> +8% bulan ini
                    </p>
                </div>
                
                <div class="text-center card-hover bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl" 
                     data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
                    </div>
                    <div class="text-3xl font-bold gradient-text mb-2" x-text="formattedStats.loans || 'Rp 0'"></div>
                    <p class="text-gray-600">Total Pinjaman</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> +15% bulan ini
                    </p>
                </div>
                
                <div class="text-center card-hover bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl" 
                     data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-store text-white text-2xl"></i>
                    </div>
                    <div class="text-3xl font-bold gradient-text mb-2" x-text="stats.business_units"></div>
                    <p class="text-gray-600">Unit Usaha</p>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> +2 unit baru
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">
                    <span class="gradient-text">Layanan Kami</span>
                </h2>
                <p class="text-lg text-gray-600">Solusi komprehensif untuk berbagai sektor usaha</p>
            </div>
            
            <!-- Service Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-12" data-aos="fade-up" data-aos-delay="100">
                <template x-for="(tab, index) in serviceTabs" :key="index">
                    <button @click="activeTab = tab.id"
                            :class="activeTab === tab.id ? 'tab-active' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105">
                        <i :class="tab.icon" class="mr-2"></i>
                        <span x-text="tab.name"></span>
                    </button>
                </template>
            </div>
            
            <!-- Service Content -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="(service, index) in currentServices" :key="index">
                    <div class="bg-white rounded-2xl shadow-lg card-hover p-8" 
                         data-aos="fade-up" 
                         :data-aos-delay="index * 100">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-100 to-orange-100 rounded-xl flex items-center justify-center text-3xl mb-6">
                            <span x-text="service.icon"></span>
                        </div>
                        <h3 class="text-xl font-bold mb-3" x-text="service.title"></h3>
                        <p class="text-gray-600 mb-4" x-text="service.desc"></p>
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-3">
                            <p class="text-sm font-semibold text-red-700" x-text="service.note"></p>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- PHP Fallback for services -->
            <div class="hidden" x-data="{}">
                @foreach($services as $key => $serviceList)
                    <div x-data="{{ json_encode($serviceList) }}" data-service="{{ $key }}"></div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Recent Activities -->
    <section id="aktivitas" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">
                    <span class="gradient-text">Aktivitas Terkini</span>
                </h2>
                <p class="text-lg text-gray-600">Update terbaru dari koperasi kami</p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- New Members -->
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold">Anggota Baru</h3>
                            <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                        </div>
                        <div class="space-y-4">
                            @foreach($recentActivities['new_members'] as $member)
                                <div class="bg-white rounded-xl p-4 flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">{{ $member->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $member->business_sector }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Recent Savings -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold">Simpanan Terbaru</h3>
                            <i class="fas fa-piggy-bank text-green-600 text-2xl"></i>
                        </div>
                        <div class="space-y-4">
                            @foreach($recentActivities['recent_savings'] as $saving)
                                <div class="bg-white rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-semibold">{{ $saving->member->full_name }}</p>
                                        <p class="text-green-600 font-bold">Rp {{ number_format($saving->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $saving->created_at->format('d M Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Recent Loans -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold">Pinjaman Terbaru</h3>
                            <i class="fas fa-hand-holding-usd text-orange-600 text-2xl"></i>
                        </div>
                        <div class="space-y-4">
                            @foreach($recentActivities['recent_loans'] as $loan)
                                <div class="bg-white rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-semibold">{{ $loan->member->full_name }}</p>
                                        <p class="text-orange-600 font-bold">Rp {{ number_format($loan->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $loan->created_at->format('d M Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-red-600 to-orange-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6" data-aos="fade-up">
                Siap Bergabung dan Tumbuh Bersama?
            </h2>
            <p class="text-xl text-white/90 mb-8" data-aos="fade-up" data-aos-delay="100">
                Lengkapi data Anda, kami verifikasi dalam 1-3 hari kerja sesuai SOP pemerintah desa.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                <button @click="showRegistration = true" 
                        class="bg-white text-red-600 px-8 py-4 rounded-full hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
                <a href="tel:02112345678" 
                   class="border-2 border-white text-white px-8 py-4 rounded-full hover:bg-white hover:text-red-600 transition-all duration-300 font-semibold">
                    <i class="fas fa-phone mr-2"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-600 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold">
                            KM
                        </div>
                        <div>
                            <p class="font-bold text-lg">Koperasi Merah Putih</p>
                            <p class="text-xs text-gray-400">Desa Sejahtera</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Membangun ekonomi desa melalui kemitraan yang kuat dan transparan.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Pertanian</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Peternakan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Perikanan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">UMKM</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Informasi</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <div class="space-y-2 text-gray-400 text-sm">
                        <p><i class="fas fa-phone mr-2"></i> 021-1234-5678</p>
                        <p><i class="fas fa-envelope mr-2"></i> info@kopmerahputih.desa.id</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Desa Sejahtera, Indonesia</p>
                        <div class="flex space-x-4 mt-4">
                            <a href="#" class="hover:text-white transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                            <a href="#" class="hover:text-white transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                            <a href="#" class="hover:text-white transition-colors"><i class="fab fa-whatsapp text-xl"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2024 Koperasi Merah Putih Desa. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Registration Modal -->
    <div x-show="showRegistration" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         @click.self="showRegistration = false">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100">
            <form action="{{ route('landing.register') }}" method="POST" class="p-8">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold gradient-text">Daftar Anggota</h3>
                    <button type="button" @click="showRegistration = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" required maxlength="16"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="tel" name="phone" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" required rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sektor Usaha</label>
                        <select name="business_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Pilih Sektor</option>
                            <option value="pertanian">Pertanian</option>
                            <option value="peternakan">Peternakan</option>
                            <option value="perikanan">Perikanan</option>
                            <option value="umkm">UMKM</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pengalaman</label>
                        <select name="experience" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Pilih Pengalaman</option>
                            <option value="baru">Baru</option>
                            <option value="2-5_tahun">2-5 Tahun</option>
                            <option value="5+_tahun">5+ Tahun</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white py-3 rounded-lg hover:shadow-lg transition-all duration-300 font-semibold mt-6">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
            </form>
        </div>
    </div>

    <script>
        function landingApp() {
            return {
                showRegistration: false,
                mobileMenu: false,
                activeTab: 'pertanian',
                counters: {
                    members: '0',
                    units: '0',
                    savings: 'Rp 0'
                },
                stats: @json($stats),
                formattedStats: @json($formattedStats),
                services: @json($services),
                serviceTabs: [
                    { id: 'pertanian', name: 'Pertanian', icon: 'fas fa-seedling' },
                    { id: 'peternakan', name: 'Peternakan', icon: 'fas fa-cow' },
                    { id: 'perikanan', name: 'Perikanan', icon: 'fas fa-fish' },
                    { id: 'umkm', name: 'UMKM', icon: 'fas fa-store' }
                ],
                
                get currentServices() {
                    return this.services[this.activeTab] || [];
                },
                
                init() {
                    // Initialize counters
                    this.animateCounters();
                    
                    // Initialize AOS
                    document.addEventListener('DOMContentLoaded', () => {
                        AOS.init({
                            duration: 1000,
                            once: true
                        });
                    });
                    
                    // Load services data
                    this.loadServicesData();
                },
                
                animateCounters() {
                    const animateValue = (element, start, end, duration, prefix = '', suffix = '') => {
                        let startTimestamp = null;
                        const step = (timestamp) => {
                            if (!startTimestamp) startTimestamp = timestamp;
                            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                            const value = Math.floor(progress * (end - start) + start);
                            element.textContent = prefix + value.toLocaleString('id-ID') + suffix;
                            if (progress < 1) {
                                window.requestAnimationFrame(step);
                            }
                        };
                        window.requestAnimationFrame(step);
                    };
                    
                    // Animate counters after a short delay
                    setTimeout(() => {
                        animateValue(
                            document.querySelector('[x-text="counters.members"]'), 
                            0, 
                            this.stats.active_members, 
                            2000
                        );
                        animateValue(
                            document.querySelector('[x-text="counters.units"]'), 
                            0, 
                            this.stats.active_business_units, 
                            2000
                        );
                        // For savings, just show the formatted value
                        document.querySelector('[x-text="counters.savings"]').textContent = this.formattedStats.savings;
                    }, 500);
                },
                
                loadServicesData() {
                    // Services data is already loaded from PHP
                    console.log('Services loaded:', this.services);
                }
            }
        }
    </script>
</body>
</html>
