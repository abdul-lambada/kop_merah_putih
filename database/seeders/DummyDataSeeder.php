<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use App\Models\SavingsLoan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        Transaction::query()->delete();
        SavingsLoan::query()->delete();
        BusinessUnit::query()->delete();
        Member::query()->delete();
        
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@kopmerahputih.com'],
            [
                'name' => 'Admin Koperasi',
                'password' => Hash::make('password'),
            ]
        );
        
        // Assign super-admin role to admin user using Spatie Permission
        $adminRole = \Spatie\Permission\Models\Role::where('name', 'Super Admin')->first();
        if ($adminRole && !$admin->hasRole('Super Admin')) {
            $admin->assignRole($adminRole);
        }

        // Indonesian names for members
        $indonesianNames = [
            'Budi Santoso', 'Siti Nurhaliza', 'Ahmad Fauzi', 'Dewi Lestari',
            'Eko Prasetyo', 'Ratna Sari', 'Hendro Wijaya', 'Maya Putri',
            'Joko Susilo', 'Intan Permata', 'Rizki Firmansyah', 'Nadia Amelia',
            'Bayu Setiawan', 'Citra Dewi', 'Fajar Nugroho', 'Sarah Maharani',
            'Dimas Arya', 'Fitri Handayani', 'Ghani Pratama', 'Olivia Zahra'
        ];

        // Indonesian cities and addresses
        $addresses = [
            'Jl. Sudirman No. 123, Jakarta Pusat',
            'Jl. Malioboro No. 45, Yogyakarta',
            'Jl. Gajah Mada No. 67, Semarang',
            'Jl. Pemuda No. 89, Surabaya',
            'Jl. Diponegoro No. 234, Bandung',
            'Jl. Imam Bonjol No. 56, Medan',
            'Jl. Ahmad Yani No. 78, Palembang',
            'Jl. Thamrin No. 90, Jakarta Selatan',
            'Jl. Gatot Subroto No. 12, Makassar',
            'Jl. Sudirman No. 345, Denpasar'
        ];

        // Business sectors (matching enum values)
        $businessSectors = ['pertanian', 'peternakan', 'perikanan', 'umkm'];

        // Create Members
        $members = [];
        foreach ($indonesianNames as $index => $name) {
            $member = Member::create([
                'member_number' => 'M' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'nik' => '320101' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'full_name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'phone' => '08' . rand(11, 99) . rand(10000000, 99999999),
                'address' => $addresses[array_rand($addresses)],
                'business_sector' => $businessSectors[array_rand($businessSectors)],
                'experience' => ['baru', '2-5_tahun', '5+_tahun'][array_rand(['baru', '2-5_tahun', '5+_tahun'])],
                'join_date' => Carbon::now()->subDays(rand(30, 1095)),
                'status' => rand(0, 1) ? 'active' : 'inactive',
                'savings_balance' => rand(1000000, 50000000),
                'loan_limit' => rand(5000000, 100000000),
                'verification_data' => json_encode([
                    'ktp_verified' => true,
                    'address_verified' => true,
                    'business_verified' => rand(0, 1) ? true : false
                ]),
                'verified_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
            $members[] = $member;
        }

        // Create Business Units
        $businessUnits = [
            [
                'name' => 'Toko Sembako Utama',
                'type' => 'sembako',
                'description' => 'Unit usaha sembako untuk seluruh anggota',
                'address' => 'Jl. Merah Putih No. 1, Jakarta',
                'phone' => '021-5551234',
                'manager_name' => 'Bambang Sutrisno',
                'status' => 'active',
                'initial_capital' => 500000000,
                'current_balance' => 750000000,
                'operating_hours' => json_encode([
                    'senin' => '08:00-16:00',
                    'selasa' => '08:00-16:00',
                    'rabu' => '08:00-16:00',
                    'kamis' => '08:00-16:00',
                    'jumat' => '08:00-16:00',
                    'sabtu' => '08:00-12:00',
                    'minggu' => 'tutup'
                ]),
                'notes' => 'Unit usaha sembako utama koperasi'
            ],
            [
                'name' => 'Apotek Koperasi "Sehat"',
                'type' => 'apotek',
                'description' => 'Penjualan obat-obatan dan alat kesehatan',
                'address' => 'Jl. Merah Putih No. 2, Jakarta',
                'phone' => '021-5551235',
                'manager_name' => 'Sri Rahayu',
                'status' => 'active',
                'initial_capital' => 100000000,
                'current_balance' => 125000000,
                'operating_hours' => json_encode([
                    'senin' => '07:00-21:00',
                    'selasa' => '07:00-21:00',
                    'rabu' => '07:00-21:00',
                    'kamis' => '07:00-21:00',
                    'jumat' => '07:00-21:00',
                    'sabtu' => '07:00-21:00',
                    'minggu' => '07:00-21:00'
                ]),
                'notes' => 'Apotek untuk anggota dan umum'
            ],
            [
                'name' => 'Klinik Kesehatan "Makmur"',
                'type' => 'klinik',
                'description' => 'Pelayanan kesehatan dasar untuk anggota',
                'address' => 'Jl. Merah Putih No. 3, Jakarta',
                'phone' => '021-5551236',
                'manager_name' => 'Hadi Wijaya',
                'status' => 'active',
                'initial_capital' => 75000000,
                'current_balance' => 95000000,
                'operating_hours' => json_encode([
                    'senin' => '08:00-20:00',
                    'selasa' => '08:00-20:00',
                    'rabu' => '08:00-20:00',
                    'kamis' => '08:00-20:00',
                    'jumat' => '08:00-20:00',
                    'sabtu' => '08:00-15:00',
                    'minggu' => 'tutup'
                ]),
                'notes' => 'Klinik kesehatan untuk anggota koperasi'
            ],
            [
                'name' => 'Logistik "Koptra"',
                'type' => 'logistik',
                'description' => 'Jasa pengiriman dan distribusi barang',
                'address' => 'Jl. Merah Putih No. 4, Jakarta',
                'phone' => '021-5551237',
                'manager_name' => 'Rudi Hartono',
                'status' => 'active',
                'initial_capital' => 50000000,
                'current_balance' => 68000000,
                'operating_hours' => json_encode([
                    'senin' => '06:00-22:00',
                    'selasa' => '06:00-22:00',
                    'rabu' => '06:00-22:00',
                    'kamis' => '06:00-22:00',
                    'jumat' => '06:00-22:00',
                    'sabtu' => '06:00-22:00',
                    'minggu' => '06:00-20:00'
                ]),
                'notes' => 'Layanan logistik untuk anggota koperasi'
            ]
        ];

        $createdBusinessUnits = [];
        foreach ($businessUnits as $unit) {
            $createdBusinessUnits[] = BusinessUnit::create($unit);
        }

        // Create Savings and Loans
        $savingsLoans = [];
        foreach ($members as $member) {
            // Create savings
            if (rand(0, 1)) {
                $savings = SavingsLoan::create([
                    'member_id' => $member->id,
                    'type' => 'savings',
                    'transaction_number' => 'SV' . uniqid() . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'amount' => rand(1000000, 10000000),
                    'interest_rate' => 3.5,
                    'tenure_months' => 12,
                    'monthly_installment' => 0,
                    'due_date' => Carbon::now()->addMonths(12),
                    'status' => 'completed',
                    'purpose' => 'Simpanan Wajib',
                    'notes' => 'Simpanan rutin bulanan',
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
                $savingsLoans[] = $savings;
            }

            // Create loans
            if (rand(0, 1)) {
                $loanAmount = rand(5000000, 50000000);
                $interestRate = rand(8, 15);
                $tenure = rand(6, 36);
                $monthlyInstallment = $loanAmount * (1 + $interestRate/100) / $tenure;

                $loan = SavingsLoan::create([
                    'member_id' => $member->id,
                    'type' => 'loan',
                    'transaction_number' => 'LN' . uniqid() . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'amount' => $loanAmount,
                    'interest_rate' => $interestRate,
                    'tenure_months' => $tenure,
                    'monthly_installment' => $monthlyInstallment,
                    'due_date' => Carbon::now()->addMonths($tenure),
                    'status' => rand(0, 1) ? 'active' : 'completed',
                    'purpose' => ['Modal Usaha', 'Renovasi Rumah', 'Biaya Pendidikan', 'Kebutuhan Darurat'][array_rand(['Modal Usaha', 'Renovasi Rumah', 'Biaya Pendidikan', 'Kebutuhan Darurat'])],
                    'notes' => 'Pinjaman dengan jaminan BPKB',
                    'approved_by' => $admin->id,
                    'approved_at' => Carbon::now()->subDays(rand(1, 60)),
                ]);
                $savingsLoans[] = $loan;
            }
        }

        // Create Transactions
        $transactionCategories = [
            'income' => ['savings_deposit', 'loan_disbursement', 'unit_revenue'],
            'expense' => ['unit_expense', 'operational_cost', 'other']
        ];

        $paymentMethods = ['Tunai', 'Transfer Bank', 'E-Wallet', 'Debit Card'];

        foreach ($createdBusinessUnits as $unit) {
            // Create transactions for each business unit
            for ($i = 0; $i < rand(20, 50); $i++) {
                $type = rand(0, 1) ? 'income' : 'expense';
                $categories = $transactionCategories[$type];
                
                Transaction::create([
                    'transaction_number' => 'TRX' . uniqid() . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'type' => $type,
                    'category' => $categories[array_rand($categories)],
                    'amount' => rand(50000, 5000000),
                    'description' => 'Transaksi ' . ($type === 'income' ? 'pemasukan' : 'pengeluaran') . ' ' . $unit->name,
                    'transaction_date' => Carbon::now()->subDays(rand(0, 365)),
                    'member_id' => rand(0, 1) ? $members[array_rand($members)]->id : null,
                    'business_unit_id' => $unit->id,
                    'savings_loan_id' => rand(0, 1) ? $savingsLoans[array_rand($savingsLoans)]->id : null,
                    'recorded_by' => $admin->id,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'reference_number' => 'REF' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'notes' => 'Transaksi otomatis generated',
                ]);
            }
        }

        // Create member transactions
        foreach ($members as $member) {
            for ($i = 0; $i < rand(5, 15); $i++) {
                Transaction::create([
                    'transaction_number' => 'MBR' . uniqid() . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'type' => rand(0, 1) ? 'income' : 'expense',
                    'category' => ['savings_deposit', 'loan_payment', 'other'][array_rand(['savings_deposit', 'loan_payment', 'other'])],
                    'amount' => rand(100000, 5000000),
                    'description' => 'Transaksi anggota ' . $member->full_name,
                    'transaction_date' => Carbon::now()->subDays(rand(0, 365)),
                    'member_id' => $member->id,
                    'business_unit_id' => $createdBusinessUnits[0]->id,
                    'savings_loan_id' => rand(0, 1) ? $savingsLoans[array_rand($savingsLoans)]->id : null,
                    'recorded_by' => $admin->id,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'reference_number' => 'MBR' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'notes' => 'Transaksi anggota',
                ]);
            }
        }

        $this->command->info('Dummy data berhasil dibuat!');
        $this->command->info('Admin user: admin@kopmerahputih.com / password');
        $this->command->info('Total Members: ' . count($members));
        $this->command->info('Total Business Units: ' . count($createdBusinessUnits));
        $this->command->info('Total Savings & Loans: ' . count($savingsLoans));
    }
}
