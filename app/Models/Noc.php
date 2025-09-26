<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Noc extends Model
{
    use HasFactory;

    protected $table = 'noc';

    protected $fillable = [
        'sabeel_id',
        'event_id',
        'remark',
        'noc_alloted_at',
    ];

    protected $casts = [
        'noc_alloted_at' => 'datetime',
    ];

    // Relationships
    public function sabeel(): BelongsTo
    {
        return $this->belongsTo(Sabeel::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the head of family (mumin) for this NOC's sabeel.
     */
    public function getHeadOfFamily()
    {
        if (!$this->sabeel) {
            return null;
        }
        
        return $this->sabeel->getHeadOfFamily();
    }

    /**
     * Check if head of family exists for this NOC's sabeel.
     */
    public function hasHeadOfFamily(): bool
    {
        if (!$this->sabeel) {
            return false;
        }
        
        return $this->sabeel->hasHeadOfFamily();
    }

    /**
     * Get the HOF photo URL.
     */
    public function getHofPhotoUrl(): ?string
    {
        $hof = $this->getHeadOfFamily();
        if ($hof) {
            return $hof->getProfileImageUrl();
        }
        
        return null;
    }

    /**
     * Check if HOF photo exists.
     */
    public function hasHofPhoto(): bool
    {
        return !empty($this->getHofPhotoUrl());
    }

    // Scopes
    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeBySabeel($query, $sabeelId)
    {
        return $query->where('sabeel_id', $sabeelId);
    }

    public function scopeAlloted($query)
    {
        return $query->whereNotNull('noc_alloted_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('noc_alloted_at');
    }

    // Validation rules
    public static function validationRules(): array
    {
        return [
            'sabeel_id' => 'required|exists:sabeels,id',
            'event_id' => 'required|exists:events,id',
            'remark' => 'nullable|string|max:1000',
            'noc_alloted_at' => 'nullable|date',
        ];
    }

    public static function createRules(): array
    {
        return [
            'sabeel_id' => 'required|exists:sabeels,id',
            'event_id' => 'required|exists:events,id',
            'remark' => 'nullable|string|max:1000',
        ];
    }
}