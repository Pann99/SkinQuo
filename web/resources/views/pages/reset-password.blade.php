@extends('layouts.app')

@section('title', 'Reset Password — SkinQuo')

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
    font-family: 'Poppins', sans-serif;
    color: #603F26;
    margin-bottom: 0.9rem;
    line-height: 1.6;
}
.auth-alert ul { padding-left: 1.1rem; margin: 0; }
.auth-alert li { margin-bottom: 0.15rem; }

    /* Password strength meter */
    .pw-strength-wrap {
        margin-top: -0.3rem;
        margin-bottom: 0.6rem;
    }
    .pw-strength-bar-bg {
        background: #EADBBF;
        border-radius: 999px;
        height: 4px;
        width: 100%;
        margin-bottom: 6px;
    }
    .pw-strength-bar {
        background: #603F26;
        border-radius: 999px;
        height: 4px;
        width: 0%;
        transition: width 0.3s ease, background 0.3s ease;
    }
    .pw-checklist {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .pw-checklist span {
        font-size: 0.6rem;
        color: #A89482;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: color 0.2s;
    }

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

            <h1 class="auth-title">Reset Password</h1>


        {{-- Status sukses (misal setelah klik "Send me a new reset link") --}}
@if (session('status'))
    <div class="auth-alert">{{ session('status') }}</div>
@endif

{{-- Error token invalid + tombol kirim ulang otomatis --}}
@error('email')
    <div class="auth-alert" style="background:rgba(220,53,69,0.08); border-left-color:#dc3545; color:#dc3545;">
        <i class="bi bi-exclamation-triangle-fill" style="margin-right:4px;"></i>{{ $message }}
        <br>
        <form method="POST" action="{{ route('password.email') }}" style="margin-top:6px; display:inline;">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">
            <button type="submit" style="background:none; border:none; padding:0; color:#603F26; font-weight:600; text-decoration:underline; cursor:pointer; font-family:'Poppins', sans-serif; font-size:0.7rem;">
                Send me a new reset link
            </button>
        </form>
    </div>
@enderror

            <form method="POST" action="{{ route('password.store') }}" id="reset-form" onsubmit="return validateResetForm()">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <label class="auth-label" for="email">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="auth-input @error('email') is-invalid @enderror"
                    placeholder="Email address"
                    value="{{ old('email', $request->email) }}"
                    required
                    autocomplete="username"
                    readonly
                    maxlength="255"
                >

                {{-- NEW PASSWORD --}}
                <label class="auth-label" for="password">New Password</label>
                <div style="position: relative;">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="New password"
                        autocomplete="new-password"
                        style="padding-right: 2.75rem;"
                        oninput="checkStrength(this.value); checkMatch();"
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

                {{-- Strength meter --}}
                <div class="pw-strength-wrap">
                    <div class="pw-strength-bar-bg">
                        <div id="strengthBar" class="pw-strength-bar"></div>
                    </div>
                    <div class="pw-checklist">
                        <span id="check8char"><i class="bi bi-circle"></i> 8+ characters</span>
                        <span id="checkUpper"><i class="bi bi-circle"></i> Uppercase</span>
                        <span id="checkSymbol"><i class="bi bi-circle"></i> Symbol</span>
                    </div>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <label class="auth-label" for="password_confirmation">Confirm Password</label>
                <div style="position: relative;">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Confirm new password"
                        autocomplete="new-password"
                        style="padding-right: 2.75rem;"
                        oninput="checkMatch()"
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

                <button type="submit" class="auth-btn" id="submit-btn">Reset Password</button>

            </form>

        </div>
    </div>

    <div class="auth-right">
        <img src="{{ asset('images/auth-model.png') }}" alt="SkinQuo Model">
    </div>

</div>

<script>
    // Password visibility toggle
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

    // Strength checker
    function checkStrength(val) {
        const has8 = val.length >= 8;
        const hasUpper = /[A-Z]/.test(val);
        const hasSym = /[^a-zA-Z0-9]/.test(val);

        const setCheck = (id, passed, label) => {
            const el = document.getElementById(id);
            el.innerHTML = (passed ? '<i class="bi bi-check-circle-fill" style="color:#603F26;"></i>' : '<i class="bi bi-circle"></i>') + ' ' + label;
            el.style.color = passed ? '#603F26' : '#A89482';
        };

        setCheck('check8char', has8, '8+ characters');
        setCheck('checkUpper', hasUpper, 'Uppercase');
        setCheck('checkSymbol', hasSym, 'Symbol');

        const score = [has8, hasUpper, hasSym].filter(Boolean).length;
        const map = {
            0: { w: 0,   c: '#603F26' },
            1: { w: 33,  c: '#dc3545' },
            2: { w: 66,  c: '#D4841C' },
            3: { w: 100, c: '#4A9B5A' }
        };
        const level = val.length === 0 ? 0 : Math.max(score, 1);
        const cfg = map[Math.min(level, 3)];
        document.getElementById('strengthBar').style.width = cfg.w + '%';
        document.getElementById('strengthBar').style.background = cfg.c;
    }

    // Match checker (visual only, real validation on submit)
    function checkMatch() {
        const np = document.getElementById('password').value;
        const cp = document.getElementById('password_confirmation').value;
        const cpField = document.getElementById('password_confirmation');

        document.querySelectorAll('.match-error').forEach(el => el.remove());

        if (cp && np !== cp) {
            cpField.classList.add('is-invalid');
        } else {
            cpField.classList.remove('is-invalid');
        }
    }

    // Disable paste on password fields
    ['password', 'password_confirmation'].forEach(function(id) {
        const el = document.getElementById(id);
        el.addEventListener('paste', function(e) {
            e.preventDefault();
            const existing = document.getElementById('pasteError-' + id);
            if (!existing) {
                const msg = document.createElement('span');
                msg.id = 'pasteError-' + id;
                msg.className = 'auth-error';
                msg.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Paste is not allowed. Please type manually.';
                this.parentElement.insertAdjacentElement('afterend', msg);
            }
        });
        el.addEventListener('input', function() {
            const pasteErr = document.getElementById('pasteError-' + id);
            if (pasteErr) pasteErr.remove();
            const fieldError = this.parentElement.parentElement.querySelector('.js-error');
            if (fieldError) fieldError.remove();
        });
    });

    // Full form validation before submit
    function validateResetForm() {
        const np = document.getElementById('password').value;
        const cp = document.getElementById('password_confirmation').value;

        document.querySelectorAll('.js-error').forEach(el => el.remove());

        const has8 = np.length >= 8;
        const hasUpper = /[A-Z]/.test(np);
        const hasSym = /[^a-zA-Z0-9]/.test(np);

        let hasError = false;

        function showError(inputId, message) {
            const field = document.getElementById(inputId);
            const msg = document.createElement('span');
            msg.className = 'auth-error js-error';
            msg.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + message;
            // insert after the field's wrapper (or directly after field)
            const wrapper = field.closest('div[style*="position: relative"]') || field;
            wrapper.insertAdjacentElement('afterend', msg);
            hasError = true;
        }

        if (!np) {
            showError('password', 'New password is required.');
        } else if (!has8) {
            showError('password', 'Password must be at least 8 characters.');
        } else if (!hasUpper) {
            showError('password', 'Password must contain at least one uppercase letter.');
        } else if (!hasSym) {
            showError('password', 'Password must contain at least one symbol.');
        }

        if (!cp) {
            showError('password_confirmation', 'Please confirm your new password.');
        } else if (np && np !== cp) {
            showError('password_confirmation', 'Password confirmation does not match.');
        }

        if (hasError) {
            const firstError = document.querySelector('.js-error');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Resetting...';

        return true;
    }
</script>
@endsection