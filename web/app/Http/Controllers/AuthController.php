<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'string'],
    //         'password' => ['required', 'string'],
    //     ], [
    //         'email.required' => 'Email atau nomor telepon tidak boleh kosong.',
    //         'password.required' => 'Password tidak boleh kosong.',
    //     ]);

    //     // Coba login dengan email atau mobile number
    //     $user = User::where('email', $credentials['email'])
    //                 ->orWhere('mobile_number', $credentials['email'])
    //                 ->first();

    //     if ($user && Hash::check($credentials['password'], $user->password)) {
    //         Auth::login($user, $request->boolean('remember'));
    //         $request->session()->regenerate();

    //         return redirect()->intended(route('home'))->with('status', 'Login berhasil!');
    //     }

    //     return back()->withErrors([
    //         'email' => 'Email/nomor telepon atau password salah.',
    //     ])->onlyInput('email');
    // }

    public function login(Request $request)
    {
        // LANGKAH 1: VALIDASI INPUT
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255'
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email terlalu panjang (maksimal 255 karakter).',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password terlalu panjang (maksimal 255 karakter).',
        ]);

        // LANGKAH 2: AUTENTIKASI MENGGUNAKAN AUTH::ATTEMPT()
        // Ini adalah cara yang aman dan benar di Laravel
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // Jangan gunakan Hash::check() manual - gunakan Auth::attempt() yang lebih aman
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        // LANGKAH 3: LOGIN BERHASIL - REGENERATE SESSION
        $request->session()->regenerate();

        // LANGKAH 4: LOAD USER DENGAN RELASI ROLE (EAGER LOADING)
        // Ambil user yang sedang login beserta relasi rolenya
        $user = Auth::user()->load('role');

        // LANGKAH 5: REDIRECT BERDASARKAN ROLE
        if ($user->role === null) {
            Log::warning('User logged in but role not found. User ID: ' . $user->user_id);
            return redirect()->route('home')
                ->with('warning', 'Role tidak ditemukan. Hubungi administrator.');
        }

        // Cek role_name dari relasi role
        $roleName = $user->role->role_name ?? null;

        try {
            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('status', 'Selamat datang, Admin! Login berhasil.');
            } elseif ($roleName === 'user') {
                return redirect()->route('profile.show')
                    ->with('status', 'Login berhasil! Selamat datang di SkinQuo.');
            } else {
                // Fallback untuk role yang tidak dikenali
                Log::warning('Unknown role detected. Role name: ' . $roleName . ', User ID: ' . $user->user_id);
                return redirect()->route('home')
                    ->with('warning', 'Role tidak dikenali. Silakan hubungi administrator.');
            }
        } catch (\Exception $e) {
            Log::error('Redirect error after login: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('status', 'Login berhasil!');
        }
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
    // public function register(Request $request)
    // {
    //     $validated = $request->validate([
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'birth_day' => ['required', 'numeric', 'between:1,31'],
    //         'birth_month' => ['required', 'numeric', 'between:1,12'],
    //         'birth_year' => ['required', 'numeric', 'min:1940', 'max:' . now()->year],
    //         'gender' => ['required', 'string', 'in:female,male,non_binary,prefer_not'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:users,mobile_number'],
    //         'password' => ['required', 'confirmed', Password::defaults()],
    //     ], [
    //         'first_name.required' => 'Nama depan tidak boleh kosong.',
    //         'last_name.required' => 'Nama belakang tidak boleh kosong.',
    //         'birth_day.required' => 'Tanggal lahir tidak boleh kosong.',
    //         'birth_month.required' => 'Bulan lahir tidak boleh kosong.',
    //         'birth_year.required' => 'Tahun lahir tidak boleh kosong.',
    //         'gender.required' => 'Jenis kelamin tidak boleh kosong.',
    //         'email.required' => 'Email atau nomor telepon tidak boleh kosong.',
    //         'email.unique' => 'Email atau nomor telepon sudah terdaftar.',
    //         'password.required' => 'Password tidak boleh kosong.',
    //         'password.confirmed' => 'Password tidak cocok.',
    //     ]);

    //     // Gabungkan birth_day, birth_month, birth_year menjadi birth_date
    //     $birthDate = sprintf('%04d-%02d-%02d', $validated['birth_year'], $validated['birth_month'], $validated['birth_day']);

    //     try {
    //         $user = User::create([
    //             'name' => $validated['first_name'] . ' ' . $validated['last_name'],
    //             'first_name' => $validated['first_name'],
    //             'last_name' => $validated['last_name'],
    //             'birth_date' => $birthDate,
    //             'gender' => $validated['gender'],
    //             'email' => $validated['email'],
    //             'mobile_number' => filter_var($validated['email'], FILTER_VALIDATE_EMAIL) ? null : $validated['email'],
    //             'password' => Hash::make($validated['password']),
    //         ]);

    //         Auth::login($user);
    //         $request->session()->regenerate();

    //         return redirect(route('home'))->with('status', 'Pendaftaran berhasil! Selamat datang di SkinQuo.');
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput();
    //     }
    // }

    public function register(Request $request)
    {
        // Validate input dengan sanitasi ketat
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'date_birth' => 'required|date|before:today|after:1940-01-01',
            'gender' => 'required|in:male,female',
        ], [
            'name.required' => 'Nama depan tidak boleh kosong.',
            'name.string' => 'Nama depan harus berupa teks.',
            'name.max' => 'Nama depan terlalu panjang (maksimal 255 karakter).',
            'surname.required' => 'Nama belakang tidak boleh kosong.',
            'surname.string' => 'Nama belakang harus berupa teks.',
            'surname.max' => 'Nama belakang terlalu panjang (maksimal 255 karakter).',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email terlalu panjang (maksimal 255 karakter).',
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password terlalu panjang (maksimal 255 karakter).',
            'date_birth.required' => 'Tanggal lahir tidak boleh kosong.',
            'date_birth.date' => 'Format tanggal lahir tidak valid.',
            'date_birth.before' => 'Tanggal lahir harus sebelum hari ini.',
            'date_birth.after' => 'Tahun lahir harus setelah 1940.',
            'gender.required' => 'Jenis kelamin tidak boleh kosong.',
            'gender.in' => 'Jenis kelamin tidak valid.',
        ]);

        // Gabungkan name dan surname menjadi username
        $username = $validated['name'] . ' ' . $validated['surname'];
        
        // Map gender ke sex_id (male = 1, female = 2)
        $sexId = $validated['gender'] === 'male' ? 1 : 2;
        
        // Sanitasi email untuk keamanan
        $email = strtolower(trim($request->input('email')));
        
        // Cegah SQL injection dengan menggunakan parameterized queries
        // (Laravel Eloquent sudah menghandle ini secara default)
        try {
            $user = User::create([
                'username' => $username,
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'sex_id' => $sexId,
                'role_id' => 2, // Default: user role
                'date_birth' => $validated['date_birth'],
                'created_at' => now(),
            ]);

            // Login otomatis setelah register
            try {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect(route('home'))->with('status', 'Pendaftaran berhasil! Selamat datang di SkinQuo.');
            } catch (\Exception $e) {
                Log::error('Auto login after register failed: ' . $e->getMessage());
                return back()->withErrors([
                    'error' => 'Terjadi kesalahan saat login otomatis. Silakan login manual.',
                ])->withInput();
            }
        } catch (\Exception $e) {
            // Log error untuk debugging (jangan tampilkan detail error ke user)
            Log::error('Registration error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
            ])->withInput();
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
