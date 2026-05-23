<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk (catalog) dengan filtering.
     * Data diambil dari Supabase, dengan case-insensitive filtering.
     * Filter bersifat OPSIONAL: user bisa memilih kategori/brand atau tidak.
     */
    public function index(Request $request)
    {
        $isAjax = $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        // Debug: Log the full query string
        Log::debug('Full query string: ' . $request->getQueryString());
        Log::debug('All query params: ' . json_encode($request->query()));
        Log::debug('Request URL: ' . $request->fullUrl());
        
        try {
            // Ambil semua kategori unik dari database
            $categories = Product::whereNotNull('kategori_produk')
                ->distinct()
                ->pluck('kategori_produk')
                ->sort()
                ->values();

            // Ambil semua brand unik dari database
            $brands = Product::whereNotNull('nama_brand')
                ->distinct()
                ->pluck('nama_brand')
                ->sort()
                ->values();

            // Get filter parameters dari request
            $category = $request->query('category');
            $brand = $request->query('brand');
            $minPrice = (int)$request->query('min_price', 0);
            $maxPrice = (int)$request->query('max_price', 2000000);
            $sort = $request->query('sort', 'newest');

            // Query dari Supabase dengan filtering opsional
            $query = Product::query();

            // Filter kategori (opsional - exact match, case-insensitive)
            $query->when(
                $request->filled('category'),
                function ($q) use ($request) {
                    return $q->whereRaw('LOWER(kategori_produk) = ?', [strtolower($request->category)]);
                }
            );

            // Filter brand (opsional - exact match, case-insensitive)
            $query->when(
                $request->filled('brand'),
                function ($q) use ($request) {
                    return $q->whereRaw('LOWER(nama_brand) = ?', [strtolower($request->brand)]);
                }
            );

            // Filter harga
            $query->when(
                $request->filled('min_price') && $request->filled('max_price'),
                function ($q) use ($minPrice, $maxPrice) {
                    return $q->whereBetween('harga_min', [$minPrice, $maxPrice]);
                }
            );

            // Sorting
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('harga_min', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('harga_min', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('nama_produk', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('product_id', 'desc');
                    break;
            }

            // Paginate dengan onEachSide untuk ringkas
            $products = $query->paginate(12)->onEachSide(1);

            // Handle AJAX requests
            if ($isAjax) {
                $html = view('partials.products-grid', ['products' => $products])->render();
                return response()->json(['html' => $html, 'success' => true]);
            }

            return view('pages.catalog', compact('products', 'categories', 'brands'));
        } catch (\Exception $e) {
            Log::error('Catalog error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Jika database error, tampilkan halaman catalog dengan produk kosong
            $products = collect();
            $categories = collect();
            $brands = collect();
            
            // Handle AJAX requests
            if ($isAjax) {
                $html = view('partials.products-grid', ['products' => $products])->render();
                return response()->json(['html' => $html, 'success' => false, 'error' => 'Gagal memuat data dari database']);
            }
            
            return view('pages.catalog', compact('products', 'categories', 'brands'));
        }
    }

    /**
     * Tampilkan detail produk dari Supabase.
     */
    public function show(string $product_id)
    {
        try {
            $product = Product::where('product_id', $product_id)->firstOrFail();
            
            return view('pages.product-detail', compact('product'));
        } catch (\Exception $e) {
            Log::error('Product detail error: ' . $e->getMessage());
            abort(404, 'Produk tidak ditemukan');
        }
    }

}