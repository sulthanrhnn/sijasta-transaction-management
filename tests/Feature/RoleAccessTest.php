<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('user.index'))
            ->assertOk();
    }

    public function test_partner_cannot_open_user_management(): void
    {
        $partner = User::factory()->create(['role' => 'mitra']);

        $this->actingAs($partner)
            ->get(route('user.index'))
            ->assertForbidden();
    }
}
