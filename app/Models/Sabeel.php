<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sabeel extends Model
{
    use HasFactory;

    protected $fillable = [
        'sabeel_code',
        'sabeel_address',
        'sabeel_sector',
        'sabeel_hof',
        'sabeel_type'
    ];

    /**
     * Get the head of family (mumin) for this sabeel.
     * This is not a foreign key relationship - just a lookup by ITS_ID.
     */
    public function getHeadOfFamily()
    {
        if (!$this->sabeel_hof) {
            return null;
        }
        
        return Mumin::where('ITS_ID', $this->sabeel_hof)->first();
    }

    /**
     * Check if head of family exists.
     */
    public function hasHeadOfFamily(): bool
    {
        return $this->sabeel_hof && Mumin::where('ITS_ID', $this->sabeel_hof)->exists();
    }

    /**
     * Get all mumineen belonging to this sabeel.
     */
    public function mumineen(): HasMany
    {
        return $this->hasMany(Mumin::class, 'sabeel_code', 'sabeel_code');
    }

    /**
     * Get sabeel type options.
     */
    public static function getTypeOptions(): array
    {
        return [
            'regular' => 'Regular',
            'student' => 'Student',
            'res_without_sabeel' => 'Resident Without Sabeel',
            'moallemeen' => 'Moallemeen',
            'regular_lock_joint' => 'Regular Lock/Joint',
            'left_sabeel' => 'Left Sabeel'
        ];
    }

    /**
     * Get sector options.
     */
    public static function getSectorOptions(): array
    {
        return [
            'ezzi' => 'Ezzi',
            'fakhri' => 'Fakhri',
            'hakimi' => 'Hakimi',
            'shujai' => 'Shujai',
            'al_masjid_us_saifee' => 'AL MASJID US SAIFEE',
            'raj_township' => 'Raj Township',
            'zainy' => 'Zainy',
            'student' => 'Student',
            'mtnc' => 'MTNC',
            'unknown' => 'UNKNOWN'
        ];
    }

    /**
     * Scope for active sabeels (non-left sabeels).
     */
    public function scopeActive($query)
    {
        return $query->where('sabeel_type', '!=', 'left_sabeel');
    }

    /**
     * Scope for inactive sabeels (left sabeels).
     */
    public function scopeInactive($query)
    {
        return $query->where('sabeel_type', 'left_sabeel');
    }

    /**
     * Get the sabeel type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypeOptions()[$this->sabeel_type] ?? $this->sabeel_type;
    }

    /**
     * Get the sector label.
     */
    public function getSectorLabelAttribute(): string
    {
        return self::getSectorOptions()[$this->sabeel_sector] ?? $this->sabeel_sector;
    }

    /**
     * Get mumineen count.
     */
    public function getMumineenCountAttribute(): int
    {
        return $this->mumineen()->count();
    }
}
