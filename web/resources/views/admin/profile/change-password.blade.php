@extends('layouts.admin.admin')
@section('title', 'Change Password - The Sanctuary')
@section('content')

<div style="max-width:960px; margin:0 auto; padding:60px 32px;">

  {{-- ===== BACK TO PROFILE — LEFT ALIGNED ===== --}}
  <div style="text-align:left; margin-bottom:24px; padding-left:20px;">
    <a href="{{ route('admin.profile') }}" class="back-link">
      <i class="bi bi-arrow-left"></i> Back to Profile
    </a>
  </div>

  {{-- ===== HERO TEXT — LEFT ALIGNED ===== --}}
  <div style="text-align:left; margin-bottom:40px; padding-left:20px;">
    <h1 style="font-family:'Playfair Display'; font-style:italic; font-weight:700;
               font-size:48px; color:var(--brown-dark); margin-bottom:16px; line-height:1.1;">
      Secure Your Sanctuary
    </h1>
    <p style="font-family:'Jost'; font-size:16px; color:var(--brown-mid);
              line-height:1.6; margin:0;">
      Ensure your personal ritual remains private with a strong, updated password.
    </p>
  </div>

  {{-- ===== FORM CARD ===== --}}
  <div style="background:#FAF4EB; border-radius:40px; padding:48px; box-shadow:0 24px 64px rgba(61,35,20,0.04); border:1px solid rgba(124, 90, 60, 0.12);">
    
    <form action="{{ route('admin.profile.update-password') }}" method="POST"
          onsubmit="return validateForm()">
      @csrf
      @method('PATCH')

      {{-- USER NAME — disabled, auto-filled --}}
      <div style="margin-bottom:28px;">
        <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">User Name</label>
        <input type="text" value="Dr. Elara Vance" disabled placeholder="Dr. Elara Vance"
               style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 28px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02); opacity: 0.8;">
      </div>

      {{-- CURRENT PASSWORD --}}
      <div style="margin-bottom:28px;">
        <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">Current Password</label>
        <div style="position:relative; width:100%;">
          <input type="password" name="current_password" id="currentPasswordInput"
                 style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 28px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);"
                 placeholder="••••••••">
        </div>
        @error('current_password')
          <span style="color:#C04444; font-size:12px; font-family:'Jost'; display:block; margin-top:6px;">
            {{ $message }}
          </span>
        @enderror
      </div>

      {{-- NEW PASSWORD --}}
      <div style="margin-bottom:28px;">
        <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">New Password</label>
        <div style="position:relative; width:100%;">
          <input type="password" name="new_password" id="newPasswordInput"
                 style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 60px 18px 28px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);"
                 placeholder="••••••••" oninput="checkStrength(this.value)">
          <button type="button" onclick="togglePasswordVisibility('newPasswordInput', this)"
                  style="position:absolute; right:20px; top:50%; transform:translateY(-50%); background:transparent; border:none; color:#7A5C43; cursor:pointer; font-size:18px; display:flex; align-items:center;">
            <i class="bi bi-eye"></i>
          </button>
        </div>

        {{-- Password Strength UI --}}
        <div style="margin-top:16px;">
          <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
            <span style="font-family:'Jost'; font-size:12px; color:#7A5C43; font-weight:600; text-transform:uppercase; letter-spacing:0.04em;">
              Ritual Strength
            </span>
            <span id="strengthLabel"
                  style="font-family:'Jost'; font-size:12px; color:#3C2010; font-weight:700;">
              —
            </span>
          </div>
          <div style="background:#EADBBF; border-radius:999px; height:6px; width:100%;">
            <div id="strengthBar"
                 style="background:#3C2010; border-radius:999px;
                        height:6px; width:0%; transition:width 0.35s ease;">
            </div>
          </div>
          <div style="display:flex; gap:28px; margin-top:12px;">
            <span id="check8char"
                  style="font-family:'Jost'; font-size:13px; color:#A89482; display:inline-flex; align-items:center; gap:6px; transition:color 0.2s;">
              <i class="bi bi-circle"></i> 8+ characters
            </span>
            <span id="checkSymbol"
                  style="font-family:'Jost'; font-size:13px; color:#A89482; display:inline-flex; align-items:center; gap:6px; transition:color 0.2s;">
              <i class="bi bi-circle"></i> Uppercase & Symbols
            </span>
          </div>
        </div>
      </div>

      {{-- CONFIRM NEW PASSWORD --}}
      <div style="margin-bottom:36px;">
        <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">Confirm New Password</label>
        <div style="position:relative; width:100%;">
          <input type="password" name="new_password_confirmation" id="confirmPasswordInput"
                 style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 28px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);"
                 placeholder="••••••••">
        </div>
        <span id="matchError"
              style="color:#C04444; font-size:12px; font-family:'Jost';
                     display:none; margin-top:6px;">
          Passwords do not match.
        </span>
      </div>

      {{-- SUBMIT BUTTON --}}
      <button type="submit"
              style="width:100%; background:var(--brown-dark); color:white; border:none; padding:18px; border-radius:999px; font-family:'Jost'; font-size:12px; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer; font-weight:600; text-align:center; transition:background 0.2s;"
              onmouseover="this.style.background='var(--brown-mid)'" onmouseout="this.style.background='var(--brown-dark)'">
        Save New Password
      </button>

    </form>
  </div>

</div>

@endsection

@push('scripts')
<script>
function togglePasswordVisibility(id, btn) {
  const input = document.getElementById(id);
  const icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
  }
}

function checkStrength(val) {
  const has8     = val.length >= 8;
  const hasUpper = /[A-Z]/.test(val);
  const hasSym   = /[^a-zA-Z0-9]/.test(val);

  // Check8char label & color
  const charElem = document.getElementById('check8char');
  charElem.innerHTML = (has8
    ? '<i class="bi bi-check-circle-fill" style="color:#3C2010;"></i>'
    : '<i class="bi bi-circle"></i>')
    + ' 8+ characters';
  charElem.style.color = has8 ? '#3C2010' : '#A89482';
  charElem.style.fontWeight = has8 ? '600' : 'normal';

  // CheckSymbol label & color
  const symElem = document.getElementById('checkSymbol');
  symElem.innerHTML = (hasUpper && hasSym
    ? '<i class="bi bi-check-circle-fill" style="color:#3C2010;"></i>'
    : '<i class="bi bi-circle"></i>')
    + ' Uppercase & Symbols';
  symElem.style.color = (hasUpper && hasSym) ? '#3C2010' : '#A89482';
  symElem.style.fontWeight = (hasUpper && hasSym) ? '600' : 'normal';

  // Strength level
  let width = 0, label = '—';
  if (val.length === 0) { width = 0;   label = '—'; }
  else if (!has8)       { width = 25;  label = 'Fragile'; }
  else if (has8 && !(hasUpper && hasSym)) { width = 50; label = 'Balanced'; }
  else if (has8 && (hasUpper || hasSym))  { width = 75; label = 'Harmonious'; }
  if (has8 && hasUpper && hasSym)         { width = 100; label = 'Fortified'; }

  document.getElementById('strengthBar').style.width  = width + '%';
  document.getElementById('strengthLabel').textContent = label;
}

function validateForm() {
  const np  = document.getElementById('newPasswordInput').value;
  const cp  = document.getElementById('confirmPasswordInput').value;
  const err = document.getElementById('matchError');
  if (np !== cp) {
    err.style.display = 'block';
    return false;
  }
  err.style.display = 'none';
  return true;
}
</script>
@endpush
