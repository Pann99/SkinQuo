<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'harga_min',
        'harga_max',
        'image',
        'is_best_seller',
        'sold_count',
    ];

    protected $casts = [
        'harga_min' => 'decimal:2',
        'harga_max' => 'decimal:2',
        'is_best_seller' => 'boolean',
        'sold_count' => 'integer',
    ];

    /**
     * Scope: hanya best seller products
     */
    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }
}
