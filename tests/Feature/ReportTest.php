<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FinancialReport;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Manager Keuangan', 'slug' => 'manager-keuangan', 'level' => 2]);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('slug', 'super-admin')->first());
    }

    public function test_admin_can_view_reports_index()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/reports');

        $response->assertStatus(200);
        $response->assertSee('Laporan Koperasi');
    }

    public function test_admin_can_view_financial_report()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/reports/financial');

        $response->assertStatus(200);
        $response->assertSee('Laporan Keuangan');
    }

    public function test_admin_can_view_members_report()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/reports/members');

        $response->assertStatus(200);
        $response->assertSee('Laporan Anggota');
    }

    public function test_admin_can_view_units_report()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/reports/units');

        $response->assertStatus(200);
        $response->assertSee('Laporan Unit Usaha');
    }

    public function test_admin_can_generate_custom_report()
    {
        $reportData = [
            'title' => 'Laporan Kustom Test',
            'type' => 'custom',
            'period_start' => now()->subMonth()->format('Y-m-d'),
            'period_end' => now()->format('Y-m-d'),
            'summary' => 'Laporan kustom untuk testing'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $response->assertRedirect('/admin/reports');
        $this->assertDatabaseHas('financial_reports', [
            'title' => 'Laporan Kustom Test',
            'type' => 'custom'
        ]);
    }

    public function test_admin_can_view_report_detail()
    {
        $report = FinancialReport::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/admin/reports/{$report->id}");

        $response->assertStatus(200);
        $response->assertSee($report->title);
    }

    public function test_report_number_is_generated_automatically()
    {
        $reportData = [
            'title' => 'Laporan Test',
            'type' => 'monthly',
            'period_start' => now()->startOfMonth()->format('Y-m-d'),
            'period_end' => now()->endOfMonth()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $report = FinancialReport::first();
        $this->assertNotNull($report->report_number);
        $this->assertStringStartsWith('RPT-', $report->report_number);
    }

    public function test_report_validation_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', []);

        $response->assertSessionHasErrors(['title', 'type', 'period_start', 'period_end']);
    }

    public function test_report_period_end_must_be_after_start()
    {
        $reportData = [
            'title' => 'Invalid Period Report',
            'type' => 'monthly',
            'period_start' => now()->format('Y-m-d'),
            'period_end' => now()->subDay()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $response->assertSessionHasErrors(['period_end']);
    }

    public function test_report_type_must_be_valid()
    {
        $reportData = [
            'title' => 'Invalid Type Report',
            'type' => 'invalid-type',
            'period_start' => now()->startOfMonth()->format('Y-m-d'),
            'period_end' => now()->endOfMonth()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $response->assertSessionHasErrors(['type']);
    }

    public function test_monthly_report_generation()
    {
        $reportData = [
            'title' => 'Laporan Bulanan',
            'type' => 'monthly',
            'period_start' => now()->startOfMonth()->format('Y-m-d'),
            'period_end' => now()->endOfMonth()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $response->assertRedirect();
        $report = FinancialReport::first();
        $this->assertEquals('monthly', $report->type);
        $this->assertEquals($this->admin->id, $report->generated_by);
    }

    public function test_quarterly_report_generation()
    {
        $reportData = [
            'title' => 'Laporan Kuartalan',
            'type' => 'quarterly',
            'period_start' => now()->startOfQuarter()->format('Y-m-d'),
            'period_end' => now()->endOfQuarter()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $response->assertRedirect();
        $report = FinancialReport::first();
        $this->assertEquals('quarterly', $report->type);
    }

    public function test_annual_report_generation()
    {
        $reportData = [
            'title' => 'Laporan Tahunan',
            'type' => 'annual',
            'period_start' => now()->startOfYear()->format('Y-m-d'),
            'period_end' => now()->endOfYear()->format('Y-m-d')
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/reports/generate', $reportData);

        $response->assertRedirect();
        $report = FinancialReport::first();
        $this->assertEquals('annual', $report->type);
    }

    public function test_report_can_be_filtered_by_type()
    {
        FinancialReport::factory()->create(['type' => 'monthly']);
        FinancialReport::factory()->create(['type' => 'quarterly']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/reports?type=monthly');

        $response->assertStatus(200);
        $response->assertSee('monthly');
    }

    public function test_report_can_be_filtered_by_year()
    {
        FinancialReport::factory()->create(['generated_at' => now()]);
        FinancialReport::factory()->create(['generated_at' => now()->subYear()]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/reports?year=' . now()->year);

        $response->assertStatus(200);
        // Should only show current year reports
    }
}
