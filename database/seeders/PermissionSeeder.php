<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // Members
            'members.view',
            'members.create',
            'members.edit',
            'members.delete',
            'members.verify',
            
            // Savings
            'savings.view',
            'savings.create',
            'savings.edit',
            'savings.approve',
            'savings.withdraw',
            
            // Loans
            'loans.view',
            'loans.create',
            'loans.edit',
            'loans.approve',
            'loans.reject',
            'loans.payment',
            
            // Business Units
            'units.view',
            'units.create',
            'units.edit',
            'units.delete',
            'units.transaction',
            'units.report',
            
            // Transactions
            'transactions.view',
            'transactions.create',
            'transactions.edit',
            'transactions.delete',
            'transactions.export',
            
            // Reports
            'reports.view',
            'reports.generate',
            'reports.export',
            'reports.financial',
            'reports.members',
            'reports.units',
            'reports.personal',
            
            // Settings
            'settings.view',
            'settings.edit',
            'settings.system',
            
            // Profile
            'profile.view',
            'profile.edit',
            'profile.avatar',
            
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'roles.assign',

            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            'permissions.assign',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
