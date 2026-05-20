<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * Display list of published articles (Skin Guide catalog).
     * Supports filtering by category and search functionality.
     */
    public function index(Request $request)
    {
        try {
            // Get search query and category filter from request
            $searchQuery = $request->input('search', '');
            $selectedCategory = $request->input('category', '');

            // Build base query with eager loading to avoid N+1 problem
            $articlesQuery = Article::with('tags')
                ->published()
                ->orderBy('created_at', 'desc');

            // Apply search filter (title, content, category) - use LIKE for cross-database compatibility
            if (!empty($searchQuery)) {
                $articlesQuery->where(function ($q) use ($searchQuery) {
                    $q->where('title', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('content', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('category', 'LIKE', "%{$searchQuery}%");
                });
            }

            // Apply category filter
            if (!empty($selectedCategory)) {
                $articlesQuery->where('category', '=', $selectedCategory);
            }

            // Get all filtered articles
            $allArticles = $articlesQuery->get();

            // Get distinct categories for filter buttons
            $categories = Article::published()
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->filter(fn($cat) => !empty($cat))
                ->values();

            // Data splitting: featured (first article) + remaining (rest)
            $featuredPost = $allArticles->first();
            $remainingPosts = $allArticles->skip(1);

            return view('pages.skin-guide', compact(
                'featuredPost',
                'remainingPosts',
                'categories',
                'searchQuery',
                'selectedCategory'
            ));
        } catch (\Exception $e) {
            Log::error('Skin Guide Index Error: ' . $e->getMessage());
            return view('pages.skin-guide', [
                'featuredPost' => null,
                'remainingPosts' => collect(),
                'categories' => collect(),
                'searchQuery' => '',
                'selectedCategory' => ''
            ]);
        }
    }

    /**
     * Display detail page for a single article.
     * Shows article content with related articles, latest articles, and popular tags.
     */
    public function show($slug)
    {
        try {
            // Fetch article by slug with eager loaded relations
            $article = Article::with('tags')
                ->where('slug', $slug)
                ->published()
                ->firstOrFail();

            // Calculate word count and reading time
            $content = $article->content ?? '';
            $wordCount = str_word_count(strip_tags($content));
            $readingTime = max(1, (int) ceil($wordCount / 200));

            // Get related articles: same category OR shared tags (exclude current article)
            $relatedArticles = Article::with('tags')
                ->published()
                ->where('id', '!=', $article->id)
                ->where(function ($query) use ($article) {
                    // Same category
                    $query->where('category', $article->category);
                    
                    // OR shared tags
                    if ($article->tags->isNotEmpty()) {
                        $tagIds = $article->tags->pluck('id')->toArray();
                        $query->orWhereHas('tags', function ($q) use ($tagIds) {
                            $q->whereIn('tags.id', $tagIds);
                        });
                    }
                })
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            // Get latest articles (exclude current article)
            $latestArticles = Article::with('tags')
                ->published()
                ->where('id', '!=', $article->id)
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get();

            // Get popular tags (ordered by article count)
            $popularTags = Tag::popular()
                ->limit(8)
                ->get();

            return view('pages.article-detail', compact(
                'article',
                'readingTime',
                'relatedArticles',
                'latestArticles',
                'popularTags'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Article not found');
        } catch (\Exception $e) {
            Log::error('Article Detail Error: ' . $e->getMessage());
            abort(500, 'Error loading article');
        }
    }
}


