<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignRolesToUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users and assign roles based on existing role_user relationships
        $users = User::all();
        
        foreach ($users as $user) {
            // Check if user has existing role assignment from old system
            $existingRole = DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $user->id)
                ->first();
            
            if ($existingRole) {
                // Map old slug to new role name
                $roleMapping = [
                    'super-admin' => 'Super Admin',
                    'ketua-koperasi' => 'Ketua Koperasi',
                    'manager-keuangan' => 'Manager Keuangan',
                    'manager-unit' => 'Manager Unit',
                    'staff-administrasi' => 'Staff Administrasi',
                    'bendahara-unit' => 'Bendahara Unit',
                    'anggota' => 'Anggota',
                ];
                
                $newRoleName = $roleMapping[$existingRole->slug] ?? 'Anggota';
                $role = Role::where('name', $newRoleName)->first();
                
                if ($role) {
                    $user->assignRole($role);
                    
                    // Assign permissions based on role
                    $this->assignPermissionsToUser($user, $role);
                }
            } else {
                // Default role for users without existing role
                $defaultRole = Role::where('name', 'Anggota')->first();
                if ($defaultRole) {
                    $user->assignRole($defaultRole);
                    $this->assignPermissionsToUser($user, $defaultRole);
                }
            }
        }
        
        // Create a default super admin if none exists
        if (!User::role('Super Admin')->exists()) {
            $superAdmin = User::firstOrCreate([
                'email' => 'admin@kopmerahputih.com'
            ], [
                'name' => 'Super Admin',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]);
            
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            $superAdmin->assignRole($superAdminRole);
            $this->assignPermissionsToUser($superAdmin, $superAdminRole);
        }
    }
    
    private function assignPermissionsToUser($user, $role)
    {
        $permissionMapping = [
            'Super Admin' => ['*'], // All permissions
            'Ketua Koperasi' => [
                'dashboard.view', 'members.view', 'members.create', 'members.edit',
                'savings.view', 'savings.approve', 'loans.view', 'loans.approve',
                'units.view', 'transactions.view', 'reports.view', 'reports.generate',
                'reports.export', 'settings.view'
            ],
            'Manager Keuangan' => [
                'dashboard.view', 'members.view', 'savings.view', 'savings.create',
                'savings.approve', 'loans.view', 'loans.create', 'loans.approve',
                'transactions.view', 'transactions.create', 'reports.view', 'reports.generate',
                'reports.financial'
            ],
            'Manager Unit' => [
                'dashboard.view', 'units.view', 'units.edit', 'transactions.view',
                'transactions.create', 'reports.view', 'reports.units'
            ],
            'Staff Administrasi' => [
                'dashboard.view', 'members.view', 'members.create', 'members.edit',
                'savings.view', 'savings.create', 'loans.view', 'loans.create',
                'transactions.view', 'transactions.create'
            ],
            'Bendahara Unit' => [
                'dashboard.view', 'units.view', 'transactions.view', 'transactions.create',
                'reports.view'
            ],
            'Anggota' => [
                'dashboard.view', 'profile.view', 'profile.edit', 'profile.avatar',
                'savings.view', 'savings.create', 'loans.view', 'loans.create',
                'transactions.view', 'transactions.personal', 'reports.view', 'reports.personal',
                'members.view', 'units.view'
            ],
        ];
        
        $permissions = $permissionMapping[$role->name] ?? [];
        
        foreach ($permissions as $permission) {
            if ($permission === '*') {
                // Give all permissions to Super Admin
                $allPermissions = Permission::all();
                foreach ($allPermissions as $perm) {
                    $user->givePermissionTo($perm);
                }
            } else {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $user->givePermissionTo($perm);
                }
            }
        }
    }
}
