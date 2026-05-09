<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Tampilkan halaman feedback.
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
     * Simpan feedback baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        // Save to database jika sudah siap
        // Feedback::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih atas feedback Anda!',
        ]);
    }
}
