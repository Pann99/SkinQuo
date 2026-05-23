@extends('layouts.admin.admin')
@section('title', 'Profile - The Sanctuary')
@section('content')

{{-- TODO [BACKEND]: Inject $admin from AdminProfileController@index --}}
{{-- TODO [BACKEND]: $admin = Auth::user() or Admin::find(auth()->id()) --}}
@php
  $admin = [
    'name'   => 'Dr. Elara Vance',
    'email'  => 'elara.vance@skinquosanctuary.com',
    'role'   => 'Administrator',
    'avatar' => 'https://i.pravatar.cc/150?img=47',
  ];
@endphp

<div style="max-width:960px; margin:0 auto; padding:60px 32px;">

  {{-- ===== PROFILE HEADER — HORIZONTAL ROW (avatar kiri, nama+badge kanan) ===== --}}
  <div style="display:flex; align-items:center; gap:28px; margin-bottom:48px; padding-left: 20px;">
    {{-- Avatar - kiri --}}
    <div style="position:relative; flex-shrink:0;">
      <img src="{{ $admin['avatar'] }}"
           alt="{{ $admin['name'] }}"
           style="width:130px; height:130px; border-radius:50%;
                  border:4px solid #FFF5E9;
                  object-fit:cover;
                  box-shadow:0 12px 30px rgba(60,32,16,0.18);">
    </div>

    {{-- Name + Badge - kanan --}}
    <div>
      <h1 style="font-family:'Playfair Display'; font-size:48px; font-weight:600;
                 color:#3C2010; margin:0; line-height:1.15; letter-spacing:-0.02em;">
        {{ $admin['name'] }}
      </h1>
      <span style="background:#5A402D; color:#EDD9B8;
                   border-radius:999px; padding:6px 18px;
                   font-family:'Jost'; font-size:10px;
                   letter-spacing:0.15em; text-transform:uppercase;
                   display:inline-block; margin-top:8px; font-weight:700;">
        {{ strtoupper($admin['role']) }}
      </span>
    </div>
  </div>

  {{-- ===== PERSONAL INFORMATION CARD ===== --}}
  <div style="background:#FAF4EB; border-radius:40px; padding:48px; box-shadow:0 24px 64px rgba(61,35,20,0.04); border:1px solid rgba(124, 90, 60, 0.12);">
    
    {{-- PERSONAL INFORMATION TITLE AT THE TOP --}}
    <div style="display:flex; align-items:center; gap:12px; color:#3C2010; margin-bottom:36px; padding-bottom:16px; border-bottom:1px solid rgba(124, 90, 60, 0.1);">
      <i class="bi bi-card-text" style="font-size:24px;"></i>
      <span style="font-family:'Playfair Display', serif; font-size:26px; font-weight:600; letter-spacing:-0.01em;">
        Personal Information
      </span>
    </div>

    {{-- FULL NAME --}}
    <div style="margin-bottom:28px;">
      <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">Full Name</label>
      <input type="text" value="{{ $admin['name'] }}" readonly
             style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 28px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);">
    </div>

    {{-- EMAIL ADDRESS --}}
    <div style="margin-bottom:28px;">
      <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">Email Address</label>
      <input type="email" value="{{ $admin['email'] }}" readonly
             style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 28px; width:100%; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);">
    </div>

    {{-- PASSWORD --}}
    <div style="margin-bottom:12px;">
      <label style="font-family:'Jost'; font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:#7A5030; display:block; margin-bottom:10px; font-weight:600;">Password</label>
      <div style="display:flex; gap:16px; align-items:center; width:100%;">
        <input type="password" value="············" readonly
               style="background:#FFFFFF; border:none; border-radius:999px; padding:18px 28px; flex:1; outline:none; font-family:'Jost'; font-size:15px; color:#3C2010; letter-spacing:0.15em; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);">
        <a href="{{ route('admin.profile.change-password') }}"
           style="background:transparent; border:1px solid #7A5030; color:#7A5030; border-radius:999px; padding:16px 28px; font-family:'Jost'; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; font-weight:600; text-decoration:none; white-space:nowrap; transition:all 0.2s;"
           onmouseover="this.style.background='rgba(122,80,48,0.05)'" onmouseout="this.style.background='transparent'">
          Ubah Password
        </a>
      </div>
    </div>

  </div>
  {{-- ===== END CARD ===== --}}

</div>

@endsection
