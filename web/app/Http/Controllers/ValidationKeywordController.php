<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidationKeywordController extends Controller
{
    // Mapping tipe → nilai kolom category di tabel validation_keywords
    private const CATEGORY_MAP = [
        'product'    => 'product',
        'problem'    => 'problem',
        'ingredient' => 'ingredient',
        'skin_type'  => 'skin_type',
    ];

    /**
     * Tampilkan halaman admin upload dictionary
     */
    public function index()
    {
        $stats = [];
        foreach (self::CATEGORY_MAP as $type => $category) {
            $stats[$type] = DB::table('validation_keywords')
                ->where('category', $category)
                ->count();
        }

        return view('admin.dictionary.index', compact('stats'));
    }

    /**
     * Handle upload CSV untuk salah satu kategori
     * POST /admin/dictionary/upload
     * Body: type (product|problem|constraint|skin_type), file (csv)
     */
    public function upload(Request $request)
    {
    try {
        $request->validate([
            'type' => ['required', 'in:product,problem,ingredient,skin_type'],
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:51200'],
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => collect($e->errors())->flatten()->first(),
        ], 422);
    }
        $type     = $request->input('type');
        $category = self::CATEGORY_MAP[$type];
        $file     = $request->file('file');

        try {
            $result = $this->processCSV($file->getRealPath(), $category);

            return response()->json([
                'success'   => true,
                'category'  => $category,
                'inserted'  => $result['inserted'],
                'skipped'   => $result['skipped'],
                'total_rows' => $result['total_rows'],
                'message'   => "Berhasil: {$result['inserted']} keyword ditambahkan, {$result['skipped']} keyword dilewati (sudah ada).",
            ]);

        } catch (\Exception $e) {
            Log::error("Dictionary upload error [{$type}]: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proses CSV dan insert ke Supabase, skip duplikat
     */
    private function processCSV(string $path, string $category): array
{
    $handle = fopen($path, 'r');
    if (!$handle) {
        throw new \RuntimeException('Tidak bisa membuka file CSV.');
    }

    // Baca header - skip baris non-header (judul & instruksi)
    $header = null;
    while (($row = fgetcsv($handle)) !== false) {
        $normalized = array_map(fn($h) => strtolower(trim($h)), $row);
        if (in_array('keyword', $normalized)) {
            $header = $normalized;
            break;
        }
    }

    if (!$header) {
        fclose($handle);
        throw new \RuntimeException('Kolom "keyword" tidak ditemukan di CSV. Pastikan menggunakan template yang benar.');
    }

    $keywordIdx    = array_search('keyword', $header);
    $precautionIdx = array_search('precaution_note', $header);

    $existing = DB::table('validation_keywords')
        ->where('category', $category)
        ->pluck('keyword')
        ->map(fn($k) => strtolower(trim($k)))
        ->flip()
        ->toArray();

    $inserted  = 0;
    $skipped   = 0;
    $totalRows = 0;
    $toInsert  = [];

    while (($row = fgetcsv($handle)) !== false) {
        if (empty(array_filter($row))) continue;
        $totalRows++;

        $keyword = strtolower(trim($row[$keywordIdx] ?? ''));
        if (empty($keyword)) { $skipped++; continue; }

        if (isset($existing[$keyword])) { $skipped++; continue; }

        $precautionNote = null;
        if ($precautionIdx !== false && isset($row[$precautionIdx])) {
            $note = trim($row[$precautionIdx]);
            $precautionNote = $note !== '' ? $note : null;
        }

        $toInsert[] = [
            'category'        => $category,
            'keyword'         => $keyword,
            'precaution_note' => $precautionNote,
            'created_at'      => now(),
        ];

        $existing[$keyword] = true;
        $inserted++;
    }

    fclose($handle);

    if (!empty($toInsert)) {
        foreach (array_chunk($toInsert, 100) as $chunk) {
            DB::table('validation_keywords')->insert($chunk);
        }
    }

    return [
        'inserted'   => $inserted,
        'skipped'    => $skipped,
        'total_rows' => $totalRows,
    ];
}

    /**
     * Hapus semua keyword untuk satu kategori
     * DELETE /admin/dictionary/{type}
     */
    public function destroy(string $type)
    {
        if (!array_key_exists($type, self::CATEGORY_MAP)) {
            return response()->json(['success' => false, 'message' => 'Tipe tidak valid.'], 400);
        }

        $deleted = DB::table('validation_keywords')
            ->where('category', self::CATEGORY_MAP[$type])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} keyword berhasil dihapus dari kategori {$type}.",
        ]);
    }
}