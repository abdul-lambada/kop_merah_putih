<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\BusinessUnit;
use App\Models\Savings;
use App\Http\Requests\StoreMemberRequest;
use App\Models\Loan;
use App\Models\VillageSettings;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Get village settings
        $villageSettings = VillageSettings::getSettings();
        
        // Get real statistics from database
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::where('status', 'active')->count(),
            'total_savings' => Savings::sum('amount'),
            'total_loans' => Loan::sum('amount'),
            'business_units' => BusinessUnit::count(),
            'active_business_units' => BusinessUnit::where('status', 'active')->count(),
        ];

        // Format numbers for display
        $formattedStats = [
            'members' => number_format($stats['active_members']) . '+',
            'savings' => 'Rp ' . number_format($stats['total_savings'], 0, ',', '.'),
            'business_units' => $stats['active_business_units'] . ' unit',
            'compliance' => '100% patuh',
        ];

        // Get business units for services
        $businessUnits = BusinessUnit::with(['transactions' => function($query) {
            $query->latest()->take(5);
        }])->where('status', 'active')->get();

        // Services data based on actual business units
        $services = [
            'pertanian' => $this->getPertanianServices($businessUnits),
            'peternakan' => $this->getPeternakanServices($businessUnits),
            'perikanan' => $this->getPerikananServices($businessUnits),
            'umkm' => $this->getUmkmServices($businessUnits),
        ];

        // Recent activities
        $recentActivities = [
            'new_members' => Member::latest()->take(3)->get(),
            'recent_savings' => Savings::with('member')->latest()->take(3)->get(),
            'recent_loans' => Loan::with('member')->latest()->take(3)->get(),
        ];

        return view('landing', compact('stats', 'services', 'villageSettings', 'businessUnits', 'recentActivities'));
    }

    private function getPertanianServices($businessUnits)
    {
        $pertanianUnits = $businessUnits->where('type', 'pertanian');
        return [
            [
                'icon' => '🌾',
                'title' => 'Benih & Pupuk',
                'desc' => 'Distribusi bersubsidi, terdata by NIK & lahan. Tersedia di ' . $pertanianUnits->count() . ' unit usaha.',
                'note' => 'Validasi kartu tani'
            ],
            [
                'icon' => '🤝',
                'title' => 'Konsolidasi Lahan',
                'desc' => 'Pendampingan kelompok tani & jadwal tanam serempak. ' . $pertanianUnits->sum('total_transactions') . ' transaksi bulan ini.',
                'note' => 'SOP Gapoktan'
            ],
            [
                'icon' => '🏪',
                'title' => 'Gudang Bersama',
                'desc' => 'Pengeringan, penyimpanan, dan akses pasar stabil. Kapasitas tersedia: ' . $pertanianUnits->sum('capacity') . ' ton.',
                'note' => 'Kemitraan Bulog/PKT'
            ],
        ];
    }

    private function getPeternakanServices($businessUnits)
    {
        $peternakanUnits = $businessUnits->where('type', 'peternakan');
        return [
            [
                'icon' => '🐓',
                'title' => 'Pakan & Vaksin',
                'desc' => 'Harga koperasi, jadwal vaksin terpantau. Melayani ' . $peternakanUnits->count() . ' unit peternakan.',
                'note' => 'Sesuai dinas peternakan'
            ],
            [
                'icon' => '🩺',
                'title' => 'Kesehatan Ternak',
                'desc' => 'Kunjungan dokter hewan & klinik keliling. ' . $peternakanUnits->sum('total_transactions') . ' layanan kesehatan bulan ini.',
                'note' => 'Laporan kasus wajib'
            ],
            [
                'icon' => '🏠',
                'title' => 'Kandang Modern',
                'desc' => 'Ventilasi baik, biosecurity, efisiensi pakan. Total kapasitas: ' . $peternakanUnits->sum('capacity') . ' ekor.',
                'note' => 'Checklist PPL'
            ],
        ];
    }

    private function getPerikananServices($businessUnits)
    {
        $perikananUnits = $businessUnits->where('type', 'perikanan');
        return [
            [
                'icon' => '🐟',
                'title' => 'Benih & Pakan',
                'desc' => 'Benih uji mutu, pakan efisien FCR rendah. Tersedia di ' . $perikananUnits->count() . ' unit perikanan.',
                'note' => 'Sertifikasi hatchery'
            ],
            [
                'icon' => '💧',
                'title' => 'Air & Kualitas',
                'desc' => 'Uji kualitas air rutin, aerasi & filtrasi. ' . $perikananUnits->sum('total_transactions') . ' tes kualitas bulan ini.',
                'note' => 'Standar budidaya'
            ],
            [
                'icon' => '📈',
                'title' => 'Akses Pasar',
                'desc' => 'Kurasi pembeli, kontrak serap, harga adil. Produksi bulan ini: ' . $perikananUnits->sum('capacity') . ' kg.',
                'note' => 'MoU kemitraan'
            ],
        ];
    }

    private function getUmkmServices($businessUnits)
    {
        $umkmUnits = $businessUnits->where('type', 'umkm');
        return [
            [
                'icon' => '💰',
                'title' => 'Modal Bergulir',
                'desc' => 'Bunga ringan, verifikasi NIK & usaha. ' . $umkmUnits->count() . ' UMKM terlayani, total pinjaman: Rp ' . number_format($umkmUnits->sum('total_transactions'), 0, ',', '.'),
                'note' => 'Akad transparan'
            ],
            [
                'icon' => '🧾',
                'title' => 'Legal & Sertif',
                'desc' => 'NIB, PIRT/Halal, dan izin edar dibantu. ' . $umkmUnits->where('is_certified', true)->count() . ' UMKM tersertifikasi.',
                'note' => 'Kolaborasi Dinkop'
            ],
            [
                'icon' => '📢',
                'title' => 'Pemasaran Digital',
                'desc' => 'Foto produk, katalog WA, dan marketplace. ' . $umkmUnits->sum('total_transactions') . ' produk dipasarkan bulan ini.',
                'note' => 'Pelatihan bulanan'
            ],
        ];
    }

    public function storeRegistration(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        // Create new member with pending status
        $member = Member::create([
            'full_name' => $validated['name'],
            'nik' => $validated['nik'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'business_sector' => $validated['business_type'],
            'experience' => $validated['experience'],
            'status' => 'pending',
            'join_date' => now(),
            'member_number' => 'KM' . date('Ym') . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT),
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Data Anda akan diverifikasi dalam 1-3 hari kerja.');
    }
}
