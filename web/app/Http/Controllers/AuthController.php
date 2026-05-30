<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
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
        // STEP 1: RATE LIMITING - Check if too many attempts
        $throttleKey = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, $max = 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // STEP 2: VALIDATE INPUT
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255'
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address is too long (maximum 255 characters).',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password is too long (maximum 255 characters).',
        ]);

        // STEP 3: AUTHENTICATE USING AUTH::ATTEMPT()
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);
            return back()->withErrors([
                'email' => 'The provided credentials are incorrect.',
            ])->onlyInput('email');
        }

        // STEP 4: LOGIN SUCCESSFUL - REGENERATE SESSION
        $request->session()->regenerate();
        RateLimiter::clear($throttleKey);

        // STEP 5: LOAD USER WITH ROLE RELATION (EAGER LOADING)
        $user = User::with('role')->find(Auth::id());

        if ($user->role === null) {
            Log::warning('User logged in but role not found. User ID: ' . $user->user_id);
            return redirect()->route('home')
                ->with('warning', 'Role not found. Please contact an administrator.');
        }

        $roleName = $user->role->role_name ?? null;

        try {
            if ($roleName === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('status', 'Welcome, Admin! Login successful.');
            } elseif ($roleName === 'user') {
                return redirect()->route('profile.show')
                    ->with('status', 'Login successful. Welcome back to SkinQuo!');
            } else {
                Log::warning('Unknown role detected. Role name: ' . $roleName . ', User ID: ' . $user->user_id);
                return redirect()->route('home')
                    ->with('warning', 'Unknown role. Please contact an administrator.');
            }
        } catch (\Exception $e) {
            Log::error('Redirect error after login: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('status', 'Login successful!');
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

    public function register(Request $request)
    {
        // STEP 1: VALIDATE INPUT WITH STRICT SANITIZATION
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'password_confirmation' => 'required|string|min:8|max:255',
            'date_birth' => 'required|date|before:today|after:1940-01-01',
            'gender' => 'required|in:male,female',
        ], [
            'name.required' => 'First name is required.',
            'name.string' => 'First name must be text.',
            'name.max' => 'First name is too long (maximum 255 characters).',
            'surname.required' => 'Last name is required.',
            'surname.string' => 'Last name must be text.',
            'surname.max' => 'Last name is too long (maximum 255 characters).',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address is too long (maximum 255 characters).',
            'email.unique' => 'This email address is already registered. Please use a different email or sign in.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password is too long (maximum 255 characters).',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'date_birth.required' => 'Date of birth is required.',
            'date_birth.date' => 'Please enter a valid date.',
            'date_birth.before' => 'Date of birth must be in the past.',
            'date_birth.after' => 'Date of birth must be after January 1, 1940.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Invalid gender selection.',
        ]);

        // STEP 2: SANITIZE AND PREPARE DATA
        $name = trim($validated['name']);
        $surname = trim($validated['surname']);
        $username = "{$name} {$surname}";
        $email = strtolower(trim($validated['email']));
        
        // Map gender to sex_id (male = 1, female = 2)
        $sexId = $validated['gender'] === 'male' ? 1 : 2;
        
        // STEP 3: CREATE USER (PARAMETERIZED QUERIES via Eloquent)
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

            // STEP 4: AUTO LOGIN AFTER REGISTRATION
            try {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect(route('home'))->with('status', 'Account created successfully. Welcome to SkinQuo!');
            } catch (\Exception $e) {
                Log::error('Auto login after register failed: ' . $e->getMessage());
                return back()->withErrors([
                    'error' => 'An error occurred during login. Please sign in manually.',
                ])->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'An error occurred. Please try again.',
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

        return redirect(route('home'))->with('status', 'Logged out successfully.');
    }
}
