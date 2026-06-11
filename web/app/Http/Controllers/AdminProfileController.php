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
        // 1. Validasi disinkronkan dengan name="new_password" pada form Blade
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            // Pesan error kustom agar lebih ramah dibaca pengguna
            'new_password.confirmed' => 'The password confirmation does not match.',
            'new_password.min'       => 'The new password must be at least 8 characters.',
            'current_password.required' => 'Please enter your current password.'
        ]);

        $user = Auth::user();

        // 2. Verifikasi kecocokan Current Password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Incorrect current password. Please try again.'])
                ->withInput(array_diff_key($request->all(), array_flip(['current_password', 'new_password', 'new_password_confirmation'])));
        }

        // 3. Update password dengan input yang baru
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // 4. Redirect ke rute admin.profile membawa session 'success' untuk memicu alert hijau
      // AdminProfileController.php — updatePassword()
return redirect()
    ->route('admin.profile')
    ->with('password_updated', true);  
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