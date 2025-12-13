<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\Role;
use App\Models\SavingsLoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
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
        $this->member = Member::factory()->create(['loan_limit' => 10000000]);
    }

    public function test_admin_can_view_loans_index()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/loans');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pinjaman');
    }

    public function test_admin_can_create_loan()
    {
        $loanData = [
            'member_id' => $this->member->id,
            'amount' => 5000000,
            'tenure' => 12,
            'interest_rate' => 12,
            'description' => 'Pinjaman modal usaha',
            'type' => 'loan',
            'status' => 'pending'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/loans', $loanData);

        $response->assertRedirect('/admin/loans');
        $this->assertDatabaseHas('savings_loans', [
            'member_id' => $this->member->id,
            'amount' => 5000000,
            'type' => 'loan'
        ]);
    }

    public function test_admin_can_approve_loan()
    {
        $loan = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'loan',
            'status' => 'pending',
            'amount' => 5000000,
            'tenure' => 12,
            'interest_rate' => 12
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/loans/{$loan->id}/approve");

        $response->assertRedirect();
        $loan->refresh();
        $this->assertEquals('active', $loan->status);
        $this->assertNotNull($loan->approved_at);
        $this->assertNotNull($loan->monthly_installment);
    }

    public function test_admin_can_reject_loan()
    {
        $loan = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'loan',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/loans/{$loan->id}/reject");

        $response->assertRedirect();
        $loan->refresh();
        $this->assertEquals('rejected', $loan->status);
    }

    public function test_admin_can_make_loan_payment()
    {
        $loan = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'loan',
            'status' => 'active',
            'amount' => 12000000,
            'tenure' => 12,
            'interest_rate' => 12,
            'monthly_installment' => 1000000,
            'remaining_amount' => 12000000
        ]);

        $paymentData = [
            'amount' => 1000000,
            'description' => 'Cicilan bulanan',
            'payment_method' => 'Transfer'
        ];

        $response = $this->actingAs($this->admin)
            ->post("/admin/loans/{$loan->id}/payment", $paymentData);

        $response->assertRedirect();
        $loan->refresh();
        $this->assertEquals(11000000, $loan->remaining_amount);
        $this->assertDatabaseHas('transactions', [
            'member_id' => $this->member->id,
            'amount' => 1000000,
            'type' => 'income'
        ]);
    }

    public function test_loan_number_is_generated_automatically()
    {
        $loanData = [
            'member_id' => $this->member->id,
            'amount' => 5000000,
            'tenure' => 12,
            'description' => 'Pinjaman modal',
            'type' => 'loan'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/loans', $loanData);

        $loan = SavingsLoan::first();
        $this->assertNotNull($loan->savings_loan_number);
        $this->assertStringStartsWith('LOAN-', $loan->savings_loan_number);
    }

    public function test_loan_amount_cannot_exceed_member_limit()
    {
        $loanData = [
            'member_id' => $this->member->id,
            'amount' => 15000000, // Exceeds limit of 10000000
            'tenure' => 12,
            'description' => 'Excessive loan',
            'type' => 'loan'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/loans', $loanData);

        $response->assertSessionHasErrors(['amount']);
    }

    public function test_loan_validation_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/loans', []);

        $response->assertSessionHasErrors(['member_id', 'amount', 'tenure', 'description']);
    }

    public function test_monthly_installment_is_calculated_automatically()
    {
        $loanData = [
            'member_id' => $this->member->id,
            'amount' => 12000000,
            'tenure' => 12,
            'interest_rate' => 12,
            'description' => 'Pinjaman dengan bunga',
            'type' => 'loan'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/loans', $loanData);

        $loan = SavingsLoan::first();
        $this->assertEquals(1000000, $loan->monthly_installment);
    }

    public function test_loan_cannot_be_approved_if_member_has_overdue_loans()
    {
        // Create overdue loan
        SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'loan',
            'status' => 'overdue'
        ]);

        $newLoan = SavingsLoan::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'loan',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/loans/{$newLoan->id}/approve");

        $response->assertSessionHasErrors(['error']);
    }
}
