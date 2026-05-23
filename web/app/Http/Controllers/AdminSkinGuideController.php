<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSkinGuideController extends Controller
{
    /**
     * Display all articles
     */
    public function index(Request $request)
    {
        $query = Article::query();

        // Search title & category
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(category) LIKE ?', ["%{$search}%"]);
            });
        }

        // Filter category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter publish status
        if ($request->has('is_published')) {
            $query->where('is_published', filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN));
        }

        $articles = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Data articles berhasil diambil',
            'data' => $articles
        ]);
    }

    /**
     * Store article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|string',
            'category' => 'required|string|max:255',
            'is_published' => 'required|boolean',
        ]);

        // Generate slug unik
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article berhasil ditambahkan',
            'data' => $article
        ], 201);
    }

    /**
     * Show detail article
     */
    public function show(Article $article)
    {
        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    /**
     * Update article
     */
   public function update(Request $request, $id)
{
    // Cari article berdasarkan ID
    $article = Article::find($id);

    // Jika article tidak ditemukan
    if (!$article) {
        return response()->json([
            'success' => false,
            'message' => 'Article tidak ditemukan'
        ], 404);
    }

    // Validasi request
    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'content' => 'sometimes|required|string',
        'image_url' => 'nullable|string',
        'category' => 'sometimes|required|string|max:255',
        'is_published' => 'sometimes|required|boolean',
    ]);

    // Update slug jika title berubah
    if (isset($validated['title'])) {

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;

        while (
            Article::where('slug', $slug)
                ->where('id', '!=', $article->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;
    }

    // Update data
    $article->update($validated);

    // Refresh data terbaru
    $article->refresh();

    return response()->json([
        'success' => true,
        'message' => 'Article berhasil diupdate',
        'data' => $article
    ], 200);
}

    /**
     * Delete article
     */
    public function destroy($id)
{
    // Cari article berdasarkan ID
    $article = Article::find($id);

    // Jika tidak ditemukan
    if (!$article) {
        return response()->json([
            'success' => false,
            'message' => 'Article tidak ditemukan'
        ], 404);
    }

    // Hapus article
    $article->delete();

    return response()->json([
        'success' => true,
        'message' => 'Article berhasil dihapus'
    ], 200);
}
}