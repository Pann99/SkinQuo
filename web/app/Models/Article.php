<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all tags for this article
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pivot_article_tag', 'article_id', 'tag_id');
    }

    /**
     * Scope: Get published articles only
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}

