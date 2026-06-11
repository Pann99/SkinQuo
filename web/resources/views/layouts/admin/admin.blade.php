<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin - SkinQuo')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/admin.css'])
  @stack('styles')

  {{-- ===== PERBAIKAN LAYOUT SCROLL DASHBOARD ===== --}}
  <style>
    /* 1. Kunci layar global agar tidak bocor (seperti aplikasi desktop) */
    html, body {
      margin: 0;
      padding: 0;
      height: 100vh !important;
      overflow: hidden !important; 
    }

    /* 2. Wrapper mentok satu layar penuh */
    .admin-wrapper {
      display: flex;
      width: 100vw;
      height: 100vh !important;
      overflow: hidden !important;
    }

    /* 3. Sidebar dikunci di kiri, full tinggi */
    .admin-sidebar {
      height: 100vh !important;
      overflow-y: auto; /* Memungkinkan sidebar di-scroll sendiri kalau menunya sangat banyak */
      flex-shrink: 0;
    }

    /* 4. INI KUNCINYA: Area Kanan (Konten) memiliki scroll-nya sendiri! */
    .admin-content-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;  /* ← ganti height jadi min-height */
  overflow-y: auto;
  overflow-x: hidden;
}
    
    /* Memastikan konten mendorong footer ke bawah */
    .admin-main {
      flex: 1; 
    }

    body {
  display: flex;
  flex-direction: column;
}

.admin-footer{
    flex-shrink: 0;
    width: 100%;
    max-width: none;
    margin: 0;
    margin-top: 50px;
}

  </style>
</head>
<body>

<div class="admin-wrapper">

  {{-- ===== SIDEBAR ===== --}}
  @if(!request()->routeIs('admin.profile.change-password'))
  <aside class="admin-sidebar">

    {{-- Brand --}}
    <div style="margin-bottom: 36px; padding: 0 8px;">
      <div style="font-family:'Playfair Display'; font-weight:700; font-size:24px;
                  color:var(--brown-dark); line-height:1.1;">
        Admin Portal
      </div>
    </div>

    {{-- Navigation --}}
    <nav style="display:flex; flex-direction:column; gap:4px;">

      <a href="{{ route('admin.dashboard') }}"
         class="nav-item-admin @if(request()->routeIs('admin.dashboard')) active @endif">
        <i class="bi bi-grid" style="font-size:16px;"></i>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.inventory') }}"
         class="nav-item-admin @if(request()->routeIs('admin.inventory') || request()->routeIs('admin.products.*')) active @endif">
        <i class="bi bi-stars" style="font-size:16px;"></i>
        <span>Inventory</span>
      </a>

      <a href="{{ route('admin.skin-guide.index') }}"
         class="nav-item-admin @if(request()->routeIs('admin.skin-guide.*')) active @endif">
        <i class="bi bi-book" style="font-size:16px;"></i>
        <span>Skin Guide</span>
      </a>

      <a href="{{ route('admin.feedback') }}"
         class="nav-item-admin @if(request()->routeIs('admin.feedback')) active @endif">
        <i class="bi bi-graph-up" style="font-size:16px;"></i>
        <span>Feedback</span>
      </a>

      <a href="{{ route('admin.profile') }}"
         class="nav-item-admin @if(request()->routeIs('admin.profile') || request()->routeIs('admin.profile.change-password')) active @endif">
        <i class="bi bi-person" style="font-size:16px;"></i>
        <span>Profile</span>
      </a>

    </nav>

    {{-- Log Out — pushed to bottom --}}
    <div style="margin-top:auto;">
      <a href="{{ route('logout') }}" class="nav-item-admin"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right" style="font-size:16px;"></i>
        <span>Log Out</span>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>

  </aside>
  @endif

  {{-- ===== MAIN AREA: Diubah menggunakan class 'admin-content-area' ===== --}}
  <div class="admin-content-area">

    {{-- Main Content --}}
    <main class="admin-main">
      @yield('content')
    

    {{-- Footer --}}
    <footer class="admin-footer">
      {{-- Left: SkinQuo Logo --}}
      <div style="display:flex; align-items:center; gap:8px;">
        <img src="{{ asset('images/logo_skinquo_cream.png') }}"
             alt="SkinQuo" style="height:40px; width:auto; object-fit:contain;" />
        <span style="font-family:'Jost', sans-serif; font-weight:600;
                     font-size:18px; color:#F5E6D0; letter-spacing:0.04em;">
          SkinQuo
        </span>
      </div>

      {{-- Center: Copyright --}}
      <div style="font-family:'Jost'; font-size:13px; color:#C4A882; letter-spacing:0.04em;">
        &copy; 2026 SkinQuo &mdash; Copyright Reserved
      </div>

      {{-- Right: Social Icons --}}
      <div style="display:flex; gap:18px; align-items:center;">
        <a href="#" style="color:#F5E6D0; font-size:20px; text-decoration:none;">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="#" style="color:#F5E6D0; font-size:20px; text-decoration:none;">
          <i class="bi bi-facebook"></i>
        </a>
      </div>
    </footer>
    </main>
  </div>

</div>{{-- end admin-wrapper --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>