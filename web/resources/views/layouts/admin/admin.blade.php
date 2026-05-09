<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard — SkinQuo')</title>

    {{-- Tailwind CSS v4 --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --dark-brown: #603F26;
            --light-peach: #FFEAC5;
            --border-light: rgba(108, 78, 49, 0.15);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f6f3;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .admin-sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid var(--border-light);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 999;
        }

        .admin-main {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8f6f3;
        }

        .admin-header {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-link-admin {
            padding: 0.875rem 1.5rem;
            color: #6b7280;
            text-decoration: none;
            display: block;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-link-admin:hover,
        .nav-link-admin.active {
            background-color: #fef3e2;
            border-left-color: var(--dark-brown);
            color: var(--dark-brown);
        }

        .btn-primary-admin {
            background: var(--dark-brown);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary-admin:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(96, 63, 38, 0.2);
        }

        .btn-secondary-admin {
            background: white;
            color: var(--dark-brown);
            padding: 0.75rem 1.5rem;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-secondary-admin:hover {
            border-color: var(--dark-brown);
            background: #fef3e2;
        }

        .card-admin {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .table-admin {
            width: 100%;
            border-collapse: collapse;
        }

        .table-admin th {
            background: #f8f6f3;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark-brown);
            border-bottom: 1px solid var(--border-light);
        }

        .table-admin td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-light);
        }

        .table-admin tbody tr:hover {
            background-color: #fef9f3;
        }

        .badge-admin {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-success {
            background: #d1f4e0;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-admin {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1f4e0;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .input-admin {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-admin:focus {
            outline: none;
            border-color: var(--dark-brown);
            box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.1);
        }

        .input-admin.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 100%;
                max-width: 280px;
            }

            .admin-sidebar.active {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="flex min-h-screen bg-gray-100">
        {{-- ━━━ SIDEBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div class="admin-sidebar">
            {{-- Logo --}}
            <div style="padding: 2rem 1.5rem; border-bottom: 1px solid var(--border-light);">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-[var(--dark-brown)]" style="font-family: 'Playfair Display', serif;">
                    SkinQuo Admin
                </a>
            </div>

            {{-- Navigation Menu --}}
            <nav style="padding: 1.5rem 0;">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link-admin {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>

                {{-- Products Section --}}
                <div style="margin-top: 2rem; padding: 0 1.5rem;">
                    <p style="font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em;">Products</p>
                </div>
                <a href="{{ route('admin.products.index') }}" 
                   class="nav-link-admin {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    🛍️ Manage Products
                </a>

                {{-- Content Section --}}
                <div style="margin-top: 2rem; padding: 0 1.5rem;">
                    <p style="font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em;">Content</p>
                </div>
                <a href="{{ route('admin.skin-guide.index') }}" 
                   class="nav-link-admin {{ request()->routeIs('admin.skin-guide.*') ? 'active' : '' }}">
                    📚 Skin Guide
                </a>

                {{-- Monitoring Section --}}
                <div style="margin-top: 2rem; padding: 0 1.5rem;">
                    <p style="font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em;">Monitoring</p>
                </div>
                <a href="{{ route('admin.feedback.monitor') }}" 
                   class="nav-link-admin {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    💬 User Feedback
                </a>
            </nav>

            {{-- Logout Section --}}
            <div style="position: absolute; bottom: 0; width: 100%; padding: 1.5rem; border-top: 1px solid var(--border-light); background: white;">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-6 py-3 text-red-600 hover:bg-red-50 rounded transition font-medium">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- ━━━ MAIN CONTENT ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div class="admin-main flex-1 flex flex-col">
            {{-- Header --}}
            <div class="admin-header">
                <h1 class="page-title text-2xl font-bold text-[var(--dark-brown)]">@yield('page_title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600 text-sm">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=603F26&color=fff" 
                         alt="Avatar" class="w-10 h-10 rounded-full">
                </div>
            </div>

            {{-- Content Area --}}
            <div class="flex-1 overflow-y-auto p-8">
                {{-- Alerts --}}
                @if ($errors->any())
                    <div class="alert-admin alert-error">
                        <h3 class="font-semibold mb-2">⚠️ Validation Errors</h3>
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-admin alert-success">
                        <p>✅ {{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-admin alert-error">
                        <p>❌ {{ session('error') }}</p>
                    </div>
                @endif

                {{-- Page Content --}}
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
