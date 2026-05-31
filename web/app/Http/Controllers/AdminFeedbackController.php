<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * AdminFeedbackController
 * 
 * Handles feedback monitoring and management in admin panel
 * Professional feedback monitoring dashboard for SkinQuo
 * 
 * @package App\Http\Controllers
 */
class AdminFeedbackController extends Controller
{
    /**
     * Display feedback monitoring dashboard with filters and search
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function monitor(Request $request)
    {
        // Get filter parameters from request
        $searchQuery = $request->get('q', '');
        $filterRating = $request->get('rating', '');
        $filterStatus = $request->get('status', ''); // 'pending', 'reviewed', or ''
        $perPage = 15;

        // Build query with relationships
        $query = Feedback::with(['user', 'consultation'])
            ->orderByDesc('id'); // Order by ID DESC since no created_at

        // Search across username, email, and feedback text
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('feedback.text', 'ilike', "%{$searchQuery}%")
                  ->orWhereHas('user', function ($userQuery) use ($searchQuery) {
                      $userQuery->where('username', 'ilike', "%{$searchQuery}%")
                                ->orWhere('email', 'ilike', "%{$searchQuery}%");
                  });
            });
        }

        // Filter by rating (1-5 stars)
        if ($filterRating && $filterRating !== '') {
            $query->where('rating', (int)$filterRating);
        }

        // Check if is_reviewed column exists
        $hasIsReviewedColumn = Schema::hasColumn('feedback', 'is_reviewed');

        // Filter by review status (only if column exists)
        if ($hasIsReviewedColumn) {
            if ($filterStatus === 'pending') {
                $query->where('is_reviewed', false);
            } elseif ($filterStatus === 'reviewed') {
                $query->where('is_reviewed', true);
            }
        }

        // Get stats
        $totalFeedback = Feedback::count();
        $pendingReview = 0;

        if ($hasIsReviewedColumn) {
            try {
                $pendingReview = Feedback::where('is_reviewed', false)->count();
            } catch (\Exception $e) {
                $pendingReview = 0;
            }
        }

        // Paginate
        $feedback = $query->paginate($perPage);

        $stats = [
            'total' => $totalFeedback,
            'pending' => $pendingReview,
        ];

        return view('admin.feedback.monitor', compact(
            'feedback',
            'stats',
            'searchQuery',
            'filterRating',
            'filterStatus',
            'hasIsReviewedColumn'
        ));
    }

    /**
     * Get single feedback detail as JSON
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $feedback = Feedback::with(['user', 'consultation'])->findOrFail($id);
            return response()->json($feedback);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Feedback not found'], 404);
        }
    }

    /**
     * Mark feedback as reviewed
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsReviewed($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->is_reviewed = true;
            $feedback->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Feedback sudah ditandai sebagai ditinjau',
                'is_reviewed' => $feedback->is_reviewed
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai feedback'
            ], 500);
        }
    }

    /**
     * Delete feedback
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus feedback'
            ], 500);
        }
    }
}
