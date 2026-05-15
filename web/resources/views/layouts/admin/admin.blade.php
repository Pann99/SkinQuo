<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin - The Sanctuary')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
  @vite(['resources/css/admin.css'])
</head>
<body>

<div class="admin-wrapper">

  {{-- ===== SIDEBAR ===== --}}
  {{-- Sidebar berdiri sendiri full height, TIDAK dibungkus footer --}}
  <aside class="admin-sidebar">

    {{-- Brand --}}
    <div style="margin-bottom: 36px; padding: 0 8px;">
      <div style="font-family:'Playfair Display'; font-weight:700; font-size:18px;
                  color:var(--brown-dark); line-height:1.2;">
        The Sanctuary
      </div>
      <div style="font-family:'Jost'; font-size:10px; letter-spacing:0.18em;
                  text-transform:uppercase; color:var(--brown-mid); margin-top:4px;">
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
         class="nav-item-admin @if(request()->routeIs('admin.inventory')) active @endif">
        <i class="bi bi-stars" style="font-size:16px;"></i>
        <span>Inventory</span>
      </a>

      <a href="{{ route('admin.journal') }}"
         class="nav-item-admin @if(request()->routeIs('admin.journal')) active @endif">
        <i class="bi bi-book" style="font-size:16px;"></i>
        <span>Journal</span>
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
        {{-- TODO [BACKEND]: Wire logout to auth — POST method required --}}
        <i class="bi bi-box-arrow-right" style="font-size:16px;"></i>
        <span>Log Out</span>
      </a>
      {{-- TODO [BACKEND]: Add logout form --}}
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>

  </aside>

  {{-- ===== MAIN AREA: content + footer (sidebar TIDAK ikut footer) ===== --}}
  <div style="flex:1; display:flex; flex-direction:column; min-height:100vh;">

    {{-- Main Content --}}
    <main class="admin-main">
      @yield('content')
    </main>

    {{-- Footer HANYA di bawah main content, bukan di bawah sidebar --}}
    <footer class="admin-footer">

      {{-- Left: SkinQuo Logo --}}
      <div style="display:flex; align-items:center; gap:10px;">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
             xmlns="http://www.w3.org/2000/svg">
          <circle cx="16" cy="16" r="15" stroke="#F5E6D0" stroke-width="1.5"/>
          <path d="M16 8 C10 12, 10 20, 16 24 C22 20, 22 12, 16 8Z"
                stroke="#F5E6D0" stroke-width="1.2" fill="none"/>
          <line x1="16" y1="8" x2="16" y2="24"
                stroke="#F5E6D0" stroke-width="0.8"/>
        </svg>
        <span style="font-family:'Playfair Display'; font-style:italic;
                     font-size:18px; color:#F5E6D0; letter-spacing:0.02em;">
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
  </div>

</div>{{-- end admin-wrapper --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
