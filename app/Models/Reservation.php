<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'space_id',
        'event_name',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Date format for serialization
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d\TH:i:s');
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Scope: only active reservations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to detect overlapping reservations
     */
    public function scopeOverlaps(
        Builder $query,
        string $start,
        string $end
    ): Builder {
        return $query
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start);
    }
}
