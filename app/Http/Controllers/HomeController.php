<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama SkinQuo.
     */
    public function index()
    {
        // Ambil 8 artikel terbaru
        $articles = Article::where('is_published', true)
                            ->latest('published_at')
                            ->take(8)
                            ->get();

        // Ambil 3 produk best seller
        $bestSellers = Product::where('is_best_seller', true)
                              ->orderByDesc('sold_count')
                              ->take(3)
                              ->get();

        return view('pages.home', compact('articles', 'bestSellers'));
    }
}