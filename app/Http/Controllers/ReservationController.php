<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\ReservationService;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'event_name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Verificar si hay overlap con otras reservas activas
        if ($this->reservationService->hasOverlap(
            $data['space_id'],
            $data['start_time'],
            $data['end_time']
        )) {
            return response()->json([
                'message' => 'The selected time slot is not available.'
            ], 422);
        }

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'space_id' => $data['space_id'],
            'event_name' => $data['event_name'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => 'active',
        ]);

        return response()->json($reservation, 201);
    }

    public function show(Reservation $reservation)
    {
        Gate::authorize('view', $reservation);

        return response()->json($reservation);
    }

    public function showAllByUser(int $userId)
    {
        $reservations = Reservation::where('user_id', $userId)->get();

        return response()->json($reservations);
    }

    public function showAllBySpace(int $spaceId)
    {
        $reservations = Reservation::where('space_id', $spaceId)->get();

        return response()->json($reservations);
    }

    public function update(Request $request, Reservation $reservation)
    {
        Gate::authorize('update', $reservation);

        $data = $request->validate([
            'event_name' => 'sometimes|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'status' => 'sometimes|in:active,cancelled,completed',
        ]);

        if (isset($data['start_time'], $data['end_time'])) {
            if ($this->reservationService->hasOverlap(
                $reservation->space_id,
                $data['start_time'],
                $data['end_time'],
                $reservation->id
            )) {
                return response()->json([
                    'message' => 'The selected time slot is not available.'
                ], 422);
            }
        }

        $reservation->update($data);

        return response()->json($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        Gate::authorize('delete', $reservation);

        $reservation->delete();

        return response()->json(null, 204);
    }
}
