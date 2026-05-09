<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLogin()
    {
        return view('pages.login');
    }

    /**
     * Proses login user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email atau nomor telepon tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        // Coba login dengan email atau mobile number
        $user = User::where('email', $credentials['email'])
                    ->orWhere('mobile_number', $credentials['email'])
                    ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('status', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email/nomor telepon atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form register.
     */
    public function showRegister()
    {
        return view('pages.register');
    }

    /**
     * Proses register user baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_day' => ['required', 'numeric', 'between:1,31'],
            'birth_month' => ['required', 'numeric', 'between:1,12'],
            'birth_year' => ['required', 'numeric', 'min:1940', 'max:' . now()->year],
            'gender' => ['required', 'string', 'in:female,male,non_binary,prefer_not'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:users,mobile_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'first_name.required' => 'Nama depan tidak boleh kosong.',
            'last_name.required' => 'Nama belakang tidak boleh kosong.',
            'birth_day.required' => 'Tanggal lahir tidak boleh kosong.',
            'birth_month.required' => 'Bulan lahir tidak boleh kosong.',
            'birth_year.required' => 'Tahun lahir tidak boleh kosong.',
            'gender.required' => 'Jenis kelamin tidak boleh kosong.',
            'email.required' => 'Email atau nomor telepon tidak boleh kosong.',
            'email.unique' => 'Email atau nomor telepon sudah terdaftar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.confirmed' => 'Password tidak cocok.',
        ]);

        // Gabungkan birth_day, birth_month, birth_year menjadi birth_date
        $birthDate = sprintf('%04d-%02d-%02d', $validated['birth_year'], $validated['birth_month'], $validated['birth_day']);

        try {
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'birth_date' => $birthDate,
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'mobile_number' => filter_var($validated['email'], FILTER_VALIDATE_EMAIL) ? null : $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect(route('home'))->with('status', 'Pendaftaran berhasil! Selamat datang di SkinQuo.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput();
        }
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'))->with('status', 'Logout berhasil!');
    }
}
