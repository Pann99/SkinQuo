@extends('layouts.app')

@section('title', 'Change Password — SkinQuo')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Poppins:wght@400;500;600;700&display=swap');

    .cp-page {
        background: #FFEAC5;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .cp-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 7rem 1.5rem 5rem;
    }

    /* ── Back — teks + panah, tanpa background ── */
    .cp-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.55);
        text-decoration: none;
        align-self: center;
        margin-bottom: 2.5rem;
        font-family: 'Poppins', sans-serif;
        transition: color 0.22s ease;
    }
    .cp-back:hover {
        color: #603F26;
    }
    .cp-back svg {
        transition: transform 0.22s ease;
        flex-shrink: 0;
    }
    .cp-back:hover svg {
        transform: translateX(-3px);
    }

    /* ── Headline ── */
    .cp-headline {
        text-align: center;
        margin-bottom: 3rem;
    }
    .cp-headline h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.2rem, 6vw, 3.25rem);
        font-weight: 700;
        font-style: italic;
        color: #3D2000;
        line-height: 1.1;
        margin-bottom: 0.9rem;
    }
    .cp-headline p {
        font-size: 0.92rem;
        color: rgba(96, 63, 38, 0.65);
        line-height: 1.7;
        max-width: 360px;
        margin: 0 auto;
    }

    /* ── Form wrapper ── */
    .cp-form-wrap {
        width: 100%;
        max-width: 560px;
    }

    /* ── Alert ── */
    .cp-alert {
        border-radius: 12px;
        padding: 0.8rem 1.1rem;
        font-size: 0.8rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    .cp-alert-success {
        background: rgba(96, 63, 38, 0.08);
        border-left: 3px solid #603F26;
        color: #603F26;
    }
    .cp-alert-error {
        background: rgba(180, 60, 40, 0.08);
        border-left: 3px solid #c0604a;
        color: #8b3020;
    }
    .cp-alert ul { padding-left: 1.1rem; margin: 0; }

    /* ── Field ── */
    .cp-field { margin-bottom: 1.75rem; }

    .cp-field-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.55);
        margin-bottom: 0.6rem;
        font-family: 'Poppins', sans-serif;
    }

    /* Badge locked */
    .cp-locked-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.55rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.38);
        background: rgba(96, 63, 38, 0.07);
        border: 1px solid rgba(96, 63, 38, 0.14);
        border-radius: 999px;
        padding: 2px 7px;
        font-family: 'Poppins', sans-serif;
    }

    .cp-input-wrap { position: relative; }

    /* ── READONLY — lonjong, border coklat, teks redup ── */
    .cp-input-readonly {
        width: 100%;
        background: rgba(96, 63, 38, 0.05);
        border: 1.5px solid rgba(96, 63, 38, 0.22);
        border-radius: 999px;
        padding: 0.95rem 1.4rem;
        font-size: 0.88rem;
        font-family: 'Poppins', sans-serif;
        color: rgba(96, 63, 38, 0.55);
        font-style: italic;
        font-weight: 500;
        outline: none;
        cursor: not-allowed;
        box-sizing: border-box;
    }

    /* ── EDITABLE — lonjong, putih bersih, border coklat solid ── */
    .cp-input {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid rgba(96, 63, 38, 0.45);
        border-radius: 999px;
        padding: 0.95rem 3.2rem 0.95rem 1.4rem;
        font-size: 0.88rem;
        font-family: 'Poppins', sans-serif;
        color: #2C1500;
        font-weight: 600;
        outline: none;
        transition: border-color 0.22s, box-shadow 0.22s;
        box-sizing: border-box;
        box-shadow: 0 1px 4px rgba(96, 63, 38, 0.08);
    }
    .cp-input::placeholder {
        color: rgba(96, 63, 38, 0.45);
        font-weight: 400;
        font-style: italic;
        font-size: 0.83rem;
    }
    .cp-input:focus {
        border-color: #3D2000;
        box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.14);
    }

    /* Eye toggle */
    .cp-eye-btn {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(96, 63, 38, 0.32);
        padding: 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .cp-eye-btn:hover { color: #603F26; }

    /* ── Strength meter ── */
    .cp-strength-wrap {
        margin-top: 1.1rem;
        padding: 0.85rem 1rem;
        background: rgba(96, 63, 38, 0.04);
        border-radius: 12px;
    }
    .cp-strength-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.55rem;
    }
    .cp-strength-label {
        font-size: 0.65rem;
        color: rgba(96, 63, 38, 0.50);
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
    }
    .cp-strength-word {
        font-size: 0.72rem;
        font-weight: 700;
        color: #603F26;
        transition: color 0.3s;
        letter-spacing: 0.02em;
        font-family: 'Poppins', sans-serif;
    }
    .cp-strength-bar-track {
        width: 100%;
        height: 3px;
        background: rgba(96, 63, 38, 0.10);
        border-radius: 999px;
        overflow: hidden;
        margin-bottom: 0.8rem;
    }
    .cp-strength-bar-fill {
        height: 100%;
        border-radius: 999px;
        width: 0%;
        transition: width 0.4s cubic-bezier(0.4,0,0.2,1), background 0.3s;
        background: #603F26;
    }
    .cp-strength-checks {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .cp-check-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.7rem;
        color: rgba(96, 63, 38, 0.38);
        transition: color 0.25s;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }
    .cp-check-item.met { color: #603F26; }
    .cp-check-icon {
        width: 14px; height: 14px;
        border-radius: 50%;
        border: 1.5px solid rgba(96, 63, 38, 0.18);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: border-color 0.25s, background 0.25s;
    }
    .cp-check-item.met .cp-check-icon {
        border-color: #603F26;
        background: #603F26;
    }
    .cp-check-icon::after {
        content: '✓';
        font-size: 0.6rem;
        color: #FFEAC5;
        font-weight: 800;
        display: none;
        line-height: 1;
    }
    .cp-check-item.met .cp-check-icon::after { display: block; }

    /* ── Submit ── */
    .cp-submit-wrap { margin-top: 2.5rem; }
    .cp-submit-btn {
        width: 100%;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 999px;
        padding: 1.05rem 2rem;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cp-submit-btn:hover {
        background: #3D2000;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(96, 63, 38, 0.28);
    }
    .cp-submit-btn:active { transform: translateY(0); }

    /* ── Toast ── */
    .toast-notification {
        position: fixed;
        top: 2rem; right: 2rem;
        background: #3D2000;
        color: #FFEAC5;
        border-radius: 10px;
        padding: 1.1rem 1.4rem;
        font-size: 0.82rem;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        z-index: 9999;
        animation: slideInRight 0.4s cubic-bezier(0.4,0,0.2,1);
        max-width: 380px;
    }
    .toast-notification.success::before { content: '✓'; font-size: 1.1rem; font-weight: 700; }
    .toast-notification.error::before   { content: '⚠'; font-size: 1.1rem; }
    .toast-close {
        margin-left: auto; background: none; border: none;
        color: rgba(255,234,197,0.55); cursor: pointer;
        font-size: 1.1rem; padding: 0; transition: color 0.2s;
    }
    .toast-close:hover { color: #FFEAC5; }
    @keyframes slideInRight {
        from { transform: translateX(400px); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }
    @keyframes fadeOutRight {
        from { transform: translateX(0);     opacity: 1; }
        to   { transform: translateX(400px); opacity: 0; }
    }
    .toast-notification.hide {
        animation: fadeOutRight 0.4s cubic-bezier(0.4,0,0.2,1) forwards;
    }

    @media (max-width: 600px) {
        .cp-main { padding: 6rem 1.25rem 4rem; }
        .cp-form-wrap { max-width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="cp-page">
<div class="cp-main">

    {{-- ── Back — teks + panah, tanpa pill/background ── --}}
    <a href="{{ route('profile.show') }}" class="cp-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M5 12L12 19M5 12L12 5"/>
        </svg>
        Back to Profile
    </a>

    {{-- ── Headline ── --}}
    <div class="cp-headline">
        <h1>Secure Your Sanctuary</h1>
        <p>Ensure your personal ritual remains private with a strong, updated password.</p>
    </div>

    {{-- ── Form ── --}}
    <div class="cp-form-wrap">

        @if (session('status'))
            <div class="cp-alert cp-alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="cp-alert cp-alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}" id="cpForm">
            @csrf
            @method('PUT')

            {{-- User Name — readonly ── --}}
            <div class="cp-field">
                <label class="cp-field-label">
                    User Name
                    <span class="cp-locked-badge">
                        <svg width="7" height="7" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Read-only
                    </span>
                </label>
                <input
                    type="text"
                    class="cp-input-readonly"
                    value="{{ Auth::user()->username ?? 'User' }}"
                    readonly
                    tabindex="-1"
                >
            </div>

            {{-- Current Password ── --}}
            <div class="cp-field">
                <label class="cp-field-label" for="current_password">Current Password</label>
                <div class="cp-input-wrap">
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="cp-input"
                        placeholder="Enter current password"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="cp-eye-btn"
                            data-target="current_password"
                            aria-label="Show/hide password">
                        <svg class="eye-show" width="18" height="18" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-hide" width="18" height="18" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                             style="display:none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- New Password + Strength ── --}}
            <div class="cp-field">
                <label class="cp-field-label" for="password">New Password</label>
                <div class="cp-input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="cp-input"
                        placeholder="Create a strong new password"
                        required
                        minlength="8"
                        maxlength="255"
                        autocomplete="new-password"
                        oninput="updateStrength(this.value)"
                    >
                    <button type="button" class="cp-eye-btn"
                            data-target="password"
                            aria-label="Show/hide password">
                        <svg class="eye-show" width="18" height="18" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-hide" width="18" height="18" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                             style="display:none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>

                <div class="cp-strength-wrap">
                    <div class="cp-strength-header">
                        <span class="cp-strength-label">Ritual Strength</span>
                        <span class="cp-strength-word" id="strengthWord">—</span>
                    </div>
                    <div class="cp-strength-bar-track">
                        <div class="cp-strength-bar-fill" id="strengthBar"></div>
                    </div>
                    <div class="cp-strength-checks">
                        <div class="cp-check-item" id="check-length">
                            <div class="cp-check-icon"></div>
                            8+ characters
                        </div>
                        <div class="cp-check-item" id="check-uppercase">
                            <div class="cp-check-icon"></div>
                            Uppercase &amp; Symbols
                        </div>
                    </div>
                </div>
            </div>

            {{-- Confirm New Password ── --}}
            <div class="cp-field" style="margin-bottom:0;">
                <label class="cp-field-label" for="password_confirmation">
                    Confirm New Password
                </label>
                <div class="cp-input-wrap">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="cp-input"
                        placeholder="Confirm new password"
                        required
                        maxlength="255"
                        autocomplete="new-password"
                    >
                    <button type="button" class="cp-eye-btn"
                            data-target="password_confirmation"
                            aria-label="Show/hide password">
                        <svg class="eye-show" width="18" height="18" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-hide" width="18" height="18" fill="none"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                             style="display:none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Submit ── --}}
            <div class="cp-submit-wrap">
                <button type="submit" class="cp-submit-btn">
                    Save New Password
                </button>
            </div>

        </form>
    </div>

</div>
</div>

<script>
    function showToast(message, type = 'success', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `<span>${message}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">✕</button>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            if (document.body.contains(toast)) {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 400);
            }
        }, duration);
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if (session('status'))
            showToast("{{ session('status') }}", 'success', 4000);
        @endif
    });

    function updateStrength(val) {
        var bar      = document.getElementById('strengthBar');
        var word     = document.getElementById('strengthWord');
        var checkLen = document.getElementById('check-length');
        var checkUp  = document.getElementById('check-uppercase');

        var hasLength = val.length >= 8;
        var hasUpper  = /[A-Z]/.test(val);
        var hasSymbol = /[^A-Za-z0-9]/.test(val) || /[0-9]/.test(val);

        checkLen.classList.toggle('met', hasLength);
        checkUp.classList.toggle('met',  hasUpper && hasSymbol);

        if (val.length === 0) {
            bar.style.width  = '0%';
            word.textContent = '—';
            word.style.color = '';
            return;
        }

        var score = 0;
        if (hasLength)        score++;
        if (val.length >= 12) score++;
        if (hasUpper)         score++;
        if (hasSymbol)        score++;

        var levels = [
            { pct: 25,  label: 'Awakening',  color: '#e07050' },
            { pct: 50,  label: 'Balanced',   color: '#c08040' },
            { pct: 75,  label: 'Harmonious', color: '#8a6030' },
            { pct: 100, label: 'Radiant',    color: '#603F26' },
        ];
        var level = levels[Math.min(score - 1, 3)];
        bar.style.width      = level.pct + '%';
        bar.style.background = level.color;
        word.textContent     = level.label;
        word.style.color     = level.color;
    }

    document.querySelectorAll('.cp-eye-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var input = document.getElementById(this.getAttribute('data-target'));
            var show  = this.querySelector('.eye-show');
            var hide  = this.querySelector('.eye-hide');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                show.style.display = 'none';
                hide.style.display = 'block';
            } else {
                input.type = 'password';
                show.style.display = 'block';
                hide.style.display = 'none';
            }
        });
    });


</script>
@endsection