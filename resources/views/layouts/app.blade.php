<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Feka Clothing is a premium womenswear destination for modern dresses, bags, accessories and curated luxury essentials.">

    <title>@yield('title', 'Feka Clothing Outlet') | Fekas Clothing Outlet</title>

    <link rel="icon" href="{{ asset('images/fekasdark.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-[#faf5ff] via-white to-[#fff5f9] text-[#1B1B18] antialiased @auth authenticated @endauth">

@unless(request()->routeIs(['login', 'register']))



{{-- HEADER --}}
<header class="sticky top-0 z-50 border-b border-[#e6d9f5] bg-white/95 backdrop-blur">

<div class="mx-auto max-w-7xl px-3">

        <div class="flex items-center justify-between py-3 md:py-4 gap-2">

            {{-- MOBILE MENU BUTTON --}}
            <button id="mobile-menu-toggle" class="md:hidden p-2 text-[#5b1e7e] flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- LOGO (BIGGER BUT RESPONSIVE) --}}
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('images/fekasdark.png') }}"
                     alt="Feka"
                     class="h-10 md:h-16 w-auto">
            </a>

            {{-- NAVIGATION (CATEGORIES KEPT) --}}
            <nav class="hidden md:flex items-center space-x-8">
                <div class="relative group">
                    <a href="{{ route('shop.index') }}"
                       class="text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition flex items-center gap-1">
                        Shop
                        <svg class="w-4 h-4 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>

                    {{-- MEGA MENU --}}
                    <div class="absolute top-full left-0 w-screen max-w-6xl bg-white border border-[#e6d9f5] rounded-b-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="grid grid-cols-5 gap-8 p-8">
                            @foreach($categories ?? collect() as $category)
                                <div>
                                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                       class="block text-sm font-semibold text-[#5b1e7e] hover:text-[#e91e8c] mb-3">
                                        {{ $category->name }}
                                    </a>
                                    <p class="text-xs text-[#6b4b8a] leading-5">
                                        {{ $category->description }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('shop.index', ['sort' => 'newest']) }}"
                   class="text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">
                    New In
                </a>

                <a href="#" class="text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">
                    Sale
                </a>
            </nav>

            {{-- SEARCH BAR (RESTORED FULLY) --}}
            <div class="flex-1 max-w-full md:max-w-2xl mx-1 md:mx-6">
                <form action="{{ route('shop.index') }}" method="get" class="relative w-full">
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search dresses, tops, shoes, bags..."
                        class="w-full rounded-full border-2 border-[#e6d9f5] bg-white px-4 md:px-6 py-2 md:py-3 pr-10 md:pr-14 text-sm md:text-base outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10 transition placeholder:text-[#a088c0]"
                    />

                    <button type="submit"
                        class="absolute right-3 md:right-4 top-1/2 -translate-y-1/2 text-[#5b1e7e] hover:text-[#e91e8c] transition">
                        <svg class="w-5 md:w-6 h-5 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- RIGHT ICONS (UNCHANGED EXACTLY) --}}
            <div class="flex items-center gap-1 flex-shrink-0">

                @auth
                <div class="relative group">
                    <button class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition inline-flex items-center gap-2" type="button" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Open profile menu</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    <div class="absolute right-0 top-full mt-2 w-44 overflow-hidden rounded-2xl border border-[#e6d9f5] bg-white shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible group-focus-within:opacity-100 group-focus-within:visible transition-all duration-200 z-50">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-[#1b1b18] hover:bg-[#f5f0ff]">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-[#f2ebff]">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-sm text-[#5b1e7e] hover:bg-[#f5f0ff]">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
                @endauth

                <a href="{{ route('wishlist.index') }}" class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>

                <a href="{{ route('cart.index') }}" class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </a>

            </div>

        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div id="mobile-menu-overlay" class="fixed inset-0 z-50 hidden bg-white md:hidden">
        <div class="flex h-full flex-col bg-gradient-to-b from-white via-[#faf5ff] to-white">
            {{-- Sticky Header with Search --}}
            <div class="sticky top-0 z-40 border-b border-[#e6d9f5] bg-white/95 backdrop-blur px-4 py-3 shadow-sm">
                {{-- Header Controls --}}
                <div class="flex items-center justify-between mb-3">
                    <button id="mobile-menu-back" class="hidden p-2 text-[#5b1e7e] transition hover:text-[#e91e8c] active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h2 id="mobile-menu-title" class="text-base font-semibold text-[#1b1b18] flex-1 text-center">Menu</h2>
                    <button id="mobile-menu-close" class="p-2 text-[#5b1e7e] transition hover:text-[#e91e8c] active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Search Bar in Sidebar --}}
                <div class="relative">
                    <input
                        type="search"
                        id="mobile-menu-search"
                        placeholder="Search..."
                        class="w-full rounded-full border-2 border-[#e6d9f5] bg-[#f7f0ff] px-4 py-2.5 text-sm outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10 transition placeholder:text-[#a088c0]"
                    />
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#a088c0] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Navigation Levels Container --}}
            <div class="flex-1 overflow-y-auto scrollbar-hide">
                {{-- Level 1: Main Categories --}}
                <div id="nav-level-1" class="nav-level absolute inset-0 translate-x-0 transition-transform duration-300 ease-in-out">
                    <div class="px-4 py-3 space-y-2">
                        {{-- Main Categories with Proper Tap Area --}}
                        <button onclick="navigateToLevel('nav-level-2-women')" class="nav-item-tap w-full flex items-center justify-between rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            <span>👗 Women</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <button onclick="navigateToLevel('nav-level-2-men')" class="nav-item-tap w-full flex items-center justify-between rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            <span>👔 Men</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <button onclick="navigateToLevel('nav-level-2-kids')" class="nav-item-tap w-full flex items-center justify-between rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            <span>👶 Kids</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        {{-- Divider --}}
                        <div class="border-t border-[#e6d9f5] my-2"></div>

                        {{-- Additional Links --}}
                        <a href="{{ route('shop.index') }}" class="nav-item-tap block w-full rounded-2xl bg-white px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95 border border-[#f0e6ff]">
                            🛍️ Shop All
                        </a>
                        <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="nav-item-tap block w-full rounded-2xl bg-white px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95 border border-[#f0e6ff]">
                            ✨ New In
                        </a>
                        <a href="#" class="nav-item-tap block w-full rounded-2xl bg-white px-4 py-3 text-[#e91e8c] font-medium transition hover:bg-[#ffe6f0] active:scale-95 border border-[#ffe6f0]">
                            🔥 Sale
                        </a>
                    </div>
                </div>

                {{-- Level 2: Subcategories for Women --}}
                <div id="nav-level-2-women" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="px-4 py-3 space-y-2">
                        <a href="{{ route('shop.index', ['category' => 'women']) }}" class="nav-item-tap block w-full rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            👗 View All Women
                        </a>
                        <button onclick="navigateToLevel('nav-level-3-clothing')" data-category="women" data-sub="clothing" class="nav-item-tap w-full flex items-center justify-between rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            <span>👕 Clothing</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sub' => 'shoes']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👠 Shoes
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sub' => 'accessories']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👜 Accessories
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sort' => 'newest']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            ✨ New In
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sale' => true]) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#ffe6f0] px-4 py-3 text-[#e91e8c] font-medium transition hover:bg-[#ffe6f0] active:scale-95">
                            🔥 Sale
                        </a>
                    </div>
                </div>

                {{-- Level 2: Subcategories for Men --}}
                <div id="nav-level-2-men" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="px-4 py-3 space-y-2">
                        <a href="{{ route('shop.index', ['category' => 'men']) }}" class="nav-item-tap block w-full rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            👔 View All Men
                        </a>
                        <button onclick="navigateToLevel('nav-level-3-clothing')" data-category="men" data-sub="clothing" class="nav-item-tap w-full flex items-center justify-between rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            <span>👕 Clothing</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sub' => 'shoes']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👟 Shoes
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sub' => 'accessories']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            ⌚ Accessories
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sort' => 'newest']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            ✨ New In
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sale' => true]) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#ffe6f0] px-4 py-3 text-[#e91e8c] font-medium transition hover:bg-[#ffe6f0] active:scale-95">
                            🔥 Sale
                        </a>
                    </div>
                </div>

                {{-- Level 2: Subcategories for Kids --}}
                <div id="nav-level-2-kids" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="px-4 py-3 space-y-2">
                        <a href="{{ route('shop.index', ['category' => 'children']) }}" class="nav-item-tap block w-full rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            👶 View All Kids
                        </a>
                        <button onclick="navigateToLevel('nav-level-3-clothing')" data-category="kids" data-sub="clothing" class="nav-item-tap w-full flex items-center justify-between rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            <span>👕 Clothing</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sub' => 'shoes']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👟 Shoes
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sub' => 'accessories']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            🎀 Accessories
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sort' => 'newest']) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            ✨ New In
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sale' => true]) }}" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#ffe6f0] px-4 py-3 text-[#e91e8c] font-medium transition hover:bg-[#ffe6f0] active:scale-95">
                            🔥 Sale
                        </a>
                    </div>
                </div>

                {{-- Level 3: Clothing Subcategories --}}
                <div id="nav-level-3-clothing" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="px-4 py-3 space-y-2">
                        <a id="view-all-clothing" href="#" class="nav-item-tap block w-full rounded-2xl border border-[#e6d9f5] bg-gradient-to-r from-[#f7f0ff] to-[#faf5ff] px-4 py-3.5 text-base font-semibold text-[#4a1f76] transition hover:border-[#5b1e7e] hover:shadow-md active:scale-95">
                            View All Clothing
                        </a>
                        <a id="polo-link" href="#" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👕 Polo
                        </a>
                        <a id="tshirts-link" href="#" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👕 T-Shirts
                        </a>
                        <a id="jeans-link" href="#" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            👖 Jeans
                        </a>
                        <a id="jackets-link" href="#" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            🧥 Jackets
                        </a>
                        <a id="hoodies-link" href="#" class="nav-item-tap block w-full rounded-2xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                            🎽 Hoodies
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Links Footer Section --}}
            <div class="border-t border-[#e6d9f5] bg-gradient-to-t from-[#faf5ff] to-white p-4 space-y-2">
                <div class="text-xs font-semibold text-[#8b7f7a] uppercase tracking-wide px-1 mb-3">Quick Links</div>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-item-tap flex items-center gap-3 rounded-xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>My Account</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="nav-item-tap flex items-center gap-3 rounded-xl bg-white border border-[#f0e6ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>My Orders</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item-tap w-full flex items-center gap-3 rounded-xl bg-[#f5e6ff] border border-[#e0c9ff] px-4 py-3 text-[#5b1e7e] font-medium transition hover:bg-[#e8d9ff] active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-item-tap flex items-center gap-3 rounded-xl bg-gradient-to-r from-[#5b1e7e] to-[#6b2e8e] text-white px-4 py-3 font-medium transition hover:shadow-lg active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1"></path>
                        </svg>
                        <span>Sign In</span>
                    </a>
                    <a href="{{ route('register') }}" class="nav-item-tap flex items-center gap-3 rounded-xl bg-white border-2 border-[#5b1e7e] text-[#5b1e7e] px-4 py-3 font-medium transition hover:bg-[#f0e6ff] active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span>Create Account</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>


</header>

@endunless

<main class="mx-auto max-w-7xl px-4 py-10">
    @yield('content')
</main>

<script>
let currentCategory = '';
let navigationHistory = [];

function navigateToLevel(levelId) {
    const levels = document.querySelectorAll('.nav-level');
    levels.forEach(level => {
        level.classList.add('translate-x-full');
        level.classList.remove('translate-x-0');
    });
    
    const targetLevel = document.getElementById(levelId);
    targetLevel.classList.remove('translate-x-full');
    targetLevel.classList.add('translate-x-0');

    updateMenuHeader(levelId);
    navigationHistory.push(levelId);
}

function updateMenuHeader(levelId) {
    const title = document.getElementById('mobile-menu-title');
    const backBtn = document.getElementById('mobile-menu-back');

    if (levelId === 'nav-level-1') {
        title.textContent = 'Menu';
        backBtn.classList.add('hidden');
        currentCategory = '';
    } else if (levelId === 'nav-level-2-women') {
        title.textContent = '👗 Women';
        backBtn.classList.remove('hidden');
        currentCategory = 'women';
    } else if (levelId === 'nav-level-2-men') {
        title.textContent = '👔 Men';
        backBtn.classList.remove('hidden');
        currentCategory = 'men';
    } else if (levelId === 'nav-level-2-kids') {
        title.textContent = '👶 Kids';
        backBtn.classList.remove('hidden');
        currentCategory = 'kids';
    } else if (levelId === 'nav-level-3-clothing') {
        title.textContent = '👕 Clothing';
        backBtn.classList.remove('hidden');
        updateLevel3Links();
    }
}

function updateLevel3Links() {
    let categoryParam = currentCategory === 'kids' ? 'children' : currentCategory;
    const baseUrl = '/shop?category=' + categoryParam + '&sub=clothing';
    
    document.getElementById('view-all-clothing').href = baseUrl;
    document.getElementById('polo-link').href = baseUrl + '&type=polo';
    document.getElementById('tshirts-link').href = baseUrl + '&type=t-shirts';
    document.getElementById('jeans-link').href = baseUrl + '&type=jeans';
    document.getElementById('jackets-link').href = baseUrl + '&type=jackets';
    document.getElementById('hoodies-link').href = baseUrl + '&type=hoodies';
}

function toggleMobileMenu() {
    const overlay = document.getElementById('mobile-menu-overlay');
    overlay.classList.toggle('hidden');
    if (!overlay.classList.contains('hidden')) {
        navigationHistory = [];
        navigateToLevel('nav-level-1');
    }
}

function closeMobileMenu() {
    document.getElementById('mobile-menu-overlay').classList.add('hidden');
}

function goBack() {
    if (navigationHistory.length > 1) {
        navigationHistory.pop(); // Remove current
        navigateToLevel(navigationHistory[navigationHistory.length - 1]);
    } else {
        navigateToLevel('nav-level-1');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Hamburger menu toggle
    const menuBtn = document.getElementById('mobile-menu-toggle');
    if (menuBtn) {
        menuBtn.addEventListener('click', toggleMobileMenu);
    }

    // Close menu
    const closeBtn = document.getElementById('mobile-menu-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeMobileMenu);
    }

    // Back button
    const backBtn = document.getElementById('mobile-menu-back');
    if (backBtn) {
        backBtn.addEventListener('click', goBack);
    }

    // Mobile menu search
    const searchInput = document.getElementById('mobile-menu-search');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            // Optional: Could open search results screen
        });
    }

    // Add tap feedback to nav items
    document.querySelectorAll('.nav-item-tap').forEach(item => {
        item.addEventListener('touchstart', function() {
            this.style.opacity = '0.7';
        });
        item.addEventListener('touchend', function() {
            this.style.opacity = '1';
        });
    });

    // Close menu when clicking on a link (except buttons with onclick)
    document.querySelectorAll('#mobile-menu-overlay a').forEach(link => {
        if (!link.getAttribute('onclick')) {
            link.addEventListener('click', function() {
                // Delay closing to allow smooth navigation
                setTimeout(closeMobileMenu, 100);
            });
        }
    });

    // Make level 2 category buttons work with onclick handler
    const level2Buttons = document.querySelectorAll('[data-category][data-sub]');
    level2Buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const sub = this.dataset.sub;
            if (sub === 'clothing') {
                navigateToLevel('nav-level-3-clothing');
            }
        });
    });
});
</script>

</body>
</html>