@extends('layouts.app')

@section('title', 'Create Account — SkinQuo')

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
        padding: 2.5rem 0;
    }

    .auth-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 0.35rem;
        line-height: 1.25;
    }

    .auth-subtitle {
        font-size: 0.82rem;
        color: rgba(96, 63, 38, 0.65);
        line-height: 1.6;
        margin-bottom: 1.75rem;
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

    .auth-select {
        width: 100%;
        background: #FFDBB5;
        border: none;
        border-radius: 999px;
        padding: 0.75rem 1.25rem;
        font-size: 0.85rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23603F26' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.1rem center;
        cursor: pointer;
        transition: box-shadow 0.2s;
        margin-bottom: 1.25rem;
    }
    .auth-select:focus {
        box-shadow: 0 0 0 2.5px rgba(96, 63, 38, 0.25);
    }
    .auth-select option { color: #603F26; background: #FFDBB5; }

    /* ── Name row (2 columns) ── */
    .name-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
        margin-bottom: 0; /* inputs inside carry their own margin */
    }
    .name-row .auth-input { margin-bottom: 1.25rem; }

    /* ── DOB row (3 columns) ── */
    .dob-row {
        display: grid;
        grid-template-columns: 1fr 1.3fr 1fr;
        gap: 0.65rem;
    }
    .dob-row .auth-select { margin-bottom: 1.25rem; }

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

            <h1 class="auth-title">Get started on SkinQuo</h1>
            <p class="auth-subtitle">
                The Right Skin Care Starts Here.<br>
                Get personalized skincare guidance and consultations.
            </p>

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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <label class="auth-label">Name</label>
                <div class="name-row">
                    <input
                        type="text"
                        name="first_name"
                        class="auth-input"
                        placeholder="First name"
                        value="{{ old('first_name') }}"
                        required
                        autocomplete="given-name"
                    >
                    <input
                        type="text"
                        name="last_name"
                        class="auth-input"
                        placeholder="Surname"
                        value="{{ old('last_name') }}"
                        required
                        autocomplete="family-name"
                    >
                </div>

                {{-- Date of Birth --}}
                <label class="auth-label">Date of birth</label>
                <div class="dob-row">
                    {{-- Day --}}
                    <select name="birth_day" class="auth-select" required>
                        <option value="" disabled {{ old('birth_day') ? '' : 'selected' }}>Day</option>
                        @for ($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}" {{ old('birth_day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endfor
                    </select>

                    {{-- Month --}}
                    <select name="birth_month" class="auth-select" required>
                        <option value="" disabled {{ old('birth_month') ? '' : 'selected' }}>Month</option>
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $month)
                            <option value="{{ $i + 1 }}" {{ old('birth_month') == ($i + 1) ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>

                    {{-- Year --}}
                    <select name="birth_year" class="auth-select" required>
                        <option value="" disabled {{ old('birth_year') ? '' : 'selected' }}>Year</option>
                        @for ($y = date('Y'); $y >= 1940; $y--)
                            <option value="{{ $y }}" {{ old('birth_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Gender --}}
                <label class="auth-label" for="gender">Gender</label>
                <select id="gender" name="gender" class="auth-select" required>
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select your gender</option>
                    <option value="female"     {{ old('gender') == 'female'     ? 'selected' : '' }}>Female</option>
                    <option value="male"       {{ old('gender') == 'male'       ? 'selected' : '' }}>Male</option>
                    <option value="non_binary" {{ old('gender') == 'non_binary' ? 'selected' : '' }}>Non-binary</option>
                    <option value="prefer_not" {{ old('gender') == 'prefer_not' ? 'selected' : '' }}>Prefer not to say</option>
                </select>

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
                    autocomplete="new-password"
                >

                {{-- Hidden: combine first + last for the 'name' field if needed --}}
                {{-- If your User model uses a single 'name' column, handle in Controller --}}

                {{-- Submit --}}
                <button type="submit" class="auth-btn">Create Account</button>

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
@endsection