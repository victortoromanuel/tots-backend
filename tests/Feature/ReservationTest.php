<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_create_reservation_if_time_is_available()
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/reservations', [
            'space_id' => $space->id,
            'event_name' => 'Team Meeting',
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function user_cannot_create_overlapping_reservation()
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $baseDate = now()->addDay()->startOfDay();

        Reservation::create([
            'user_id' => User::factory()->create()->id,
            'space_id' => $space->id,
            'event_name' => 'Existing Event',
            'start_time' => $baseDate->copy()->setTime(10, 0)->toDateTimeString(),
            'end_time' => $baseDate->copy()->setTime(11, 0)->toDateTimeString(),
            'status' => 'active',
        ]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/reservations', [
            'space_id' => $space->id,
            'event_name' => 'Another Meeting',
            'start_time' => $baseDate->copy()->setTime(10, 30)->toDateTimeString(),
            'end_time' => $baseDate->copy()->setTime(11, 30)->toDateTimeString(),
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The selected time slot is not available.'
            ]);
    }

    #[Test]
    public function user_cannot_delete_other_users_reservation()
    {
        $reservation = Reservation::factory()->create();

        $this->actingAs(User::factory()->create(), 'api');

        $this->deleteJson("/api/reservations/{$reservation->id}")
            ->assertStatus(403);
    }

    #[Test]
    public function user_can_get_their_reservations()
    {
        $user = User::create([
            'name' => 'Authenticated User',
            'email' => 'auth@tots.com',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'auth@tots.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');
        $space = Space::factory()->create();

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

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->getJson("/api/reservations/user/{$user->id}")
            ->assertStatus(200);
    }

    #[Test]
    public function user_can_get_reservations_by_space()
    {
        $user = User::create([
            'name' => 'Authenticated User',
            'email' => 'auth@tots.com',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'auth@tots.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');
        $space = Space::factory()->create();

        $baseDate = now()->addDay()->startOfDay();

        Reservation::create([
            'user_id' => $user->id,
            'space_id' => $space->id,
            'event_name' => 'Existing Event',
            'start_time' => $baseDate->copy()->setTime(10, 0)->toDateTimeString(),
            'end_time' => $baseDate->copy()->setTime(11, 0)->toDateTimeString(),
            'status' => 'active',
        ]);

        Reservation::create([
            'user_id' => $user->id,
            'space_id' => $space->id,
            'event_name' => 'Existing Event 2',
            'start_time' => $baseDate->copy()->setTime(12, 0)->toDateTimeString(),
            'end_time' => $baseDate->copy()->setTime(15, 0)->toDateTimeString(),
            'status' => 'active',
        ]);

        $this->actingAs($user, 'api');

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->getJson("/api/reservations/space/{$space->id}")
            ->assertStatus(200);
    }

    #[Test]
    public function user_can_update_reservation()
    {
        $user = User::factory()->create();
        $space = Space::factory()->create();

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'space_id' => $space->id,
            'event_name' => 'Initial Event',
            'start_time' => now()->addDay()->setTime(9, 0),
            'end_time' => now()->addDay()->setTime(10, 0),
            'status' => 'active',
        ]);

        $this->actingAs($user, 'api');

        $response = $this->putJson("/api/reservations/{$reservation->id}", [
            'event_name' => 'Updated Event',
            'start_time' => now()->addDay()->setTime(11, 0),
            'end_time' => now()->addDay()->setTime(12, 0),
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'event_name' => 'Updated Event',
            ]);
    }
}
