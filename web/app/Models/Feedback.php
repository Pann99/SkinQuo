<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'text',
        'rating',
        
    ];

    /**
     * ⚠️ IMPORTANT: This table in Supabase does NOT have created_at/updated_at columns.
     * Setting $timestamps = false prevents Eloquent from trying to update them.
     */
    public $timestamps = false;

    protected $casts = [
        'rating' => 'float',
    ];

    /**
     * Relationship: Feedback belongs to User
     * Foreign key is 'user_id', and User model uses 'user_id' as primary key (not 'id')
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: Feedback belongs to Consultation
     * Foreign key is 'consultation_id'
     */
}
