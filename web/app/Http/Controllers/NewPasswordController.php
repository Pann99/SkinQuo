<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('pages.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',      // minimal 1 huruf kapital
                'regex:/[^a-zA-Z0-9]/', // minimal 1 simbol
            ],
        ], [
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter and one symbol.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Please confirm your new password.',
        ]);

        // Cek apakah password baru sama dengan password lama
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'New password must be different from your current password.']);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}