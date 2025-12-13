<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\BusinessUnit;
use App\Models\Transaction;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $admin;

    /** @var Member */
    protected $member;

    /** @var BusinessUnit */
    protected $businessUnit;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Staff Administrasi', 'slug' => 'staff-administrasi', 'level' => 4]);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('slug', 'super-admin')->first());
        
        // Create member and business unit
        $this->member = Member::factory()->create();
        $this->businessUnit = BusinessUnit::factory()->create();
    }

    public function test_admin_can_view_transactions_index()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/transactions');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Transaksi');
    }

    public function test_admin_can_create_income_transaction()
    {
        $transactionData = [
            'description' => 'Penjualan barang',
            'amount' => 2500000,
            'type' => 'income',
            'category' => 'Operasional',
            'payment_method' => 'Transfer',
            'transaction_date' => now()->format('Y-m-d'),
            'member_id' => $this->member->id
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions', $transactionData);

        $response->assertRedirect('/admin/transactions');
        $this->assertDatabaseHas('transactions', [
            'description' => 'Penjualan barang',
            'amount' => 2500000,
            'type' => 'income'
        ]);
    }

    public function test_admin_can_create_expense_transaction()
    {
        $transactionData = [
            'description' => 'Pembelian ATK',
            'amount' => 500000,
            'type' => 'expense',
            'category' => 'Operasional',
            'payment_method' => 'Tunai',
            'transaction_date' => now()->format('Y-m-d'),
            'business_unit_id' => $this->businessUnit->id
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions', $transactionData);

        $response->assertRedirect('/admin/transactions');
        $this->assertDatabaseHas('transactions', [
            'description' => 'Pembelian ATK',
            'amount' => 500000,
            'type' => 'expense'
        ]);
    }

    public function test_admin_can_view_transaction_detail()
    {
        $transaction = Transaction::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/admin/transactions/{$transaction->id}");

        $response->assertStatus(200);
        $response->assertSee($transaction->description);
    }

    public function test_admin_can_update_transaction()
    {
        $transaction = Transaction::factory()->create();

        $updateData = [
            'description' => 'Updated transaction description',
            'amount' => 1500000,
            'type' => 'income',
            'category' => 'Investasi',
            'payment_method' => 'E-Wallet',
            'transaction_date' => now()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->put("/admin/transactions/{$transaction->id}", $updateData);

        $response->assertRedirect("/admin/transactions/{$transaction->id}");
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'description' => 'Updated transaction description'
        ]);
    }

    public function test_admin_can_delete_transaction()
    {
        $transaction = Transaction::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete("/admin/transactions/{$transaction->id}");

        $response->assertRedirect('/admin/transactions');
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_transaction_number_is_generated_automatically()
    {
        $transactionData = [
            'description' => 'Test transaction',
            'amount' => 1000000,
            'type' => 'income',
            'category' => 'Operasional',
            'payment_method' => 'Transfer',
            'transaction_date' => now()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions', $transactionData);

        $transaction = Transaction::first();
        $this->assertNotNull($transaction->transaction_number);
        $this->assertStringStartsWith('TRX-', $transaction->transaction_number);
    }

    public function test_transaction_validation_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions', []);

        $response->assertSessionHasErrors(['description', 'amount', 'type', 'category', 'payment_method', 'transaction_date']);
    }

    public function test_transaction_amount_must_be_positive()
    {
        $transactionData = [
            'description' => 'Invalid transaction',
            'amount' => -1000,
            'type' => 'income',
            'category' => 'Operasional',
            'payment_method' => 'Transfer',
            'transaction_date' => now()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions', $transactionData);

        $response->assertSessionHasErrors(['amount']);
    }

    public function test_transaction_type_must_be_valid()
    {
        $transactionData = [
            'description' => 'Invalid type transaction',
            'amount' => 1000000,
            'type' => 'invalid-type',
            'category' => 'Operasional',
            'payment_method' => 'Transfer',
            'transaction_date' => now()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions', $transactionData);

        $response->assertSessionHasErrors(['type']);
    }

    public function test_admin_can_view_daily_transactions()
    {
        Transaction::factory()->create([
            'transaction_date' => now(),
            'amount' => 1000000,
            'type' => 'income'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/transactions/daily');

        $response->assertStatus(200);
        $response->assertSee('Laporan Harian');
    }

    public function test_admin_can_view_monthly_transactions()
    {
        Transaction::factory()->create([
            'transaction_date' => now(),
            'amount' => 2000000,
            'type' => 'expense'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/transactions/monthly');

        $response->assertStatus(200);
        $response->assertSee('Laporan Bulanan');
    }

    public function test_admin_can_export_transactions()
    {
        Transaction::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->post('/admin/transactions/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_transaction_can_be_filtered_by_type()
    {
        Transaction::factory()->create(['type' => 'income']);
        Transaction::factory()->create(['type' => 'expense']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/transactions?type=income');

        $response->assertStatus(200);
        $response->assertSee('income');
    }

    public function test_transaction_can_be_filtered_by_date_range()
    {
        Transaction::factory()->create(['transaction_date' => now()->subDays(5)]);
        Transaction::factory()->create(['transaction_date' => now()]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/transactions?date_start=' . now()->subDays(3)->format('Y-m-d'));

        $response->assertStatus(200);
        // Should only show recent transaction
    }
}
