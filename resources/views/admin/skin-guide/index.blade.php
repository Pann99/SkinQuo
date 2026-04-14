@extends('layouts.admin.admin')

@section('title', 'Skin Guide Management — SkinQuo Admin')
@section('page_title', 'Skin Guide Articles')

@section('content')
<div class="mb-6 flex justify-between items-center flex-wrap gap-4">
    <h2 class="text-xl font-bold text-[var(--dark-brown)]">Articles List</h2>
    <a href="{{ route('admin.skin-guide.create') }}" class="btn-primary-admin">
        ✍️ Write Article
    </a>
</div>

{{-- Search & Filter --}}
<div class="card-admin mb-6">
    <form method="GET" action="{{ route('admin.skin-guide.index') }}" class="flex gap-4 flex-wrap">
        <input type="text" name="search" placeholder="Search articles..." 
               class="input-admin flex-1 min-w-48" 
               value="{{ request('search') }}">
        
        <select name="category" class="input-admin w-48">
            <option value="">All Categories</option>
            <option value="Moisturizing" {{ request('category') === 'Moisturizing' ? 'selected' : '' }}>Moisturizing</option>
            <option value="Anti-Aging" {{ request('category') === 'Anti-Aging' ? 'selected' : '' }}>Anti-Aging</option>
            <option value="Acne" {{ request('category') === 'Acne' ? 'selected' : '' }}>Acne</option>
            <option value="Sensitive" {{ request('category') === 'Sensitive' ? 'selected' : '' }}>Sensitive</option>
        </select>

        <select name="status" class="input-admin w-40">
            <option value="">All Status</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>

        <button type="submit" class="btn-primary-admin">
            🔍 Search
        </button>
    </form>
</div>

{{-- Articles Table --}}
<div class="card-admin overflow-x-auto">
    <table class="table-admin">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Published At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- Sample Row (To be replaced with @foreach loop) --}}
            <tr>
                <td colspan="6" class="text-center text-gray-500 py-8">
                    📭 No articles found. <a href="{{ route('admin.skin-guide.create') }}" class="text-[var(--dark-brown)] font-semibold hover:underline">Write one</a>
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
