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
    $searchQuery  = $request->get('q', '');
    $filterRating = $request->get('rating', '');
    $filterStatus = $request->get('status', '');
    $perPage      = 15;

$hasIsReviewedColumn = \Illuminate\Support\Facades\DB::select("
    SELECT column_name 
    FROM information_schema.columns 
    WHERE table_name = 'feedback' 
    AND column_name = 'is_reviewed'
") ? true : false;
    $query = Feedback::with(['user'])->orderByDesc('created_at');

    if ($searchQuery) {
        $query->where(function ($q) use ($searchQuery) {
            $q->where('feedback.text', 'ilike', "%{$searchQuery}%")
              ->orWhereHas('user', function ($userQuery) use ($searchQuery) {
                  $userQuery->where('username', 'ilike', "%{$searchQuery}%")
                            ->orWhere('email', 'ilike', "%{$searchQuery}%");
              });
        });
    }

    if ($filterRating && $filterRating !== '') {
        $query->where('rating', (int) $filterRating);
    }

    if ($hasIsReviewedColumn) {
        if ($filterStatus === 'pending') {
            $query->where('is_reviewed', false);
        } elseif ($filterStatus === 'reviewed') {
            $query->where('is_reviewed', true);
        }
    }

    $pendingReview = 0;
    if ($hasIsReviewedColumn) {
        try {
            $pendingReview = Feedback::where('is_reviewed', false)->count();
        } catch (\Exception $e) {
            $pendingReview = 0;
        }
    }

    $feedback = $query->paginate($perPage);

    $stats = [
        'total'   => Feedback::count(),
        'pending' => $pendingReview,
    ];

    return view('admin.feedback.monitor', compact(
        'feedback',
        'stats',
        'searchQuery',
        'filterRating',
        'filterStatus',
        'hasIsReviewedColumn',
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
            $feedback = Feedback::with(['user'])->findOrFail($id);
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
            'success'     => true,
            'message'     => 'Feedback sudah ditandai sebagai ditinjau',
            'is_reviewed' => true,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menandai feedback',
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
