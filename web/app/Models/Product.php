<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = "product_id";
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'kategori_produk',
        'harga_min',
        'harga_max',
        'image',
        'link_produk',
        'nama_brand',
        'cara_pakai',
        'kandungan',
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
