@extends('layouts.admin.admin')
@section('title', 'Profile - The Sanctuary')
@section('content')

{{-- ===== SUCCESS TOAST ===== --}}
@if(session('status'))
<div id="toastSuccess"
     style="position:fixed; top:28px; right:28px; z-index:9999;
            background:#3C2010; color:#EDD9B8;
            border-radius:999px; padding:14px 28px;
            font-family:'Jost'; font-size:13px; font-weight:600; letter-spacing:0.06em;
            box-shadow:0 8px 32px rgba(61,35,20,0.18);
            display:flex; align-items:center; gap:10px;
            animation: slideIn 0.4s ease;">
  <i class="bi bi-check-circle-fill" style="font-size:16px; color:#A8D5A2;"></i>
  {{ session('status') }}
</div>

<style>
  @keyframes slideIn {
    from { opacity:0; transform:translateY(-16px); }
    to   { opacity:1; transform:translateY(0); }
  }
</style>

<script>
  setTimeout(() => {
    const toast = document.getElementById('toastSuccess');
    if (toast) {
      toast.style.transition = 'opacity 0.4s ease';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 400);
    }
  }, 3000);
</script>
@endif

{{-- Profile data injected from AdminProfileController@show --}}
{{-- $admin contains authenticated admin user with loaded relationships (role, sex) --}}

<div style="max-width:960px; margin:0 auto; padding:36px 24px 20px 24px;">

  {{-- ===== PROFILE HEADER — HORIZONTAL ROW (avatar kiri, nama+badge kanan) ===== --}}
  <div style="display:flex; align-items:center; gap:18px; margin-bottom:24px; padding-left: 0;">
    {{-- Avatar - kiri --}}
    <div style="position:relative; flex-shrink:0;">
      <img src="{{ $admin->sex->icon_image_url ?? 'https://i.pravatar.cc/150?img=47' }}"
           alt="{{ $admin->username }}"
           style="width:85px; height:85px; border-radius:50%;
                  border:3px solid #FFF5E9;
                  object-fit:cover;
                  box-shadow:0 6px 16px rgba(60,32,16,0.1);">
    </div>

    {{-- Name + Badge - kanan --}}
    <div>
      <h1 style="font-family:'Playfair Display'; font-size:36px; font-weight:600;
                 color:#3C2010; margin:0; line-height:1.15; letter-spacing:-0.02em;">
        {{ $admin->username }}
      </h1>
      <span style="background:#5A402D; color:#EDD9B8;
                   border-radius:999px; padding:3px 12px;
                   font-family:'Jost'; font-size:8px;
                   letter-spacing:0.15em; text-transform:uppercase;
                   display:inline-block; margin-top:4px; font-weight:700;">
        {{ strtoupper($admin->role->role_name ?? 'Unknown') }}
      </span>
    </div>
  </div>

  {{-- ===== PERSONAL INFORMATION CARD ===== --}}
  <div style="background:#FAF4EB; border-radius:20px; padding:20px 22px; box-shadow:0 10px 32px rgba(61,35,20,0.05); border:1px solid rgba(124, 90, 60, 0.12);">
    
    {{-- PERSONAL INFORMATION TITLE AT THE TOP --}}
    <div style="display:flex; align-items:center; gap:10px; color:#3C2010; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid rgba(124, 90, 60, 0.1);">
      <i class="bi bi-card-text" style="font-size:20px;"></i>
      <span style="font-family:'Playfair Display', serif; font-size:22px; font-weight:600; letter-spacing:-0.01em;">
        Personal Information
      </span>
    </div>

    {{-- FULL NAME --}}
    <div style="margin-bottom:14px;">
      <label style="font-family:'Jost'; font-size:10px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:6px; font-weight:600;">Full Name</label>
      <input type="text" value="{{ $admin->username }}" readonly
             style="background:#FFFFFF; border:2px solid #D4C5B9; border-radius:999px; padding:11px 18px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-sizing:border-box; height:46px; box-shadow:0 2px 8px rgba(60,32,16,0.04);">
    </div>

    {{-- EMAIL ADDRESS --}}
    <div style="margin-bottom:14px;">
      <label style="font-family:'Jost'; font-size:10px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:6px; font-weight:600;">Email Address</label>
      <input type="email" value="{{ $admin->email }}" readonly
             style="background:#FFFFFF; border:2px solid #D4C5B9; border-radius:999px; padding:11px 18px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-sizing:border-box; height:46px; box-shadow:0 2px 8px rgba(60,32,16,0.04);">
    </div>

    {{-- PASSWORD --}}
    <div style="margin-bottom:0;">
      <label style="font-family:'Jost'; font-size:10px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:6px; font-weight:600;">Password</label>
      <div style="display:flex; gap:10px; align-items:center; width:100%;">
        <input type="password" value="············" readonly
               style="background:#FFFFFF; border:2px solid #D4C5B9; border-radius:999px; padding:11px 18px; flex:1; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; letter-spacing:0.15em; box-sizing:border-box; height:46px; box-shadow:0 2px 8px rgba(60,32,16,0.04);">
        <a href="{{ route('admin.profile.change-password') }}"
           style="background:transparent; border:2px solid #7A5030; color:#7A5030; border-radius:999px; padding:8px 16px; font-family:'Jost'; font-size:10px; letter-spacing:0.12em; text-transform:uppercase; font-weight:600; text-decoration:none; white-space:nowrap; transition:all 0.2s; height:46px; display:flex; align-items:center; cursor:pointer;"
           onmouseover="this.style.background='#7A5030'; this.style.color='#EDD9B8';" onmouseout="this.style.background='transparent'; this.style.color='#7A5030';">
          Ubah Password
        </a>
      </div>
    </div>

  </div>
  {{-- ===== END CARD ===== --}}

</div>

@endsection
