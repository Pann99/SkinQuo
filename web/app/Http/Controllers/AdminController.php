<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Article;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * AdminController
 * 
 * Handles admin dashboard and main admin operations
 * 
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Show admin dashboard with real-time statistics from Supabase
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        try {
            // Fetch dashboard statistics from Supabase
            $totalProducts   = Product::count();
            $totalArticles   = Article::where('is_published', true)->count();
            $totalFeedback   = Feedback::count();
            $pendingFeedback = Feedback::where('is_reviewed', false)->count(); // tambahkan ini
            $totalUsers      = User::count();
            
            // Fetch latest 3 feedbacks with user information
            $feedbacks = Feedback::with('user')
                ->orderByDesc('id')  // Assuming newest records have highest ID, adjust if table has created_at
                ->limit(3)
                ->get()
                ->map(function ($fb) {
                    return [
                        'name' => $fb->user?->username ?? 'Anonymous',
                        'text' => $fb->text ?? '-',
                        'rating' => $fb->rating ?? null,
                        'created_at' => $fb->created_at ?? null,
                        'id' => $fb->id,
                    ];
                });
            
            // Reverse to show newest first
            $feedbacks = $feedbacks->reverse();
            
                    } catch (\Exception $e) {
                    $totalProducts   = 0;
                    $totalArticles   = 0;
                    $totalFeedback   = 0;
                    $totalUsers      = 0;
                    $pendingFeedback = 0; // tambahkan ini
                    $feedbacks       = collect([]);

                    Log::error('Dashboard data fetch failed: ' . $e->getMessage());
                }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalArticles',
            'totalFeedback',
            'totalUsers',
            'feedbacks',
            'pendingFeedback',
        ));
    }
}
