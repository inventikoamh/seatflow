<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mumin extends Model
{
    use HasFactory;

    protected $table = 'mumineen';

    protected $fillable = [
        'ITS_ID',
        'full_name',
        'sabeel_code',
        'mobile_number',
        'age',
        'gender',
        'type',
        'misaq'
    ];

    protected $casts = [
        'age' => 'integer',
        'type' => 'string',
        'misaq' => 'string'
    ];

    /**
     * Get the sabeel that this mumin belongs to.
     */
    public function sabeel(): BelongsTo
    {
        return $this->belongsTo(Sabeel::class, 'sabeel_code', 'sabeel_code');
    }

    /**
     * Scope for mumineen with mobile numbers.
     */
    public function scopeWithMobile($query)
    {
        return $query->whereNotNull('mobile_number');
    }

    /**
     * Scope for mumineen without mobile numbers.
     */
    public function scopeWithoutMobile($query)
    {
        return $query->whereNull('mobile_number');
    }

    /**
     * Get formatted mobile number.
     */
    public function getFormattedMobileAttribute(): ?string
    {
        if (!$this->mobile_number) {
            return null;
        }

        // Format mobile number (assuming Indian format)
        $mobile = preg_replace('/[^0-9]/', '', $this->mobile_number);
        if (strlen($mobile) === 10) {
            return '+91 ' . substr($mobile, 0, 5) . ' ' . substr($mobile, 5);
        }

        return $this->mobile_number;
    }

    /**
     * Check if this mumin is head of family for any sabeel.
     */
    public function isHeadOfFamily(): bool
    {
        return Sabeel::where('sabeel_hof', $this->ITS_ID)->exists();
    }

    /**
     * Get sabeels where this mumin is head of family.
     */
    public function headOfFamilySabeels()
    {
        return Sabeel::where('sabeel_hof', $this->ITS_ID)->get();
    }

    /**
     * Get the profile image URL for this mumin.
     */
    public function getProfileImageUrl(): string
    {
        return "https://jumur.dbjindore.org/photos/snap_med/{$this->ITS_ID}.jpg";
    }

    /**
     * Check if profile image exists (basic check - assumes all images exist).
     * In a real implementation, you might want to make an HTTP request to check.
     */
    public function hasProfileImage(): bool
    {
        return !empty($this->ITS_ID);
    }
}
