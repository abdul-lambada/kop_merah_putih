<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Member;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'level' => 1]);
        Role::create(['name' => 'Staff Administrasi', 'slug' => 'staff-administrasi', 'level' => 4]);
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('slug', 'super-admin')->first());
    }

    public function test_admin_can_view_members_index()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/members');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Anggota');
    }

    public function test_admin_can_create_member()
    {
        $memberData = [
            'full_name' => 'Test Member',
            'email' => 'test@example.com',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'business_sector' => 'Pertanian',
            'experience' => 'Menengah',
            'join_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/members', $memberData);

        $response->assertRedirect('/admin/members');
        $this->assertDatabaseHas('members', [
            'full_name' => 'Test Member',
            'email' => 'test@example.com'
        ]);
    }

    public function test_admin_can_view_member_detail()
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/admin/members/{$member->id}");

        $response->assertStatus(200);
        $response->assertSee($member->full_name);
    }

    public function test_admin_can_update_member()
    {
        $member = Member::factory()->create();

        $updateData = [
            'full_name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '08987654321',
            'address' => 'Updated Address',
            'business_sector' => 'Perdagangan',
            'experience' => 'Ahli',
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)
            ->put("/admin/members/{$member->id}", $updateData);

        $response->assertRedirect("/admin/members/{$member->id}");
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'full_name' => 'Updated Name'
        ]);
    }

    public function test_admin_can_verify_member()
    {
        $member = Member::factory()->create(['verified_at' => null]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/members/{$member->id}/verify");

        $response->assertRedirect();
        $member->refresh();
        $this->assertNotNull($member->verified_at);
    }

    public function test_admin_can_delete_member()
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete("/admin/members/{$member->id}");

        $response->assertRedirect('/admin/members');
        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }

    public function test_member_validation_required_fields()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/members', []);

        $response->assertSessionHasErrors(['full_name', 'email', 'phone', 'address', 'business_sector', 'experience', 'join_date']);
    }

    public function test_member_number_is_generated_automatically()
    {
        $memberData = [
            'full_name' => 'Test Member',
            'email' => 'test@example.com',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'business_sector' => 'Pertanian',
            'experience' => 'Menengah',
            'join_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/members', $memberData);

        $member = Member::first();
        $this->assertNotNull($member->member_number);
        $this->assertStringStartsWith('KMP-', $member->member_number);
    }
}
