<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'capacity',
        'price_per_hour',
        'is_active',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'capacity' => 'integer',
        'price_per_hour' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     * A space can have many reservations
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Scope: only active spaces
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
