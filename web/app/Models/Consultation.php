<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skin_story',
        'tags',
        'detected_traits',
        'concern_1',
        'concern_2',
        'preferences',
        'recommendations',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'detected_traits' => 'array',
        'preferences' => 'array',
        'recommendations' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Consultation belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get pending consultations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get processed consultations
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Check if consultation has recommendations
     */
    public function hasRecommendations(): bool
    {
        return !empty($this->recommendations);
    }

    /**
     * Get primary traits as array
     */
    public function getPrimaryTraits(): array
    {
        return array_slice($this->detected_traits ?? [], 0, 2);
    }
}
