<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TakhmeenImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'filename',
        'total_records',
        'successful_imports',
        'failed_imports',
        'errors',
        'status',
        'imported_by',
    ];

    protected $casts = [
        'errors' => 'array',
        'total_records' => 'integer',
        'successful_imports' => 'integer',
        'failed_imports' => 'integer',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function importedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function takhmeen(): HasMany
    {
        return $this->hasMany(Takhmeen::class, 'import_batch_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    // Accessors
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_records === 0) {
            return 0;
        }
        
        return round(($this->successful_imports / $this->total_records) * 100, 2);
    }

    public function getFailureRateAttribute(): float
    {
        if ($this->total_records === 0) {
            return 0;
        }
        
        return round(($this->failed_imports / $this->total_records) * 100, 2);
    }
}