<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'name',
        'slug',
        'description',
        'capacity',
        'gender_type',
        'floor',
        'section',
        'event_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'floor' => 'integer',
    ];

    /**
     * Get the location that owns the area.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the seats for this area.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Scope for active areas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for areas by event type.
     */
    public function scopeByEventType($query, $eventType)
    {
        return $query->where(function ($q) use ($eventType) {
            $q->where('event_type', $eventType)
              ->orWhere('event_type', 'both');
        });
    }

    /**
     * Scope for areas by gender type.
     */
    public function scopeByGender($query, $genderType)
    {
        return $query->where('gender_type', $genderType);
    }

    /**
     * Scope for areas by floor.
     */
    public function scopeByFloor($query, $floor)
    {
        return $query->where('floor', $floor);
    }

    /**
     * Scope for Ramzaan areas.
     */
    public function scopeRamzaan($query)
    {
        return $this->scopeByEventType($query, 'ramzaan');
    }

    /**
     * Scope for Ashara areas.
     */
    public function scopeAshara($query)
    {
        return $this->scopeByEventType($query, 'ashara');
    }
}