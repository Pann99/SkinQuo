<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware
 * 
 * Ensures only admin users can access admin routes
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // STEP 1: Verifikasi user sudah authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        // STEP 2: Ambil user dengan eager loading relasi role
        $user = auth()->user()->load('role');

        // STEP 3: Validasi relasi role ada
        if ($user->role === null) {
            \Log::warning('Admin access attempt but role not found. User ID: ' . $user->user_id);
            abort(403, 'Role tidak ditemukan. Hubungi administrator.');
        }

        // STEP 4: Cek apakah role_name === 'admin' menggunakan relasi
        if ($user->role->role_name !== 'admin') {
            \Log::warning(
                'Unauthorized admin access attempt. User ID: ' . $user->user_id .
                ', Role: ' . $user->role->role_name
            );
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses administrator.');
        }

        // STEP 5: User adalah admin, lanjutkan request
        return $next($request);
    }
}
