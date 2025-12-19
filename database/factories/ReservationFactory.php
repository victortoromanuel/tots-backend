<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'space_id' => Space::factory(),
            'event_name' => 'Test Event',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'status' => 'active',
        ];
    }
}
