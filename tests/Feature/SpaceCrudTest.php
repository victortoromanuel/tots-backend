<?php

namespace Tests\Feature;

use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SpaceCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin()
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function user()
    {
        return User::factory()->create(['is_admin' => false]);
    }

    #[Test]
    public function authenticated_user_can_list_spaces()
    {
        Space::factory()->count(3)->create();

        $this->actingAs($this->user(), 'api');

        $this->getJson('/api/spaces')
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function admin_can_create_space()
    {
        $this->actingAs($this->admin(), 'api');

        $this->postJson('/api/spaces', [
            'name' => 'Conference Room',
            'type' => 'meeting_room',
            'capacity' => 10,
            'price_per_hour' => 50,
        ])
        ->assertStatus(201)
        ->assertJson([
            'name' => 'Conference Room',
            'capacity' => 10,
        ]);
    }

    #[Test]
    public function regular_user_cannot_create_space()
    {
        $this->actingAs($this->user(), 'api');

        $this->postJson('/api/spaces', [
            'name' => 'Hack Room',
            'type' => 'meeting_room',
            'capacity' => 5,
        ])
        ->assertStatus(403);
    }

    #[Test]
    public function admin_can_update_space()
    {
        $space = Space::factory()->create();

        $this->actingAs($this->admin(), 'api');

        $this->putJson("/api/spaces/{$space->id}", [
            'capacity' => 20,
        ])
        ->assertStatus(200)
        ->assertJson([
            'capacity' => 20,
        ]);
    }

    #[Test]
    public function admin_can_delete_space()
    {
        $space = Space::factory()->create();

        $this->actingAs($this->admin(), 'api');

        $this->deleteJson("/api/spaces/{$space->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('spaces', [
            'id' => $space->id,
        ]);
    }
}
