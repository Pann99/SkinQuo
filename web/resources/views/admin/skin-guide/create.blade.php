@extends('layouts.admin.admin')

@section('title', 'Create Article — SkinQuo Admin')
@section('page_title', 'Write New Article')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.skin-guide.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-admin mb-6">
            <h3 class="text-lg font-bold text-[var(--dark-brown)] mb-6">📝 Article Information</h3>

            {{-- Article Title --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Article Title *</label>
                <input type="text" name="title" class="input-admin @error('title') error @enderror" 
                       placeholder="e.g., How to Care for Sensitive Skin" 
                       value="{{ old('title') }}"
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL-friendly) *</label>
                <input type="text" name="slug" class="input-admin @error('slug') error @enderror" 
                       placeholder="how-to-care-for-sensitive-skin" 
                       value="{{ old('slug') }}"
                       required>
                @error('slug')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Excerpt --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt (Short Summary) *</label>
                <textarea name="excerpt" rows="2" class="input-admin @error('excerpt') error @enderror" 
                          placeholder="Brief summary (max 160 characters)..." required>{{ old('excerpt') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">This will be displayed as preview on the site</p>
                @error('excerpt')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category & Featured Image --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" class="input-admin @error('category') error @enderror" required>
                        <option value="">Select Category</option>
                        <option value="Moisturizing" {{ old('category') === 'Moisturizing' ? 'selected' : '' }}>Moisturizing</option>
                        <option value="Anti-Aging" {{ old('category') === 'Anti-Aging' ? 'selected' : '' }}>Anti-Aging</option>
                        <option value="Acne" {{ old('category') === 'Acne' ? 'selected' : '' }}>Acne Care</option>
                        <option value="Sensitive" {{ old('category') === 'Sensitive' ? 'selected' : '' }}>Sensitive Skin</option>
                        <option value="Treatment" {{ old('category') === 'Treatment' ? 'selected' : '' }}>Treatment</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                    <input type="file" name="thumbnail" class="input-admin @error('thumbnail') error @enderror" 
                           accept="image/*">
                    <p class="text-gray-500 text-xs mt-1">Recommended: 800x400px</p>
                    @error('thumbnail')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Author & Reading Time --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Author Name *</label>
                    <input type="text" name="author" class="input-admin @error('author') error @enderror" 
                           placeholder="e.g., Dr. Skin Expert" 
                           value="{{ old('author') }}"
                           required>
                    @error('author')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reading Time (minutes)</label>
                    <input type="number" name="reading_time" class="input-admin @error('reading_time') error @enderror" 
                           placeholder="5" 
                           value="{{ old('reading_time', 5) }}"
                           min="1">
                    @error('reading_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Content --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Article Content *</label>
                <textarea name="content" rows="12" class="input-admin @error('content') error @enderror font-mono text-sm" 
                          placeholder="Write your article content here... (Supports Markdown)" required>{{ old('content') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">💡 Markdown is supported. Use # for headings, ** for bold, etc.</p>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SEO Meta --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description (SEO)</label>
                    <input type="text" name="meta_description" class="input-admin @error('meta_description') error @enderror" 
                           placeholder="Keep under 160 characters" 
                           value="{{ old('meta_description') }}"
                           maxlength="160">
                    @error('meta_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords (SEO)</label>
                    <input type="text" name="meta_keywords" class="input-admin @error('meta_keywords') error @enderror" 
                           placeholder="skincare, tips, beauty" 
                           value="{{ old('meta_keywords') }}">
                    @error('meta_keywords')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Publish Settings --}}
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="is_published" name="is_published" value="1" 
                       class="w-4 h-4 text-[var(--dark-brown)] border-gray-300 rounded cursor-pointer"
                       {{ old('is_published') ? 'checked' : '' }}>
                <label for="is_published" class="ml-2 text-sm text-gray-700">Publish immediately</label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-4">
            <button type="submit" class="btn-primary-admin">
                ✅ Publish Article
            </button>
            <a href="{{ route('admin.skin-guide.index') }}" class="btn-secondary-admin">
                ❌ Cancel
            </a>
        </div>
    </form>
</div>
@endsection
