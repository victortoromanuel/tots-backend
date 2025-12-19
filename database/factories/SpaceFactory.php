<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Room',
            'description' => $this->faker->sentence,
            'type' => 'meeting_room',
            'capacity' => $this->faker->numberBetween(2, 20),
            'price_per_hour' => 25,
            'is_active' => true,
        ];
    }
}
