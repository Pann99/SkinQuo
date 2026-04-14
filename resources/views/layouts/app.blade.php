<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SkinQuo')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- CSS Global --}}
    <style>
        :root {
            --cream:      #FFEAC5;
            --peach:      #FFDBB5;
            --brown:      #6C4E31;
            --dark-brown: #603F26;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            overflow-x: hidden;
        }
        .font-serif { font-family: 'Playfair Display', serif; }

        /* ═══════════════════════════════
           NAVBAR
        ═══════════════════════════════ */
        .navbar-wrap {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 800px;
            z-index: 1000;
        }

        .navbar-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 30px;
            border-radius: 999px;
            background: rgba(255, 219, 181, 0.78);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.60);
            /* 4 layer shadow = efek timbul premium */
            box-shadow:
                0 1px 2px   rgba(96, 63, 38, 0.05),
                0 4px 12px  rgba(96, 63, 38, 0.10),
                0 16px 40px rgba(96, 63, 38, 0.13),
                inset 0 1px 0 rgba(255, 255, 255, 0.75);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Lebih solid & lebih terangkat saat scroll */
        .navbar-pill.is-scrolled {
            background: rgba(255, 219, 181, 0.97);
            padding: 13px 30px;
            box-shadow:
                0 2px 4px   rgba(96, 63, 38, 0.08),
                0 8px 24px  rgba(96, 63, 38, 0.15),
                0 28px 56px rgba(96, 63, 38, 0.16),
                inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .nav-link {
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--brown);
            text-decoration: none;
            position: relative;
            padding-bottom: 2px;
            transition: color 0.2s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0; bottom: 0;
            width: 0; height: 1.5px;
            background: var(--dark-brown);
            border-radius: 2px;
            transition: width 0.28s ease;
        }
        .nav-link:hover { color: var(--dark-brown); }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active { color: var(--dark-brown); }
        .nav-link.active::after { width: 100%; }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark-brown);
            text-decoration: none;
            letter-spacing: -0.02em;
            transition: opacity 0.2s;
        }
        .nav-logo:hover { opacity: 0.72; }

        /* ═══════════════════════════════
           FOOTER
        ═══════════════════════════════ */
        .footer-link {
            font-size: 0.82rem;
            color: rgba(255, 219, 181, 0.72);
            text-decoration: none;
            transition: color 0.2s;
            display: inline-block;
            margin-bottom: 0.55rem;
        }
        .footer-link:hover { color: var(--peach); }

        .social-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: rgba(255, 219, 181, 0.10);
            border: 1px solid rgba(255, 219, 181, 0.22);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--peach);
            text-decoration: none;
            transition: background 0.2s;
        }
        .social-btn:hover { background: rgba(255, 219, 181, 0.22); }

        /* ═══════════════════════════════
           SCROLLBAR HIDDEN (carousel)
        ═══════════════════════════════ */
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ━━━ NAVBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
    <div class="navbar-wrap"
         x-data="{ scrolled: false }"
         @scroll.window="scrolled = window.scrollY > 28">

        <nav :class="scrolled ? 'navbar-pill is-scrolled' : 'navbar-pill'">

            {{-- Kiri --}}
            <div style="display:flex; gap:1.75rem; align-items:center;">
                <a href="{{ route('skin-guide.index') }}"
                   class="nav-link {{ request()->routeIs('skin-guide.*') ? 'active' : '' }}">
                    Skin Guide
                </a>
                <a href="{{ route('catalog.index') }}"
                   class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
                    Catalog
                </a>
            </div>

            {{-- Logo Tengah --}}
            <a href="{{ route('home') }}" class="nav-logo">SkinQuo</a>

            {{-- Kanan --}}
            <div style="display:flex; gap:1.75rem; align-items:center;">
                <a href="{{ route('consultation.index') }}"
                   class="nav-link {{ request()->routeIs('consultation.*') ? 'active' : '' }}">
                    Consultation
                </a>

                @auth
                    <a href="{{ route('profile.show') }}"
                       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                       style="display:flex; align-items:center; gap:6px;">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                 alt="Avatar"
                                 style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:1.5px solid var(--brown);">
                        @else
                            <span style="width:26px;height:26px;border-radius:50%;background:var(--dark-brown);display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;color:var(--cream);font-weight:700;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                        Profile
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Profile</a>
                @endauth
            </div>
        </nav>
    </div>
    {{-- ━━━ END NAVBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}


    {{-- ━━━ KONTEN HALAMAN ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
    @yield('content')
    {{-- ━━━ END KONTEN ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}


    {{-- ━━━ FOOTER ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
    <footer style="background:var(--dark-brown); padding:4rem 2rem 2rem;">
        <div style="max-width:1100px; margin:0 auto;">

            {{-- Grid utama --}}
            <div style="display:grid; grid-template-columns:1.6fr 1fr 1fr 1.4fr; gap:2.5rem;
                        padding-bottom:2.5rem; border-bottom:1px solid rgba(255,219,181,0.15);">

                {{-- Brand --}}
                <div>
                    <h3 class="font-serif"
                        style="font-size:2rem; font-weight:700; color:var(--cream); margin-bottom:0.75rem;">
                        SkinQuo
                    </h3>
                    <p style="font-size:0.8rem; line-height:1.75; color:rgba(255,219,181,0.65); max-width:190px;">
                        Because Every Skin Has Its Own Quo. Gentle skincare for every skin type.
                    </p>
                    <div style="display:flex; gap:8px; margin-top:1.25rem;">
                        <a href="#" class="social-btn" aria-label="Instagram">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn" aria-label="Facebook">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- INFO --}}
                <div>
                    <h4 style="font-size:0.68rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--cream); margin-bottom:1.1rem;">
                        INFO
                    </h4>
                    <div style="display:flex; flex-direction:column;">
                        <a href="#" class="footer-link">Courses</a>
                        <a href="#" class="footer-link">Schedule</a>
                        <a href="#" class="footer-link">Product</a>
                        <a href="#" class="footer-link">Teachers</a>
                    </div>
                </div>

                {{-- ABOUT --}}
                <div>
                    <h4 style="font-size:0.68rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--cream); margin-bottom:1.1rem;">
                        ABOUT
                    </h4>
                    <div style="display:flex; flex-direction:column;">
                        <a href="#" class="footer-link">Blog</a>
                        <a href="#" class="footer-link">About us</a>
                    </div>
                </div>

                {{-- CONTACT --}}
                <div>
                    <h4 style="font-size:0.68rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--cream); margin-bottom:1.1rem;">
                        CONTACT US
                    </h4>
                    <address style="font-style:normal;">
                        <p style="font-size:0.82rem; color:rgba(255,219,181,0.72); line-height:1.8;">
                            Jl. Soekarno Hatta No. 9<br>
                            Kota Malang, Jawa Timur
                        </p>
                        <p style="font-size:0.82rem; color:rgba(255,219,181,0.72); line-height:1.8; margin-top:0.75rem;">
                            (0341) 662345<br>
                            skinquo@gmail.com
                        </p>
                    </address>
                </div>
            </div>

            {{-- Copyright --}}
            <div style="text-align:center; padding-top:1.75rem;">
                <p style="font-size:0.72rem; color:rgba(255,219,181,0.38);">
                    © {{ date('Y') }} SkinQuo. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    {{-- ━━━ END FOOTER ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}

    @stack('scripts')
</body>
</html>
