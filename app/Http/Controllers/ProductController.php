<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk (catalog).
     */
    public function index()
    {
        // Query dari database atau return empty
        $products = Product::paginate(12);
        
        // Temporary dummy data untuk testing tanpa database
        if ($products->isEmpty()) {
            $products = [
            [
                'id' => 1,
                'slug' => 'hydrating-essence-toner',
                'name' => 'Hydrating Essence Toner',
                'brand' => 'Herbivore Botanicals',
                'category' => 'toner',
                'skin_type' => ['dry', 'sensitive'],
                'price' => 425000,
                'image' => '💧',
                'rating' => 4.8,
                'reviews' => 245,
                'is_bestseller' => true,
            ],
            [
                'id' => 2,
                'slug' => 'cerave-moisturizing-cream',
                'name' => 'CeraVe Moisturizing Cream',
                'brand' => 'CeraVe',
                'category' => 'moisturizer',
                'skin_type' => ['dry', 'sensitive'],
                'price' => 185000,
                'image' => '🧴',
                'rating' => 4.9,
                'reviews' => 612,
                'is_bestseller' => true,
            ],
            [
                'id' => 3,
                'slug' => 'ultra-sheer-sunscreen-spf-50',
                'name' => 'Ultra Sheer Sunscreen SPF 50',
                'brand' => 'Neutrogena',
                'category' => 'sunscreen',
                'skin_type' => ['all'],
                'price' => 95000,
                'image' => '☀️',
                'rating' => 4.6,
                'reviews' => 398,
                'is_bestseller' => false,
            ],
            [
                'id' => 4,
                'slug' => 'luminous-dewy-skin-mist',
                'name' => 'Luminous Dewy Skin Mist',
                'brand' => 'Tatcha',
                'category' => 'toner',
                'skin_type' => ['normal', 'combination'],
                'price' => 375000,
                'image' => '💦',
                'rating' => 4.7,
                'reviews' => 156,
                'is_bestseller' => false,
            ],
            [
                'id' => 5,
                'slug' => 'purifying-gel-cleanser',
                'name' => 'Purifying Gel Cleanser',
                'brand' => 'La Roche Posay',
                'category' => 'cleanser',
                'skin_type' => ['oily', 'combination'],
                'price' => 145000,
                'image' => '🫧',
                'rating' => 4.5,
                'reviews' => 324,
                'is_bestseller' => false,
            ],
            [
                'id' => 6,
                'slug' => 'vitamin-c-brightening-serum',
                'name' => 'Vitamin C Brightening Serum',
                'brand' => 'Ordinary',
                'category' => 'serum',
                'skin_type' => ['all'],
                'price' => 89000,
                'image' => '✨',
                'rating' => 4.4,
                'reviews' => 542,
                'is_bestseller' => true,
            ],
        ];
        }

        return view('pages.catalog', compact('products'));
    }

    /**
     * Tampilkan detail produk.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();
        
        if (!$product) {
            // Dummy data produk untuk testing tanpa database
            $dummyProducts = [
                'hydrating-essence-toner' => [
                    'id' => 1,
                    'slug' => 'hydrating-essence-toner',
                    'name' => 'Hydrating Essence Toner',
                    'brand' => 'Herbivore Botanicals',
                    'category' => 'toner',
                    'skin_type' => ['dry', 'sensitive'],
                    'price' => 425000,
                    'image' => '💧',
                    'rating' => 4.8,
                    'reviews' => 245,
                    'is_bestseller' => true,
                    'description' => 'A lightweight, hydrating toner infused with rose water and hyaluronic acid to enhance skin\'s natural moisture retention.',
                    'benefits' => ['Hydrates skin deeply', 'Improves skin texture', 'Balances pH', 'Contains antioxidants'],
                    'ingredients' => ['Rose Water', 'Hyaluronic Acid', 'Glycerin', 'Allantoin'],
                    'usage' => 'After cleansing, apply with a cotton pad or spray directly onto face. Follow with your favorite serum and moisturizer.',
                    'how_it_works' => 'The essence formula delivers essential hydration while maintaining the skin\'s natural pH balance.'
                ],
                'cerave-moisturizing-cream' => [
                    'id' => 2,
                    'slug' => 'cerave-moisturizing-cream',
                    'name' => 'CeraVe Moisturizing Cream',
                    'brand' => 'CeraVe',
                    'category' => 'moisturizer',
                    'skin_type' => ['dry', 'sensitive'],
                    'price' => 185000,
                    'image' => '🧴',
                    'rating' => 4.9,
                    'reviews' => 612,
                    'is_bestseller' => true,
                    'description' => 'Fragrance-free, hypoallergenic moisturizing cream with ceramides and hyaluronic acid to restore and protect the skin barrier.',
                    'benefits' => ['Restores moisture barrier', 'Reduces irritation', 'Long-lasting hydration', 'Dermatologist recommended'],
                    'ingredients' => ['Ceramides', 'Hyaluronic Acid', 'Petrolatum', 'Cetyl Alcohol'],
                    'usage' => 'Apply to face and body twice daily or as needed. Can be used on sensitive skin and during pregnancy.',
                    'how_it_works' => 'CeraVe\'s unique formula contains 3 essential ceramides and hyaluronic acid to maintain the skin\'s natural barrier.'
                ],
                'ultra-sheer-sunscreen-spf-50' => [
                    'id' => 3,
                    'slug' => 'ultra-sheer-sunscreen-spf-50',
                    'name' => 'Ultra Sheer Sunscreen SPF 50',
                    'brand' => 'Neutrogena',
                    'category' => 'sunscreen',
                    'skin_type' => ['all'],
                    'price' => 95000,
                    'image' => '☀️',
                    'rating' => 4.6,
                    'reviews' => 398,
                    'is_bestseller' => false,
                    'description' => 'Ultra-sheer, light-weight sunscreen protection with broad spectrum UVA/UVB defense. Water-resistant up to 80 minutes.',
                    'benefits' => ['Broad spectrum protection', 'Water resistant', 'Non-greasy', 'Fast absorbing'],
                    'ingredients' => ['Avobenzone', 'Octinoxate', 'Glycerin', 'Tocopherol'],
                    'usage' => 'Apply liberally 15 minutes before sun exposure. Reapply after swimming or sweating.',
                    'how_it_works' => 'Advanced UVA/UVB filtering technology provides comprehensive sun protection without a heavy feel.'
                ],
            ];
            
            $product = $dummyProducts[$slug] ?? null;
            if (!$product) {
                abort(404, 'Produk tidak ditemukan');
            }
        }
        
        return view('pages.product-detail', compact('product'));
    }
}
