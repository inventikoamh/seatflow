<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'event_type',
        'previous_event_id',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the previous event for this event.
     */
    public function previousEvent()
    {
        return $this->belongsTo(Event::class, 'previous_event_id');
    }

    /**
     * Get the events that have this event as previous.
     */
    public function nextEvents()
    {
        return $this->hasMany(Event::class, 'previous_event_id');
    }

    /**
     * Get the seat maps for this event.
     */
    public function seatMaps()
    {
        return $this->hasMany(SeatMap::class);
    }

    /**
     * Scope for active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for events by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for default event.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the default event for a specific type.
     */
    public static function getDefaultEvent($type = null)
    {
        $query = static::where('is_default', true)->where('is_active', true);
        
        if ($type) {
            $query->where('event_type', $type);
        }
        
        return $query->first();
    }

    /**
     * Set this event as default and unset others of the same type.
     */
    public function setAsDefault()
    {
        // Unset other default events of the same type
        static::where('event_type', $this->event_type)
              ->where('id', '!=', $this->id)
              ->update(['is_default' => false]);

        // Set this event as default
        $this->update(['is_default' => true]);
    }

    /**
     * Get available previous events for this event type.
     */
    public function getAvailablePreviousEvents()
    {
        return static::where('event_type', $this->event_type)
                    ->where('id', '!=', $this->id)
                    ->where('is_active', true)
                    ->orderBy('start_date', 'desc')
                    ->get();
    }
}
