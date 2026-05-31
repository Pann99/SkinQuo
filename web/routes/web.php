<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSkinGuideController;
use App\Http\Controllers\AdminFeedbackController;
use App\Http\Controllers\DebugAuthController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// A. ROUTE PUBLIC
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Skin Guide
Route::get('/skin-guide', [ArticleController::class, 'index'])
    ->name('skin-guide.index');

Route::get('/skin-guide/{slug}', [ArticleController::class, 'show'])
    ->name('skin-guide.show');

// Debug Products
Route::get('/products', function () {
    return DB::table('products')->get();
});

// Catalog
Route::get('/catalog', [ProductController::class, 'index'])
    ->name('catalog.index');

Route::get('/catalog/{product_id}', [ProductController::class, 'show'])
    ->name('products.show');

// Consultation
Route::get('/consultation', [ConsultationController::class, 'index'])
    ->name('consultation.index');

Route::post('/api/recommend', [ConsultationController::class, 'sendConsultation'])
    ->name('consultation.recommend');

Route::get('/consultation/{id}/result', [ConsultationController::class, 'result'])
    ->name('consultation.result');

Route::post('/consultation/analyze', [ConsultationController::class, 'analyze']);

// Feedback
Route::post('/feedback', [FeedbackController::class, 'store'])
    ->name('feedback.store');

// Static Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/how-it-works', 'pages.how-it-works')->name('how-it-works');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// B. AUTH ROUTES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Preview Routes
Route::view('/admin/profile-preview', 'admin.profile.profile')
    ->name('admin.profile.preview');

Route::view('/admin/profile-preview/change-password', 'admin.profile.change-password')
    ->name('admin.profile.preview.change-password');

Route::view('/admin/journal-preview', 'admin.journal.index')
    ->name('admin.journal.preview');

Route::view('/admin/journal-preview/create', 'admin.journal.create')
    ->name('admin.journal.preview.create');

Route::view('/admin/journal-preview/edit', 'admin.journal.edit')
    ->name('admin.journal.preview.edit');

Route::get('/admin/feedback-preview', [AdminFeedbackController::class, 'monitor'])
    ->name('admin.feedback.preview');

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// C. USER ROUTES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile/password/edit', [ProfileController::class, 'editPassword'])
        ->name('profile.password.edit');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    Route::post('/consultation/feedback', [ConsultationController::class, 'storeFeedback'])
        ->name('consultation.feedback.store');
});

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// D. ADMIN ROUTES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Profile
        Route::get('/profile', [AdminProfileController::class, 'show'])
            ->name('profile');

        Route::get('/profile/change-password', [AdminProfileController::class, 'showChangePassword'])
            ->name('profile.change-password');

        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])
            ->name('profile.update-password');

        Route::put('/profile', [AdminProfileController::class, 'update'])
            ->name('profile.update');

        // Inventory
        Route::get('/inventory', [AdminProductController::class, 'index'])
            ->name('inventory');

        // Feedback
        Route::get('/feedback', [AdminFeedbackController::class, 'monitor'])
            ->name('feedback');

        // Journal
        Route::get('/journal', function () {
            return view('admin.journal.index');
        })->name('journal');

        Route::get('/journal/create', function () {
            return view('admin.journal.create');
        })->name('journal.create');

        Route::post('/journal', function () {
            return redirect()
                ->route('admin.journal')
                ->with('success', 'Article created successfully!');
        })->name('journal.store');

        Route::get('/journal/{id}/edit', function ($id) {
            return view('admin.journal.edit');
        })->name('journal.edit');

        Route::put('/journal/{id}', function ($id) {
            return redirect()
                ->route('admin.journal')
                ->with('success', 'Article updated successfully!');
        })->name('journal.update');

        Route::delete('/journal/{id}', function ($id) {
            return redirect()
                ->route('admin.journal')
                ->with('success', 'Article deleted successfully!');
        })->name('journal.destroy');

        // Products CRUD
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

        // Skin Guide CRUD
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

        // Feedback Monitoring
        Route::get('/feedback/monitor', [AdminFeedbackController::class, 'monitor'])
            ->name('feedback.monitor');

        Route::get('/feedback/{id}', [AdminFeedbackController::class, 'show'])
            ->name('feedback.show');

        Route::post('/feedback/{id}/mark-reviewed', [AdminFeedbackController::class, 'markAsReviewed'])
            ->name('feedback.mark-reviewed');

        Route::delete('/feedback/{id}', [AdminFeedbackController::class, 'destroy'])
            ->name('feedback.destroy');

        Route::get('/feedback/export/csv', [AdminFeedbackController::class, 'exportCsv'])
            ->name('feedback.export.csv');

        Route::get('/feedback/export/pdf', [AdminFeedbackController::class, 'exportPdf'])
            ->name('feedback.export.pdf');
    });

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// DEBUG ROUTES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Route::prefix('debug')->middleware('web')->group(function () {

    Route::get('/check-db', [DebugAuthController::class, 'checkDb'])
        ->name('debug.check-db');

    Route::get('/reset-admin-password', [DebugAuthController::class, 'resetAdminPassword'])
        ->name('debug.reset-admin-password');
});