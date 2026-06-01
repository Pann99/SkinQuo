<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSkinGuideController extends Controller
{
    /**
     * Display all articles with stats
     */
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(category) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(content) LIKE ?', ["%{$search}%"]);
            });
        }

        $articles = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate stats
        $totalArticles = Article::count();
        $publishedArticles = Article::where('is_published', true)->count();
        $draftCount = Article::where('is_published', false)->count();
        $totalTags = Tag::count();

        return view('admin.skin-guide.index', compact('articles', 'totalArticles', 'publishedArticles', 'draftCount', 'totalTags'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $tags = Tag::all();
        return view('admin.skin-guide.create', compact('tags'));
    }

    /**
     * Store new article with tags
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'category' => 'required|string|max:255',
            'is_published' => 'required|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        // Generate slug unik jika duplikat
        $slug = $validated['slug'];
        $originalSlug = $slug;
        $count = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        // Simpan artikel
        $article = Article::create($validated);

        // Simpan tags
        $tagIds = $validated['tags'] ?? [];
        if (!empty($tagIds)) {
            $article->tags()->attach($tagIds);
        }

        return redirect()
            ->route('admin.skin-guide.index')
            ->with('success', 'Skin Guide berhasil dibuat!');
    }

    /**
     * Show edit form
     */
    public function edit(Article $article)
    {
        $tags = Tag::all();
        $selectedTagIds = $article->tags->pluck('id')->toArray();
        return view('admin.skin-guide.edit', compact('article', 'tags', 'selectedTagIds'));
    }

    /**
     * Update article with tags
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $article->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'category' => 'required|string|max:255',
            'is_published' => 'required|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        // Update slug jika berubah
        if ($validated['slug'] !== $article->slug) {
            $slug = $validated['slug'];
            $originalSlug = $slug;
            $count = 1;

            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $validated['slug'] = $slug;
        }

        // Update article
        $article->update($validated);

        // Update tags
        $tagIds = $validated['tags'] ?? [];
        $article->tags()->sync($tagIds);

        return redirect()
            ->route('admin.skin-guide.index')
            ->with('success', 'Skin Guide berhasil diperbarui!');
    }

    /**
     * Delete article and related tags
     */
    public function destroy(Article $article)
    {
        // Detach all tags (cascade handled by database if configured, but explicit here)
        $article->tags()->detach();

        // Delete article
        $article->delete();

        return redirect()
            ->route('admin.skin-guide.index')
            ->with('success', 'Skin Guide berhasil dihapus!');
    }
}