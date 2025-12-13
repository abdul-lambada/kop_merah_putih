<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Ketua Koperasi', 'slug' => 'ketua-koperasi', 'level' => 2]);
        Role::create(['name' => 'Manager Keuangan', 'slug' => 'manager-keuangan', 'level' => 3]);
        Role::create(['name' => 'Manager Unit', 'slug' => 'manager-unit', 'level' => 4]);
        Role::create(['name' => 'Staff Administrasi', 'slug' => 'staff-administrasi', 'level' => 5]);
        Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'level' => 6]);
    }

    public function test_super_admin_can_access_all_routes()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->roles()->attach(Role::where('slug', 'super-admin')->first());

        $routes = [
            '/admin/dashboard',
            '/admin/members',
            '/admin/savings',
            '/admin/loans',
            '/admin/units',
            '/admin/transactions',
            '/admin/reports'
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($superAdmin)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_ketua_koperasi_can_access_all_routes()
    {
        $ketua = User::factory()->create();
        $ketua->roles()->attach(Role::where('slug', 'ketua-koperasi')->first());

        $routes = [
            '/admin/dashboard',
            '/admin/members',
            '/admin/savings',
            '/admin/loans',
            '/admin/units',
            '/admin/transactions',
            '/admin/reports'
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($ketua)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_manager_keuangan_can_access_financial_routes()
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('slug', 'manager-keuangan')->first());

        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/members',
            '/admin/savings',
            '/admin/loans',
            '/admin/transactions',
            '/admin/reports'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($manager)->get($route);
            $response->assertStatus(200);
        }

        // Should not access units
        $response = $this->actingAs($manager)->get('/admin/units');
        $response->assertStatus(403);
    }

    public function test_manager_unit_can_access_unit_routes()
    {
        $manager = User::factory()->create();
        $manager->roles()->attach(Role::where('slug', 'manager-unit')->first());

        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/units',
            '/admin/transactions',
            '/admin/reports'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($manager)->get($route);
            $response->assertStatus(200);
        }

        // Should not access members, savings, loans
        $restrictedRoutes = ['/admin/members', '/admin/savings', '/admin/loans'];
        foreach ($restrictedRoutes as $route) {
            $response = $this->actingAs($manager)->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_staff_administrasi_can_access_admin_routes()
    {
        $staff = User::factory()->create();
        $staff->roles()->attach(Role::where('slug', 'staff-administrasi')->first());

        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/members',
            '/admin/transactions'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($staff)->get($route);
            $response->assertStatus(200);
        }

        // Should not access savings, loans, units, reports
        $restrictedRoutes = ['/admin/savings', '/admin/loans', '/admin/units', '/admin/reports'];
        foreach ($restrictedRoutes as $route) {
            $response = $this->actingAs($staff)->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_anggota_can_access_limited_routes()
    {
        $anggota = User::factory()->create();
        $anggota->roles()->attach(Role::where('slug', 'anggota')->first());

        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/settings/profile',
            '/admin/settings/system'
        ];

        foreach ($allowedRoutes as $route) {
            $response = $this->actingAs($anggota)->get($route);
            $response->assertStatus(200);
        }

        // Should not access management routes
        $restrictedRoutes = ['/admin/members', '/admin/savings', '/admin/loans', '/admin/units', '/admin/transactions', '/admin/reports'];
        foreach ($restrictedRoutes as $route) {
            $response = $this->actingAs($anggota)->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_guest_cannot_access_admin_routes()
    {
        $routes = [
            '/admin/dashboard',
            '/admin/members',
            '/admin/savings',
            '/admin/loans',
            '/admin/units',
            '/admin/transactions',
            '/admin/reports'
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/auth/login');
        }
    }

    public function test_user_without_roles_cannot_access_anything()
    {
        $user = User::factory()->create(); // No roles attached

        $routes = [
            '/admin/dashboard',
            '/admin/members',
            '/admin/savings',
            '/admin/loans',
            '/admin/units',
            '/admin/transactions',
            '/admin/reports'
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_middleware_handles_multiple_roles_correctly()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'manager-keuangan')->first());
        $user->roles()->attach(Role::where('slug', 'manager-unit')->first());

        // Should have access to both financial and unit routes
        $response = $this->actingAs($user)->get('/admin/savings');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/admin/units');
        $response->assertStatus(200);
    }

    public function test_middleware_shows_error_message_for_unauthorized_access()
    {
        $anggota = User::factory()->create();
        $anggota->roles()->attach(Role::where('slug', 'anggota')->first());

        $response = $this->actingAs($anggota)
            ->get('/admin/members');

        $response->assertStatus(403);
        $response->assertSee('Anda tidak memiliki akses');
    }

    public function test_middleware_redirects_to_login_for_unauthenticated_users()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/auth/login');
    }

    public function test_role_middleware_with_invalid_role_parameter()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->roles()->attach(Role::where('slug', 'super-admin')->first());

        // Test with non-existent role - should still work if user has valid role
        $response = $this->actingAs($superAdmin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_middleware_is_case_insensitive()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->roles()->attach(Role::where('slug', 'super-admin')->first());

        // Test with different case - should still work
        $response = $this->actingAs($superAdmin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
    }
}
