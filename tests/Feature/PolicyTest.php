<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_manage_spaces()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@tots.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin, 'api');
        $this->assertTrue($admin->fresh()->is_admin);

        $response = $this->postJson('/api/spaces', [
            'name' => 'Test Space',
            'type' => 'meeting_room',
            'capacity' => 5,
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function regular_user_cannot_manage_spaces()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/spaces', [
            'name' => 'Forbidden Space',
            'type' => 'meeting_room',
            'capacity' => 5,
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function user_cannot_access_other_users_reservation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $space = Space::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user1->id,
            'space_id' => $space->id,
        ]);

        $this->actingAs($user2, 'api');

        $response = $this->getJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(403);
    }
}
