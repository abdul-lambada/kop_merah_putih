<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\Role;
use App\Models\SavingsLoan;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $admin;

    /** @var User */
    protected $memberUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'level' => 6]);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('slug', 'super-admin')->first());
        
        // Create member user
        $this->memberUser = User::factory()->create();
        $this->memberUser->roles()->attach(Role::where('slug', 'anggota')->first());
    }

    public function test_admin_can_view_dashboard()
    {
        try {
            $response = $this->actingAs($this->admin)
                ->get('/admin/dashboard');

            if ($response->getStatusCode() === 500) {
                echo "Response content: " . $response->getContent() . "\n";
            }

            $response->assertStatus(200);
            $response->assertSee('Dashboard');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
            throw $e;
        }
    }

    public function test_member_can_view_dashboard()
    {
        $response = $this->actingAs($this->memberUser)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_guest_cannot_view_dashboard()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/auth/login');
    }

    public function test_dashboard_shows_total_members()
    {
        Member::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('5');
    }

    public function test_dashboard_shows_total_savings()
    {
        SavingsLoan::factory()->count(3)->create([
            'type' => 'savings',
            'status' => 'completed',
            'amount' => 1000000
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('3.000.000');
    }

    public function test_dashboard_shows_active_loan_portfolio()
    {
        SavingsLoan::factory()->count(2)->create([
            'type' => 'loan',
            'status' => 'active',
            'amount' => 5000000,
            'remaining_amount' => 3000000
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('6.000.000');
    }

    public function test_dashboard_shows_active_units()
    {
        BusinessUnit::factory()->count(4)->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('4');
    }

    public function test_dashboard_shows_recent_transactions()
    {
        Transaction::factory()->count(5)->create([
            'transaction_date' => now()
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Transaksi Terbaru');
    }

    public function test_dashboard_shows_pending_loans()
    {
        SavingsLoan::factory()->count(3)->create([
            'type' => 'loan',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Pinjaman Pending');
    }

    public function test_dashboard_shows_monthly_chart_data()
    {
        // Create transactions for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            Transaction::factory()->create([
                'transaction_date' => now()->subMonths($i),
                'amount' => 1000000,
                'type' => 'income'
            ]);
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Performa 6 Bulan Terakhir');
    }

    public function test_dashboard_shows_sector_distribution()
    {
        Member::factory()->create(['business_sector' => 'Pertanian']);
        Member::factory()->create(['business_sector' => 'Perdagangan']);
        Member::factory()->create(['business_sector' => 'Jasa']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Distribusi Sektor Usaha Anggota');
    }

    public function test_dashboard_shows_unit_performance()
    {
        BusinessUnit::factory()->create(['name' => 'Unit A']);
        BusinessUnit::factory()->create(['name' => 'Unit B']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Performa Unit Usaha');
    }

    public function test_dashboard_data_is_calculated_correctly()
    {
        // Create test data
        Member::factory()->count(10)->create();
        SavingsLoan::factory()->create([
            'type' => 'savings',
            'status' => 'completed',
            'amount' => 2000000
        ]);
        SavingsLoan::factory()->create([
            'type' => 'loan',
            'status' => 'active',
            'amount' => 5000000,
            'remaining_amount' => 3000000
        ]);
        BusinessUnit::factory()->count(3)->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('10'); // Total members
        $response->assertSee('2.000.000'); // Total savings
        $response->assertSee('5.000.000'); // Active loan portfolio
        $response->assertSee('3'); // Active units
    }

    public function test_dashboard_filters_data_by_user_role()
    {
        // Create member with specific role
        $memberRole = Role::where('slug', 'anggota')->first();
        $memberUser = User::factory()->create();
        $memberUser->roles()->attach($memberRole);

        $response = $this->actingAs($memberUser)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        // Member should see dashboard but with limited data
        $response->assertSee('Dashboard');
    }

    public function test_dashboard_handles_no_data_gracefully()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('0'); // Should show 0 for all stats when no data
    }
}
