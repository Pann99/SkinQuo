<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // WAJIB DITAMBAHKAN UNTUK CEK LOGIN

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama SkinQuo.
     */
    public function index()
    {
        // =========================================================
        // CEK LOGIN ADMIN: 
        // Jika sudah login dan role-nya Admin, langsung lempar ke Dashboard Admin
        // =========================================================
        if (Auth::check()) {
            $user = Auth::user();
            
            // Mengecek apakah user adalah admin (role_id 1 atau role_name 'admin')
            // Menyesuaikan dengan logika di AuthController kamu
            if ($user->role_id == 1 || ($user->role && $user->role->role_name === 'admin')) {
                return redirect()->route('admin.dashboard');
            }
        }

        // =========================================================
        // JIKA BUKAN ADMIN / BELUM LOGIN, TAMPILKAN HOMEPAGE BIASA
        // =========================================================
        
        // Ambil 8 artikel terbaru yang dipublikasikan
        $articles = Article::where('is_published', true)
                            ->latest('created_at')
                            ->take(8)
                            ->get();

        // Ambil feedback dengan rating >= 4 untuk Community Voices section
        // Order by latest first, take 3
        $communityVoices = Feedback::with('user')
            ->whereNotNull('text')
            ->where('rating', '>=', 4)
            ->where('is_reviewed', true)
            ->latest('id')
            ->take(3)
            ->get();

        // Ambil 3 produk best seller
        // TEMPORARY: Ambil 3 produk dengan harga tertinggi sebagai proxy untuk "best seller"
        // TODO: Setelah migration, gunakan kolom is_best_seller dan sold_count
        $bestSellers = Product::orderByDesc('harga_max')
                              ->take(3)
                              ->get();

        // Jika tidak ada produk, tampilkan placeholder di view
        if ($bestSellers->isEmpty()) {
            $bestSellers = collect([]);
        }

        return view('pages.home', compact('articles', 'communityVoices', 'bestSellers'));
    }
}