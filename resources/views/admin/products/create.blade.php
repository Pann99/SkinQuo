@extends('layouts.admin.admin')

@section('title', 'Create Product — SkinQuo Admin')
@section('page_title', 'Create New Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-admin mb-6">
            <h3 class="text-lg font-bold text-[var(--dark-brown)] mb-6">📦 Product Information</h3>

            {{-- Product Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input type="text" name="name" class="input-admin @error('name') error @enderror" 
                       placeholder="e.g., Herbivore Botanical Serum" 
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Product Slug --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL-friendly) *</label>
                <input type="text" name="slug" class="input-admin @error('slug') error @enderror" 
                       placeholder="herbivore-botanical-serum" 
                       value="{{ old('slug') }}"
                       required>
                @error('slug')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="4" class="input-admin @error('description') error @enderror" 
                          placeholder="Detailed product description..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category & Price --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" class="input-admin @error('category') error @enderror" required>
                        <option value="">Select Category</option>
                        <option value="Serum" {{ old('category') === 'Serum' ? 'selected' : '' }}>Serum</option>
                        <option value="Moisturizer" {{ old('category') === 'Moisturizer' ? 'selected' : '' }}>Moisturizer</option>
                        <option value="Cleanser" {{ old('category') === 'Cleanser' ? 'selected' : '' }}>Cleanser</option>
                        <option value="Mask" {{ old('category') === 'Mask' ? 'selected' : '' }}>Mask</option>
                        <option value="Sunscreen" {{ old('category') === 'Sunscreen' ? 'selected' : '' }}>Sunscreen</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (IDR) *</label>
                    <input type="number" name="price" step="0.01" class="input-admin @error('price') error @enderror" 
                           placeholder="0.00" 
                           value="{{ old('price') }}"
                           required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Stock & Discount --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" class="input-admin @error('stock_quantity') error @enderror" 
                           placeholder="0" 
                           value="{{ old('stock_quantity', 0) }}"
                           min="0"
                           required>
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discount Price (optional)</label>
                    <input type="number" name="discount_price" step="0.01" class="input-admin @error('discount_price') error @enderror" 
                           placeholder="0.00" 
                           value="{{ old('discount_price') }}">
                    @error('discount_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Product Image --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                <input type="file" name="main_image" class="input-admin @error('main_image') error @enderror" 
                       accept="image/*">
                <p class="text-gray-500 text-xs mt-1">Recommended: 800x800px or higher</p>
                @error('main_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Is Active Checkbox --}}
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       class="w-4 h-4 text-[var(--dark-brown)] border-gray-300 rounded cursor-pointer"
                       {{ old('is_active') ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 text-sm text-gray-700">Publish product immediately</label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-4">
            <button type="submit" class="btn-primary-admin">
                ✅ Create Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary-admin">
                ❌ Cancel
            </a>
        </div>
    </form>
</div>
@endsection
