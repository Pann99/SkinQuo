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
        // STEP 1: Verify user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        // STEP 2: Fetch user with role relation
        $user = User::with('role')->find(auth()->id());

        // STEP 3: Validate user exists
        if (!$user) {
            abort(403, 'User account not found.');
        }

        // STEP 4: Validate role relation exists
        if ($user->role === null) {
            Log::warning(
                'Admin access attempt but role not found. User ID: ' . $user->user_id
            );

            abort(403, 'Role not found. Please contact an administrator.');
        }

        // STEP 5: Check if user is admin
        if ($user->role->role_name !== 'admin') {

            Log::warning(
                'Unauthorized admin access attempt. User ID: ' .
                $user->user_id .
                ', Role: ' .
                $user->role->role_name
            );

            abort(
                403,
                'Access denied. You do not have administrator permissions.'
            );
        }

        // STEP 6: Lanjut request
        return $next($request);
    }
}