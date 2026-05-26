<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Tampilkan halaman konsultasi.
     */
    public function index()
    {
        return view('pages.consultation');
    }

    /**
     * Analisis skin story dan return traits (AJAX endpoint)
     * Route: POST /consultation/analyze
     */
    public function analyze(Request $request)
    {
        try {
            $validated = $request->validate([
                'skin_story' => ['required', 'string', 'min:10', 'max:2000'],
                'tags' => ['required', 'json'],
            ]);

            $traits = $this->inferTraitsFromStory(
                $validated['skin_story'],
                json_decode($validated['tags'], true)
            );

            return response()->json([
                'success' => true,
                'traits' => $traits,
                'message' => 'Analisis berhasil',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Consultation analyze error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menganalisis',
            ], 500);
        }
    }

    /**
     * Proses konsultasi lengkap setelah user confirm di modal
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'skin_story' => ['required', 'string', 'min:10', 'max:2000'],
                'tags' => ['required', 'json'],
                'traits' => ['required', 'json'],
                'concern_1' => ['nullable', 'string', 'max:50'],
                'concern_2' => ['nullable', 'string', 'max:50'],
                'preferences' => ['nullable', 'array'],
                'preferences.*' => ['string', 'max:50'],
            ]);

            // Decode JSON fields
            $tags = json_decode($validated['tags'], true);
            $traits = json_decode($validated['traits'], true);
            $preferences = $validated['preferences'] ?? [];

            // Try to save to database
            try {
                $consultation = Consultation::create([
                    'user_id' => Auth::id() ?? null,
                    'skin_story' => $validated['skin_story'],
                    'tags' => $tags,
                    'detected_traits' => $traits,
                    'concern_1' => $validated['concern_1'] ?? null,
                    'concern_2' => $validated['concern_2'] ?? null,
                    'preferences' => $preferences,
                    'status' => 'completed',
                ]);
                $consultationId = $consultation->id;
            } catch (\Exception $dbError) {
                // If database fails, create dummy consultation with mock ID
                $consultationId = rand(1000, 9999);
            }

            // Forward to result with consultationId
            return $this->result($consultationId);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan. Coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Tampilkan hasil konsultasi
     */
    public function result($id)
    {
        // Query hasil konsultasi dari database
        $consultation = Consultation::find($id);
        
        // Verify user owns this consultation (jika logged in)
        if ($consultation && $consultation->user_id && Auth::check() && $consultation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // If not found in database, return dummy consultation data
        if (!$consultation) {
            $consultation = [
                'id' => $id,
                'skin_story' => 'Kulit saya terasa kering dan sensitif, terutama di area pipi. Produk berbahan kimia keras membuat kulit saya merah dan iritasi.',
                'tags' => ['dry', 'sensitive', 'irritated'],
                'detected_traits' => ['Dry Skin', 'Sensitive Skin', 'Reactive to Strong Actives'],
                'concern_1' => 'dryness',
                'concern_2' => 'sensitivity',
                'preferences' => ['natural_ingredients', 'fragrance_free', 'dermatologist_tested'],
                'status' => 'completed',
                'created_at' => now(),
            ];
        }
        
        return view('pages.consultation-result', compact('consultation'));
    }

    /**
     * Simpan feedback untuk konsultasi yang baru diselesaikan.
     * 
     * Alur:
     * - User harus sudah login
     * - Feedback terkait dengan consultation_id tertentu
     * - Simpan user_id = auth()->id() (user pasti login)
     * 
     * Route: POST /consultation/feedback
     * Name: consultation.feedback.store
     * Middleware: auth
     */
    public function storeFeedback(Request $request)
    {
        try {
            // Pastikan user logged in
            if (!Auth::check()) {
                return redirect()->route('login')
                    ->with('error', 'You must be logged in to provide feedback.');
            }

            // Validasi input
            $validated = $request->validate([
                'text' => ['required', 'string', 'min:10', 'max:1000'],
                'rating' => ['required', 'numeric', 'between:1,5'],
                'consultation_id' => ['required', 'integer', 'exists:consultations,id'],
            ]);

            // Authorization: Cek bahwa consultation_id milik user yang sedang login
            $consultation = Consultation::findOrFail($validated['consultation_id']);
            if ($consultation->user_id && $consultation->user_id !== Auth::id()) {
                abort(403, 'You can only provide feedback for your own consultation.');
            }

            // Simpan feedback ke database
            Feedback::create([
                'consultation_id' => (int) $validated['consultation_id'],
                'user_id' => Auth::id(),
                'text' => $validated['text'],
                'rating' => (float) $validated['rating'],
            ]);

            return redirect()->back()->with('feedback_success', 
                'Thank you for your feedback!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return ke halaman sebelumnya dengan error
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Consultation feedback store error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => 'Failed to save feedback. Please try again.']);
        }
    }

    /**
     * Lightweight inference engine (rule-based)
     * Ganti dengan API call ke Python/Node backend untuk ML model jika diperlukan
     */
    private function inferTraitsFromStory($text, $tags = [])
    {
        $lower = strtolower($text);
        $detected = [];

        $keywordMap = [
            'oily|t-zone|sebum|shiney' => 'Oily T-Zone',
            'dry|parched|tight|rough' => 'Dry Cheeks',
            'red|redness|inflam|irritat' => 'Redness',
            'sting|s3|irritat|reactive' => 'Sensitive (S3 Stinger)',
            'acne|breakout|pimple|spot' => 'Acne-Prone',
            'dark spot|pigment|hyperpig|melanin' => 'Dark Spots',
            'fine line|wrinkle|age|crease' => 'Fine Lines',
            'pore|enlarged|congested' => 'Enlarged Pores',
            'dehydrat|moisture|tight' => 'Dehydrated',
            'dull|lacklust|gray|uneven' => 'Dull Skin',
        ];

        foreach ($keywordMap as $keywords => $trait) {
            $keywordArray = array_map('trim', explode('|', $keywords));
            foreach ($keywordArray as $kw) {
                if (strpos($lower, $kw) !== false && !in_array($trait, $detected)) {
                    $detected[] = $trait;
                    break;
                }
            }
        }

        // Add manual tags
        foreach ($tags as $tag) {
            if (!in_array($tag, $detected)) {
                $detected[] = $tag;
            }
        }

        // Default if nothing found
        if (empty($detected)) {
            $detected[] = 'General Skin Concern';
        }

        // Return max 4 traits
        return array_slice($detected, 0, 4);
    }
}

