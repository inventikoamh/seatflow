<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    /**
     * Get the areas for this location.
     */
    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    /**
     * Get Ramzaan areas for this location.
     */
    public function ramzaanAreas()
    {
        return $this->hasMany(Area::class)->ramzaan();
    }

    /**
     * Get Ashara areas for this location.
     */
    public function asharaAreas()
    {
        return $this->hasMany(Area::class)->ashara();
    }

    /**
     * Scope for active locations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
