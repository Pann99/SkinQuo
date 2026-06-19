@extends('layouts.app')

@section('title', 'Login — SkinQuo')

@push('styles')
<style>
    /* Hide navbar on auth pages */
    .navbar-wrap { display: none !important; }
    footer { display: none !important; }

    body { background: #FFEAC5; }

    .auth-wrapper {
        display: grid;
        grid-template-columns: 63% 37%;
        height: 100vh;
        overflow: hidden;
    }

    /* ── LEFT PANEL ── */
    .auth-left {
        background: #FFEAC5;
        display: flex;
        flex-direction: column;
        padding: 0.8rem 1.5rem;
        position: relative;
        overflow-y: auto;
    }

    .auth-brand {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #603F26;
        text-decoration: none;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .auth-brand:hover { opacity: 0.75; }
    .auth-brand img {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }

    .auth-form-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        max-width: 420px;
        width: 100%;
        margin: 0 auto;
        padding: 0;
    }

    .auth-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 0.8rem;
        line-height: 1.2;
    }

    .auth-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 500;
        color: #603F26;
        margin-bottom: 0.2rem;
    }

    .auth-input {
        width: 100%;
        background: #FFDBB5;
        border: 2px solid transparent;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.75rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        transition: box-shadow 0.2s, border-color 0.2s;
        margin-bottom: 0.6rem;
    }
    .auth-input::placeholder { color: rgba(96, 63, 38, 0.45); }
    .auth-input:focus {
        box-shadow: 0 0 0 2.5px rgba(96, 63, 38, 0.25);
    }
    .auth-input.is-invalid {
        border-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.08);
    }
    .auth-input.is-invalid:focus {
        box-shadow: 0 0 0 2.5px rgba(220, 53, 69, 0.25);
    }

    .auth-error {
        display: block;
        color: #dc3545;
        font-size: 0.65rem;
        margin-top: -0.45rem;
        margin-bottom: 0.6rem;
        font-weight: 500;
    }

    .auth-btn {
        display: block;
        width: fit-content;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.5rem 1.5rem;
        font-size: 0.72rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        text-decoration: none;
        margin-top: 0.4rem;
        margin-bottom: 0;
    }
    .auth-btn:hover:not(:disabled) { opacity: 0.85; transform: translateY(-1px); }
    .auth-btn:active:not(:disabled) { transform: translateY(0); }
    .auth-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .auth-switch {
        margin-top: 0.4rem;
        font-size: 0.65rem;
        color: rgba(96, 63, 38, 0.65);
        margin-bottom: 0;
    }
    .auth-switch a {
        color: #603F26;
        font-weight: 600;
        text-decoration: none;
    }
    .auth-switch a:hover { text-decoration: underline; }

    /* ── RIGHT PANEL ── */
    .auth-right {
        overflow: hidden;
    }
    .auth-right img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 45% 20%;
        display: block;
    }

    /* ── Alert / Validation errors ── */
    .auth-alert {
        background: rgba(96, 63, 38, 0.08);
        border-left: 3px solid #603F26;
        border-radius: 8px;
        padding: 0.6rem 0.8rem;
        font-size: 0.7rem;
        color: #603F26;
        margin-bottom: 0.9rem;
    }
    .auth-alert ul { padding-left: 1.1rem; margin: 0; }
    .auth-alert li { margin-bottom: 0.15rem; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .auth-wrapper { grid-template-columns: 1fr; height: auto; }
        .auth-right { display: none; }
        .auth-left { padding: 1.5rem 1.5rem; }
        .auth-form-area { padding: 0; }
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">

    {{-- ── LEFT: FORM AREA ── --}}
    <div class="auth-left">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="auth-brand">
            <img src="{{ asset('images/logo_skinquo_coklat.png') }}" alt="SkinQuo Logo">
            SkinQuo
        </a>

       {{-- Form --}}
<div class="auth-form-area">

    <h1 class="auth-title">Login to SkinQuo</h1>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="auth-alert">{{ session('status') }}</div>
    @endif

    {{-- Info (redirect dari feedback) --}}
    @if (session('info'))
        <div class="auth-alert" style="background:rgba(255,193,7,0.12);border-left-color:#D4841C;color:#7A5030;">
            ⚠️ {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf
                {{-- Email / Mobile --}}
                <label class="auth-label" for="email">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="auth-input @error('email') is-invalid @enderror"
                    placeholder="Email address"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    autofocus
                    maxlength="255"
    onpaste="return false"
    oncopy="return false"
    oncut="return false"
                >
                @error('email')
                    <span class="auth-error">{{ $message }}</span>
                @enderror

                {{-- Password --}}
            <div style="display:flex; justify-content:space-between; align-items:center;">
    <label class="auth-label" for="password">Password</label>
    <a href="{{ route('password.request') }}" style="font-size:0.65rem; color:#603F26; text-decoration:none; font-weight:600;">
        Forgot password?
    </a>
</div>
                <div style="position: relative; margin-bottom: 0.6rem;">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="Password"
                        required
                        autocomplete="current-password"
<<<<<<< HEAD
                        style="padding-right: 2.75rem; margin-bottom: 0;"
=======
                         style="padding-right: 2.75rem;"
    onpaste="return false"
    oncopy="return false"
    oncut="return false"
>>>>>>> 2a6a45dd0b4e1cdadaadfaa8361c59d8f1152379
                    >
                    <button
                        type="button"
                        class="password-toggle"
                        data-target="password"
                        style="position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #603F26; opacity: 0.6; transition: opacity 0.2s; display: flex; align-items: center; justify-content: center; padding: 0;"
                        onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='0.6'"
                    >
                        <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-off-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="auth-error">{{ $message }}</span>
                @enderror

                {{-- Submit --}}
                <button type="submit" class="auth-btn" id="submit-btn">Sign In</button>

            </form>

            <p class="auth-switch">
                Don't have an account? <a href="{{ route('register') }}">Create Account</a>
            </p>

        </div>
    </div>

    {{-- ── RIGHT: IMAGE ── --}}
    <div class="auth-right">
        <img src="{{ asset('images/auth-model.png') }}" alt="SkinQuo Model">
    </div>

</div>

<script>
    // Password visibility toggle functionality
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeOffIcon = this.querySelector('.eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        });
    });

    // Form submission - Disable button and show loading state
    const loginForm = document.getElementById('login-form');
    const submitBtn = document.getElementById('submit-btn');
    
    loginForm.addEventListener('submit', function() {
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Signing In...';
    });

    document.getElementById('email').addEventListener('paste', e => {
    e.preventDefault();
});

document.getElementById('password').addEventListener('paste', e => {
    e.preventDefault();
});
</script>
@endsection