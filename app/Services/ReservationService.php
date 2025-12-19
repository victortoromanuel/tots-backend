<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;

class ReservationService
{
    public function hasOverlap(
        int $spaceId,
        string $start,
        string $end,
        ?int $excludeReservationId = null
    ): bool {
        $startFormatted = Carbon::parse($start)->format('Y-m-d H:i:s');
        $endFormatted = Carbon::parse($end)->format('Y-m-d H:i:s');
        
        return Reservation::where('space_id', $spaceId)
            ->where('status', 'active')
            ->when($excludeReservationId, function ($query) use ($excludeReservationId) {
                $query->where('id', '!=', $excludeReservationId);
            })
            ->overlaps($startFormatted, $endFormatted)
            ->exists();
    }
}
