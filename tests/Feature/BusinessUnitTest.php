<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Manager Unit', 'slug' => 'manager-unit', 'level' => 3]);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('slug', 'super-admin')->first());
    }

    public function test_admin_can_view_units_index()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/units');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Unit Usaha');
    }

    public function test_admin_can_create_business_unit()
    {
        $unitData = [
            'name' => 'Unit Sembako Test',
            'type' => 'sembako',
            'location' => 'Jakarta',
            'initial_capital' => 50000000,
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/units', $unitData);

        $response->assertRedirect('/admin/units');
        $this->assertDatabaseHas('business_units', [
            'name' => 'Unit Sembako Test',
            'type' => 'sembako'
        ]);
    }

    public function test_admin_can_view_unit_detail()
    {
        $unit = BusinessUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/admin/units/{$unit->id}");

        $response->assertStatus(200);
        $response->assertSee($unit->name);
    }

    public function test_admin_can_update_business_unit()
    {
        $unit = BusinessUnit::factory()->create();

        $updateData = [
            'name' => 'Updated Unit Name',
            'type' => 'apotek',
            'location' => 'Surabaya',
            'initial_capital' => 75000000,
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)
            ->put("/admin/units/{$unit->id}", $updateData);

        $response->assertRedirect("/admin/units/{$unit->id}");
        $this->assertDatabaseHas('business_units', [
            'id' => $unit->id,
            'name' => 'Updated Unit Name'
        ]);
    }

    public function test_admin_can_delete_business_unit()
    {
        $unit = BusinessUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete("/admin/units/{$unit->id}");

        $response->assertRedirect('/admin/units');
        $this->assertDatabaseMissing('business_units', ['id' => $unit->id]);
    }

    public function test_admin_can_view_sembako_units()
    {
        BusinessUnit::factory()->create(['type' => 'sembako']);
        BusinessUnit::factory()->create(['type' => 'apotek']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/units/type/sembako');

        $response->assertStatus(200);
        $response->assertSee('Sembako');
    }

    public function test_admin_can_view_apotek_units()
    {
        BusinessUnit::factory()->create(['type' => 'apotek']);
        BusinessUnit::factory()->create(['type' => 'klinik']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/units/type/apotek');

        $response->assertStatus(200);
        $response->assertSee('Apotek');
    }

    public function test_admin_can_view_klinik_units()
    {
        BusinessUnit::factory()->create(['type' => 'klinik']);
        BusinessUnit::factory()->create(['type' => 'logistik']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/units/type/klinik');

        $response->assertStatus(200);
        $response->assertSee('Klinik');
    }

    public function test_admin_can_view_logistik_units()
    {
        BusinessUnit::factory()->create(['type' => 'logistik']);
        BusinessUnit::factory()->create(['type' => 'sembako']);

        $response = $this->actingAs($this->admin)
            ->get('/admin/units/type/logistik');

        $response->assertStatus(200);
        $response->assertSee('Logistik');
    }

    public function test_business_unit_validation_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/units', []);

        $response->assertSessionHasErrors(['name', 'type', 'location', 'initial_capital']);
    }

    public function test_business_unit_type_must_be_valid()
    {
        $unitData = [
            'name' => 'Invalid Unit',
            'type' => 'invalid-type',
            'location' => 'Jakarta',
            'initial_capital' => 1000000
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/units', $unitData);

        $response->assertSessionHasErrors(['type']);
    }

    public function test_initial_capital_must_be_positive()
    {
        $unitData = [
            'name' => 'Negative Capital Unit',
            'type' => 'sembako',
            'location' => 'Jakarta',
            'initial_capital' => -1000000
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/units', $unitData);

        $response->assertSessionHasErrors(['initial_capital']);
    }

    public function test_unit_status_can_be_updated()
    {
        $unit = BusinessUnit::factory()->create(['status' => 'active']);

        $updateData = [
            'name' => $unit->name,
            'type' => $unit->type,
            'location' => $unit->location,
            'initial_capital' => $unit->initial_capital,
            'status' => 'maintenance'
        ];

        $response = $this->actingAs($this->admin)
            ->put("/admin/units/{$unit->id}", $updateData);

        $response->assertRedirect();
        $unit->refresh();
        $this->assertEquals('maintenance', $unit->status);
    }
}
