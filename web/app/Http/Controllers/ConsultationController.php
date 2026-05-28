<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str; // Tambahkan ini untuk fungsi Str::limit

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
     * Integrasi Utama: Kirim query ke FastAPI dan simpan hasil ke Supabase.
     */
    public function sendConsultation(Request $request)
    {
        try {
            $validated = $request->validate([
                'query' => ['required', 'string', 'min:5', 'max:200']
            ]);

            $userId = Auth::id() ?? 5;

            return DB::transaction(function () use ($validated, $userId) {
                
                // Kirim ke FastAPI dengan timeout 120 detik
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

                // Jika status validasi internal Python menyatakan "invalid"
                if (isset($aiData['status']) && $aiData['status'] === 'invalid') {
                    return response()->json([
                        'success' => false,
                        'status' => 'invalid',
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
                // FITUR BARU: CARI ARTIKEL TERKAIT BERDASARKAN KATA KUNCI AI
                // ====================================================================
                $concerns = $aiData['extracted_concerns'] ?? [];
                $constraints = $aiData['extracted_constraints'] ?? [];
                $products = $aiData['extracted_products'] ?? [];
                
                // Gabungkan semua kata kunci yang ditemukan AI
                $searchKeywords = array_filter(array_merge($concerns, $constraints, $products));
                
                $articlesQuery = DB::table('articles');
                
                if (!empty($searchKeywords)) {
                    $articlesQuery->where(function($q) use ($searchKeywords) {
                        foreach ($searchKeywords as $keyword) {
                            // Cari di kolom title (menggunakan ILIKE agar case-insensitive di Supabase/PostgreSQL)
                            $q->orWhere('title', 'ILIKE', '%' . trim($keyword) . '%');
                        }
                    });
                } else {
                    // Fallback: Jika tidak ada keyword, tampilkan 3 artikel terbaru
                    $articlesQuery->orderBy('id', 'desc');
                }
                
                // Ambil maksimal 3 artikel paling relevan
                $dbArticles = $articlesQuery->limit(3)->get();
                
                // Format data artikel agar sesuai dengan Blade template milikmu
                $relatedArticles = $dbArticles->map(function($art) {
                    $contentStr = strip_tags($art->content ?? '');
                    $wordCount = str_word_count($contentStr);
                    $readTime = ceil($wordCount / 200); // Estimasi membaca rata-rata (200 kata/menit)

                    return [
                        'title'        => $art->title,
                        'url'          => url('/artikel/' . $art->slug), // Asumsi URL artikelmu memakai prefix /artikel/
                        'cover_image'  => $art->image_url,
                        'category'     => $art->category ?? 'Skincare Tips',
                        'excerpt'      => Str::limit($contentStr, 90),
                        'published_at' => 'Artikel Pilihan',
                        'read_time'    => ($readTime > 0 ? $readTime : 1) . ' min'
                    ];
                })->toArray();
                // ====================================================================

                // Simpan ke Supabase
                $consultationId = DB::table('consultations')->insertGetId([
                    'user_id'           => $userId,
                    'product_result_id' => $topProductId,
                    'skin_concern'      => json_encode($aiData['extracted_concerns'] ?? []),
                    'ingredient_result' => json_encode([
                        'cleaned_query'      => $aiData['cleaned_query'] ?? '',
                        'constraints'        => $aiData['extracted_constraints'] ?? [],
                        'requested_products' => $aiData['extracted_products'] ?? [], 
                        'all_products'       => $recommendations,
                        'related_articles'   => $relatedArticles // <-- ARTIKEL DISISIPKAN DI SINI
                    ]),
                    'created_at'        => now(),
                    'updated_at'        => now()
                ]);

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
            }); 

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'errors' => $e->errors()
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
            $userId = Auth::id() ?? 1;

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
     * Tampilkan halaman hasil konsultasi berdasarkan ID di Supabase
     */
    public function result($id)
    {
        $consultation = DB::table('consultations')->where('id', $id)->first();
        
        if (!$consultation) {
            abort(404, 'Data konsultasi tidak ditemukan.');
        }

        if (Auth::check() && $consultation->user_id !== Auth::id() && $consultation->user_id !== 1) {
            abort(403, 'Akses ditolak.');
        }

        return view('pages.consultation-result', compact('consultation'));
    }
}