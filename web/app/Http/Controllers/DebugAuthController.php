<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DebugAuthController extends Controller
{
    /**
     * Reset admin password to a valid Bcrypt hash
     * URL: /debug/reset-admin-password?secret=skinquo2026
     * 
     * SECURITY: Hapus endpoint ini setelah production!
     */
    public function resetAdminPassword(Request $request)
    {
        // Simple security check
        if ($request->query('secret') !== 'skinquo2026') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $password = 'password123';
            $hashedPassword = Hash::make($password);

            // Update admin password directly
            $updated = DB::table('users')
                ->where('email', 'admin@skinquo.co')
                ->update(['password' => $hashedPassword]);

            if ($updated > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin password updated successfully!',
                    'email' => 'admin@skinquo.co',
                    'password' => $password,
                    'password_hash' => $hashedPassword,
                    'rows_updated' => $updated
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin user not found or not updated',
                    'email' => 'admin@skinquo.co'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Error updating password'
            ], 500);
        }
    }

    /**
     * Check database connection and users
     * URL: /debug/check-db
     */
    public function checkDb()
    {
        try {
            $users = DB::table('users')->get();
            $roles = DB::table('roles')->get();

            return response()->json([
                'database_connected' => true,
                'users_count' => count($users),
                'users' => $users,
                'roles_count' => count($roles),
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'database_connected' => false
            ], 500);
        }
    }
}
