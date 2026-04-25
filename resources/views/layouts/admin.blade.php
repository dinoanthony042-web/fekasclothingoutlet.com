<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Feka Admin Dashboard - Manage your store">
        <title>@yield('title', 'Admin Dashboard') | Feka Admin</title>
        <link rel="icon" href="/storage/fekasdark.png" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
        <header class="bg-white border-b border-gray-200">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
                <div class="flex items-center gap-4">
                    <img src="/storage/fekasdark.png" alt="Feka" class="h-10 w-auto">
                    <span class="text-lg font-semibold text-gray-900">Admin Panel</span>
                </div>

                <nav class="flex items-center gap-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Categories</a>
                    <a href="{{ route('admin.sliders.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Hero Sliders</a>
                    <a href="{{ route('admin.discounts.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Discounts</a>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Orders</a>
                    <a href="{{ route('admin.reports.sales') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Reports</a>
                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">View Store</a>
                </nav>

                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white py-6 mt-12">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 text-sm text-gray-600 md:flex-row md:items-center md:justify-between">
                <p>Feka Admin © {{ date('Y') }}. Store management system.</p>
                <p>Built for efficient store administration.</p>
            </div>
        </footer>
    </body>
</html>