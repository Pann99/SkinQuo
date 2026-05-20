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




    
    // =========================
    // CREATE - FORM
    // =========================
    public function create()
    {
        return view('pages.product-create');
    }

    // =========================
    // CREATE - SAVE DATA
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'nama_brand' => 'required',
            'kategori_produk' => 'required',
            'harga_min' => 'required|numeric',
        ]);

        try {

            Product::create([
                'nama_produk' => $request->nama_produk,
                'nama_brand' => $request->nama_brand,
                'kategori_produk' => $request->kategori_produk,
                'harga_min' => $request->harga_min,
                'harga_max' => $request->harga_max,
                'deskripsi' => $request->deskripsi,
                'image' => $request->image,
                'link_produk' => $request->link_produk,
            ]);

            return redirect()
                ->route('catalog.index')
                ->with('success', 'Produk berhasil ditambahkan');

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return back()->with('error', 'Gagal tambah produk');
        }
    }

    // =========================
    // UPDATE - FORM EDIT
    // =========================
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id);

        return view('pages.product-edit', compact('product'));
    }

    // =========================
    // UPDATE - SAVE EDIT
    // =========================
    public function update(Request $request, $product_id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'nama_brand' => 'required',
            'kategori_produk' => 'required',
            'harga_min' => 'required|numeric',
        ]);

        try {

            $product = Product::findOrFail($product_id);

            $product->update([
                'nama_produk' => $request->nama_produk,
                'nama_brand' => $request->nama_brand,
                'kategori_produk' => $request->kategori_produk,
                'harga_min' => $request->harga_min,
                'harga_max' => $request->harga_max,
                'deskripsi' => $request->deskripsi,
                'image' => $request->image,
                'link_produk' => $request->link_produk,
            ]);

            return redirect()
                ->route('catalog.index')
                ->with('success', 'Produk berhasil diupdate');

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return back()->with('error', 'Gagal update produk');
        }
    }

    // =========================
    // DELETE PRODUCT
    // =========================
    public function destroy($product_id)
    {
        try {

            $product = Product::findOrFail($product_id);

            $product->delete();

            return redirect()
                ->route('catalog.index')
                ->with('success', 'Produk berhasil dihapus');

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return back()->with('error', 'Gagal hapus produk');
        }
    }
}



