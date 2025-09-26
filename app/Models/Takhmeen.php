<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Takhmeen extends Model
{
    use HasFactory;

    protected $table = 'takhmeen';

    protected $fillable = [
        'sabeel_id',
        'event_id',
        'amount',
        'notes',
        'hof_photo',
        'import_batch_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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

    public function importBatch(): BelongsTo
    {
        return $this->belongsTo(TakhmeenImportBatch::class);
    }

    /**
     * Get the head of family (mumin) for this takhmeen's sabeel.
     */
    public function getHeadOfFamily()
    {
        if (!$this->sabeel) {
            return null;
        }
        
        return $this->sabeel->getHeadOfFamily();
    }

    /**
     * Check if head of family exists for this takhmeen's sabeel.
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
        if ($this->hof_photo) {
            return $this->hof_photo;
        }
        
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

    /**
     * Format amount in Indian number format (lakhs, crores).
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₹' . $this->formatIndianNumber($this->amount);
    }

    /**
     * Get amount in Indian format with lakhs/crores notation.
     */
    public function getIndianAmountAttribute(): string
    {
        $amount = $this->amount;
        
        if ($amount >= 10000000) { // 1 crore
            $crores = $amount / 10000000;
            return '₹' . number_format($crores, 2, '.', ',') . ' Cr';
        } elseif ($amount >= 100000) { // 1 lakh
            $lakhs = $amount / 100000;
            return '₹' . number_format($lakhs, 2, '.', ',') . ' L';
        } else {
            return '₹' . $this->formatIndianNumber($amount);
        }
    }

    /**
     * Format amount in Indian comma format without L/Cr notation.
     */
    public function getIndianCommaAmountAttribute(): string
    {
        return '₹' . $this->formatIndianNumber($this->amount);
    }

    /**
     * Format number in Indian numbering system.
     */
    private function formatIndianNumber($number): string
    {
        $number = (float) $number;
        $integerPart = floor($number);
        $decimalPart = $number - $integerPart;
        
        // Convert to string and reverse for easier processing
        $str = strrev((string) $integerPart);
        $length = strlen($str);
        
        $result = '';
        
        for ($i = 0; $i < $length; $i++) {
            if ($i == 3) {
                // First comma after 3 digits (thousands)
                $result .= ',';
            } elseif ($i > 3 && ($i - 3) % 2 == 0) {
                // Then every 2 digits (lakhs, crores, etc.)
                $result .= ',';
            }
            $result .= $str[$i];
        }
        
        // Reverse back and add decimal part
        $formatted = strrev($result);
        
        if ($decimalPart > 0) {
            $formatted .= '.' . sprintf('%02d', round($decimalPart * 100));
        } else {
            $formatted .= '.00';
        }
        
        return $formatted;
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

    // Validation rules
    public static function validationRules(): array
    {
        return [
            'sabeel_id' => 'required|exists:sabeels,id',
            'event_id' => 'required|exists:events,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'hof_photo' => 'nullable|string|max:500',
            'import_batch_id' => 'nullable|exists:takhmeen_import_batches,id',
        ];
    }

    public static function createRules(): array
    {
        return [
            'sabeel_id' => 'required|exists:sabeels,id',
            'event_id' => 'required|exists:events,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'hof_photo' => 'nullable|string|max:500',
            'import_batch_id' => 'nullable|exists:takhmeen_import_batches,id',
        ];
    }
}