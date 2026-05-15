@extends('layouts.admin.admin')
@section('title', 'Change Password - The Sanctuary')
@section('content')

{{-- TODO [BACKEND]: Handle POST in AdminPasswordController@update --}}
{{-- TODO [BACKEND]: Validate: Hash::check($request->current_password, $admin->password) --}}
{{-- TODO [BACKEND]: Save: $admin->update(['password' => Hash::make($request->new_password)]) --}}

<div style="max-width:580px; margin:0 auto; padding:32px 24px;">

  {{-- ===== BACK TO PROFILE — CENTER ===== --}}
  <div style="text-align:center; margin-bottom:24px;">
    <a href="{{ route('admin.profile') }}" class="back-link">
      <i class="bi bi-arrow-left"></i> Back to Profile
    </a>
  </div>

  {{-- ===== HERO TEXT — CENTER ===== --}}
  <div style="text-align:center; margin-bottom:40px;">
    <h1 style="font-family:'Playfair Display'; font-style:italic; font-weight:700;
               font-size:48px; color:var(--brown-dark); margin-bottom:16px; line-height:1.1;">
      Secure Your Sanctuary
    </h1>
    <p style="font-family:'Jost'; font-size:16px; color:var(--brown-mid);
              line-height:1.6; margin:0;">
      Ensure your personal ritual remains private with a<br>strong, updated password.
    </p>
  </div>

  {{-- ===== FORM ===== --}}
  <form action="{{ route('admin.profile.update-password') }}" method="POST"
        onsubmit="return validateForm()">
    @csrf
    @method('PATCH')
    {{-- TODO [BACKEND]: Confirm PATCH or POST with backend PJ --}}

    {{-- USER NAME — disabled, auto-filled --}}
    <div style="margin-bottom:24px;">
      <label class="field-label">User Name</label>
      <input type="text" class="admin-input-cream"
             value="Dr. Elara Vance" disabled placeholder="Dr. Elara Vance">
      {{-- TODO [BACKEND]: value="{{ Auth::user()->name }}" auto-filled, no editing --}}
    </div>

    {{-- CURRENT PASSWORD --}}
    <div style="margin-bottom:24px;">
      <label class="field-label">Current Password</label>
      <input type="password" name="current_password"
             class="admin-input-cream" placeholder="••••••••">
      {{-- TODO [BACKEND]: Validate with Hash::check() --}}
      @error('current_password')
        <span style="color:red; font-size:12px; font-family:'Jost';">
          {{ $message }}
        </span>
      @enderror
    </div>

    {{-- NEW PASSWORD --}}
    <div style="margin-bottom:8px;">
      <label class="field-label">New Password</label>
      <input type="password" name="new_password" id="newPasswordInput"
             class="admin-input-cream" placeholder="••••••••"
             oninput="checkStrength(this.value)">

      {{-- Password Strength UI --}}
      <div style="margin-top:12px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
          <span style="font-family:'Jost'; font-size:12px; color:var(--brown-mid);">
            Ritual Strength
          </span>
          <span id="strengthLabel"
                style="font-family:'Jost'; font-size:12px; color:var(--brown-mid);">
            —
          </span>
        </div>
        <div style="background:#E8D5BC; border-radius:999px; height:6px; width:100%;">
          <div id="strengthBar"
               style="background:var(--brown-dark); border-radius:999px;
                      height:6px; width:0%; transition:width 0.35s ease;">
          </div>
        </div>
        <div style="display:flex; gap:24px; margin-top:10px;">
          <span id="check8char"
                style="font-family:'Jost'; font-size:13px; color:#bbb;">
            <i class="bi bi-circle"></i> 8+ characters
          </span>
          <span id="checkSymbol"
                style="font-family:'Jost'; font-size:13px; color:#bbb;">
            <i class="bi bi-circle"></i> Uppercase & Symbols
          </span>
        </div>
      </div>
    </div>

    {{-- CONFIRM NEW PASSWORD --}}
    <div style="margin-bottom:8px; margin-top:24px;">
      <label class="field-label">Confirm New Password</label>
      <input type="password" name="new_password_confirmation"
             id="confirmPasswordInput"
             class="admin-input-cream" placeholder="••••••••">
      <span id="matchError"
            style="color:red; font-size:12px; font-family:'Jost';
                   display:none; margin-top:4px;">
        Passwords do not match.
      </span>
    </div>

    {{-- SUBMIT BUTTON --}}
    <button type="submit" class="btn-save-password" style="margin-top:32px;">
      Save New Password
    </button>
    {{-- TODO [BACKEND]: On success → redirect()->route('admin.profile')->with('success','Password updated!') --}}
    {{-- TODO [BACKEND]: On fail → return back()->withErrors(['current_password' => 'Incorrect password']) --}}

  </form>

</div>

@endsection

@push('scripts')
<script>
function checkStrength(val) {
  const has8     = val.length >= 8;
  const hasUpper = /[A-Z]/.test(val);
  const hasSym   = /[^a-zA-Z0-9]/.test(val);

  // Checkmark icons
  document.getElementById('check8char').innerHTML =
    (has8
      ? '<i class="bi bi-check-circle-fill" style="color:#7A5C3E;"></i>'
      : '<i class="bi bi-circle"></i>')
    + ' 8+ characters';

  document.getElementById('checkSymbol').innerHTML =
    (hasUpper && hasSym
      ? '<i class="bi bi-check-circle-fill" style="color:#7A5C3E;"></i>'
      : '<i class="bi bi-circle"></i>')
    + ' Uppercase & Symbols';

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
