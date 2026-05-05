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

{{-- TOP BAR --}}
<div class="bg-[#f8f6ff] border-b border-[#e6d9f5]">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 text-xs">
        <span class="text-[#6b4b8a]">Free shipping on orders over ₦50,000</span>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">My Account</a>
            @else
                <a href="{{ route('login') }}" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">Sign In</a>
                <a href="{{ route('register') }}" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">Join</a>
            @endauth

            <a href="#" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">Help & FAQs</a>
        </div>
    </div>
</div>

{{-- HEADER --}}
<header class="sticky top-0 z-50 border-b border-[#e6d9f5] bg-white/95 backdrop-blur">

    <div class="mx-auto max-w-7xl px-4">

        <div class="flex items-center justify-between py-3 md:py-4 gap-3">

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
                     class="h-12 md:h-16 w-auto">
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
            <div class="flex-1 max-w-xl md:max-w-2xl mx-2 md:mx-6">
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
        <div class="flex h-full flex-col">
            {{-- Sticky Header --}}
            <div class="sticky top-0 z-50 flex items-center justify-between border-b border-[#e6d9f5] bg-white px-4 py-4">
                <button id="mobile-menu-back" class="hidden p-2 text-[#5b1e7e] transition hover:text-[#e91e8c]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h2 id="mobile-menu-title" class="text-lg font-semibold text-[#1b1b18]">Menu</h2>
                <button id="mobile-menu-close" class="p-2 text-[#5b1e7e] transition hover:text-[#e91e8c] cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Navigation Levels --}}
            <div class="flex-1 overflow-hidden">
                {{-- Level 1: Main Categories --}}
                <div id="nav-level-1" class="nav-level absolute inset-0 translate-x-0 transition-transform duration-300 ease-in-out">
                    <div class="p-4 space-y-0">
                        <a href="{{ route('shop.index', ['category' => 'women']) }}" class="flex items-center justify-between rounded-2xl border border-[#e6d9f5] bg-[#f7f0ff] px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#e8d9ff]">
                            <span>Women</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men']) }}" class="flex items-center justify-between rounded-2xl border border-[#e6d9f5] bg-[#f7f0ff] px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#e8d9ff]">
                            <span>Men</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children']) }}" class="flex items-center justify-between rounded-2xl border border-[#e6d9f5] bg-[#f7f0ff] px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#e8d9ff]">
                            <span>Kids</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <a href="{{ route('shop.index') }}" class="block rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">Shop All</a>
                        <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="block rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">New In</a>
                        <a href="#" class="block rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">Sale</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="block rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">My Account</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">Sign In</a>
                        @endauth
                    </div>
                </div>

                {{-- Level 2: Subcategories for Women --}}
                <div id="nav-level-2-women" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="p-4 space-y-0">
                        <a href="{{ route('shop.index', ['category' => 'women']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">View All Women</span>
                        </a>
                        <button data-category="women" data-sub="clothing" class="flex items-center justify-between w-full rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Clothing</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sub' => 'shoes']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Shoes</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sub' => 'accessories']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Accessories</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sort' => 'newest']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">New In</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'women', 'sale' => true]) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Sale</span>
                        </a>
                    </div>
                </div>

                {{-- Level 2: Subcategories for Men --}}
                <div id="nav-level-2-men" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="p-4 space-y-0">
                        <a href="{{ route('shop.index', ['category' => 'men']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">View All Men</span>
                        </a>
                        <button data-category="men" data-sub="clothing" class="flex items-center justify-between w-full rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Clothing</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sub' => 'shoes']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Shoes</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sub' => 'accessories']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Accessories</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sort' => 'newest']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">New In</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'men', 'sale' => true]) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Sale</span>
                        </a>
                    </div>
                </div>

                {{-- Level 2: Subcategories for Kids --}}
                <div id="nav-level-2-kids" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="p-4 space-y-0">
                        <a href="{{ route('shop.index', ['category' => 'children']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">View All Kids</span>
                        </a>
                        <button data-category="kids" data-sub="clothing" class="flex items-center justify-between w-full rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Clothing</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sub' => 'shoes']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Shoes</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sub' => 'accessories']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Accessories</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sort' => 'newest']) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">New In</span>
                        </a>
                        <a href="{{ route('shop.index', ['category' => 'children', 'sale' => true]) }}" class="flex items-center justify-between rounded-2xl bg-[#faf5ff] p-4 text-[#5b1e7e] transition hover:bg-[#f0e6ff]">
                            <span class="text-lg font-medium">Sale</span>
                        </a>
                    </div>
                </div>

                {{-- Level 3: Clothing Subcategories --}}
                <div id="nav-level-3-clothing" class="nav-level absolute inset-0 translate-x-full transition-transform duration-300 ease-in-out">
                    <div class="p-4 space-y-0">
                        <a id="view-all-clothing" href="#" class="block rounded-2xl border border-[#e6d9f5] bg-white px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#faf5ff]">
                            <span>View All Clothing</span>
                        </a>
                        <a id="polo-link" href="#" class="block rounded-2xl border border-[#e6d9f5] bg-white px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#faf5ff]">
                            <span>Polo</span>
                        </a>
                        <a id="tshirts-link" href="#" class="block rounded-2xl border border-[#e6d9f5] bg-white px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#faf5ff]">
                            <span>T-Shirts</span>
                        </a>
                        <a id="jeans-link" href="#" class="block rounded-2xl border border-[#e6d9f5] bg-white px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#faf5ff]">
                            <span>Jeans</span>
                        </a>
                        <a id="jackets-link" href="#" class="block rounded-2xl border border-[#e6d9f5] bg-white px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#faf5ff]">
                            <span>Jackets</span>
                        </a>
                        <a id="hoodies-link" href="#" class="block rounded-2xl border border-[#e6d9f5] bg-white px-4 py-3 text-base font-semibold text-[#4a1f76] transition hover:bg-[#faf5ff]">
                            <span>Hoodies</span>
                        </a>
                    </div>
                </div>
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

function toggleMobileMenu() {
    const overlay = document.getElementById('mobile-menu-overlay');
    overlay.classList.toggle('hidden');
    if (!overlay.classList.contains('hidden')) {
        showLevel('nav-level-1');
    }
}

function showLevel(levelId) {
    const levels = document.querySelectorAll('.nav-level');
    levels.forEach(level => {
        level.classList.add('translate-x-full');
        level.classList.remove('translate-x-0');
    });
    const targetLevel = document.getElementById(levelId);
    targetLevel.classList.remove('translate-x-full');
    targetLevel.classList.add('translate-x-0');

    // Update header title
    const title = document.getElementById('mobile-menu-title');
    const backBtn = document.getElementById('mobile-menu-back');

    if (levelId === 'nav-level-1') {
        title.textContent = 'Menu';
        backBtn.classList.add('hidden');
        currentCategory = '';
    } else if (levelId.startsWith('nav-level-2')) {
        const category = levelId.split('-')[3];
        currentCategory = category === 'kids' ? 'children' : category;
        title.textContent = category.charAt(0).toUpperCase() + category.slice(1);
        backBtn.classList.remove('hidden');
    } else if (levelId === 'nav-level-3-clothing') {
        title.textContent = 'Clothing';
        backBtn.classList.remove('hidden');
        updateLevel3Links();
    }
}

function updateLevel3Links() {
    const baseUrl = '/shop?category=' + currentCategory + '&sub=clothing';
    document.getElementById('view-all-clothing').href = baseUrl;
    document.getElementById('polo-link').href = baseUrl + '&type=polo';
    document.getElementById('tshirts-link').href = baseUrl + '&type=t-shirts';
    document.getElementById('jeans-link').href = baseUrl + '&type=jeans';
    document.getElementById('jackets-link').href = baseUrl + '&type=jackets';
    document.getElementById('hoodies-link').href = baseUrl + '&type=hoodies';
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
        closeBtn.addEventListener('click', function() {
            document.getElementById('mobile-menu-overlay').classList.add('hidden');
        });
    }

    // Back button
    const backBtn = document.getElementById('mobile-menu-back');
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            const currentLevel = document.querySelector('.nav-level.translate-x-0');
            if (currentLevel.id.startsWith('nav-level-3')) {
                showLevel(`nav-level-2-${currentCategory === 'children' ? 'kids' : currentCategory}`);
            } else if (currentLevel.id.startsWith('nav-level-2')) {
                showLevel('nav-level-1');
            }
        });
    }

    // Level 1 navigation
    document.querySelectorAll('#nav-level-1 a[href*="category="]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const urlParams = new URLSearchParams(this.href.split('?')[1]);
            const category = urlParams.get('category');
            if (category === 'women' || category === 'men' || category === 'children') {
                showLevel(`nav-level-2-${category === 'children' ? 'kids' : category}`);
            } else {
                window.location.href = this.href;
            }
        });
    });

    // Level 2 navigation
    document.querySelectorAll('[data-category][data-sub]').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            const sub = this.dataset.sub;
            if (sub === 'clothing') {
                showLevel('nav-level-3-clothing');
            }
        });
    });
});
</script>

</body>
</html>