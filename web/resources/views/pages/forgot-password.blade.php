@extends('layouts.app')

@section('title', 'Forgot Password — SkinQuo')

@push('styles')
<style>
    .navbar-wrap { display: none !important; }
    footer { display: none !important; }

    body { background: #FFEAC5; }

    .auth-wrapper {
        display: grid;
        grid-template-columns: 63% 37%;
        height: 100vh;
        overflow: hidden;
    }

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
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .auth-subtitle {
        font-size: 0.72rem;
        color: rgba(96, 63, 38, 0.65);
        margin-bottom: 1rem;
        line-height: 1.5;
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

    <div class="auth-left">

        <a href="{{ route('home') }}" class="auth-brand">
            <img src="{{ asset('images/logo_skinquo_coklat.png') }}" alt="SkinQuo Logo">
            SkinQuo
        </a>

        <div class="auth-form-area">

            <h1 class="auth-title">Forgot your password?</h1>
            <p class="auth-subtitle">
                No worries — enter your email address below and we'll send you a link to reset your password.
            </p>

            @if (session('status'))
                <div class="auth-alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgot-form">
                @csrf

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
                >
                @error('email')
                    <span class="auth-error">{{ $message }}</span>
                @enderror

                <button type="submit" class="auth-btn" id="submit-btn">Send Reset Link</button>

            </form>

            <p class="auth-switch">
                Remembered your password? <a href="{{ route('login') }}">Back to Login</a>
            </p>

        </div>
    </div>

    <div class="auth-right">
        <img src="{{ asset('images/auth-model.png') }}" alt="SkinQuo Model">
    </div>

</div>

<script>
    const forgotForm = document.getElementById('forgot-form');
    const submitBtn = document.getElementById('submit-btn');

    forgotForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
    });
</script>
@endsection