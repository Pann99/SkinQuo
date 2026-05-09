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
        display: inline-block;
    }
    .auth-brand:hover { opacity: 0.75; }

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
        <a href="{{ route('home') }}" class="auth-brand">SkinQuo</a>

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
                <label class="auth-label" for="email">Mobile number or email address</label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    class="auth-input"
                    placeholder="Mobile number or email address"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    autofocus
                >

                {{-- Password --}}
                <label class="auth-label" for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="auth-input"
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                >

                {{-- Remember (hidden, default true for UX) --}}
                <input type="hidden" name="remember" value="1">

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
@endsection