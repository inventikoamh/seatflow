<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'seat_number',
        'row_number',
        'column_number',
        'column_label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'seat_number' => 'integer',
        'row_number' => 'integer',
        'column_number' => 'integer',
    ];

    /**
     * Get the area that owns the seat.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the location through the area.
     */
    public function location()
    {
        return $this->hasOneThrough(Location::class, Area::class, 'id', 'id', 'area_id', 'location_id');
    }

    /**
     * Scope for active seats.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
     * Scope for seats by row.
     */
    public function scopeByRow($query, $rowNumber)
    {
        return $query->where('row_number', $rowNumber);
    }

    /**
     * Scope for seats by column.
     */
    public function scopeByColumn($query, $columnNumber)
    {
        return $query->where('column_number', $columnNumber);
    }

    /**
     * Get seat position as string (e.g., "A1", "BC21").
     */
    public function getPositionAttribute()
    {
        return $this->column_label . $this->row_number;
    }

    /**
     * Check if seat is available.
     */
    public function isAvailable()
    {
        return $this->is_active;
    }

    /**
     * Check if seat is selectable.
     */
    public function isSelectable()
    {
        return $this->is_active;
    }
}