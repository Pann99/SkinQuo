<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSkinGuideController;
use App\Http\Controllers\AdminFeedbackController;
use Illuminate\Support\Facades\Route;

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// A. ROUTE PUBLIC - Accessible untuk Guest dan User
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

// ── Landing Page ───────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Public Skin Guide / Artikel ────────────────────
Route::get('/skin-guide', [ArticleController::class, 'index'])->name('skin-guide.index');
Route::get('/skin-guide/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// ── Public Catalog / Produk ────────────────────────
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [ProductController::class, 'show'])->name('products.show');

// ── Public Konsultasi (Form & AJAX Analysis) ───────────────
Route::get('/consultation', [ConsultationController::class, 'index'])->name('consultation.index');
Route::post('/consultation/analyze', [ConsultationController::class, 'analyze']); // AJAX endpoint
Route::post('/consultation', [ConsultationController::class, 'store'])->name('consultation.store'); // Guest dapat submit
Route::get('/consultation/{id}', [ConsultationController::class, 'result'])->name('consultation.result'); // Guest dapat view hasil

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// B. ROUTE AUTENTIKASI - LOGIN, REGISTER, LOGOUT (Laravel Breeze/Fortify Style)
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

// ── Login (GET & POST) ─────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest'); // Redirect ke home jika sudah login

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// ── Register (GET & POST) ──────────────────────────
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');

// ── Logout ─────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// C. ROUTE USER (Protected by auth middleware)
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Route::middleware('auth')->group(function () {
    // ── User Profile Management ────────────────────
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// D. ROUTE ADMIN (Protected by auth + admin middleware)
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// 
// ⚠️  MIDDLEWARE REQUIREMENTS:
//     - 'auth'  : User harus login (Authenticated)
//     - 'admin' : User.role HARUS === 'admin' (Custom Middleware Check)
//
// AKSES ADMIN HANYA UNTUK USER DENGAN ROLE = 'ADMIN'
//

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // ── Admin Dashboard ────────────────────────────
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ── Products Management (Full CRUD) ────────────
    Route::resource('products', AdminProductController::class, [
        'names' => [
            'index'   => 'products.index',
            'create'  => 'products.create',
            'store'   => 'products.store',
            'show'    => 'products.show',
            'edit'    => 'products.edit',
            'update'  => 'products.update',
            'destroy' => 'products.destroy',
        ]
    ]);

    // ── Skin Guide / Articles Management (Full CRUD) ────
    Route::resource('skin-guide', AdminSkinGuideController::class, [
        'names' => [
            'index'   => 'skin-guide.index',
            'create'  => 'skin-guide.create',
            'store'   => 'skin-guide.store',
            'show'    => 'skin-guide.show',
            'edit'    => 'skin-guide.edit',
            'update'  => 'skin-guide.update',
            'destroy' => 'skin-guide.destroy',
        ]
    ]);

    // ── Feedback Monitoring & Management ───────────
    Route::get('/feedback/monitor', [AdminFeedbackController::class, 'monitor'])->name('feedback.monitor');
    Route::post('/feedback/{id}/approve', [AdminFeedbackController::class, 'approve'])->name('feedback.approve');
    Route::post('/feedback/{id}/reject', [AdminFeedbackController::class, 'reject'])->name('feedback.reject');
    Route::post('/feedback/{id}/helpful', [AdminFeedbackController::class, 'markHelpful'])->name('feedback.helpful');

});
