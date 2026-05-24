<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Feka Admin Dashboard - Manage your store">
        <title>@yield('title', 'Admin Dashboard') | Feka Admin</title>
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-3 sm:px-4 py-3 sm:py-4">
                <div class="flex items-center gap-2 sm:gap-4">
                    <img src="{{ asset('images/fekasdark.png') }}" alt="Feka" class="h-8 sm:h-10 w-auto">
                    <span class="text-base sm:text-lg font-semibold text-gray-900 hidden sm:inline">Admin Panel</span>
                </div>

                <!-- Mobile Menu Button -->
                <button id="menuBtn" class="sm:hidden text-gray-700 hover:text-gray-900 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <nav id="navMenu" class="hidden sm:flex w-full sm:w-auto sm:items-center gap-2 sm:gap-6 order-last sm:order-none basis-full sm:basis-auto py-3 sm:py-0 border-t sm:border-0">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Categories</a>
                    <a href="{{ route('admin.sliders.index') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Hero Sliders</a>
                    <a href="{{ route('admin.discounts.index') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Discounts</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Orders</a>
                    <a href="{{ route('admin.reports.sales') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">Reports</a>
                    <a href="{{ url('/') }}" class="block px-3 py-2 sm:px-0 sm:py-0 text-sm font-medium text-gray-700 hover:text-gray-900 transition">View Store</a>
                </nav>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 text-xs sm:text-sm">
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto rounded-md bg-gray-900 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white transition hover:bg-gray-800">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-3 sm:px-4 py-6 sm:py-8">
            @if(session('success'))
                <div class="mb-4 sm:mb-6 rounded-lg border border-green-200 bg-green-50 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 sm:mb-6 rounded-lg border border-red-200 bg-red-50 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white py-4 sm:py-6 mt-8 sm:mt-12">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 sm:gap-4 px-3 sm:px-4 text-xs sm:text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                <p>Feka Admin © {{ date('Y') }}. Store management system.</p>
                <p>Built for efficient store administration.</p>
            </div>
        </footer>

        <script>
            // Mobile menu toggle
            document.getElementById('menuBtn').addEventListener('click', function() {
                const menu = document.getElementById('navMenu');
                menu.classList.toggle('hidden');
            });
        </script>
    </body>
</html>