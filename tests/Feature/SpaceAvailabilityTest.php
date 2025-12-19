<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SpaceAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function space_is_available_when_no_reservations_exist()
    {
        $space = Space::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $baseDate = now()->addDay()->startOfDay();

        $response = $this->getJson(
            "/api/spaces/{$space->id}/availability?start_time="
            . $baseDate->copy()->setTime(10, 0)->toDateTimeString()
            . "&end_time="
            . $baseDate->copy()->setTime(11, 0)->toDateTimeString()
        );

        $response->assertStatus(200)
            ->assertJson([
                'available' => true
            ]);
    }

    #[Test]
    public function space_is_not_available_when_reservation_overlaps()
    {
        $space = Space::factory()->create();
        $user = User::factory()->create();

        $baseDate = now()->addDay()->startOfDay();

        Reservation::create([
            'user_id' => $user->id,
            'space_id' => $space->id,
            'event_name' => 'Existing Event',
            'start_time' => $baseDate->copy()->setTime(10, 0)->toDateTimeString(),
            'end_time' => $baseDate->copy()->setTime(11, 0)->toDateTimeString(),
            'status' => 'active',
        ]);

        $this->actingAs($user, 'api');

        $response = $this->getJson(
            "/api/spaces/{$space->id}/availability?start_time="
            . $baseDate->copy()->setTime(10, 30)->toDateTimeString()
            . "&end_time="
            . $baseDate->copy()->setTime(11, 30)->toDateTimeString()
        );

        $response->assertStatus(200)
            ->assertJson([
                'available' => false
            ]);
    }
}
