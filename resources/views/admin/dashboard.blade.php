@extends('layouts.admin.admin')

@section('title', 'Admin Dashboard — SkinQuo')
@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Total Products Card --}}
    <div class="card-admin">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Products</p>
                <p class="text-3xl font-bold text-[var(--dark-brown)] mt-2">0</p>
                <p class="text-gray-500 text-xs mt-1">Active products in catalog</p>
            </div>
            <div class="text-4xl">🛍️</div>
        </div>
    </div>

    {{-- Total Articles Card --}}
    <div class="card-admin">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Skin Guide Articles</p>
                <p class="text-3xl font-bold text-[var(--dark-brown)] mt-2">0</p>
                <p class="text-gray-500 text-xs mt-1">Published articles</p>
            </div>
            <div class="text-4xl">📚</div>
        </div>
    </div>

    {{-- Pending Feedback Card --}}
    <div class="card-admin">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pending Feedback</p>
                <p class="text-3xl font-bold text-[var(--dark-brown)] mt-2">0</p>
                <p class="text-gray-500 text-xs mt-1">Awaiting review</p>
            </div>
            <div class="text-4xl">💬</div>
        </div>
    </div>

    {{-- Total Users Card --}}
    <div class="card-admin">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-[var(--dark-brown)] mt-2">0</p>
                <p class="text-gray-500 text-xs mt-1">Registered users</p>
            </div>
            <div class="text-4xl">👥</div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card-admin mb-8">
    <h2 class="text-xl font-bold text-[var(--dark-brown)] mb-6">🚀 Quick Actions</h2>
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.products.create') }}" class="btn-primary-admin">
            ➕ Add New Product
        </a>
        <a href="{{ route('admin.skin-guide.create') }}" class="btn-primary-admin">
            ✍️ Write Article
        </a>
        <a href="{{ route('admin.feedback.monitor') }}" class="btn-secondary-admin">
            👁️ View Feedback
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary-admin">
            📦 Manage Products
        </a>
    </div>
</div>

{{-- Recent Activities --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Products --}}
    <div class="card-admin">
        <h3 class="text-lg font-bold text-[var(--dark-brown)] mb-4">📦 Recent Products</h3>
        <div class="space-y-3">
            <p class="text-gray-500 text-center py-6">No recent products yet</p>
        </div>
    </div>

    {{-- Recent Articles --}}
    <div class="card-admin">
        <h3 class="text-lg font-bold text-[var(--dark-brown)] mb-4">📰 Recent Articles</h3>
        <div class="space-y-3">
            <p class="text-gray-500 text-center py-6">No recent articles yet</p>
        </div>
    </div>
</div>
@endsection
