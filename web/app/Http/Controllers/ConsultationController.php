<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    private $aiServiceUrl;

    public function __construct()
    {
        $this->aiServiceUrl = config('services.ai.url');

        if (empty($this->aiServiceUrl)) {
            Log::emergency('Critical Config Missing: services.ai.url is undefined.');
            abort(500, 'Terjadi kesalahan pada konfigurasi koneksi internal sistem.');
        }
    }

    public function index()
    {
        try {
            $allKeywords = DB::table('validation_keywords')->get();

            $wizardSkinTypes = $allKeywords->where('category', 'skin_type')
                ->map(function($k) {
                    return (object) ['keyword' => ucwords(trim($k->keyword)), 'category' => 'skin_type'];
                })->unique('keyword')->values();

            $wizardProblems = $allKeywords->where('category', 'problem')
                ->map(function($k) {
                    return (object) ['keyword' => ucwords(trim($k->keyword)), 'category' => 'problem'];
                })->unique('keyword')->values();

            $wizardProducts = $allKeywords->where('category', 'product')
                ->map(function($k) {
                    return (object) ['keyword' => ucwords(trim($k->keyword)), 'category' => 'product'];
                })->unique('keyword')->values();

            return view('pages.consultation', compact('wizardSkinTypes', 'wizardProblems', 'wizardProducts'));

        } catch (\Exception $e) {
            Log::error('Gagal memuat keyword: ' . $e->getMessage());
            return view('pages.consultation', [
                'wizardSkinTypes' => collect([]),
                'wizardProblems'  => collect([]),
                'wizardProducts'  => collect([])
            ]);
        }
    }

    private function generateSkinProfileChart($concerns, $topProduct = null)
    {
        $mapping = [
            'Kulit Kering'         => 'Hydration',
            'Dehidrasi'            => 'Hydration',
            'Kulit Normal'         => 'Hydration',
            'Kulit Berminyak'      => 'Sebum Control',
            'Komedo'               => 'Sebum Control',
            'Pori-Pori Besar'      => 'Sebum Control',
            'Kulit Kombinasi'      => 'Sebum Control',
            'Jerawat'              => 'Acne',
            'Bekas Jerawat / Luka' => 'Acne',
            'Kulit Sensitif'       => 'Soothing',
            'Kemerahan / Iritasi'  => 'Soothing',
            'Kulit Kusam'          => 'Brightening',
            'Flek Hitam'           => 'Brightening',
            'Kerutan / Penuaan'    => 'Anti-Aging'
        ];

        $userScores = [
            'Hydration'     => 20,
            'Sebum Control' => 20,
            'Acne'          => 20,
            'Soothing'      => 20,
            'Brightening'   => 20,
            'Anti-Aging'    => 20,
        ];

        foreach ($concerns as $concern) {
            if (isset($mapping[$concern])) {
                $userScores[$mapping[$concern]] = 90; 
            }
        }

        $productScores = [
            'Hydration'     => 20, 
            'Sebum Control' => 20, 
            'Acne'          => 20,
            'Soothing'      => 20, 
            'Brightening'   => 20, 
            'Anti-Aging'    => 20,
        ];

        if ($topProduct) {
            $matchedIngredients = $topProduct['reasoning_meta']['matched_ingredients'] ?? [];
            
            $ingMapping = [
                'Hyaluronic Acid'   => 'Hydration', 
                'Ceramide'          => 'Hydration', 
                'Glycerin'          => 'Hydration',
                'Salicylic Acid'    => 'Sebum Control', 
                'Bha'               => 'Sebum Control', 
                'Aha'               => 'Sebum Control', 
                'Zinc'              => 'Sebum Control',
                'Tea Tree'          => 'Acne', 
                'Centella Asiatica' => 'Soothing', 
                'Panthenol'         => 'Soothing', 
                'Mugwort'           => 'Soothing',
                'Vitamin C'         => 'Brightening', 
                'Niacinamide'       => 'Brightening', 
                'Alpha Arbutin'     => 'Brightening', 
                'Licorice'          => 'Brightening',
                'Retinol'           => 'Anti-Aging', 
                'Peptide'           => 'Anti-Aging', 
                'Collagen'          => 'Anti-Aging', 
                'Bakuchiol'         => 'Anti-Aging'
            ];

            foreach ($matchedIngredients as $ing) {
                foreach ($ingMapping as $key => $dim) {
                    if (stripos($ing, $key) !== false) {
                        $productScores[$dim] = 95; 
                    }
                }
            }

            if (count(array_unique($productScores)) === 1) {
                foreach ($userScores as $dim => $val) {
                    if ($val > 20) $productScores[$dim] = 85;
                }
            }
        }

        return [
            'labels' => array_keys($userScores), 
            'datasets' => [
                [
                    'label'           => 'Kebutuhan Kulitmu',
                    'data'            => array_values($userScores),
                    'backgroundColor' => 'rgba(205, 133, 63, 0.2)', 
                    'borderColor'     => 'rgba(205, 133, 63, 1)',
                    'borderWidth'     => 2
                ],
                [
                    'label'           => 'Fokus Formula Produk',
                    'data'            => array_values($productScores),
                    'backgroundColor' => 'rgba(74, 85, 104, 0.15)', 
                    'borderColor'     => 'rgba(74, 85, 104, 1)',
                    'borderWidth'     => 1.5
                ]
            ]
        ];
    }

    public function sendConsultation(Request $request)
    {
        try {
            $validated = $request->validate([
                'query'          => ['required', 'string', 'min:5', 'max:500'],
                'original_query' => ['nullable', 'string', 'max:500'],
                'harga_max'      => ['nullable', 'numeric', 'min:0'],
            ]);

            $originalQuery = $validated['original_query'] ?? $validated['query'];
            $isGuest = !Auth::check();
            $userId = Auth::id() ?? 0;

            if ($isGuest) {
                $cacheKey = 'guest_consultation_limit:' . $request->ip();
                $requestCount = cache()->get($cacheKey, 0);

                if ($requestCount >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Batas konsultasi gratis telah habis. Silakan Login!'
                    ], 429);
                }
                cache()->put($cacheKey, $requestCount + 1, now()->endOfDay());
            }

            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($this->aiServiceUrl . '/api/recommend', [
                'query'     => $validated['query'],
                'harga_max' => $validated['harga_max'] ?? null
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $errorMsg = $statusCode === 422 ? 'Query ditolak oleh AI: ' . $response->body() : 'Gagal mendapatkan respons AI.';
                Log::error("AI Service Error [$statusCode]: " . $response->body());
                return response()->json(['success' => false, 'message' => $errorMsg], $statusCode >= 500 ? 500 : 422);
            }

            $aiData = $response->json();

            if (isset($aiData['status']) && $aiData['status'] === 'invalid') {
                return response()->json([
                    'success' => false,
                    'status'  => 'invalid',
                    'message' => $aiData['message'] ?? 'Query tidak valid.',
                    'missing_points' => $aiData['missing_points'] ?? []
                ], 422);
            }

            $recommendations = $aiData['recommendations'] ?? [];
            $cleanedQuery    = $aiData['cleaned_query'] ?? '';
            $displayExplainability = $aiData['display_explainability'] ?? [];

            $displayIngredients = $displayExplainability['Kandungan Aktif'] ?? [];
            $displaySkinTypes   = $displayExplainability['Jenis/Tipe Kulit'] ?? [];
            $displayProblems    = $displayExplainability['Keluhan Kulit'] ?? [];

            $concerns = array_unique(array_merge($displaySkinTypes, $displayProblems));
            
            $topProduct = $recommendations[0] ?? null;
            $chartData  = $this->generateSkinProfileChart($concerns, $topProduct);

            $ingredientResultPayload = [
                'original_query'         => $originalQuery,
                'cleaned_query'          => $cleanedQuery,
                'display_explainability' => $displayExplainability, 
                'all_products'           => $recommendations,
                'related_articles'       => $aiData['related_articles'] ?? [],
                'skin_profile_chart'     => $chartData 
            ];

            $consultationId = null;

            if (!$isGuest) {
                $consultationId = DB::table('consultations')->insertGetId([
                    'user_id'               => $userId,
                    'raw_query'             => $originalQuery,
                    'cleaned_query'         => $cleanedQuery,
                    'extracted_concerns'    => json_encode($concerns),
                    'extracted_ingredients' => json_encode($displayIngredients),
                    'user_budget'           => $validated['harga_max'] ?? null,
                    'ai_response'           => json_encode($ingredientResultPayload),
                    'created_at'            => now()
                ]);

                $detailData = [];
                foreach ($recommendations as $item) {
                    $detailData[] = [
                        'consultation_id'  => $consultationId,
                        'product_id'       => $item['product_id'] ?? 0,
                        'rank_position'    => $item['rank'] ?? 1,
                        'similarity_score' => $item['similarity_score'] ?? 0,
                        'reasoning_code'   => $item['reasoning_meta']['reason_code'] ?? 'SAW_DYNAMIC',
                        'created_at'       => now()
                    ];
                }
                if (!empty($detailData)) {
                    DB::table('consultation_results')->insert($detailData);
                }
            } else {
                $guestResultData = [
                    'created_at'  => now()->toIso8601String(),
                    'ai_response' => json_encode($ingredientResultPayload)
                ];
                $consultationId = 'guest_' . Str::random(40);
                session()->put($consultationId, $guestResultData);
            }

            return response()->json([
                'success'         => true,
                'consultation_id' => $consultationId,
                'status'          => $aiData['status'] ?? 'success'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Consultation send error: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Terjadi masalah internal server.'], 500);
        }
    }

    public function getHistory()
    {
        try {
            $userId = Auth::id();
            if (!$userId) return response()->json(['success' => true, 'data' => []]);

            $history = DB::table('consultations')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedHistory = $history->map(function ($item) {
                $item->skin_concern = json_decode($item->extracted_concerns ?? $item->skin_concern ?? '[]');
                $item->ingredient_result = json_decode($item->ai_response ?? $item->ingredient_result ?? '{}');

                $item->product_results = DB::table('consultation_results')
                    ->where('consultation_id', $item->id)
                    ->orderBy('rank_position', 'asc')
                    ->get();

                return $item;
            });

            return response()->json(['success' => true, 'data' => $formattedHistory]);
        } catch (\Exception $e) {
            Log::error('Get History Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat riwayat.'], 500);
        }
    }

    public function result($id)
    {
        if (is_string($id) && str_starts_with($id, 'guest_')) {
            if (!session()->has($id)) abort(404, 'Sesi tamu kedaluwarsa.');
            $sessionData = session()->get($id);
            
            $consultation = (object) [
                'id'          => $id,
                'user_id'     => null,
                'created_at'  => $sessionData['created_at'],
                'ai_response' => is_string($sessionData['ai_response']) ? $sessionData['ai_response'] : json_encode($sessionData['ai_response'])
            ];
            return view('pages.consultation-result', compact('consultation'));
        }

        $consultation = DB::table('consultations')->where('id', $id)->first();
        if (!$consultation) abort(404, 'Data tidak ditemukan.');
        if (Auth::check() && $consultation->user_id !== Auth::id()) abort(403, 'Akses ditolak.');

        return view('pages.consultation-result', compact('consultation'));
    }

    public function analyze(Request $request)
    {
        try {
            $validated = $request->validate([
                'query' => ['required', 'string', 'min:5', 'max:500'],
            ]);

            $query = trim($validated['query']);
            $allKeywords = DB::table('validation_keywords')->pluck('keyword')->map(fn($k) => mb_strtolower(trim($k)))->toArray();
            $queryLower   = mb_strtolower($query);
            $foundKeyword = collect($allKeywords)->first(fn($kw) => str_contains($queryLower, $kw));

            if (!$foundKeyword) {
                return response()->json([
                    'success' => false, 'status' => 'invalid',
                    'message' => 'Query tidak mengandung kata kunci skincare yang dikenali.',
                ], 422);
            }
            return response()->json(['success' => true, 'status'  => 'valid']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi masalah internal.'], 500);
        }
    }
}