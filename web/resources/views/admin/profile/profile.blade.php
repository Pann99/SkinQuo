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

<div style="max-width:720px; margin:0 auto; padding:40px 32px;">

  {{-- ===== PROFILE HEADER — HORIZONTAL ROW (avatar kiri, nama+badge kanan) ===== --}}
  <div style="display:flex; align-items:center; gap:32px; margin-bottom:40px;">

    {{-- Avatar - kiri --}}
    <img src="{{ $admin['avatar'] }}"
         alt="{{ $admin['name'] }}"
         style="width:110px; height:110px; border-radius:50%;
                border:4px solid var(--brown-mid);
                object-fit:cover; flex-shrink:0;">
    {{-- TODO [BACKEND]: Replace avatar src with $admin->avatar or default placeholder --}}

    {{-- Name + Badge - kanan --}}
    <div>
      <h1 style="font-family:'Playfair Display'; font-size:42px; font-weight:700;
                 color:var(--brown-dark); margin:0 0 12px 0; line-height:1.1;">
        {{ $admin['name'] }}
      </h1>
      {{-- TODO [BACKEND]: $admin->name from users table --}}

      <span style="background:var(--brown-mid); color:var(--bg-main);
                   border-radius:999px; padding:5px 20px;
                   font-family:'Jost'; font-size:11px;
                   letter-spacing:0.18em; text-transform:uppercase;">
        {{ strtoupper($admin['role']) }}
      </span>
      {{-- TODO [BACKEND]: $admin->role from users/roles table --}}
    </div>

  </div>
  {{-- ===== END PROFILE HEADER ===== --}}

  {{-- ===== PERSONAL INFORMATION CARD ===== --}}
  <div class="admin-card">

    <h2 style="font-family:'Playfair Display'; font-size:22px;
               color:var(--brown-dark); margin-bottom:32px;
               display:flex; align-items:center; gap:10px;">
      <i class="bi bi-person-badge" style="font-size:22px;"></i>
      Personal Information
    </h2>

    {{-- FULL NAME --}}
    <div style="margin-bottom:24px;">
      <label class="field-label">Full Name</label>
      <input type="text" class="admin-input"
             value="{{ $admin['name'] }}" readonly>
      {{-- TODO [BACKEND]: value="{{ $admin->name }}" from users table column: name --}}
    </div>

    {{-- EMAIL ADDRESS --}}
    <div style="margin-bottom:24px;">
      <label class="field-label">Email Address</label>
      <input type="email" class="admin-input"
             value="{{ $admin['email'] }}" readonly>
      {{-- TODO [BACKEND]: value="{{ $admin->email }}" from users table column: email --}}
    </div>

    {{-- PASSWORD --}}
    <div style="margin-bottom:8px;">
      <label class="field-label">Password</label>
      <div style="display:flex; gap:12px; align-items:center;">
        <input type="password" class="admin-input"
               value="············" readonly style="flex:1;">
        <a href="{{ route('admin.profile.change-password') }}"
           class="btn-ubah-password">
          Ubah Password
        </a>
      </div>
      {{-- TODO [BACKEND]: Password display only; button routes to change-password page --}}
    </div>

  </div>
  {{-- ===== END CARD ===== --}}

</div>

@endsection
