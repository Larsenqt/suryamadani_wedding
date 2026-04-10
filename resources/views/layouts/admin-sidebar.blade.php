@php
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#0f172a',
                    }
                }
            }
        }
    </script>

    <!-- Vite (jika ada) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', 'Inter', system-ui, -apple-system, sans-serif;
            background: #f5f7fa;
            color: #1f2937;
            overflow-x: hidden;
        }

        /* Admin Wrapper */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            width: 280px;
            background: #ffffff;
            border-right: 1px solid #e9ecef;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Brand Section */
        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid #eef2f6;
        }

        .brand-logo {
            display: flex;
            align-items: center;
        }

        .brand-image {
            max-height: 40px;
            width: auto;
            object-fit: contain;
        }

        /* Page Indicator */
        .page-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 0;
            margin-top: 1rem;
        }

        .page-indicator-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #3b82f6;
        }

        .page-indicator-text {
            font-size: 0.75rem;
            font-weight: 500;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .current-page {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            margin-top: 4px;
        }

        /* Navigation */
        .nav-area {
            flex: 1;
            padding: 1.25rem 0;
        }

        .nav-section {
            margin-bottom: 1.75rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.625rem 1.5rem;
            margin: 0 0.5rem;
            text-decoration: none;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: #f1f5f9;
            color: #1e40af;
        }

        .nav-link.active {
            background: #eef2ff;
            color: #1e40af;
        }

        .nav-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
        }

        .nav-link.active .nav-icon svg {
            stroke: #1e40af;
        }

        /* User Section */
        .sidebar-footer {
            border-top: 1px solid #eef2f6;
            padding: 1rem 1rem 1.5rem;
            background: #ffffff;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }

        .user-profile:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout-button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.625rem;
            border-radius: 10px;
            background: transparent;
            border: 1px solid #e2e8f0;
            color: #ef4444;
            font-size: 0.813rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .logout-button:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .logout-button svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            background: #f5f7fa;
        }

        /* Top Header */
        .top-header {
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: #475569;
        }

        .menu-toggle:hover {
            background: #f1f5f9;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0f172a;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding: 2rem;
        }

        /* Flash Messages */
        .flash-message {
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flash-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .flash-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: 99;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .overlay.open {
                display: block;
            }
            .content-wrapper {
                padding: 1rem;
            }
            .top-header {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <button class="menu-toggle" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 12h18M3 6h18M3 18h18"/>
        </svg>
    </button>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/navbar.png') }}" alt="Logo SuryaMadani" class="brand-image">
                </div>
                <div class="page-indicator">
                    <div class="page-indicator-dot"></div>
                    <span class="page-indicator-text">Current Page</span>
                </div>
                <div class="current-page">@yield('title', 'Dashboard')</div>
            </div>

            <div class="nav-area">
                <div class="nav-section">
                    <div class="nav-section-title">Overview</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                            </svg>
                        </span>
                        Dashboard
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        Users
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Catalog</div>
                    <a href="{{ route('admin.item-types.index') }}" class="nav-link {{ request()->routeIs('admin.item-types.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M7 7h10M7 12h10M7 17h6M3 3h18v18H3V3z"/>
                            </svg>
                        </span>
                        Categories
                    </a>
                    <a href="{{ route('admin.items.index') }}" class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 7h-4.18A3 3 0 0 0 16 5.18V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1.18A3 3 0 0 0 8.18 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                <path d="M12 11v4M10 13h4"/>
                            </svg>
                        </span>
                        Items
                    </a>
                    <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </span>
                        Testimonials
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Orders</div>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.index') && !request('status') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 6h18M8 6V4h8v2M3 10h18M5 14h14M7 18h10"/>
                            </svg>
                        </span>
                        All Orders
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </span>
                        Pending
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'approved']) }}" class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                        </span>
                        Approved
                    </a>
                </div>

                {{-- ==================== INVOICE MENU ==================== --}}
                <div class="nav-section">
                    <div class="nav-section-title">Finance</div>
                    <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                <path d="M16 2v4h4"/>
                                <path d="M3 10h18"/>
                            </svg>
                        </span>
                        Invoices
                    </a>
                    <a href="{{ route('admin.invoices.create') }}" class="nav-link {{ request()->routeIs('admin.invoices.create') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                        Create Invoice
                    </a>
                </div>
                {{-- ==================== END INVOICE MENU ==================== --}}
            </div>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-email">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-header">
                <div></div>
                <div class="page-title">@yield('title', 'Dashboard')</div>
                <div class="header-actions"></div>
            </div>
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="flash-message flash-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash-message flash-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        // Close sidebar when clicking overlay
        document.getElementById('overlay')?.addEventListener('click', function() {
            document.getElementById('sidebar')?.classList.remove('open');
            this.classList.remove('open');
        });

        // Close sidebar on window resize if open and screen becomes desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar')?.classList.remove('open');
                document.getElementById('overlay')?.classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>