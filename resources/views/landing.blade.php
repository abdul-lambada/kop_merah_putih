<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Kop Merah Putih</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#8B1E1E',
                            primaryDark: '#6C1515',
                            cream: '#F6EEE4',
                            creamSoft: '#FBF5EC',
                        },
                        fontFamily: {
                            sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
                        },
                    },
                },
            };
        </script>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body
        class="antialiased bg-cream text-slate-900 font-sans"
        x-data="{ mobileOpen: false, programFilter: 'semua', tabImpact: 'tahun' }"
    >
        <div class="min-h-screen flex flex-col">
            <!-- NAVBAR -->
            <header class="sticky top-0 z-30 border-b border-creamSoft bg-cream/90 backdrop-blur">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
                    <a href="#hero" class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-semibold text-sm">
                            KM
                        </div>
                        <div class="leading-tight">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-primary">Kop Merah Putih</div>
                            <div class="text-[11px] text-slate-500">Ngopi sambil berbagi kebaikan</div>
                        </div>
                    </a>

                    <nav class="hidden md:flex items-center gap-6 text-[13px] font-medium text-slate-700">
                        <a href="#program" class="hover:text-primary transition">Program</a>
                        <a href="#produk" class="hover:text-primary transition">Produk</a>
                        <a href="#tentang" class="hover:text-primary transition">Tentang</a>
                        <a href="#berita" class="hover:text-primary transition">Berita</a>
                        <a href="#kontak" class="hover:text-primary transition">Kontak</a>
                    </nav>

                    <div class="hidden md:flex items-center gap-3 text-[13px]">
                        @if (Route::has('login'))
                            @auth
                                <a
                                    href="{{ url('/home') }}"
                                    class="px-3 py-1.5 rounded-full border border-primary/20 text-primary text-xs font-medium hover:bg-primary/5 transition"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="px-3 py-1.5 rounded-full border border-creamSoft bg-white text-slate-700 text-xs font-medium hover:bg-creamSoft transition"
                                >
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="px-3.5 py-1.5 rounded-full bg-primary text-white text-xs font-semibold hover:bg-primaryDark shadow-sm transition"
                                    >
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <button
                        type="button"
                        class="md:hidden inline-flex items-center justify-center rounded-full border border-creamSoft bg-white p-2 text-slate-700"
                        @click="mobileOpen = !mobileOpen"
                        aria-label="Toggle navigation"
                    >
                        <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg
                            x-show="mobileOpen"
                            x-cloak
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="md:hidden border-t border-creamSoft" x-show="mobileOpen" x-transition.opacity.duration.200ms x-cloak>
                    <nav class="max-w-6xl mx-auto px-4 py-3 space-y-1 text-[13px] font-medium text-slate-700 bg-cream">
                        <a href="#program" class="block py-1.5" @click="mobileOpen = false">Program</a>
                        <a href="#produk" class="block py-1.5" @click="mobileOpen = false">Produk</a>
                        <a href="#tentang" class="block py-1.5" @click="mobileOpen = false">Tentang</a>
                        <a href="#berita" class="block py-1.5" @click="mobileOpen = false">Berita</a>
                        <a href="#kontak" class="block py-1.5" @click="mobileOpen = false">Kontak</a>
                    </nav>
                </div>
            </header>

            <main class="flex-1">
                <!-- HERO -->
                <section id="hero" class="border-b border-creamSoft bg-cream">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                        <div class="space-y-5">
                            <span
                                class="inline-flex items-center gap-1 rounded-full border border-primary/10 bg-white px-3 py-1 text-[11px] font-medium uppercase tracking-[0.2em] text-primary"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
                                Gerakan Sosial Kop Merah Putih
                            </span>
                            <h1 class="text-2xl sm:text-3xl lg:text-[32px] leading-tight font-semibold text-slate-900">
                                Menyatukan rasa kopi dan kepedulian
                                <span class="text-primary">untuk Indonesia.</span>
                            </h1>
                            <p class="text-sm sm:text-[15px] leading-relaxed text-slate-600 max-w-xl">
                                Setiap cangkir kopi yang Anda nikmati turut menggerakkan program pendidikan, kesehatan, dan ekonomi untuk masyarakat di berbagai
                                daerah.
                            </p>
                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <a
                                    href="#program"
                                    class="inline-flex items-center justify-center rounded-full bg-primary px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primaryDark transition"
                                >
                                    Lihat Program
                                </a>
                                <a
                                    href="#tentang"
                                    class="inline-flex items-center justify-center rounded-full border border-creamSoft bg-white px-4 py-2 text-xs font-medium text-slate-700 hover:bg-creamSoft transition"
                                >
                                    Tentang Kop Merah Putih
                                </a>
                            </div>
                            <div class="grid grid-cols-3 gap-4 pt-4 max-w-md text-xs">
                                <div>
                                    <div class="font-semibold text-slate-900">120+</div>
                                    <div class="text-slate-500">Desa dampingan</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">8.500</div>
                                    <div class="text-slate-500">Penerima manfaat</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">15</div>
                                    <div class="text-slate-500">Program aktif</div>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="aspect-[4/3] rounded-2xl border border-creamSoft bg-slate-900/80 overflow-hidden shadow-[0_18px_45px_rgba(15,23,42,0.25)]">
                                <div
                                    class="absolute inset-0 bg-[url('https://images.pexels.com/photos/34085/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center opacity-80"
                                ></div>
                                <div class="relative h-full flex flex-col justify-between p-5 sm:p-6">
                                    <div
                                        class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-medium text-creamSoft border border-white/20 backdrop-blur"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                        Program Sedang Berjalan
                                    </div>
                                    <div class="space-y-3 bg-slate-900/60 rounded-xl p-4 backdrop-blur-sm border border-white/10">
                                        <div class="flex items-center justify-between gap-3 text-[11px] text-creamSoft">
                                            <span class="font-medium uppercase tracking-[0.18em]">Pendidikan</span>
                                            <span class="text-creamSoft/80">Batch 04 • Papua</span>
                                        </div>
                                        <h2 class="text-sm font-semibold text-white">Beasiswa Kopi untuk Anak Petani</h2>
                                        <p class="text-[11px] text-creamSoft/80 leading-relaxed">
                                            Mendukung pendidikan anak-anak petani kopi melalui program beasiswa dan pendampingan belajar.
                                        </p>
                                        <div class="space-y-1.5">
                                            <div class="h-1.5 rounded-full bg-white/10 overflow-hidden">
                                                <div class="h-full w-2/3 rounded-full bg-emerald-400"></div>
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] text-creamSoft/80">
                                                <span>Rp 215.000.000 terkumpul</span>
                                                <span class="font-semibold text-white">68%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- PROGRAM -->
                <section id="program" class="bg-creamSoft border-b border-creamSoft/70">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-12">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold text-slate-900 mb-1">Program Unggulan</h2>
                                <p class="text-xs sm:text-[13px] text-slate-600 max-w-md">
                                    Inisiatif sosial dari secangkir kopi, mulai dari pendidikan hingga pemberdayaan ekonomi lokal.
                                </p>
                            </div>
                            <div class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 text-[11px] text-slate-600 border border-creamSoft">
                                <button type="button" class="px-2 py-0.5 rounded-full" :class="programFilter === 'semua' ? 'bg-primary text-white' : ''" @click="programFilter = 'semua'">
                                    Semua
                                </button>
                                <button
                                    type="button"
                                    class="px-2 py-0.5 rounded-full"
                                    :class="programFilter === 'pendidikan' ? 'bg-primary text-white' : ''"
                                    @click="programFilter = 'pendidikan'"
                                >
                                    Pendidikan
                                </button>
                                <button
                                    type="button"
                                    class="px-2 py-0.5 rounded-full"
                                    :class="programFilter === 'ekonomi' ? 'bg-primary text-white' : ''"
                                    @click="programFilter = 'ekonomi'"
                                >
                                    Ekonomi
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                            <div class="rounded-xl bg-white border border-creamSoft p-4 flex flex-col gap-3" x-show="programFilter === 'semua' || programFilter === 'pendidikan'">
                                <div class="inline-flex items-center gap-2 text-[11px]">
                                    <span class="rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5 font-medium">Pendidikan</span>
                                    <span class="text-slate-400">Batch 04</span>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900">Kelas Literasi Kopi untuk Remaja</h3>
                                <p class="text-[11px] leading-relaxed text-slate-600">Pelatihan pengolahan kopi dan pengembangan soft-skill untuk remaja desa.</p>
                                <div class="h-1.5 w-full rounded-full bg-creamSoft overflow-hidden">
                                    <div class="h-full w-3/4 rounded-full bg-primary"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Rp 75juta / Rp 100juta</span>
                                    <span class="font-semibold text-primary">75%</span>
                                </div>
                            </div>

                            <div class="rounded-xl bg-white border border-creamSoft p-4 flex flex-col gap-3" x-show="programFilter === 'semua' || programFilter === 'ekonomi'">
                                <div class="inline-flex items-center gap-2 text-[11px]">
                                    <span class="rounded-full bg-amber-50 text-amber-800 px-2 py-0.5 font-medium">Ekonomi</span>
                                    <span class="text-slate-400">UMKM</span>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900">Rumah Roastery Komunitas</h3>
                                <p class="text-[11px] leading-relaxed text-slate-600">Ruang bersama untuk roasting, packaging, dan pemasaran kopi petani lokal.</p>
                                <div class="h-1.5 w-full rounded-full bg-creamSoft overflow-hidden">
                                    <div class="h-full w-1/2 rounded-full bg-primary"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Rp 120juta / Rp 240juta</span>
                                    <span class="font-semibold text-primary">50%</span>
                                </div>
                            </div>

                            <div class="rounded-xl bg-white border border-creamSoft p-4 flex flex-col gap-3" x-show="programFilter === 'semua' || programFilter === 'pendidikan'">
                                <div class="inline-flex items-center gap-2 text-[11px]">
                                    <span class="rounded-full bg-sky-50 text-sky-800 px-2 py-0.5 font-medium">Digital</span>
                                    <span class="text-slate-400">Pelatihan</span>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900">Sekolah Konten Kopi</h3>
                                <p class="text-[11px] leading-relaxed text-slate-600">Belajar membuat konten kreatif untuk promosi kopi lokal.</p>
                                <div class="h-1.5 w-full rounded-full bg-creamSoft overflow-hidden">
                                    <div class="h-full w-5/6 rounded-full bg-primary"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>120 peserta</span>
                                    <span class="font-semibold text-primary">85% kursi terisi</span>
                                </div>
                            </div>

                            <div class="rounded-xl bg-white border border-creamSoft p-4 flex flex-col gap-3" x-show="programFilter === 'semua' || programFilter === 'ekonomi'">
                                <div class="inline-flex items-center gap-2 text-[11px]">
                                    <span class="rounded-full bg-rose-50 text-rose-700 px-2 py-0.5 font-medium">Lingkungan</span>
                                    <span class="text-slate-400">Hijau</span>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900">Satu Pohon Satu Cangkir</h3>
                                <p class="text-[11px] leading-relaxed text-slate-600">Setiap pembelian kopi = satu pohon ditanam di daerah hulu.</p>
                                <div class="h-1.5 w-full rounded-full bg-creamSoft overflow-hidden">
                                    <div class="h-full w-2/3 rounded-full bg-primary"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>3.200 / 5.000 pohon</span>
                                    <span class="font-semibold text-primary">64%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- PRODUK -->
                <section id="produk" class="bg-cream py-10 lg:py-12 border-b border-creamSoft/70">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold text-slate-900 mb-1">Produk Kopi & UMKM</h2>
                                <p class="text-xs sm:text-[13px] text-slate-600 max-w-md">
                                    Pilih kopi dan produk olahan dari petani dampingan. Setiap pembelian langsung berdampak.
                                </p>
                            </div>
                            <a href="#" class="text-[12px] font-semibold text-primary hover:text-primaryDark">Lihat katalog lengkap</a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                            <div class="rounded-xl bg-white border border-creamSoft overflow-hidden flex flex-col">
                                <div class="h-28 bg-[url('https://images.pexels.com/photos/585750/pexels-photo-585750.jpeg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center"></div>
                                <div class="p-4 space-y-2 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Kopi Arabika</span>
                                        <span class="rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5">Aceh</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900">Gayo Light Roast 200gr</h3>
                                    <div class="mt-auto flex items-center justify-between pt-2">
                                        <div class="text-[13px] font-semibold text-primary">Rp 75.000</div>
                                        <button class="text-[11px] font-semibold text-primary hover:text-primaryDark">Beli</button>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl bg-white border border-creamSoft overflow-hidden flex flex-col">
                                <div class="h-28 bg-[url('https://images.pexels.com/photos/324028/pexels-photo-324028.jpeg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center"></div>
                                <div class="p-4 space-y-2 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Kopi Robusta</span>
                                        <span class="rounded-full bg-amber-50 text-amber-800 px-2 py-0.5">Lampung</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900">Red Honey Roast 250gr</h3>
                                    <div class="mt-auto flex items-center justify-between pt-2">
                                        <div class="text-[13px] font-semibold text-primary">Rp 68.000</div>
                                        <button class="text-[11px] font-semibold text-primary hover:text-primaryDark">Beli</button>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl bg-white border border-creamSoft overflow-hidden flex flex-col">
                                <div class="h-28 bg-[url('https://images.pexels.com/photos/1860204/pexels-photo-1860204.jpeg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center"></div>
                                <div class="p-4 space-y-2 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Merchandise</span>
                                        <span class="rounded-full bg-sky-50 text-sky-800 px-2 py-0.5">UMKM</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900">Tumbler Kop Merah Putih</h3>
                                    <div class="mt-auto flex items-center justify-between pt-2">
                                        <div class="text-[13px] font-semibold text-primary">Rp 95.000</div>
                                        <button class="text-[11px] font-semibold text-primary hover:text-primaryDark">Beli</button>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl bg-white border border-creamSoft overflow-hidden flex flex-col">
                                <div class="h-28 bg-[url('https://images.pexels.com/photos/373888/pexels-photo-373888.jpeg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center"></div>
                                <div class="p-4 space-y-2 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Paket Bundling</span>
                                        <span class="rounded-full bg-rose-50 text-rose-700 px-2 py-0.5">Gift</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900">Hampers Kopi & Kebaikan</h3>
                                    <div class="mt-auto flex items-center justify-between pt-2">
                                        <div class="text-[13px] font-semibold text-primary">Rp 185.000</div>
                                        <button class="text-[11px] font-semibold text-primary hover:text-primaryDark">Beli</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- TENTANG + DAMPAK RINGKAS -->
                <section id="tentang" class="bg-creamSoft py-10 lg:py-12 border-b border-creamSoft/70">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-8 lg:gap-12 items-start text-xs sm:text-[13px]">
                        <div class="space-y-4">
                            <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Tentang Kop Merah Putih</h2>
                            <p class="text-slate-600 leading-relaxed">
                                Gerakan kolaborasi petani kopi, pelaku usaha, dan masyarakat luas untuk menghadirkan rantai kopi yang adil sekaligus berdampak
                                sosial bagi berbagai daerah di Indonesia.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div class="rounded-xl bg-white border border-creamSoft p-4 space-y-2">
                                    <div class="text-[11px] font-semibold text-primary uppercase tracking-[0.18em]">Visi</div>
                                    <p class="text-slate-600 leading-relaxed">
                                        Ekosistem kopi yang menyejahterakan petani, menjaga alam, dan menggerakkan solidaritas sosial lintas daerah.
                                    </p>
                                </div>
                                <div class="rounded-xl bg-white border border-creamSoft p-4 space-y-2">
                                    <div class="text-[11px] font-semibold text-primary uppercase tracking-[0.18em]">Misi</div>
                                    <ul class="list-disc list-inside space-y-1 text-slate-600">
                                        <li>Menguatkan kapasitas petani dan komunitas kopi.</li>
                                        <li>Mengelola program sosial berbasis kebutuhan lokal.</li>
                                        <li>Membuka akses pasar yang adil dan transparan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white border border-creamSoft p-4 sm:p-5 space-y-4">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-sm font-semibold text-slate-900">Dampak Singkat</h3>
                                <div class="inline-flex items-center gap-1 rounded-full bg-creamSoft px-2 py-0.5 text-[11px]">
                                    <button
                                        type="button"
                                        class="px-1.5 py-0.5 rounded-full"
                                        :class="tabImpact === 'tahun' ? 'bg-primary text-white' : ''"
                                        @click="tabImpact = 'tahun'"
                                    >
                                        Tahun ini
                                    </button>
                                    <button
                                        type="button"
                                        class="px-1.5 py-0.5 rounded-full"
                                        :class="tabImpact === 'total' ? 'bg-primary text-white' : ''"
                                        @click="tabImpact = 'total'"
                                    >
                                        Sejak awal
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 text-center text-[11px]">
                                <div class="rounded-lg bg-creamSoft p-3">
                                    <div class="text-[18px] font-semibold text-slate-900" x-text="tabImpact === 'tahun' ? '3.200' : '8.500'"></div>
                                    <div class="text-slate-500">Penerima manfaat</div>
                                </div>
                                <div class="rounded-lg bg-creamSoft p-3">
                                    <div class="text-[18px] font-semibold text-slate-900" x-text="tabImpact === 'tahun' ? '12' : '45'"></div>
                                    <div class="text-slate-500">Program aktif</div>
                                </div>
                                <div class="rounded-lg bg-creamSoft p-3">
                                    <div class="text-[18px] font-semibold text-slate-900" x-text="tabImpact === 'tahun' ? '18' : '72'"></div>
                                    <div class="text-slate-500">Kota & kabupaten</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- BERITA -->
                <section id="berita" class="bg-cream py-10 lg:py-12 border-b border-creamSoft/70">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold text-slate-900 mb-1">Berita & Cerita</h2>
                                <p class="text-xs sm:text-[13px] text-slate-600 max-w-md">
                                    Ikuti kabar terbaru dari desa dampingan dan kisah para penerima manfaat.
                                </p>
                            </div>
                            <a href="#" class="text-[12px] font-semibold text-primary hover:text-primaryDark">Lihat semua artikel</a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-[1.2fr_0.8fr] gap-4 text-xs">
                            <article class="rounded-xl bg-white border border-creamSoft p-4 sm:p-5 flex flex-col gap-3">
                                <div class="text-[11px] text-slate-500">Artikel • 12 Des 2025</div>
                                <h3 class="text-sm sm:text-[15px] font-semibold text-slate-900">
                                    Dari kebun ke cangkir: perjalanan kopi yang lebih adil bagi petani
                                </h3>
                                <p class="text-[11px] text-slate-600 leading-relaxed max-w-xl">
                                    Bagaimana skema perdagangan yang adil dan komunitas penikmat kopi dapat memperbaiki kesejahteraan petani kecil.
                                </p>
                                <div class="flex items-center justify-between pt-1 text-[11px]">
                                    <span class="text-slate-500">Oleh Tim Cerita Dampak</span>
                                    <button class="text-primary font-semibold hover:text-primaryDark">Baca cerita</button>
                                </div>
                            </article>

                            <div class="space-y-3">
                                <article class="rounded-xl bg-white border border-creamSoft p-3.5 space-y-1.5">
                                    <div class="text-[11px] text-slate-500">Catatan Lapangan • 5 Des 2025</div>
                                    <h3 class="text-sm font-semibold text-slate-900">Belajar bersama ibu-ibu penggerak di Enrekang</h3>
                                    <p class="text-[11px] text-slate-600 leading-relaxed">Kisah kelompok ibu yang mengelola kedai kopi kecil dan kegiatan baca tulis anak.</p>
                                </article>
                                <article class="rounded-xl bg-white border border-creamSoft p-3.5 space-y-1.5">
                                    <div class="text-[11px] text-slate-500">Highlight • 28 Nov 2025</div>
                                    <h3 class="text-sm font-semibold text-slate-900">3 kolaborasi brand yang mendukung program beasiswa kopi</h3>
                                    <p class="text-[11px] text-slate-600 leading-relaxed">Kolaborasi dengan brand kopi urban membantu memperluas dampak program.</p>
                                </article>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- KONTAK -->
                <section id="kontak" class="bg-creamSoft py-10 lg:py-12">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 text-xs sm:text-[13px]">
                        <div class="space-y-4">
                            <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Lokasi & Kontak</h2>
                            <p class="text-slate-600 leading-relaxed">
                                Kami terbuka untuk kolaborasi, pertanyaan program, maupun ide inisiatif baru. Silakan tinggalkan pesan atau datang langsung ke ruang
                                kopi kami.
                            </p>
                            <div class="rounded-xl bg-cream border border-creamSoft overflow-hidden">
                                <div class="h-52 bg-[url('https://images.pexels.com/photos/373883/pexels-photo-373883.jpeg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center"></div>
                                <div class="p-4 space-y-2">
                                    <div class="font-semibold text-slate-900">Ruang Kop Merah Putih</div>
                                    <p class="text-slate-600 leading-relaxed">Jl. Contoh Kopi No. 17, Bandung, Jawa Barat 40111</p>
                                    <div class="grid grid-cols-2 gap-3 pt-1 text-[11px]">
                                        <div>
                                            <div class="text-slate-500">Email</div>
                                            <div class="font-medium text-slate-900">halo@kopmerahputih.id</div>
                                        </div>
                                        <div>
                                            <div class="text-slate-500">WhatsApp</div>
                                            <div class="font-medium text-slate-900">+62 811-0000-123</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white border border-creamSoft p-4 sm:p-5 space-y-4">
                            <h3 class="text-sm font-semibold text-slate-900">Kirim Pesan</h3>
                            <form class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-medium text-slate-700">Nama lengkap</label>
                                        <input
                                            type="text"
                                            class="w-full rounded-lg border border-creamSoft bg-creamSoft px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/40"
                                            placeholder="Nama Anda"
                                        />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[11px] font-medium text-slate-700">Email</label>
                                        <input
                                            type="email"
                                            class="w-full rounded-lg border border-creamSoft bg-creamSoft px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/40"
                                            placeholder="email@contoh.id"
                                        />
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-medium text-slate-700">Subjek</label>
                                    <input
                                        type="text"
                                        class="w-full rounded-lg border border-creamSoft bg-creamSoft px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/40"
                                        placeholder="Mis. Kolaborasi program, pemesanan, dsb."
                                    />
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-medium text-slate-700">Pesan</label>
                                    <textarea
                                        rows="4"
                                        class="w-full rounded-lg border border-creamSoft bg-creamSoft px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/40"
                                        placeholder="Ceritakan kebutuhan atau ide Anda"
                                    ></textarea>
                                </div>
                                <div class="flex items-center justify-between pt-1">
                                    <p class="text-[11px] text-slate-500 max-w-[220px]">Form ini hanya tampilan. Integrasikan dengan backend sesuai kebutuhan.</p>
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-full bg-primary px-4 py-2 text-xs font-semibold text-white hover:bg-primaryDark shadow-sm"
                                    >
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </main>

            <!-- FOOTER -->
            <footer class="border-t border-creamSoft bg-creamSoft">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between text-[11px] text-slate-500">
                    <div>
                        <span class="font-semibold text-slate-700">Kop Merah Putih</span>
                        <span class="mx-1">•</span>
                        <span>Gerakan ngopi sambil berbagi kebaikan.</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span>&copy; {{ date('Y') }} Kop Merah Putih.</span>
                        <span class="hidden sm:inline">All rights reserved.</span>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
