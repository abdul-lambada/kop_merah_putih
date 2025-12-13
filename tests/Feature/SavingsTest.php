<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\Role;
use App\Models\SavingsLoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavingsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $admin;

    /** @var Member */
    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Manager Keuangan', 'slug' => 'manager-keuangan', 'level' => 2]);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('slug', 'super-admin')->first());
        
        // Create member
        $this->member = Member::factory()->create();
    }

    public function test_admin_can_view_savings_index()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/savings');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Simpanan');
    }

    public function test_admin_can_create_savings()
    {
        $savingsData = [
            'member_id' => $this->member->id,
            'amount' => 1000000,
            'description' => 'Simpanan rutin bulanan',
            'type' => 'savings',
            'status' => 'pending'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/savings', $savingsData);

        $response->assertRedirect('/admin/savings');
        $this->assertDatabaseHas('savings_loans', [
            'member_id' => $this->member->id,
            'amount' => 1000000,
            'type' => 'savings'
        ]);
    }

    public function test_admin_can_approve_savings()
    {
        $savings = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'savings',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/savings/{$savings->id}/approve");

        $response->assertRedirect();
        $savings->refresh();
        $this->assertEquals('approved', $savings->status);
    }

    public function test_admin_can_withdraw_savings()
    {
        $savings = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'savings',
            'status' => 'completed',
            'amount' => 2000000
        ]);

        $withdrawData = [
            'amount' => 500000,
            'description' => 'Penarikan untuk keperluan mendesak'
        ];

        $response = $this->actingAs($this->admin)
            ->post("/admin/savings/{$savings->id}/withdraw", $withdrawData);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'member_id' => $this->member->id,
            'amount' => 500000,
            'type' => 'expense'
        ]);
    }

    public function test_savings_number_is_generated_automatically()
    {
        $savingsData = [
            'member_id' => $this->member->id,
            'amount' => 1000000,
            'description' => 'Simpanan rutin',
            'type' => 'savings'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/savings', $savingsData);

        $savings = SavingsLoan::first();
        $this->assertNotNull($savings->savings_loan_number);
        $this->assertStringStartsWith('SAV-', $savings->savings_loan_number);
    }

    public function test_savings_validation_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/savings', []);

        $response->assertSessionHasErrors(['member_id', 'amount', 'description']);
    }

    public function test_savings_amount_must_be_positive()
    {
        $savingsData = [
            'member_id' => $this->member->id,
            'amount' => -1000,
            'description' => 'Invalid amount',
            'type' => 'savings'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/savings', $savingsData);

        $response->assertSessionHasErrors(['amount']);
    }

    public function test_member_can_only_withdraw_up_to_available_balance()
    {
        $savings = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'savings',
            'status' => 'completed',
            'amount' => 1000000
        ]);

        $withdrawData = [
            'amount' => 1500000, // More than available
            'description' => 'Excessive withdrawal'
        ];

        $response = $this->actingAs($this->admin)
            ->post("/admin/savings/{$savings->id}/withdraw", $withdrawData);

        $response->assertSessionHasErrors(['amount']);
    }
}
