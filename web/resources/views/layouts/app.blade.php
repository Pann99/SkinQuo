<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SkinQuo')</title>
       <link rel="icon" type="image/png" href="{{ asset('images/logo_skinquo_coklat.png') }}">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- CSS Global --}}
    <style>
        :root {
            --cream:      #FFEAC5;
            --peach:      #FFDBB5;
            --brown:      #6C4E31;
            --dark-brown: #603F26;
        }

        /* ═══════════════════════════════
           PAGE LOADER & ANTI-FOUC
        ═══════════════════════════════ */
        html { visibility: hidden; scroll-behavior: smooth; } /* Anti-FOUC */
        
        #page-loader {
            position: fixed;
            inset: 0;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.45s ease, visibility 0.45s ease;
        }

        #page-loader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .loader-inner { text-align: center; }

        .loader-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-brown);
            margin-bottom: 1rem;
        }

        .loader-brand span {
            font-style: italic;
            font-weight: 400;
        }

        .loader-dots {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .loader-dots span {
            width: 10px;
            height: 10px;
            background-color: var(--dark-brown);
            border-radius: 50%;
            animation: loaderBounce 1.4s infinite ease-in-out both;
        }

        .loader-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loader-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes loaderBounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        #app-content {
            opacity: 0;
            transition: opacity 0.45s ease;
        }

        #app-content.visible {
            opacity: 1;
        }

        /* ═══════════════════════════════
           BASE STYLES
        ═══════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            overflow-x: hidden;
        }
        body.no-scroll {
            overflow: hidden;
            height: 100vh;
        }
        .font-serif { font-family: 'Playfair Display', serif; }

        /* ═══════════════════════════════
           NAVBAR
        ═══════════════════════════════ */
        .navbar-wrap {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 800px;
            z-index: 9999;
            padding-top: 20px;
            pointer-events: none;
        }

        .navbar-pill {
            /* UBAH BAGIAN INI MENJADI GRID */
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            pointer-events: auto;
            
            padding: 11px 30px;
            border-radius: 999px;
            background: rgba(255, 219, 181, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.60);
            box-shadow:
                0 2px 8px   rgba(96, 63, 38, 0.08),
                0 8px 24px  rgba(96, 63, 38, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.75);
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
           NAVBAR PROFILE DROPDOWN
        ═══════════════════════════════ */
        .nav-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--brown);
            transition: background 0.2s;
            position: relative;
            cursor: pointer;
            border: none;
            background: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .nav-profile-btn:hover {
            background: rgba(96, 63, 38, 0.08);
            color: var(--dark-brown);
        }

        .nav-profile-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid var(--brown);
        }

        .nav-profile-avatar-placeholder {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--dark-brown);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: var(--cream);
            font-weight: 700;
        }

        .nav-profile-container {
            position: relative;
            padding-bottom: 12px;
            margin-bottom: -12px;
        }

        .nav-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0;
            background: #FFF8F0;
            border: 1px solid rgba(96, 63, 38, 0.15);
            border-radius: 12px;
            padding: 8px 0;
            min-width: 140px;
            box-shadow: 0 8px 24px rgba(96, 63, 38, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            pointer-events: none;
        }

        .nav-profile-container:hover .nav-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .nav-dropdown-item {
            display: block;
            width: 100%;
            padding: 10px 16px;
            text-align: left;
            background: none;
            border: none;
            text-decoration: none;
            color: var(--dark-brown);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s;
            font-family: 'Poppins', sans-serif;
        }

        .nav-dropdown-item:hover {
            background: rgba(96, 63, 38, 0.08);
        }

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

        /* ═══════════════════════════════
           SHARED CARD HOVER (LIFT EFFECT)
           Konsisten untuk semua card: artikel,
           katalog, produk, testimoni, dll.
        ═══════════════════════════════ */
        .lift-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .lift-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(96, 63, 38, 0.18);
        }

        /* ═══════════════════════════════
           SCROLL TO TOP (FLOATING BUTTON)
        ═══════════════════════════════ */
        .scroll-to-top {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--dark-brown);
            color: var(--cream);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 9998;
            box-shadow: 0 8px 24px rgba(96, 63, 38, 0.35);
            opacity: 0;
            visibility: hidden;
            transform: translateY(16px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease, background 0.2s ease;
        }
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .scroll-to-top:hover {
            background: var(--brown);
            transform: translateY(-3px);
        }
        .scroll-to-top.on-dark {
            background: var(--cream);
            color: var(--dark-brown);
        }
        .scroll-to-top.on-dark:hover {
            background: var(--peach);
        }
        .scroll-to-top svg {
            width: 20px;
            height: 20px;
        }

        /* ═══════════════════════════════
           SCROLL REVEAL ANIMATION
        ═══════════════════════════════ */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal-on-scroll {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ━━━ PAGE LOADER ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
    <div id="page-loader">
        <div class="loader-inner">
            <div class="loader-brand">Skin<span>Quo</span></div>
            <div class="loader-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>
    {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}


    {{-- Semua konten aplikasi dibungkus di dalam app-content --}}
    <div id="app-content">
        
        {{-- ━━━ NAVBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div class="navbar-wrap">

        <nav class="navbar-pill">

                {{-- Kiri --}}
                <div style="display:flex; gap:1.75rem; align-items:center; justify-content:flex-start;">
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
                <div style="display:flex; justify-content:center;">
                    <a href="{{ route('home') }}" class="nav-logo">SkinQuo</a>
                </div>

                {{-- Kanan --}}
                <div style="display:flex; gap:1.75rem; align-items:center; justify-content:flex-end;">
                    <a href="{{ route('consultation.index') }}"
                       class="nav-link {{ request()->routeIs('consultation.*') ? 'active' : '' }}">
                        Consultation
                    </a>

                    @auth
                        <div class="nav-profile-container" style="position:relative;">
                            <button class="nav-profile-btn" onclick="location.href='{{ route('profile.show') }}'" type="button">
                                @if(auth()->user()->sex && auth()->user()->sex->icon_image_url)
                                    <img src="{{ auth()->user()->sex->icon_image_url }}"
                                         alt="Avatar"
                                         class="nav-profile-avatar">
                                @else
                                    <span class="nav-profile-avatar-placeholder">
                                        {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                                    </span>
                                @endif
                                
                                {{-- text-overflow tetap dipertahankan --}}
                                <span style="max-width: 80px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: left;">
                                    {{ explode(' ', auth()->user()->username ?? 'Profile')[0] }}
                                </span>
                            </button>

                            <div class="nav-dropdown">
                                <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                                    @csrf
                                    <button type="submit" class="nav-dropdown-item">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
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
                        <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom:1rem;">
                            <img src="{{ asset('images/logo_skinquo_cream.png') }}" alt="SkinQuo Logo" style="width:48px; height:48px; object-fit:contain;">
                            <h3 class="font-serif"
                                style="font-size:2rem; font-weight:700; color:var(--cream); margin:0;">
                                SkinQuo
                            </h3>
                        </div>
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
                            <a href="{{ route('skin-guide.index') }}" class="footer-link">Skin Guide</a>
                            <a href="{{ route('catalog.index') }}" class="footer-link">Catalog</a>
                            <a href="{{ route('consultation.index') }}" class="footer-link">Consultation</a>
                            <a href="{{ route('home') }}#community-voices" class="footer-link">Feedback</a>
                        </div>
                    </div>

                    {{-- ABOUT --}}
                    <div>
                        <h4 style="font-size:0.68rem; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:var(--cream); margin-bottom:1.1rem;">
                            ABOUT
                        </h4>
                        <div style="display:flex; flex-direction:column;">
                            <a href="{{ route('about') }}" class="footer-link">About Us</a>
                            <a href="{{ route('how-it-works') }}" class="footer-link">How It Works</a>
                            <a href="{{ route('privacy-policy') }}" class="footer-link">Privacy Policy</a>
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
        
        {{-- ━━━ SCROLL TO TOP BUTTON ━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <button id="scrollToTopBtn" class="scroll-to-top" aria-label="Scroll to top" type="button">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
        {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}

    </div> 
    {{-- Penutup div #app-content --}}

    {{-- PAGE LOADER SCRIPT --}}
    <script>
        function revealPage() {
            // Hilangkan anti-FOUC
            document.documentElement.style.visibility = 'visible';
            
            var loader = document.getElementById('page-loader');
            var content = document.getElementById('app-content');

            function doReveal() {
                setTimeout(function() {
                    if (loader) loader.classList.add('fade-out');
                    if (content) content.classList.add('visible');
                    
                    setTimeout(function() {
                        if (loader) loader.style.display = 'none';
                    }, 450); // Waktu yang selaras dengan durasi transisi CSS
                }, 450); // minimum display 450ms
            }

            // Tunggu hingga font selesai dimuat sebelum menampilkan konten
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(doReveal);
            } else {
                // Fallback untuk browser lama
                window.addEventListener('load', doReveal);
            }
        }

        document.addEventListener('DOMContentLoaded', revealPage);
    </script>

    {{-- SCROLL TO TOP + SCROLL REVEAL SCRIPT --}}
    <script>
        // ── Scroll To Top ──
        (function () {
            var btn = document.getElementById('scrollToTopBtn');
            if (!btn) return;

            function toggleBtn() {
                if (window.scrollY > 300) {
                    btn.classList.add('show');
                } else {
                    btn.classList.remove('show');
                }

                // Cek apakah tombol sedang berada di atas section/footer berwarna gelap
                var darkSections = document.querySelectorAll('.articles-section, footer');
                var btnCenterY = window.innerHeight - 24 - 23; // bottom:24px + height/2
                var onDark = false;

                darkSections.forEach(function (section) {
                    var rect = section.getBoundingClientRect();
                    if (rect.top <= btnCenterY && rect.bottom >= btnCenterY) {
                        onDark = true;
                    }
                });

                btn.classList.toggle('on-dark', onDark);
            }

            window.addEventListener('scroll', toggleBtn, { passive: true });
            toggleBtn();

            btn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();

        // ── Scroll Reveal Animation ──
        (function () {
            var targets = document.querySelectorAll('.reveal-on-scroll');
            if (!targets.length) return;

            if (!('IntersectionObserver' in window)) {
                targets.forEach(function (el) { el.classList.add('revealed'); });
                return;
            }

            var observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

            targets.forEach(function (el) { observer.observe(el); });
        })();
    </script>

    @stack('scripts')
</body>
</html>