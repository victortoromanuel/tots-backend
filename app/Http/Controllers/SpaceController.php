<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SpaceController extends Controller
{
    public function index(Request $request)
    {
        $startDateTime = null;
        $endDateTime = null;
        
        if ($request->has(['date', 'start_time', 'end_time'])) {
            $data = $request->validate([
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            $startDateTime = $data['date'] . ' ' . $data['start_time'] . ':00';
            $endDateTime = $data['date'] . ' ' . $data['end_time'] . ':00';
        }

        $spaces = Space::where('is_active', true)
            ->when($startDateTime && $endDateTime, function ($query) use ($startDateTime, $endDateTime) {
                $query->whereDoesntHave('reservations', function ($q) use ($startDateTime, $endDateTime) {
                    $q->where(function ($subQuery) use ($startDateTime, $endDateTime) {
                        $subQuery->whereBetween('start_time', [$startDateTime, $endDateTime])
                                 ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                                 ->orWhere(function ($qq) use ($startDateTime, $endDateTime) {
                                     $qq->where('start_time', '<=', $startDateTime)
                                        ->where('end_time', '>=', $endDateTime);
                                 });
                    });
                });
            })
            ->get();
            
        return response()->json($spaces);
    }

    public function store(Request $request)
    {
        Gate::authorize('manage', Space::class);

        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'nullable|numeric',
        ]);

        $data['is_active'] = true;
        $space = Space::create($data);

        return response()->json($space, 201);
    }

    public function update(Request $request, Space $space)
    {
        Gate::authorize('manage', Space::class);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'type' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1',
            'price_per_hour' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $space->update($data);

        return response()->json($space);
    }

    public function destroy(Space $space)
    {
        Gate::authorize('manage', Space::class);

        $space->delete();

        return response()->json(null, 204);
    }
}
