<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('consultation')->group(function () {
    // Endpoint pengiriman konsultasi (FastAPI Connection & Supabase Storing)
    Route::post('/send', [ConsultationController::class, 'sendConsultation'])->name('api.consultation.send');
    // Endpoint pengambilan data riwayat tracking
    Route::get('/history', [ConsultationController::class, 'getHistory'])->name('api.consultation.history');
});