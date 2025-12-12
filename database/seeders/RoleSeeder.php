<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Pemilik/Founder koperasi dengan akses penuh',
                'level' => 100,
                'permissions' => [
                    'dashboard.view' => true,
                    'members.view' => true,
                    'members.create' => true,
                    'members.edit' => true,
                    'members.delete' => true,
                    'savings.view' => true,
                    'savings.create' => true,
                    'savings.approve' => true,
                    'loans.view' => true,
                    'loans.create' => true,
                    'loans.approve' => true,
                    'loans.approve-large' => true,
                    'units.view' => true,
                    'units.create' => true,
                    'units.edit' => true,
                    'units.delete' => true,
                    'transactions.view' => true,
                    'transactions.create' => true,
                    'transactions.edit' => true,
                    'reports.view' => true,
                    'reports.generate' => true,
                    'reports.export' => true,
                    'users.view' => true,
                    'users.create' => true,
                    'users.edit' => true,
                    'users.delete' => true,
                    'roles.view' => true,
                    'roles.create' => true,
                    'roles.edit' => true,
                    'roles.delete' => true,
                    'settings.view' => true,
                    'settings.edit' => true,
                ],
            ],
            [
                'name' => 'Ketua Koperasi',
                'slug' => 'ketua-koperasi',
                'description' => 'Pengurus utama dengan overview lengkap',
                'level' => 80,
                'permissions' => [
                    'dashboard.view' => true,
                    'members.view' => true,
                    'members.create' => true,
                    'members.edit' => true,
                    'savings.view' => true,
                    'savings.approve' => true,
                    'loans.view' => true,
                    'loans.approve' => true,
                    'loans.approve-large' => true,
                    'units.view' => true,
                    'transactions.view' => true,
                    'reports.view' => true,
                    'reports.generate' => true,
                    'reports.export' => true,
                    'settings.view' => true,
                ],
            ],
            [
                'name' => 'Manager Keuangan',
                'slug' => 'manager-keuangan',
                'description' => 'Handle simpan pinjam dan laporan keuangan',
                'level' => 60,
                'permissions' => [
                    'dashboard.view' => true,
                    'members.view' => true,
                    'savings.view' => true,
                    'savings.create' => true,
                    'savings.approve' => true,
                    'loans.view' => true,
                    'loans.create' => true,
                    'loans.approve' => true,
                    'transactions.view' => true,
                    'transactions.create' => true,
                    'reports.view' => true,
                    'reports.generate' => true,
                    'reports.financial' => true,
                ],
            ],
            [
                'name' => 'Manager Unit Usaha',
                'slug' => 'manager-unit',
                'description' => 'Kelola operasional unit usaha',
                'level' => 50,
                'permissions' => [
                    'dashboard.view' => true,
                    'units.view' => true,
                    'units.edit' => true,
                    'transactions.view' => true,
                    'transactions.create' => true,
                    'reports.view' => true,
                    'reports.units' => true,
                ],
            ],
            [
                'name' => 'Staff Administrasi',
                'slug' => 'staff-administrasi',
                'description' => 'Pendaftaran anggota dan data entry',
                'level' => 30,
                'permissions' => [
                    'dashboard.view' => true,
                    'members.view' => true,
                    'members.create' => true,
                    'members.edit' => true,
                    'savings.view' => true,
                    'savings.create' => true,
                    'loans.view' => true,
                    'loans.create' => true,
                    'transactions.view' => true,
                    'transactions.create' => true,
                ],
            ],
            [
                'name' => 'Bendahara Unit',
                'slug' => 'bendahara-unit',
                'description' => 'Operasional kas unit harian',
                'level' => 20,
                'permissions' => [
                    'dashboard.view' => true,
                    'units.view' => true,
                    'transactions.view' => true,
                    'transactions.create' => true,
                    'reports.view' => true,
                ],
            ],
            [
                'name' => 'Anggota',
                'slug' => 'anggota',
                'description' => 'Anggota koperasi dengan akses terbatas',
                'level' => 10,
                'permissions' => [
                    'dashboard.view' => true,
                    'profile.view' => true,
                    'profile.edit' => true,
                    'savings.view' => true,
                    'savings.create' => true,
                    'loans.view' => true,
                    'loans.create' => true,
                    'transactions.personal' => true,
                    'reports.personal' => true,
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
