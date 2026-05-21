<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
     * Show admin dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // TODO: Fetch dashboard statistics
        // - Total products count
        // - Total articles count
        // - Pending feedback count
        // - Total users count
        // - Recent products list
        // - Recent articles list
        
        // Provide a small demo set of recent feedback so the dashboard can show real-like data
        $feedbacks = collect([
            [
                'name' => 'Anisa Pratiwi',
                'message' => 'Konsultasi skincare SkinQuo sangat membantu! Hasilnya akurat dan rekomendasi produknya cocok untuk kulit kombinasi saya.',
                'created_at' => Carbon::parse('2025-01-12'),
            ],
            [
                'name' => 'Dina Kusuma',
                'message' => 'User interface-nya sangat user-friendly. Proses konsultasi cepat dan hasilnya detail.',
                'created_at' => Carbon::parse('2025-01-10'),
            ],
            [
                'name' => 'Budi Santoso',
                'message' => 'Fitur artikel skincare sangat informatif. Saya jadi lebih paham tentang rutinitas perawatan saya.',
                'created_at' => Carbon::parse('2025-01-08'),
            ],
        ]);

        return view('admin.dashboard', compact('feedbacks'));
    }
}
