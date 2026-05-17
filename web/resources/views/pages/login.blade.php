@extends('layouts.app')

@section('title', 'Login — SkinQuo')

@push('styles')
<style>
    /* Hide navbar on auth pages */
    .navbar-wrap { display: none !important; }

    body { background: #FFEAC5; }

    .auth-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 100vh;
    }

    /* ── LEFT PANEL ── */
    .auth-left {
        background: #FFEAC5;
        display: flex;
        flex-direction: column;
        padding: 2.5rem 3.5rem 2.5rem 3.5rem;
        position: relative;
    }

    .auth-brand {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #603F26;
        text-decoration: none;
        letter-spacing: -0.02em;
        margin-bottom: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }
    .auth-brand:hover { opacity: 0.75; }
    .auth-brand img {
        width: 45px;
        height: 45px;
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
        padding: 3rem 0;
    }

    .auth-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 2rem;
        line-height: 1.25;
    }

    .auth-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 500;
        color: #603F26;
        margin-bottom: 0.45rem;
    }

    .auth-input {
        width: 100%;
        background: #FFDBB5;
        border: none;
        border-radius: 999px;
        padding: 0.75rem 1.25rem;
        font-size: 0.85rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        transition: box-shadow 0.2s;
        margin-bottom: 1.25rem;
    }
    .auth-input::placeholder { color: rgba(96, 63, 38, 0.45); }
    .auth-input:focus {
        box-shadow: 0 0 0 2.5px rgba(96, 63, 38, 0.25);
    }

    .auth-btn {
        display: block;
        width: fit-content;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.72rem 2.2rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        text-decoration: none;
        margin-top: 0.5rem;
    }
    .auth-btn:hover { opacity: 0.85; transform: translateY(-1px); }
    .auth-btn:active { transform: translateY(0); }

    .auth-switch {
        margin-top: 1rem;
        font-size: 0.82rem;
        color: rgba(96, 63, 38, 0.65);
    }
    .auth-switch a {
        color: #603F26;
        font-weight: 600;
        text-decoration: none;
    }
    .auth-switch a:hover { text-decoration: underline; }

    /* ── RIGHT PANEL ── */
    .auth-right {
        position: relative;
        overflow: hidden;
    }
    .auth-right img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
    }

    /* ── Alert / Validation errors ── */
    .auth-alert {
        background: rgba(96, 63, 38, 0.08);
        border-left: 3px solid #603F26;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.78rem;
        color: #603F26;
        margin-bottom: 1.25rem;
    }
    .auth-alert ul { padding-left: 1.1rem; }
    .auth-alert li { margin-bottom: 0.2rem; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .auth-wrapper { grid-template-columns: 1fr; }
        .auth-right { display: none; }
        .auth-left { padding: 2rem 1.5rem; }
        .auth-form-area { padding: 2rem 0; }
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

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="auth-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email / Mobile --}}
                <label class="auth-label" for="email">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="auth-input"
                    placeholder="Email address"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    autofocus
                    maxlength="255"
                >

                {{-- Password --}}
                <label class="auth-label" for="password">Password</label>
                <div style="position: relative; margin-bottom: 1.25rem;">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth-input"
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                        style="padding-right: 2.75rem;"
                    >
                    <button
                        type="button"
                        class="password-toggle"
                        data-target="password"
                        style="position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #603F26; opacity: 0.6; transition: opacity 0.2s;"
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

                {{-- Submit --}}
                <button type="submit" class="auth-btn">Sign In</button>

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
</script>
@endsection