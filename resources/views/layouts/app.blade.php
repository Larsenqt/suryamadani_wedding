<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', '')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        
        @auth
            @if(auth()->user()->role === 'admin')
                <!-- Admin Navigation (Sidebar) -->
                @include('layouts.admin-sidebar')
            @elseif(auth()->user()->role === 'customer')
                <!-- Customer Navigation (Top Navbar) -->
                @include('layouts.customer')
            @else
                <!-- Default Navigation -->
                @include('layouts.navigation')
            @endif
        @else
            <!-- Guest Navigation -->
            @include('layouts.navigation')
        @endauth

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="transition-all duration-300 flex-1
            @auth
                @if(auth()->user()->role === 'admin')
                    lg:ml-64
                @endif
            @endauth
        ">
            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- Footer (Only for Customer & Guest) -->
        @auth
            @if(auth()->user()->role === 'customer')
                @include('layouts.footer')
            @endif
        @else
            @include('layouts.footer')
        @endauth
    </div>

    @stack('scripts')
</body>
</html>