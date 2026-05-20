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
        'excerpt',
        'body',
        'content',
        'thumbnail',
        'image_url',
        'category',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Many-to-Many relationship with Tag
     */
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'pivot_article_tag',
            'article_id',
            'tag_id'
        );
    }

    /**
     * Scope: hanya artikel yang dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
