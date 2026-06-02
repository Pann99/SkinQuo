<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile user.
     */
    public function show()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }
            
            $consultations = $user->consultations()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($c) {
                    $c->skin_concern_parsed = is_string($c->skin_concern)
                        ? json_decode($c->skin_concern, true)
                        : ($c->skin_concern ?? []);
                    $c->ingredient_result_parsed = is_string($c->ingredient_result)
                        ? json_decode($c->ingredient_result, true)
                        : ($c->ingredient_result ?? []);
                    return $c;
                });
            
            // Debug: Log consultation count
            \Log::info('Profile show - User ID: ' . $user->user_id . ', Consultations count: ' . $consultations->count());
            
            // Eager load sex and role relationships
            $user->load(['sex', 'role']);
            
            return view('pages.profile', compact('user', 'consultations'));
        } catch (\Exception $e) {
            \Log::error('Profile show error: ' . $e->getMessage());
            return redirect()->route('home')->withErrors(['error' => 'Terjadi kesalahan saat memuat profil.']);
        }
    }

    /**
     * Update profile user.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->user_id . ',user_id'],
            'date_birth' => ['nullable', 'date', 'before:today'],
            'sex_id' => ['nullable', 'integer', 'in:1,2'],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'username.max' => 'Username terlalu panjang (maksimal 255 karakter).',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'date_birth.date' => 'Format tanggal lahir tidak valid.',
            'date_birth.before' => 'Tanggal lahir harus sebelum hari ini.',
            'sex_id.integer' => 'Jenis kelamin tidak valid.',
            'sex_id.in' => 'Jenis kelamin hanya boleh 1 (Laki-laki) atau 2 (Perempuan).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password terlalu panjang (maksimal 255 karakter).',
        ]);

        try {
            // Update data dasar
            $updateData = [
                'username' => $validated['username'],
                'email' => strtolower(trim($validated['email'])),
            ];

            // Update opsional
            if ($validated['date_birth']) {
                $updateData['date_birth'] = $validated['date_birth'];
            }
            if ($validated['sex_id']) {
                $updateData['sex_id'] = $validated['sex_id'];
            }

            // Update password jika ada input
            if ($validated['password']) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Lakukan update
            $user->update($updateData);

            return redirect()->route('profile.show')->with('status', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Profile update error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.',
            ])->withInput();
        }
    }

    /**
     * Tampilkan halaman ubah password.
     */
    public function editPassword()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }
            
            return view('pages.change-password', compact('user'));
        } catch (\Exception $e) {
            \Log::error('Edit password page error: ' . $e->getMessage());
            return redirect()->route('profile.show')->withErrors(['error' => 'Terjadi kesalahan saat membuka halaman ubah password.']);
        }
    }

    /**
     * Update password user dengan validasi current_password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ], [
            'current_password.required' => 'Password saat ini tidak boleh kosong.',
            'current_password.min' => 'Password saat ini minimal 8 karakter.',
            'password.required' => 'Password baru tidak boleh kosong.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai dengan password baru.',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
            'password_confirmation.min' => 'Konfirmasi password minimal 8 karakter.',
        ]);

        try {
            // Verifikasi current password cocok
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini tidak sesuai.',
                ])->withInput($request->except('current_password', 'password', 'password_confirmation'));
            }

            // Cegah password baru sama dengan current password
            if (Hash::check($validated['password'], $user->password)) {
                return back()->withErrors([
                    'password' => 'Password baru tidak boleh sama dengan password saat ini.',
                ])->withInput($request->except('current_password', 'password', 'password_confirmation'));
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('profile.show')->with('status', 'Your password has been securely updated.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Password update error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.',
            ])->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }
    }
}
