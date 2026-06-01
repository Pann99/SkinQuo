<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class AdminProductController extends Controller
{
    /**
     * Display all products
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search product
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_produk) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(nama_brand) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(kategori_produk) LIKE ?', ["%{$search}%"]);
            });
        }

        $products = $query
            ->orderBy('product_id', 'desc')
            ->paginate(15);

        return view('admin.inventory.index', compact('products'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|integer|unique:products,product_id',
            'nama_produk' => 'required|string|max:255',
            'nama_brand' => 'required|string|max:255',
            'kategori_produk' => 'required|string|max:255',
            'harga_min' => 'required|numeric',
            'harga_max' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'cara_pakai' => 'nullable|string',
            'kandungan' => 'nullable|string',
            'image' => 'nullable|string',
            'link_produk' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil ditambahkan',
            'data' => $product
        ]);
    }

    /**
     * Show detail product
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama_produk' => 'sometimes|string|max:255',
            'nama_brand' => 'sometimes|string|max:255',
            'kategori_produk' => 'sometimes|string|max:255',
            'harga_min' => 'sometimes|numeric',
            'harga_max' => 'sometimes|numeric',
            'deskripsi' => 'nullable|string',
            'cara_pakai' => 'nullable|string',
            'kandungan' => 'nullable|string',
            'image' => 'nullable|string',
            'link_produk' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()
            ->route('admin.inventory')
            ->with('success', 'Product berhasil diupdate!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil dihapus'
        ]);
    }
}