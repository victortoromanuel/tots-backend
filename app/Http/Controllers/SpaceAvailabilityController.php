<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\Request;
use App\Services\ReservationService;

class SpaceAvailabilityController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function check(Request $request, Space $space)
    {
        $data = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $hasOverlap = $this->reservationService->hasOverlap(
            $space->id,
            $data['start_time'],
            $data['end_time']
        );

        return response()->json([
            'available' => ! $hasOverlap
        ]);
    }
}
