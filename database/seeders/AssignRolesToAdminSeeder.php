<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignRolesToAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Get the admin user
        $admin = User::where('email', 'admin@kopmerahputih.com')->first();
        
        if ($admin) {
            // Get Super Admin role
            $superAdminRole = Role::where('name', 'Super Admin')->first();
            
            if ($superAdminRole) {
                // Assign Super Admin role to admin user
                $admin->assignRole($superAdminRole);
                
                // Get all permissions
                $permissions = Permission::all();
                
                // Assign all permissions to Super Admin role
                $superAdminRole->syncPermissions($permissions);
                
                echo "Super Admin role and all permissions assigned to admin user\n";
            } else {
                echo "Super Admin role not found\n";
            }
        } else {
            echo "Admin user not found\n";
        }
    }
}
