@extends('layouts.admin.admin')
@section('title', 'Change Password - Admin Portal')

@push('styles')
<style>
  .admin-main, .admin-footer {
    margin-left: 0 !important;
    padding-left: 0 !important;
    width: 100% !important;
  }
</style>
@endpush

@section('content')
<div style="display: flex; flex-direction: column; align-items: center; padding: 48px 24px 60px; width: 100%; box-sizing: border-box;">
  <div style="width: 100%; max-width: 680px;">

    {{-- BACK TO PROFILE ADMIN --}}
    <div style="margin-bottom: 28px;">
      <a href="{{ route('admin.profile') }}"
         style="font-family:'Jost'; font-size:13px; color:var(--brown-mid); text-decoration:none; display:inline-flex; align-items:center; gap:6px; letter-spacing:0.04em; transition:color 0.2s;"
         onmouseover="this.style.color='var(--brown-dark)'"
         onmouseout="this.style.color='var(--brown-mid)'">
        <i class="bi bi-arrow-left"></i> Back to Profile
      </a>
    </div>

    {{-- HERO TEXT --}}
    <div style="margin-bottom: 36px;">
      <h1 style="font-family:'Playfair Display'; font-style:italic; font-weight:700; font-size:36px; color:var(--brown-dark); margin-bottom:10px; line-height:1.15;">
        Secure Your Portal
      </h1>
      <p style="font-family:'Jost'; font-size:15px; color:var(--brown-mid); line-height:1.6; margin:0;">
        Keep your admin access protected with a strong, regularly updated password.
      </p>
    </div>

    {{-- SUCCESS MESSAGE DARI BACKEND --}}
    @if(session('status') === 'password-updated' || session('success'))
      <div style="background:#EEF7EE; border:1px solid #A8D5A2; border-radius:16px; padding:16px 24px; margin-bottom:28px; display:flex; align-items:center; gap:12px;">
        <i class="bi bi-check-circle-fill" style="color:#4A9B5A; font-size:18px;"></i>
        <span style="font-family:'Jost'; font-size:14px; color:#2D6B36; font-weight:500;">
          {{ session('success') ?? 'Password updated successfully.' }}
        </span>
      </div>
    @endif

    {{-- JAVASCRIPT ERROR ALERT BOX (Hidden by default) --}}
    <div id="jsErrorAlert" style="display:none; background:#FFF5F5; border:1px solid #FC8181; border-radius:16px; padding:16px 24px; margin-bottom:28px; align-items:flex-start; gap:12px;">
      <i class="bi bi-exclamation-triangle-fill" style="color:#C04444; font-size:18px; margin-top:2px;"></i>
      <div style="font-family:'Jost'; font-size:14px; color:#9B2335;">
        <span style="font-weight:600; display:block; margin-bottom:4px;">Action Required:</span>
        <ul id="jsErrorList" style="margin:0; padding-left:16px; line-height:1.6;"></ul>
      </div>
    </div>

    {{-- FORM CARD ADMIN --}}
    @if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorMap = {
            'current_password': @json($errors->first('current_password')),
            'new_password':     @json($errors->first('new_password')),
            'new_password_confirmation': @json($errors->first('new_password_confirmation')),
        };
        const idMap = {
            'current_password':          'currentPasswordInput',
            'new_password':              'newPasswordInput',
            'new_password_confirmation': 'confirmPasswordInput',
        };
        Object.entries(errorMap).forEach(([key, msg]) => {
            if (!msg) return;
            const inputId = idMap[key];
            const field = document.getElementById(inputId);
            if (!field) return;
            const el = document.createElement('span');
            el.className = 'cp-inline-error';
            el.style.cssText = 'color:#C04444; font-size:12px; font-family:"Jost"; display:inline-flex; align-items:center; gap:5px; margin-top:6px;';
            el.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + msg;
            field.closest('div').parentElement.appendChild(el);
        });
        const firstError = document.querySelector('.cp-inline-error');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
</script>
@endif

    <div style="background:#FAF4EB; border-radius:32px; padding:40px 44px; box-shadow:0 16px 48px rgba(61,35,20,0.05); border:1px solid rgba(124,90,60,0.12);">
      <form action="{{ route('admin.profile.update-password') }}" method="POST" onsubmit="return validateForm()">
        @csrf
        @method('PUT')

        {{-- ADMIN NAME --}}
        <div style="margin-bottom:24px;">
          <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:8px; font-weight:600;">
            Admin Name
          </label>
          <input type="text" value="{{ auth()->user()->name ?? 'Administrator' }}" disabled style="background:#FFFFFF; border:none; border-radius:999px; padding:16px 24px; width:100%; outline:none; font-family:'Jost'; font-size:14px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02); opacity:0.75; cursor:not-allowed; box-sizing:border-box;">
        </div>

        {{-- CURRENT PASSWORD --}}
        <div style="margin-bottom:24px;">
          <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:8px; font-weight:600;">
            Current Password
          </label>
          <div style="position:relative; width:100%;">
          <input type="password" name="current_password" id="currentPasswordInput" 
    style="background:#FFFFFF; border:none; border-radius:999px; padding:16px 52px 16px 24px; width:100%; outline:none; font-family:'Jost'; font-size:14px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02); box-sizing:border-box;" 
    placeholder="••••••••">
            <button type="button" onclick="togglePasswordVisibility('currentPasswordInput', this)" style="position:absolute; right:18px; top:50%; transform:translateY(-50%); background:transparent; border:none; color:#7A5C43; cursor:pointer; font-size:17px; display:flex; align-items:center; padding:0;">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        {{-- NEW PASSWORD --}}
        <div style="margin-bottom:24px;">
          <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:8px; font-weight:600;">
            New Password
          </label>
          <div style="position:relative; width:100%;">
            <input type="password" name="new_password" id="newPasswordInput" 
    style="background:#FFFFFF; border:none; border-radius:999px; padding:16px 52px 16px 24px; width:100%; outline:none; font-family:'Jost'; font-size:14px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02); box-sizing:border-box;" 
    placeholder="••••••••" oninput="checkStrength(this.value); checkMatch();">
            <button type="button" onclick="togglePasswordVisibility('newPasswordInput', this)" style="position:absolute; right:18px; top:50%; transform:translateY(-50%); background:transparent; border:none; color:#7A5C43; cursor:pointer; font-size:17px; display:flex; align-items:center; padding:0;">
              <i class="bi bi-eye"></i>
            </button>
          </div>

          {{-- Strength Meter --}}
          <div style="margin-top:14px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:7px;">
              <span style="font-family:'Jost'; font-size:11px; color:#7A5C43; font-weight:600; text-transform:uppercase; letter-spacing:0.06em;">Password Strength</span>
              <span id="strengthLabel" style="font-family:'Jost'; font-size:12px; color:#3C2010; font-weight:700;">—</span>
            </div>
            <div style="background:#EADBBF; border-radius:999px; height:5px; width:100%;">
              <div id="strengthBar" style="background:var(--brown-dark); border-radius:999px; height:5px; width:0%; transition:width 0.35s ease, background 0.35s ease;"></div>
            </div>
            <div style="display:flex; gap:24px; margin-top:10px; flex-wrap:wrap;">
              <span id="check8char" style="font-family:'Jost'; font-size:12px; color:#A89482; display:inline-flex; align-items:center; gap:5px; transition:color 0.2s;"><i class="bi bi-circle"></i> 8+ characters</span>
              <span id="checkUpper" style="font-family:'Jost'; font-size:12px; color:#A89482; display:inline-flex; align-items:center; gap:5px; transition:color 0.2s;"><i class="bi bi-circle"></i> Uppercase letter</span>
              <span id="checkSymbol" style="font-family:'Jost'; font-size:12px; color:#A89482; display:inline-flex; align-items:center; gap:5px; transition:color 0.2s;"><i class="bi bi-circle"></i> Symbol</span>
            </div>
          </div>
        </div>

        {{-- CONFIRM NEW PASSWORD --}}
        <div style="margin-bottom:32px;">
          <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:8px; font-weight:600;">
            Confirm New Password
          </label>
          <div style="position:relative; width:100%;">
           {{-- Hanya ini yang diubah, hapus required dari confirm password saja --}}
<input type="password" name="new_password_confirmation" id="confirmPasswordInput" 
    style="background:#FFFFFF; border:none; border-radius:999px; padding:16px 52px 16px 24px; width:100%; outline:none; font-family:'Jost'; font-size:14px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02); box-sizing:border-box;" 
    placeholder="••••••••" oninput="checkMatch()">
            <button type="button" onclick="togglePasswordVisibility('confirmPasswordInput', this)" style="position:absolute; right:18px; top:50%; transform:translateY(-50%); background:transparent; border:none; color:#7A5C43; cursor:pointer; font-size:17px; display:flex; align-items:center; padding:0;">
              <i class="bi bi-eye"></i>
            </button>
          </div>
      
        </div>

        {{-- ACTIONS --}}
        <div style="display:flex; gap:12px;">
          <a href="{{ route('admin.profile') }}" style="flex:1; background:transparent; color:var(--brown-dark); border:1.5px solid rgba(124,90,60,0.35); padding:15px; border-radius:999px; font-family:'Jost'; font-size:12px; letter-spacing:0.10em; text-transform:uppercase; cursor:pointer; font-weight:600; text-align:center; text-decoration:none; display:block; transition: background 0.2s;">Cancel</a>
          <button type="submit" style="flex:2; background:var(--brown-dark); color:white; border:none; padding:15px; border-radius:999px; font-family:'Jost'; font-size:12px; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer; font-weight:600; text-align:center; transition: background 0.2s;" onmouseover="this.style.background='var(--brown-mid)'" onmouseout="this.style.background='var(--brown-dark)'"><i class="bi bi-shield-lock" style="margin-right:6px;"></i> Save New Password</button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection


@push('scripts')
<script>
function togglePasswordVisibility(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { input.type = 'password'; icon.className = 'bi bi-eye'; }
}

function checkStrength(val) {
    const has8 = val.length >= 8, hasUpper = /[A-Z]/.test(val), hasSym = /[^a-zA-Z0-9]/.test(val);
    const setCheck = (id, passed, label) => {
        const el = document.getElementById(id);
        el.innerHTML = (passed ? '<i class="bi bi-check-circle-fill" style="color:var(--brown-dark);"></i>' : '<i class="bi bi-circle"></i>') + ' ' + label;
        el.style.color = passed ? 'var(--brown-dark)' : '#A89482';
    };
    setCheck('check8char', has8, '8+ characters');
    setCheck('checkUpper', hasUpper, 'Uppercase letter');
    setCheck('checkSymbol', hasSym, 'Symbol');
    const score = [has8, hasUpper, hasSym].filter(Boolean).length;
    const map = {
        0: { w: 0,   l: '—',        c: 'var(--brown-dark)' },
        1: { w: 33,  l: 'Weak',     c: '#C04444' },
        2: { w: 66,  l: 'Moderate', c: '#B07830' },
        3: { w: 100, l: 'Strong',   c: '#4A9B5A' }
    };
    const level = val.length === 0 ? 0 : Math.max(score, 1);
    const cfg = map[Math.min(level, 3)];
    document.getElementById('strengthBar').style.width      = cfg.w + '%';
    document.getElementById('strengthBar').style.background = cfg.c;
    document.getElementById('strengthLabel').textContent    = cfg.l;
    document.getElementById('strengthLabel').style.color    = cfg.c;
}

// Disable paste + hapus inline error saat mengetik — semua field
['currentPasswordInput', 'newPasswordInput', 'confirmPasswordInput'].forEach(function(id) {
    const el = document.getElementById(id);
    el.addEventListener('paste', function(e) {
        e.preventDefault();
        const existing = document.getElementById('pasteError-' + id);
        if (!existing) {
            const msg = document.createElement('span');
            msg.id = 'pasteError-' + id;
            msg.style.cssText = 'color:#C04444; font-size:12px; font-family:"Jost"; display:inline-flex; align-items:center; gap:5px; margin-top:6px;';
            msg.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Paste is not allowed. Please type manually.';
            this.closest('div').parentElement.appendChild(msg);
        }
    });
    el.addEventListener('input', function() {
        const pasteErr = document.getElementById('pasteError-' + id);
        if (pasteErr) pasteErr.remove();
        const inlineErr = this.closest('div').parentElement.querySelector('.cp-inline-error');
        if (inlineErr) inlineErr.remove();
    });
});

function validateForm() {
    const cur = document.getElementById('currentPasswordInput').value;
    const np  = document.getElementById('newPasswordInput').value;
    const cp  = document.getElementById('confirmPasswordInput').value;

    document.querySelectorAll('.cp-inline-error').forEach(el => el.remove());

    const has8      = np.length >= 8;
    const hasUpper  = /[A-Z]/.test(np);
    const hasSymbol = /[^a-zA-Z0-9]/.test(np);

    let hasError = false;

    function showInlineError(inputId, message) {
        const field = document.getElementById(inputId);
        if (!field) return;
        const existing = field.closest('div').parentElement.querySelector('.cp-inline-error');
        if (existing) existing.remove();
        const msg = document.createElement('span');
        msg.className = 'cp-inline-error';
        msg.style.cssText = 'color:#C04444; font-size:12px; font-family:"Jost"; display:inline-flex; align-items:center; gap:5px; margin-top:6px;';
        msg.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + message;
        field.closest('div').parentElement.appendChild(msg);
        hasError = true;
    }

    if (!cur)                          showInlineError('currentPasswordInput', 'Current password is required.');
    if (!has8)                         showInlineError('newPasswordInput',     'Password must be at least 8 characters.');
    else if (!hasUpper)                showInlineError('newPasswordInput',     'Password must contain at least one uppercase letter.');
    else if (!hasSymbol)               showInlineError('newPasswordInput',     'Password must contain at least one number or symbol.');
    else if (cur && np && cur === np)  showInlineError('newPasswordInput',     'New password must be different from your current password.');
    if (!cp)                           showInlineError('confirmPasswordInput', 'Please confirm your new password.');
    else if (np && np !== cp)          showInlineError('confirmPasswordInput', 'Confirmation password does not match.');

    if (hasError) {
        document.getElementById('jsErrorAlert').style.display = 'none';
        const firstError = document.querySelector('.cp-inline-error');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }

    return true;
}
</script>
@endpush