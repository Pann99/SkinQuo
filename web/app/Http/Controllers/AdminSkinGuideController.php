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
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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


       if (!$request->filled('image_url') && !$request->hasFile('image_file')) {
    return back()
        ->withErrors([
            'image_url' => 'Upload gambar atau masukkan URL gambar.'
        ])
        ->withInput();
}

if ($request->hasFile('image_file')) {

    $path = $request->file('image_file')
        ->store('skin-guide', 'public');

    $validated['image_url'] = asset('storage/' . $path);
}

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
   public function edit($id)
{
    $article = Article::with('tags')->findOrFail($id);

    $tags = Tag::all();
    $selectedTagIds = $article->tags->pluck('id')->toArray();

    return view(
        'admin.skin-guide.edit',
        compact('article', 'tags', 'selectedTagIds')
    );
}

    /**
     * Update article with tags
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $article->id,
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            // Tambahkan validasi untuk file gambar lokal
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', 
            'category' => 'required|string|max:255',
            'is_published' => 'required|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        // Cek jika ada upload gambar dari lokal
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('skin-guide', 'public');
            // Konversi file lokal menjadi URL yang bisa diakses (tersimpan di DB)
            $validated['image_url'] = asset('storage/' . $path);
        }

        // Hapus array image_file agar tidak terjadi error saat update ke database
        unset($validated['image_file']);

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
public function destroy($id)
{
    $article = Article::findOrFail($id);

    $article->tags()->detach();
    $article->delete();

    return redirect()
        ->route('admin.skin-guide.index')
        ->with('success', 'Skin Guide berhasil dihapus!');
}
}