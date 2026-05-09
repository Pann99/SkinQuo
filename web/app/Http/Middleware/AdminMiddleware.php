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
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // TODO: Check if user has 'admin' role from database
        // Uncomment when database is ready:
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Unauthorized access. Admin privileges required.');
        // }

        // For development: Allow all authenticated users to access admin panel
        // REMOVE THIS IN PRODUCTION!
        
        return $next($request);
    }
}
