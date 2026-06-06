<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Tampilkan halaman daftar feedback (untuk admin).
     */
    public function index()
    {
        // Dummy feedback data untuk demo
        $feedbackItems = [
            [
                'id' => 1,
                'name' => 'Anisa Pratiwi',
                'avatar' => null,
                'rating' => 5,
                'date' => '12 Januari 2025',
                'feedback' => 'Konsultasi skincare SkinQuo sangat membantu! Hasilnya akurat dan rekomendasi produknya cocok untuk kulit kombinasi saya.',
                'helpful_count' => 24,
                'verified' => true,
            ],
            [
                'id' => 2,
                'name' => 'Dina Kusuma',
                'avatar' => null,
                'rating' => 5,
                'date' => '10 Januari 2025',
                'feedback' => 'User interface-nya sangat user-friendly. Proses konsultasi cepat dan hasilnya detail dengan rekomendasi yang mudah diikuti.',
                'helpful_count' => 18,
                'verified' => true,
            ],
            [
                'id' => 3,
                'name' => 'Budi Santoso',
                'avatar' => null,
                'rating' => 4,
                'date' => '08 Januari 2025',
                'feedback' => 'Fitur artikel skincare sangat informatif. Saya jadi lebih paham tentang rutinitas perawatan yang tepat untuk tipe kulit saya.',
                'helpful_count' => 15,
                'verified' => true,
            ],
            [
                'id' => 4,
                'name' => 'Eka Wijaya',
                'avatar' => null,
                'rating' => 5,
                'date' => '05 Januari 2025',
                'feedback' => 'Rekomendasi produk yang diberikan terjangkau namun berkualitas. Kulit saya lebih sehat setelah mengikuti saran dari SkinQuo.',
                'helpful_count' => 32,
                'verified' => true,
            ],
            [
                'id' => 5,
                'name' => 'Fiona Maharani',
                'avatar' => null,
                'rating' => 5,
                'date' => '02 Januari 2025',
                'feedback' => 'Fitur tracking progress skincare sangat membantu. Saya bisa melihat perkembangan kulit saya dari waktu ke waktu dengan jelas.',
                'helpful_count' => 28,
                'verified' => true,
            ],
            [
                'id' => 6,
                'name' => 'Gita Ayu',
                'avatar' => null,
                'rating' => 4,
                'date' => '30 Desember 2024',
                'feedback' => 'SkinQuo membantu saya menemukan produk yang tepat. Customer service mereka juga sangat responsif dan membantu setiap pertanyaan.',
                'helpful_count' => 21,
                'verified' => true,
            ],
        ];

        return view('pages.feedback', compact('feedbackItems'));
    }

    /**
     * Simpan feedback baru dari halaman homepage.
     * 
     * Alur:
     * - Feedback dari homepage (guest atau auth user)
     * - consultation_id = NULL (tidak terkait konsultasi apapun)
     * - user_id = auth()->id() jika login, NULL jika guest
     * 
     * Route: POST /feedback
     * Name: feedback.store
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'text' => ['required', 'string', 'min:10', 'max:1000'],
                'rating' => ['required', 'numeric', 'between:1,5'],
            ]);

            // Simpan feedback ke database
            Feedback::create([
                'user_id' => Auth::id(),   // NULL jika guest, user_id jika login
                'text' => $validated['text'],
                'rating' => (float) $validated['rating'],
            ]);

            return redirect()->back()->with('feedback_success', 
                'Thank you! Your feedback has been received.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return ke halaman sebelumnya dengan error
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Feedback store error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => 'Failed to save feedback. Please try again.']);
        }
    }
}

