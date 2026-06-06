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
        // KEAMANAN: Mengambil URL dari konfigurasi internal Laravel (config/services.php)
        $this->aiServiceUrl = config('services.ai.url');

        // KEAMANAN: Jika konfigurasi AI service URL kosong atau tidak ditemukan
        if (empty($this->aiServiceUrl)) {
            Log::emergency('Critical Config Missing: services.ai.url is undefined. System halted to prevent routing leak.');
            abort(500, 'Terjadi kesalahan pada konfigurasi koneksi internal sistem.');
        }
    }

    /**
     * Tampilkan halaman konsultasi utama.
     */
    public function index()
    {
        return view('pages.consultation');
    }
    /**
     * Integrasi Utama: Kirim query ke FastAPI, tangani Guest/User + Rate Limiting, dan filter Artikel.
     */
    public function sendConsultation(Request $request)
    {
        try {
            $validated = $request->validate([
                'query' => ['required', 'string', 'min:5', 'max:500']
            ]);

            // 1. CEK OTENTIKASI & IDENTITAS (USER ATAU GUEST)
            $isGuest = !Auth::check();
            $userId = Auth::id(); // Otomatis null jika tamu

            // 2. PEMBATASAN (RATE LIMITING)
            if ($isGuest) {
                // Gunakan IP Address sebagai pengenal unik tamu di cache
                $cacheKey = 'guest_consultation_limit:' . $request->ip();
                $requestCount = cache()->get($cacheKey, 0);

                if ($requestCount >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Batas konsultasi gratis telah habis (Maksimal 3 kali per hari). Silakan Login untuk menikmati fitur tanpa batas!'
                    ], 429); // 429 Too Many Requests
                }

                // Tambah hitungan kueri di cache, simpan hingga akhir hari (tengah malam)
                cache()->put($cacheKey, $requestCount + 1, now()->endOfDay());
            }

            // 3. KIRIM PERMINTAAN DATA KE FASTAPI (HUGGING FACE)
            // Dikeluarkan dari DB::transaction agar koneksi database tidak menggantung saat menunggu AI
            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($this->aiServiceUrl . '/api/recommend', [
                'query' => $validated['query']
            ]);

            if ($response->failed()) {
                Log::error('AI Service Connection Failed on endpoint: ' . $this->aiServiceUrl . ' | Body: ' . $response->body());
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal mendapatkan respons data dari AI Service.'
                ], 500);
            }

            $aiData = $response->json();

            // Cek jika status validasi internal Python menyatakan "invalid"
            if (isset($aiData['status']) && $aiData['status'] === 'invalid') {
                return response()->json([
                    'success' => false,
                    'status'  => 'invalid',
                    'message' => $aiData['message'] ?? 'Query pencarian tidak valid.',
                    'missing_points' => $aiData['missing_points'] ?? []
                ], 422);
            }

            $recommendations = $aiData['recommendations'] ?? [];
            $topProductId = null;
            
            if (!empty($recommendations)) {
                $dbProduct = DB::table('products')
                    ->where('nama_produk', 'ILIKE', $recommendations[0]['product_name'])
                    ->first();
                $topProductId = $dbProduct ? $dbProduct->product_id : null;
            }

            // ====================================================================
            // ARTIKEL TERKAIT (DENGAN FIX ERROR 500 ARRAY PARSING)
            // ====================================================================
            $concerns = $aiData['extracted_concerns'] ?? [];
            $constraints = $aiData['extracted_constraints'] ?? [];
            $products = $aiData['extracted_products'] ?? [];
            
            // Ekstrak nama produk dari rekomendasi jika ada
            if (isset($aiData['recommendations']) && is_array($aiData['recommendations'])) {
                foreach ($aiData['recommendations'] as $rec) {
                    if (isset($rec['product_name'])) {
                        $products[] = $rec['product_name'];
                    }
                }
            }

            $rawKeywords = array_merge($concerns, $constraints, $products);
            $searchKeywords = [];

            // AMAN DARI ERROR 500: Pastikan hanya data teks (string) yang diproses
            foreach ($rawKeywords as $item) {
                if (is_string($item) && trim($item) !== '') {
                    $searchKeywords[] = trim($item);
                } elseif (is_array($item) && isset($item['name']) && is_string($item['name'])) {
                    $searchKeywords[] = trim($item['name']);
                }
            }
            
            $articlesQuery = DB::table('articles');
            
            if (!empty($searchKeywords)) {
                $articlesQuery->where(function($q) use ($searchKeywords) {
                    foreach ($searchKeywords as $keyword) {
                        $q->orWhere('title', 'ILIKE', '%' . $keyword . '%');
                    }
                });
            } else {
                $articlesQuery->orderBy('id', 'desc');
            }
            
            $dbArticles = $articlesQuery->limit(3)->get();
            
            $relatedArticles = $dbArticles->map(function($art) {
                $contentStr = strip_tags($art->content ?? '');
                $wordCount = str_word_count($contentStr);
                $readTime = ceil($wordCount / 200);

                return [
                    'title'        => $art->title,
                    'url'          => url('/artikel/' . $art->slug),
                    'cover_image'  => $art->image_url,
                    'category'     => $art->category ?? 'Skincare Tips',
                    'excerpt'      => Str::limit($contentStr, 90),
                    'published_at' => 'Artikel Pilihan',
                    'read_time'    => ($readTime > 0 ? $readTime : 1) . ' min'
                ];
            })->toArray();
            // ====================================================================

            // 4. PENYIMPANAN DATA (USER VS GUEST)
            $consultationId = null;

            if (!$isGuest) {
                // User Login: Simpan ke Database
                $consultationId = DB::table('consultations')->insertGetId([
                    'user_id'           => $userId,
                    'product_result_id' => $topProductId,
                    'skin_concern'      => json_encode($aiData['extracted_concerns'] ?? []),
                    'ingredient_result' => json_encode([
                        'cleaned_query'      => $aiData['cleaned_query'] ?? '',
                        'constraints'        => $aiData['extracted_constraints'] ?? [],
                        'requested_products' => $aiData['extracted_products'] ?? [], 
                        'all_products'       => $recommendations,
                        'related_articles'   => $relatedArticles
                    ]),
                    'created_at'        => now(),
                    'updated_at'        => now()
                ]);
            } else {
                // Tamu (Guest): Simpan ke Session Sementara
                $guestResultData = [
                    'created_at'        => now()->toIso8601String(),
                    'skin_concern'      => $aiData['extracted_concerns'] ?? [],
                    'ingredient_result' => [
                        'cleaned_query'      => $aiData['cleaned_query'] ?? '',
                        'constraints'        => $aiData['extracted_constraints'] ?? [],
                        'requested_products' => $aiData['extracted_products'] ?? [], 
                        'all_products'       => $recommendations,
                        'related_articles'   => $relatedArticles
                    ]
                ];
                
                $consultationId = 'guest_' . Str::random(40);
                session()->put($consultationId, $guestResultData);
            }

            return response()->json([
                'success'               => true,
                'consultation_id'       => $consultationId,
                'status'                => $aiData['status'] ?? 'success',
                'cleaned_query'         => $aiData['cleaned_query'] ?? '',
                'extracted_products'    => $aiData['extracted_products'] ?? [],
                'extracted_concerns'    => $aiData['extracted_concerns'] ?? [],
                'extracted_constraints' => $aiData['extracted_constraints'] ?? [],
                'recommendations'       => $recommendations
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Consultation send error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi masalah internal pada server.'
            ], 500);
        }
    }

    /**
     * Ambil data history tracker konsultasi untuk user aktif.
     */
    public function getHistory()
    {
        try {
            $userId = Auth::id();

            // Tamu tidak punya history
            if (!$userId) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $history = DB::table('consultations')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedHistory = $history->map(function ($item) {
                $item->skin_concern = json_decode($item->skin_concern);
                $item->ingredient_result = json_decode($item->ingredient_result);
                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $formattedHistory
            ]);

        } catch (\Exception $e) {
            Log::error('Consultation history error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal memuat daftar riwayat.'
            ], 500);
        }
    }

    /**
     * Tampilkan halaman hasil konsultasi berdasarkan ID (Database / Session Guest)
     */
    public function result($id)
    {
        // 1. PENANGANAN TAMU (GUEST)
        if (is_string($id) && str_starts_with($id, 'guest_')) {
            if (!session()->has($id)) {
                abort(404, 'Sesi konsultasi tamu telah kedaluwarsa.');
            }

            $sessionData = session()->get($id);
            
            // Palsukan bentuk object agar Blade Result tidak error
            $consultation = (object) [
                'id'                => $id,
                'user_id'           => null,
                'created_at'        => $sessionData['created_at'],
                'skin_concern'      => json_encode($sessionData['skin_concern']),
                'ingredient_result' => json_encode($sessionData['ingredient_result'])
            ];

            return view('pages.consultation-result', compact('consultation'));
        }

        // 2. PENANGANAN USER LOGIN (DATABASE)
        $consultation = DB::table('consultations')->where('id', $id)->first();
        
        if (!$consultation) {
            abort(404, 'Data konsultasi tidak ditemukan.');
        }

        // Mencegah user login melihat hasil orang lain
        if (Auth::check() && $consultation->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('pages.consultation-result', compact('consultation'));
    }
}