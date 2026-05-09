@extends('layouts.admin.admin')

@section('title', 'Manage Products — SkinQuo Admin')
@section('page_title', 'Manage Products')

@section('content')
<div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <h2 class="text-xl font-bold text-[var(--dark-brown)]">Products List</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-primary-admin">
        ➕ Add New Product
    </a>
</div>

{{-- Search & Filter --}}
<div class="card-admin mb-6">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-4 flex-wrap">
        <input type="text" name="search" placeholder="Search products..." 
               class="input-admin flex-1 min-w-48" 
               value="{{ request('search') }}">
        
        <select name="status" class="input-admin w-40">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button type="submit" class="btn-primary-admin">
            🔍 Search
        </button>
    </form>
</div>

{{-- Products Table --}}
<div class="card-admin overflow-x-auto">
    <table class="table-admin">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- Sample Row (To be replaced with @foreach loop) --}}
            <tr>
                <td colspan="6" class="text-center text-gray-500 py-8">
                    📭 No products found. <a href="{{ route('admin.products.create') }}" class="text-[var(--dark-brown)] font-semibold hover:underline">Create one</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Pagination (placeholder) --}}
<div class="mt-6 flex justify-center">
    {{-- Add pagination links here --}}
</div>
@endsection
