<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * AdminProductController
 * 
 * Handles CRUD operations for products in admin panel
 * 
 * @package App\Http\Controllers
 */
class AdminProductController extends Controller
{
    /**
     * Display all products with pagination
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // TODO: Fetch all products with pagination
        // TODO: Apply search filter if provided
        // TODO: Apply status filter if provided
        
        $products = []; // Product::paginate(15);
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show product creation form
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // TODO: Fetch skin types for dropdown
        
        return view('admin.products.create');
    }

    /**
     * Store product in database
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // TODO: Validate input with proper rules
        // TODO: Handle image upload to storage
        // TODO: Create product record in database
        // TODO: Log admin action to admin_logs table
        
        return redirect()->route('admin.products.index')
                       ->with('success', 'Product created successfully');
    }

    /**
     * Show product details
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // TODO: Show product detail view with all related data
        
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show product edit form
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        // TODO: Load product data into edit form
        // TODO: Fetch skin types for dropdown
        
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product in database
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        // TODO: Validate input with proper rules
        // TODO: Handle image update if new image provided
        // TODO: Update product record in database
        // TODO: Log admin action to admin_logs table
        
        return redirect()->route('admin.products.index')
                       ->with('success', 'Product updated successfully');
    }

    /**
     * Delete (soft delete) product
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // TODO: Soft delete product record (set deleted_at)
        // TODO: Log admin action to admin_logs table
        
        return redirect()->route('admin.products.index')
                       ->with('success', 'Product deleted successfully');
    }
}
