<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Many-to-Many relationship with Article
     */
    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'pivot_article_tag',
            'tag_id',
            'article_id'
        );
    }

    /**
     * Get popular tags ordered by article count
     */
    public function scopePopular($query)
    {
        return $query
            ->withCount('articles')
            ->orderBy('articles_count', 'desc');
    }
}
