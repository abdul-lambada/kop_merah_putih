<!DOCTYPE html>
<html lang="id" x-data="app()" :class="fontSize">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi Merah Putih Desa</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Georgia:wght@400;700&family=Times+New+Roman&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        html.text-small body { font-size: 0.95rem; }
        html.text-normal body { font-size: 1rem; }
        html.text-large body { font-size: 1.08rem; }
    </style>
</head>
<body class="bg-cream text-gray-900 font-classic antialiased">
    <!-- Top bar -->
    <header class="border-b-4 border-accent bg-warm/30">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-accent text-xl font-bold border-2 border-accent">KM</div>
                <div>
                    <p class="text-primary font-bold text-lg leading-tight">Koperasi Merah Putih Desa</p>
                    <p class="text-primary/70 text-sm">Sejahtera bersama, selaras kebijakan pemerintah</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-sm">
                <button class="px-3 py-1 rounded border border-primary text-primary hover:bg-primary hover:text-cream transition" @click="fontSize = 'text-small'">A-</button>
                <button class="px-3 py-1 rounded border border-primary text-primary hover:bg-primary hover:text-cream transition" @click="fontSize = 'text-normal'">A</button>
                <button class="px-3 py-1 rounded border border-primary text-primary hover:bg-primary hover:text-cream transition" @click="fontSize = 'text-large'">A+</button>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative overflow-hidden border-b-4 border-accent bg-gradient-to-br from-warm/50 to-primary/90">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-6xl mx-auto px-4 py-16 grid lg:grid-cols-2 gap-10 items-center text-cream">
            <div>
                <p class="inline-flex items-center gap-2 px-4 py-2 border-2 border-accent text-accent rounded-lg bg-primary/60 mb-4">
                    <span class="text-sm">Selaras Program Pemerintah</span>
                </p>
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">Membangun Ekonomi Desa<br><span class="text-accent">Dengan Semangat Gotong Royong</span></h1>
                <p class="text-lg text-cream/90 mb-6">Koperasi Merah Putih Desa hadir memperkuat ketahanan pangan, akses modal UMKM, dan perlindungan sosial sesuai arahan pemerintah.</p>
                <div class="flex flex-wrap gap-3">
                    <button @click="showRegistration = true" class="px-6 py-3 bg-accent text-primary font-bold rounded-lg border-2 border-primary hover:bg-primary hover:text-accent transition">Daftar Anggota</button>
                    <a href="#layanan" class="px-6 py-3 bg-transparent text-accent font-bold rounded-lg border-2 border-accent hover:bg-accent hover:text-primary transition">Lihat Program</a>
                </div>
            </div>
            <div class="bg-cream/90 border-4 border-primary rounded-2xl p-6 shadow-xl text-primary space-y-3">
                <h3 class="text-2xl font-bold mb-1">Prioritas & Kepatuhan</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start gap-2"><span class="text-accent mt-1">●</span> Selaras dengan Permendesa & program ketahanan pangan.</li>
                    <li class="flex items-start gap-2"><span class="text-accent mt-1">●</span> Transparansi dana, laporan periodik ke anggota & pemerintah desa.</li>
                    <li class="flex items-start gap-2"><span class="text-accent mt-1">●</span> Keamanan data anggota, verifikasi NIK & domisili.</li>
                    <li class="flex items-start gap-2"><span class="text-accent mt-1">●</span> Fokus inklusi: petani, nelayan, peternak, dan UMKM lokal.</li>
                </ul>
                <button @click="showRegistration = true" class="mt-3 w-full px-4 py-3 bg-primary text-accent font-semibold rounded-lg border-2 border-accent hover:bg-primaryDark transition">Mulai Pendaftaran</button>
            </div>
        </div>
    </section>

    <!-- Quick stats -->
    <section class="bg-cream border-b-4 border-primary">
        <div class="max-w-6xl mx-auto px-4 py-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 border-2 border-primary rounded-xl bg-white shadow-sm">
                <p class="text-sm text-primary/70">Anggota Aktif</p>
                <p class="text-2xl font-bold text-primary">2.130+</p>
            </div>
            <div class="p-4 border-2 border-primary rounded-xl bg-white shadow-sm">
                <p class="text-sm text-primary/70">Dana Bergulir</p>
                <p class="text-2xl font-bold text-primary">Rp 4,2 M</p>
            </div>
            <div class="p-4 border-2 border-primary rounded-xl bg-white shadow-sm">
                <p class="text-sm text-primary/70">Program Pemerintah</p>
                <p class="text-2xl font-bold text-primary">8 sinergi</p>
            </div>
            <div class="p-4 border-2 border-primary rounded-xl bg-white shadow-sm">
                <p class="text-sm text-primary/70">Kepatuhan Audit</p>
                <p class="text-2xl font-bold text-primary">100% patuh</p>
            </div>
        </div>
    </section>

    <!-- Program utama -->
    <section id="layanan" class="bg-white border-b-4 border-primary">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <div class="text-center mb-10">
                <p class="text-accent font-semibold">Program Utama</p>
                <h2 class="text-3xl font-bold text-primary mt-2">Fokus Layanan Koperasi</h2>
                <p class="text-primary/70 mt-3 max-w-3xl mx-auto">Dirancang sesuai arahan pemerintah: ketahanan pangan, peningkatan UMKM, dan perlindungan sosial.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-5 border-2 border-primary rounded-xl bg-cream/60 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-accent text-xl">P</div>
                        <h3 class="text-xl font-bold text-primary">Ketahanan Pangan</h3>
                    </div>
                    <p class="text-primary/80 mb-3 text-sm">Pupuk subsidi, benih unggul, pendampingan tanam, dan gudang bersama.</p>
                    <span class="text-primary font-semibold text-sm">Sesuai program: Kementan & Ketahanan Pangan</span>
                </div>
                <div class="p-5 border-2 border-primary rounded-xl bg-cream/60 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-accent text-xl">U</div>
                        <h3 class="text-xl font-bold text-primary">UMKM & Digital</h3>
                    </div>
                    <p class="text-primary/80 mb-3 text-sm">Modal bergulir bunga ringan, pendampingan ijin usaha, dan pemasaran digital.</p>
                    <span class="text-primary font-semibold text-sm">Sesuai program: UMKM Naik Kelas, Desa Digital</span>
                </div>
                <div class="p-5 border-2 border-primary rounded-xl bg-cream/60 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-accent text-xl">S</div>
                        <h3 class="text-xl font-bold text-primary">Sosial & Perlindungan</h3>
                    </div>
                    <p class="text-primary/80 mb-3 text-sm">Dana darurat, koperasi siaga bencana, dan kesehatan dasar bagi anggota.</p>
                    <span class="text-primary font-semibold text-sm">Sesuai program: Jaring Pengaman Sosial</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs layanan detail -->
    <section class="bg-cream border-b-4 border-primary">
        <div class="max-w-6xl mx-auto px-4 py-14">
            <div class="flex flex-wrap gap-3 justify-center mb-8">
                <button :class="tab==='pertanian' ? activeTab : idleTab" @click="tab='pertanian'">Pertanian</button>
                <button :class="tab==='peternakan' ? activeTab : idleTab" @click="tab='peternakan'">Peternakan</button>
                <button :class="tab==='perikanan' ? activeTab : idleTab" @click="tab='perikanan'">Perikanan</button>
                <button :class="tab==='umkm' ? activeTab : idleTab" @click="tab='umkm'">UMKM</button>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="item in services[tab]" :key="item.title">
                    <div class="bg-white border-2 border-primary rounded-xl p-5 shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-lg bg-primary text-accent flex items-center justify-center text-xl" x-text="item.icon"></div>
                            <h3 class="text-lg font-bold text-primary" x-text="item.title"></h3>
                        </div>
                        <p class="text-primary/80 text-sm mb-3" x-text="item.desc"></p>
                        <div class="text-sm font-semibold text-primary" x-text="item.note"></div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-primary text-cream border-b-4 border-accent">
        <div class="max-w-6xl mx-auto px-4 py-12 flex flex-col md:flex-row items-center gap-6">
            <div class="flex-1">
                <h3 class="text-2xl font-bold mb-2">Siap bergabung dan tumbuh bersama?</h3>
                <p class="text-cream/80 text-sm">Lengkapi data Anda, kami verifikasi dalam 1-3 hari kerja sesuai SOP pemerintah desa.</p>
            </div>
            <div class="flex gap-3">
                <button @click="showRegistration = true" class="px-5 py-3 bg-accent text-primary font-bold rounded-lg border-2 border-cream hover:bg-cream hover:text-primary transition">Daftar Anggota</button>
                <a href="tel:02112345678" class="px-5 py-3 bg-transparent text-cream font-bold rounded-lg border-2 border-cream hover:bg-cream hover:text-primary transition">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-cream border-t-4 border-accent">
        <div class="max-w-6xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8 text-cream">
            <div>
                <h4 class="text-xl font-bold text-accent mb-2">Koperasi Merah Putih</h4>
                <p class="text-cream/80 text-sm">Sejak 1985 mendampingi desa membangun ekonomi yang mandiri dan berkeadilan.</p>
            </div>
            <div>
                <h4 class="font-bold text-accent mb-2">Tautan</h4>
                <ul class="space-y-1 text-cream/80 text-sm">
                    <li><a href="#layanan" class="hover:text-accent">Program</a></li>
                    <li><a href="#layanan" class="hover:text-accent">Layanan</a></li>
                    <li><a href="#kontak" class="hover:text-accent">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-accent mb-2">Kontak</h4>
                <p class="text-cream/80 text-sm">Jl. Desa Sejahtera No. 1, Makmur</p>
                <p class="text-cream/80 text-sm">Telp: (021) 1234-5678</p>
                <p class="text-cream/80 text-sm">WA: 0812-3456-7890</p>
            </div>
        </div>
    </footer>

    <!-- Modal pendaftaran -->
    <div x-show="showRegistration" x-cloak x-transition class="fixed inset-0 bg-black/60 flex items-center justify-center px-4 z-50">
        <div class="bg-white rounded-xl border-4 border-primary w-full max-w-lg shadow-2xl p-6 relative">
            <button class="absolute right-4 top-4 text-primary hover:text-primaryDark" @click="showRegistration=false">✕</button>
            <h3 class="text-2xl font-bold text-primary mb-4">Form Pendaftaran Anggota</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-primary mb-1">Nama Lengkap *</label>
                    <input type="text" class="w-full border-2 border-primary rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent">
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-primary mb-1">NIK *</label>
                        <input type="text" class="w-full border-2 border-primary rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-primary mb-1">No. HP/WA *</label>
                        <input type="tel" class="w-full border-2 border-primary rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-primary mb-1">Alamat *</label>
                    <textarea rows="3" class="w-full border-2 border-primary rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent"></textarea>
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-primary mb-1">Bidang *</label>
                        <select class="w-full border-2 border-primary rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent">
                            <option>Pertanian</option>
                            <option>Peternakan</option>
                            <option>Perikanan</option>
                            <option>UMKM</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-primary mb-1">Pengalaman</label>
                        <select class="w-full border-2 border-primary rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent">
                            <option>Baru</option>
                            <option>2-5 tahun</option>
                            <option>5+ tahun</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm text-primary">
                    <input type="checkbox" class="w-4 h-4 border-primary" required>
                    <span>Saya menyetujui kebijakan koperasi dan arahan pemerintah.</span>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showRegistration=false" class="flex-1 px-4 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary/10">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-accent text-primary font-semibold rounded-lg border-2 border-primary hover:bg-primary hover:text-accent">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function app() {
            return {
                fontSize: 'text-normal',
                showRegistration: false,
                tab: 'pertanian',
                activeTab: 'px-4 py-2 rounded-lg bg-primary text-accent border-2 border-accent font-semibold transition',
                idleTab: 'px-4 py-2 rounded-lg bg-white text-primary border-2 border-primary hover:bg-cream transition',
                services: {
                    pertanian: [
                        { icon: '🌾', title: 'Benih & Pupuk', desc: 'Distribusi bersubsidi, terdata by NIK & lahan.', note: 'Validasi kartu tani' },
                        { icon: '🤝', title: 'Konsolidasi Lahan', desc: 'Pendampingan kelompok tani & jadwal tanam serempak.', note: 'SOP Gapoktan' },
                        { icon: '🏪', title: 'Gudang Bersama', desc: 'Pengeringan, penyimpanan, dan akses pasar stabil.', note: 'Kemitraan Bulog/PKT' },
                    ],
                    peternakan: [
                        { icon: '🐓', title: 'Pakan & Vaksin', desc: 'Harga koperasi, jadwal vaksin terpantau.', note: 'Sesuai dinas peternakan' },
                        { icon: '🩺', title: 'Kesehatan Ternak', desc: 'Kunjungan dokter hewan & klinik keliling.', note: 'Laporan kasus wajib' },
                        { icon: '🏠', title: 'Kandang Modern', desc: 'Ventilasi baik, biosecurity, efisiensi pakan.', note: 'Checklist PPL' },
                    ],
                    perikanan: [
                        { icon: '🐟', title: 'Benih & Pakan', desc: 'Benih uji mutu, pakan efisien FCR rendah.', note: 'Sertifikasi hatchery' },
                        { icon: '💧', title: 'Air & Kualitas', desc: 'Uji kualitas air rutin, aerasi & filtrasi.', note: 'Standar budidaya' },
                        { icon: '📈', title: 'Akses Pasar', desc: 'Kurasi pembeli, kontrak serap, harga adil.', note: 'MoU kemitraan' },
                    ],
                    umkm: [
                        { icon: '💰', title: 'Modal Bergulir', desc: 'Bunga ringan, verifikasi NIK & usaha.', note: 'Akad transparan' },
                        { icon: '🧾', title: 'Legal & Sertif', desc: 'NIB, PIRT/Halal, dan izin edar dibantu.', note: 'Kolaborasi Dinkop' },
                        { icon: '📢', title: 'Pemasaran Digital', desc: 'Foto produk, katalog WA, dan marketplace.', note: 'Pelatihan bulanan' },
                    ],
                }
            }
        }
    </script>
</body>
</html>
