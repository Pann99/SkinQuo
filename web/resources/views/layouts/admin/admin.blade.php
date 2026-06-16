<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin - SkinQuo')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/admin.css'])
  <style>
    /* === SIDEBAR SYSTEM — prefix sq- agar tidak bentrok admin.css === */
    :root {
      --sq-w: 240px; --sq-wmin: 64px;
      --sq-bg: #FFDBB5; --sq-surface: #FFCFA0;
      --sq-active: #6B3A28; --sq-act-text: #F9EFE4;
      --sq-text: #7A5040; --sq-muted: #9E7055;
      --sq-border: #E8B48A; --sq-accent: #6B3A28;
      --sq-tr: 250ms cubic-bezier(.4,0,.2,1);
    }
    
    /* 1. Paksa scroll vertikal dan cegah kunci tinggi layar */
    html, body { 
      margin: 0; 
      padding: 0; 
      min-height: 100vh;
      overflow-y: auto !important; 
      overflow-x: hidden; 
    }

    /* Shell - Biarkan shell menyesuaikan isi konten */
    .sq-shell { 
      display: flex; 
      min-height: 100vh; 
      height: auto !important; 
    }

    /* Sidebar */
    .sq-sidebar {
      position:fixed; top:0; left:0; height:100vh;
      width:var(--sq-w);
      background:var(--sq-bg);
      border-right:1px solid var(--sq-border);
      display:flex; flex-direction:column;
      transition:width var(--sq-tr);
      z-index:200;
      box-shadow:2px 0 16px rgba(43,26,14,.07);
      overflow:hidden;
    }
    .sq-sidebar.is-collapsed { width:var(--sq-wmin); }

    /* Body offset - Pastikan body utama melar ke bawah */
    .sq-body {
      margin-left:var(--sq-w);
      flex:1; min-width:0;
      display:flex; flex-direction:column;
      min-height: 100vh;
      transition:margin-left var(--sq-tr);
      overflow: visible !important;
    }
    .sq-shell.is-collapsed .sq-body { margin-left:var(--sq-wmin); }

    /* Main Area & Footer Flex System */
    .admin-main {
      flex: 1 0 auto;
      height: auto !important;
      overflow: visible !important;
      padding-bottom: 2rem;
    }
    .admin-footer {
      flex-shrink: 0;
    }

    /* Header */
    .sq-hdr {
      display:flex; align-items:center; gap:10px;
      padding:18px 14px 14px; min-height:64px; flex-shrink:0;
    }
    .sq-logo {
      width:32px; height:32px; object-fit:contain;
      border-radius:8px; flex-shrink:0;
      transition:opacity var(--sq-tr), width var(--sq-tr);
    }
    .sq-sidebar.is-collapsed .sq-logo { opacity:0; width:0; }

    .sq-brand {
      font-family:'Jost',sans-serif; font-size:13px; font-weight:600;
      color:var(--sq-text); white-space:nowrap; flex:1; overflow:hidden;
      transition:opacity var(--sq-tr), width var(--sq-tr);
    }
    .sq-sidebar.is-collapsed .sq-brand { opacity:0; width:0; }

    /* Toggle — SELALU VISIBLE */
    .sq-toggle {
      display:flex; align-items:center; justify-content:center;
      width:30px; height:30px; flex-shrink:0;
      background:transparent; border:1px solid var(--sq-border);
      border-radius:7px; cursor:pointer; color:var(--sq-muted);
      font-size:13px; transition:background var(--sq-tr), color var(--sq-tr);
    }
    .sq-toggle:hover { background:var(--sq-surface); color:var(--sq-active); }
    .sq-toggle i { display:block; transition:transform var(--sq-tr); }
    .sq-sidebar.is-collapsed .sq-toggle i { transform:rotate(180deg); }

    /* Saat collapsed: header center, toggle di tengah */
    .sq-sidebar.is-collapsed .sq-hdr {
      justify-content:center; padding:18px 0 14px;
    }
    .sq-sidebar.is-collapsed .sq-toggle { width:36px; height:36px; }

    /* Divider */
    .sq-line { height:1px; background:var(--sq-border); margin:0 14px; flex-shrink:0; }

    /* Nav */
    .sq-nav {
      display:flex; flex-direction:column; gap:2px;
      padding:10px 8px; flex:1; overflow-y:auto; overflow-x:hidden;
      scrollbar-width:none;
    }
    .sq-nav::-webkit-scrollbar { display:none; }

    /* Nav item */
    .sq-item {
      display:flex; align-items:center; gap:12px;
      padding:9px 10px; border-radius:10px;
      color:var(--sq-muted); text-decoration:none;
      font-family:'Jost',sans-serif; font-size:13.5px; font-weight:500;
      white-space:nowrap; position:relative;
      transition:background var(--sq-tr), color var(--sq-tr);
      overflow:hidden;
    }
    .sq-item:hover { background:var(--sq-surface); color:var(--sq-text); }
    .sq-item.is-active { background:var(--sq-active); color:var(--sq-act-text); }
    .sq-item.is-active::before {
      content:''; position:absolute; left:0; top:20%;
      height:60%; width:3px;
      background:rgba(245,239,230,.45); border-radius:0 3px 3px 0;
    }
    .sq-item--logout:hover { background:rgba(166,53,32,.09)!important; color:#A63520!important; }

    /* Saat collapsed: center icon, tooltip bisa muncul */
    .sq-sidebar.is-collapsed .sq-item {
      overflow:visible; justify-content:center; padding:9px 0;
    }

    /* Icon */
    .sq-ic {
      display:flex; align-items:center; justify-content:center;
      width:20px; height:20px; font-size:17px; flex-shrink:0;
    }

    /* Label */
    .sq-lbl { overflow:hidden; transition:opacity var(--sq-tr), width var(--sq-tr); }
    .sq-sidebar.is-collapsed .sq-lbl { opacity:0; width:0; pointer-events:none; }

    /* Tooltip (collapsed only, via ::after) */
    .sq-item::after {
      content: attr(data-tooltip);
      position:absolute; left:calc(var(--sq-wmin) + 8px); top:50%;
      transform:translateY(-50%) translateX(6px);
      background:var(--sq-active); color:var(--sq-act-text);
      font-family:'Jost',sans-serif; font-size:12px; font-weight:500;
      padding:5px 10px; border-radius:7px;
      white-space:nowrap; pointer-events:none; opacity:0;
      transition:opacity var(--sq-tr), transform var(--sq-tr);
      z-index:9999; box-shadow:0 4px 12px rgba(43,26,14,.18);
    }
    .sq-sidebar.is-collapsed .sq-item:hover::after {
      opacity:1; transform:translateY(-50%) translateX(0);
    }

    /* Footer */
    .sq-footer { padding:8px 8px 18px; flex-shrink:0; }
  </style>
  @stack('styles')
</head>
<body>

<div class="sq-shell" id="sqShell">

  @if(!request()->routeIs('admin.profile.change-password'))
  <aside class="sq-sidebar" id="sqSidebar">

    <div class="sq-hdr">
      <img src="{{ asset('images/logo_skinquo_cream.png') }}" alt="SkinQuo" class="sq-logo">
      <span class="sq-brand">Admin Portal</span>
      <button class="sq-toggle" id="sqToggle" aria-label="Toggle sidebar">
        <i class="bi bi-chevron-left" style="font-size:12px"></i>
      </button>
    </div>

    <div class="sq-line"></div>

    <nav class="sq-nav">
      <a href="{{ route('admin.dashboard') }}"
         class="sq-item @if(request()->routeIs('admin.dashboard')) is-active @endif"
         data-tooltip="Dashboard">
        <span class="sq-ic"><i class="bi bi-grid-1x2"></i></span>
        <span class="sq-lbl">Dashboard</span>
      </a>
      <a href="{{ route('admin.inventory') }}"
         class="sq-item @if(request()->routeIs('admin.inventory') || request()->routeIs('admin.products.*')) is-active @endif"
         data-tooltip="Inventory">
        <span class="sq-ic"><i class="bi bi-stars"></i></span>
        <span class="sq-lbl">Inventory</span>
      </a>
      <a href="{{ route('admin.skin-guide.index') }}"
         class="sq-item @if(request()->routeIs('admin.skin-guide.*')) is-active @endif"
         data-tooltip="Skin Guide">
        <span class="sq-ic"><i class="bi bi-journal-richtext"></i></span>
        <span class="sq-lbl">Skin Guide</span>
      </a>
      <a href="{{ route('admin.feedback') }}"
         class="sq-item @if(request()->routeIs('admin.feedback')) is-active @endif"
         data-tooltip="Feedback">
        <span class="sq-ic"><i class="bi bi-bar-chart-line"></i></span>
        <span class="sq-lbl">Feedback</span>
      </a>
      <a href="{{ route('admin.profile') }}"
         class="sq-item @if(request()->routeIs('admin.profile') || request()->routeIs('admin.profile.change-password')) is-active @endif"
         data-tooltip="Profile">
        <span class="sq-ic"><i class="bi bi-person-circle"></i></span>
        <span class="sq-lbl">Profile</span>
      </a>
    </nav>

    <div class="sq-line"></div>

    <div class="sq-footer">
      <a href="{{ route('logout') }}"
         class="sq-item sq-item--logout"
         data-tooltip="Log Out"
         onclick="event.preventDefault(); document.getElementById('sq-logout-form').submit();">
        <span class="sq-ic"><i class="bi bi-box-arrow-right"></i></span>
        <span class="sq-lbl">Log Out</span>
      </a>
      <form id="sq-logout-form" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
    </div>

  </aside>
  @endif
  
  <div class="sq-body">
    <main class="admin-main">@yield('content')</main>
    @include('layouts.admin.footer')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  var KEY = 'sq_v3';
  var shell   = document.getElementById('sqShell');
  var sidebar = document.getElementById('sqSidebar');
  var btn     = document.getElementById('sqToggle');
  if (!sidebar || !btn) return;

  function set(collapsed, animate) {
    if (!animate) {
      sidebar.style.transition = 'none';
      shell.style.transition   = 'none';
      void sidebar.offsetHeight;
    }
    sidebar.classList.toggle('is-collapsed', collapsed);
    shell.classList.toggle('is-collapsed', collapsed);
    if (!animate) {
      void sidebar.offsetHeight;
      sidebar.style.transition = '';
      shell.style.transition   = '';
    }
  }

  set(localStorage.getItem(KEY) === '1', false);

  btn.addEventListener('click', function(){
    var next = !sidebar.classList.contains('is-collapsed');
    set(next, true);
    localStorage.setItem(KEY, next ? '1' : '0');
  });
})();
</script>
@stack('scripts')
</body>
</html>