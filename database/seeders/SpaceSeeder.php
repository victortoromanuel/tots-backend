<?php

namespace Database\Seeders;

use App\Models\Space;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        Space::insert([
            [
                'name' => 'Main Meeting Room',
                'description' => 'A fully equipped meeting room ideal for team meetings and presentations.',
                'type' => 'meeting_room',
                'capacity' => 10,
                'price_per_hour' => 25.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conference Auditorium',
                'description' => 'Large auditorium suitable for conferences, talks, and corporate events.',
                'type' => 'auditorium',
                'capacity' => 100,
                'price_per_hour' => 120.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Open Collaboration Space',
                'description' => 'Open space designed for workshops, brainstorming sessions, and group activities.',
                'type' => 'open_space',
                'capacity' => 30,
                'price_per_hour' => 50.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Small Private Room',
                'description' => 'Small and quiet room for private meetings or interviews.',
                'type' => 'meeting_room',
                'capacity' => 4,
                'price_per_hour' => 15.00,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
