@extends('layouts.app')

@section('title', 'Create Account — SkinQuo')

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
        margin-bottom: 0.3rem;
        line-height: 1.20;
    }

    .auth-subtitle {
        font-size: 0.8rem;
        color: rgba(96, 63, 38, 0.65);
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .auth-label {
        display: block;
        font-size: 0.62rem;
        font-weight: 500;
        color: #603F26;
        margin-bottom: 0.18rem;
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
        margin-bottom: 0.5rem;
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
        margin-top: -0.4rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .auth-select {
        width: 100%;
        background: #FFDBB5;
        border: 2px solid transparent;
        border-radius: 999px;
        padding: 0.48rem 0.85rem;
        font-size: 0.72rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23603F26' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
        margin-bottom: 0.5rem;
    }
    .auth-select:focus {
        box-shadow: 0 0 0 2.5px rgba(96, 63, 38, 0.25);
    }
    .auth-select.is-invalid {
        border-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.08);
    }
    .auth-select.is-invalid:focus {
        box-shadow: 0 0 0 2.5px rgba(220, 53, 69, 0.25);
    }
    .auth-select option { color: #603F26; background: #FFDBB5; }

    /* ── Name row (2 columns) ── */
    .name-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.3rem;
        margin-bottom: 0;
    }
    .name-row .auth-input { margin-bottom: 0.5rem; }

    /* ── Date of Birth & Gender Row (2 columns) ── */
    .dob-gender-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
        margin-bottom: 0;
    }
    .dob-gender-row > div {
        display: flex;
        flex-direction: column;
    }
    .dob-gender-row .auth-input,
    .dob-gender-row .auth-select {
        margin-bottom: 0.5rem;
    }
    .dob-gender-row .auth-error {
        margin-bottom: 0;
    }
    /* Responsive: Stack vertically on mobile */
    @media (max-width: 768px) {
        .dob-gender-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    /* ── Date input styling ── */
    input[type="date"] {
        width: 100%;
        background: #FFDBB5;
        border: 2px solid transparent;
        border-radius: 999px;
        padding: 0.48rem 0.85rem;
        font-size: 0.72rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
        margin-bottom: 0.5rem;
    }
    input[type="date"]::placeholder {
        color: rgba(96, 63, 38, 0.45);
    }
    input[type="date"]:focus {
        box-shadow: 0 0 0 2.5px rgba(96, 63, 38, 0.25);
    }
    input[type="date"].is-invalid {
        border-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.08);
    }
    input[type="date"].is-invalid:focus {
        box-shadow: 0 0 0 2.5px rgba(220, 53, 69, 0.25);
    }
    /* Styling untuk calendar picker */
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        border-radius: 4px;
        margin-right: 0.4rem;
        opacity: 0.6;
        filter: invert(0.3) sepia(0.3) hue-rotate(340deg) saturate(1.5);
    }
    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }

    .auth-btn {
        display: block;
        width: fit-content;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 0.48rem 1.4rem;
        font-size: 0.72rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        text-decoration: none;
        margin-top: 0.15rem;
        margin-bottom: 0;
    }
    .auth-btn:hover:not(:disabled) { opacity: 0.85; transform: translateY(-1px); }
    .auth-btn:active:not(:disabled) { transform: translateY(0); }
    .auth-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .auth-switch {
        margin-top: 0.3rem;
        font-size: 0.62rem;
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

            <h1 class="auth-title">Get started on SkinQuo</h1>
            <p class="auth-subtitle">
                The Right Skin Care Starts Here.<br>
                Get personalized skincare guidance and consultations.
            </p>

            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                {{-- Name --}}
                <label class="auth-label">Name</label>
                <div class="name-row">
                    <div>
                        <input
                            type="text"
                            name="name"
                            class="auth-input @error('name') is-invalid @enderror"
                            placeholder="First name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="given-name"
                        >
                        @error('name')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input
                            type="text"
                            name="surname"
                            class="auth-input @error('surname') is-invalid @enderror"
                            placeholder="Surname"
                            value="{{ old('surname') }}"
                            required
                            autocomplete="family-name"
                        >
                        @error('surname')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Date of Birth & Gender Row --}}
                <div class="dob-gender-row">
                    <div>
                        {{-- Date of Birth --}}
                        <label class="auth-label">Date of birth</label>
                        <input
                            type="date"
                            name="date_birth"
                            id="date_birth"
                            class="auth-input @error('date_birth') is-invalid @enderror"
                            value="{{ old('date_birth') }}"
                            required
                            max="{{ date('Y-m-d', strtotime('-13 years')) }}"
                        >
                        @error('date_birth')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        {{-- Gender --}}
                        <label class="auth-label" for="gender">Gender</label>
                        <select id="gender" name="gender" class="auth-select @error('gender') is-invalid @enderror" required>
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select your gender</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        </select>
                        @error('gender')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
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
                    maxlength="255"
                >
                @error('email')
                    <span class="auth-error">{{ $message }}</span>
                @enderror

                {{-- Password --}}
                <label class="auth-label" for="password">Password</label>
                <div style="position: relative;">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="Password"
                        required
                        autocomplete="new-password"
                        minlength="8"
                        maxlength="255"
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
                @error('password')
                    <span class="auth-error">{{ $message }}</span>
                @enderror

                {{-- Password Confirmation --}}
                <label class="auth-label" for="password_confirmation">Confirm Password</label>
                <div style="position: relative;">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="auth-input @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirm password"
                        required
                        autocomplete="new-password"
                        minlength="8"
                        maxlength="255"
                        style="padding-right: 2.75rem;"
                    >
                    <button
                        type="button"
                        class="password-toggle"
                        data-target="password_confirmation"
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
                @error('password_confirmation')
                    <span class="auth-error">{{ $message }}</span>
                @enderror

                {{-- Submit --}}
                <button type="submit" class="auth-btn" id="submit-btn">Create Account</button>

            </form>

            <p class="auth-switch">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
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
    const registerForm = document.getElementById('register-form');
    const submitBtn = document.getElementById('submit-btn');
    
    registerForm.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating Account...';
    });
</script>
@endsection