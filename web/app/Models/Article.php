<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_url',
        'category',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Scope: hanya artikel yang dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}