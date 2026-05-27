<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * AdminProfileController
 *
 * Handles admin profile management including viewing and updating admin profile information
 *
 * @package App\Http\Controllers
 */
class AdminProfileController extends Controller
{
    /**
     * Show admin profile page
     *
     * Fetches authenticated admin user data with relationships (role, sex)
     * and passes it to the profile view
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Get authenticated admin user with relationships
        $admin = Auth::user()->load('role', 'sex');

        return view('admin.profile.profile', compact('admin'));
    }

    /**
     * Show change password page
     *
     * @return \Illuminate\View\View
     */
    public function showChangePassword()
    {
        $admin = Auth::user();

        return view('admin.profile.change-password', compact('admin'));
    }

    /**
     * Update admin password
     *
     * Validates old password and updates to new password
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')->with('status', 'Password berhasil diubah.');
    }

    /**
     * Update admin profile information
     *
     * Currently placeholder for future profile update functionality
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // TODO: Implement profile update functionality
        // - Update username, email, phone, etc
        // - Validate unique email

        return back()->with('status', 'Profile update coming soon.');
    }
}
