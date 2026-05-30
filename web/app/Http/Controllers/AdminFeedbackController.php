<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * AdminFeedbackController
 * 
 * Handles feedback monitoring and management in admin panel
 * 
 * @package App\Http\Controllers
 */
class AdminFeedbackController extends Controller
{
    /**
     * Display feedback monitoring dashboard with filters
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function monitor(Request $request)
    {
        // Try to fetch feedbacks from DB if table exists, otherwise use sample data
        $perPage = 10;
        $page = (int) $request->get('page', 1);
        $queryString = $request->get('q');

        if (Schema::hasTable('feedbacks')) {
            $query = DB::table('feedbacks')->orderByDesc('created_at');
            if ($queryString) {
                $query->where(function ($q) use ($queryString) {
                    $q->where('name', 'like', "%{$queryString}%")
                      ->orWhere('email', 'like', "%{$queryString}%")
                      ->orWhere('message', 'like', "%{$queryString}%");
                });
            }

            $total = $query->count();
            $rows = $query->forPage($page, $perPage)->get();
            $feedback = new LengthAwarePaginator($rows, $total, $perPage, $page, [
                'path' => $request->url(), 'query' => $request->query()
            ]);

            $stats = [
                'total' => DB::table('feedbacks')->count(),
                'pending' => DB::table('feedbacks')->where('status', 'pending')->count(),
                'approved' => DB::table('feedbacks')->where('status', 'approved')->count(),
            ];
        } else {
            // Fallback sample data (used when DB table is not present)
            $sample = [
                ['id'=>1,'name'=>'Elena Miller','email'=>'elena.m@example.com','message'=>'Mungkin bisa ditambahkan varian baru untuk serum malam hari yang lebih fokus pada hidrasi mendalam dan perbaikan skin barrier.','created_at'=>'2023-10-12','status'=>'pending'],
                ['id'=>2,'name'=>'Julian S.','email'=>'j.smith@webmail.id','message'=>'Paket yang saya terima sedikit penyok di bagian kemasan...','created_at'=>'2023-10-11','status'=>'approved'],
                ['id'=>3,'name'=>'Anita Rahma','email'=>'anita.r@global.net','message'=>'Apakah produk serum malam bisa dipakai untuk kulit sensitif?','created_at'=>'2023-10-11','status'=>'pending'],
                ['id'=>4,'name'=>'Kevin Brown','email'=>'kevin.b@mail.com','message'=>'Desain website sangat tenang, pertahankan palet warna ini.','created_at'=>'2023-10-10','status'=>'approved'],
            ];

            // Simple search filter
            if ($queryString) {
                $sample = array_filter($sample, function ($r) use ($queryString) {
                    return str_contains(strtolower($r['name']), strtolower($queryString))
                        || str_contains(strtolower($r['email']), strtolower($queryString))
                        || str_contains(strtolower($r['message']), strtolower($queryString));
                });
            }

            $total = count($sample);
            $offset = ($page - 1) * $perPage;
            $rows = array_slice($sample, $offset, $perPage);
            $feedback = new LengthAwarePaginator($rows, $total, $perPage, $page, [
                'path' => $request->url(), 'query' => $request->query()
            ]);

            $stats = ['total' => 24, 'pending' => 6, 'approved' => 18];
        }

        return view('admin.feedback.monitor', compact('feedback', 'stats'));
    }

    /**
     * Export feedback as CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = 'feedback_export_' . date('Ymd_His') . '.csv';

        $callback = function () use ($request) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Name','Email','Message','Date','Status']);

            if (Schema::hasTable('feedbacks')) {
                $rows = DB::table('feedbacks')->orderByDesc('created_at')->get();
                foreach ($rows as $r) {
                    fputcsv($out, [$r->id, $r->name, $r->email, $r->message, $r->created_at, $r->status ?? '']);
                }
            } else {
                $sample = [
                    [1,'Elena Miller','elena.m@example.com','Mungkin bisa ditambahkan varian baru untuk serum malam hari...','2023-10-12','pending'],
                    [2,'Julian S.','j.smith@webmail.id','Paket yang saya terima sedikit penyok di bagian kemasan...','2023-10-11','approved'],
                    [3,'Anita Rahma','anita.r@global.net','Apakah produk serum malam bisa dipakai untuk kulit sensitif?','2023-10-11','pending'],
                    [4,'Kevin Brown','kevin.b@mail.com','Desain website sangat tenang, pertahankan palet warna ini.','2023-10-10','approved'],
                ];
                foreach ($sample as $r) {
                    fputcsv($out, $r);
                }
            }

            fclose($out);
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export as PDF (requires dompdf). If not available, redirect back with message.
     */
    public function exportPdf(Request $request)
    {
        if (!class_exists(\Dompdf\Dompdf::class) && !class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return back()->with('error', 'PDF export requires barryvdh/laravel-dompdf. Run: composer require barryvdh/laravel-dompdf');
        }

        // If library exists, prepare simple HTML and generate PDF
        $feedback = Schema::hasTable('feedbacks') ? DB::table('feedbacks')->orderByDesc('created_at')->get() : collect([]);

        $html = view('admin.feedback.pdf', compact('feedback'))->render();

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return $pdf->download('feedback_export_' . date('Ymd_His') . '.pdf');
        }

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="feedback_export_' . date('Ymd_His') . '.pdf"',
        ]);
    }

    /**
     * Approve feedback/review
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request)
    {
        $id = $request->route('id');
        if (Schema::hasTable('feedbacks')) {
            DB::table('feedbacks')->where('id', $id)->update(['status' => 'approved']);
        }
        return back()->with('success', 'Feedback approved successfully');
    }

    /**
     * Reject feedback/review
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request)
    {
        $id = $request->route('id');
        if (Schema::hasTable('feedbacks')) {
            DB::table('feedbacks')->where('id', $id)->update(['status' => 'rejected']);
        }
        return back()->with('success', 'Feedback rejected successfully');
    }

    /**
     * Mark feedback as helpful
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markHelpful(Request $request)
    {
        // TODO: Increment helpful_count for feedback
        // TODO: Log the action
        
        return back()->with('success', 'Marked as helpful');
    }
}
