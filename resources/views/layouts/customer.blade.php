<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', 'Inter', sans-serif;
            background: #f5f7fa;
            color: #1f2937;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Navbar Customer */
        .customer-navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-image {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-link {
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            font-size: 0.875rem;
            transition: color 0.2s;
            padding: 0.5rem 0;
        }

        .nav-link:hover {
            color: #2563eb;
        }

        .nav-link.active {
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
        }

        .cart-icon {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            text-decoration: none;
            color: #1e293b;
            transition: all 0.2s;
        }

        .cart-icon:hover {
            background: #eff6ff;
        }

        .cart-count {
            background: #2563eb;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            cursor: pointer;
            border: none;
            font-family: inherit;
            font-size: 0.875rem;
            color: #1e293b;
            transition: all 0.2s;
        }

        .user-btn:hover {
            background: #eff6ff;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            z-index: 100;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #475569;
            font-size: 0.875rem;
            transition: all 0.2s;
            width: 100%;
            background: white;
            border: none;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f8fafc;
            color: #2563eb;
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0;
        }

        .dropdown-icon {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 1.5;
            flex-shrink: 0;
        }

        .logout-item {
            color: #ef4444;
        }

        .logout-item:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #475569;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }

        .mobile-menu-btn:hover {
            background: #f1f5f9;
        }

        .mobile-nav {
            display: none;
            padding: 1rem 2rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .mobile-nav.open {
            display: block;
        }

        .mobile-nav-link {
            display: block;
            padding: 0.75rem 0;
            text-decoration: none;
            color: #475569;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 60px);
        }

        /* Flash Messages */
        .flash-success, .flash-error {
            max-width: 1400px;
            margin: 1rem auto 0;
            padding: 0 2rem;
        }
        
        .flash-success div {
            background: #dcfce7;
            border: 1px solid #a7f3d0;
            border-radius: 0.5rem;
            padding: 1rem;
            color: #065f46;
        }

        .flash-error div {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 1rem;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0.75rem 1rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <nav class="customer-navbar">
        <div class="nav-container">
            <a href="{{ route('customer.catalog') }}" class="logo">
                @php
                    $logoPath = public_path('images/navbar.png');
                    $logoExists = file_exists($logoPath);
                @endphp
                @if($logoExists)
                    <img src="{{ asset('images/navbar.png') }}" alt="SuryaMadani" class="logo-image">
                @else
                    <span style="font-size: 1.25rem; font-weight: 700; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        
                    </span>
                @endif
            </a>
            
            <div class="nav-links">
                <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('customer.catalog') }}" class="nav-link {{ request()->routeIs('customer.catalog') ? 'active' : '' }}">
                    Katalog
                </a>
                <a href="{{ route('customer.orders.history') }}" class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                    Riwayat Pesanan
                </a>
                <a href="{{ route('customer.checkout') }}" class="cart-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    Keranjang
                </a>
                
                @auth
                <div class="user-dropdown" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="user-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M5.5 20c.5-2 3-4 6.5-4s6 2 6.5 4"/>
                        </svg>
                        {{ Auth::user()->name }}
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M5.5 20c.5-2 3-4 6.5-4s6 2 6.5 4"/>
                            </svg>
                            Pengaturan
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link">Register</a>
                @endauth
            </div>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
        </div>
        
        <div class="mobile-nav" id="mobileNav">
            <a href="{{ route('customer.dashboard') }}" class="mobile-nav-link">Dashboard</a>
            <a href="{{ route('customer.catalog') }}" class="mobile-nav-link">Katalog</a>
            <a href="{{ route('customer.orders.history') }}" class="mobile-nav-link">Riwayat Pesanan</a>
            <a href="{{ route('customer.checkout') }}" class="mobile-nav-link">🛒 Keranjang</a>
            @auth
            <a href="{{ route('profile.edit') }}" class="mobile-nav-link">⚙️ Pengaturan</a>
            <div class="dropdown-divider" style="margin: 0.5rem 0;"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mobile-nav-link" style="background: none; border: none; text-align: left; width: 100%; cursor: pointer; color: #ef4444;">
                    🚪 Logout
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="mobile-nav-link">Login</a>
            <a href="{{ route('register') }}" class="mobile-nav-link">Register</a>
            @endauth
        </div>
    </nav>

    <main class="main-content">
        @if(session('success'))
            <div class="flash-success">
                <div>✅ {{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error">
                <div>❌ {{ session('error') }}</div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <script>
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            mobileNav.classList.toggle('open');
        }

        async function updateCartCount() {
            try {
                const response = await fetch('{{ route("customer.cart.count") }}');
                const data = await response.json();
                const cartCount = document.getElementById('cartCount');
                if (cartCount) {
                    cartCount.textContent = data.count || 0;
                }
            } catch (error) {
                console.error('Failed to update cart count');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>