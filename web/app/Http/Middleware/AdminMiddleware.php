<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
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

        // STEP 2: Ambil user dengan relasi role
        $user = User::with('role')->find(auth()->id());

        // STEP 3: Validasi user ditemukan
        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }

        // STEP 4: Validasi relasi role ada
        if ($user->role === null) {
            Log::warning(
                'Admin access attempt but role not found. User ID: ' . $user->user_id
            );

            abort(403, 'Role tidak ditemukan. Hubungi administrator.');
        }

        // STEP 5: Cek apakah admin
        if ($user->role->role_name !== 'admin') {

            Log::warning(
                'Unauthorized admin access attempt. User ID: ' .
                $user->user_id .
                ', Role: ' .
                $user->role->role_name
            );

            abort(
                403,
                'Akses ditolak. Anda tidak memiliki hak akses administrator.'
            );
        }

        // STEP 6: Lanjut request
        return $next($request);
    }
}