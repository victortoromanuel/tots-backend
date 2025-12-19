<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        Reservation::create([
            'user_id' => 2, // John Doe
            'space_id' => 1, // Main Meeting Room
            'event_name' => 'Weekly Team Sync',
            'start_time' => Carbon::now()->addDays(1)->setTime(9, 0),
            'end_time' => Carbon::now()->addDays(1)->setTime(10, 0),
            'status' => 'active',
        ]);

        Reservation::create([
            'user_id' => 3, // Jane Smith
            'space_id' => 2, // Conference Auditorium
            'event_name' => 'Tech Conference',
            'start_time' => Carbon::now()->addDays(3)->setTime(14, 0),
            'end_time' => Carbon::now()->addDays(3)->setTime(18, 0),
            'status' => 'active',
        ]);
    }
}
