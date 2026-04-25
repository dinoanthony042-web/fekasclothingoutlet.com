<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Feka Clothing is a premium womenswear destination for modern dresses, bags, accessories and curated luxury essentials.">
        <title>@yield('title', 'Feka Clothing') | Feka</title>
        <link rel="icon" href="/storage/fekasdark.png" type="image/png">
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
                <div class="flex items-center gap-4">
                    <span class="text-[#6b4b8a]">Free shipping on orders over ₦50,000</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <span class="text-[#6b4b8a]">Welcome back!</span>
                        <a href="{{ route('dashboard') }}" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">My Account</a>
                    @else
                        <a href="{{ route('login') }}" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">Sign In</a>
                        <a href="{{ route('register') }}" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">Join</a>
                    @endauth
                    <a href="#" class="text-[#5b1e7e] hover:text-[#e91e8c] transition">Help & FAQs</a>
                </div>
            </div>
        </div>

        {{-- MAIN HEADER --}}
        <header class="sticky top-0 z-50 border-b border-[#e6d9f5] bg-white/95 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4">
                <div class="flex items-center justify-between py-4">
                    {{-- MOBILE MENU BUTTON --}}
                    <button class="md:hidden p-2 text-[#5b1e7e]" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    {{-- LOGO --}}
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="/storage/fekasdark.png" alt="Feka" class="h-12 w-auto">
                    </a>

                    {{-- DESKTOP NAVIGATION --}}
                    <nav class="hidden md:flex items-center space-x-8">
                        <div class="relative group">
                            <a href="{{ route('shop.index') }}" class="text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition flex items-center gap-1">
                                Shop
                                <svg class="w-4 h-4 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            {{-- MEGA MENU --}}
                            <div class="absolute top-full left-0 w-screen max-w-6xl bg-white border border-[#e6d9f5] rounded-b-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="grid grid-cols-5 gap-8 p-8">
                                    @foreach($categories ?? collect() as $category)
                                        <div>
                                            <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="block text-sm font-semibold text-[#5b1e7e] hover:text-[#e91e8c] mb-3">
                                                {{ $category->name }}
                                            </a>
                                            <p class="text-xs text-[#6b4b8a] leading-5">{{ $category->description }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">New In</a>
                        <a href="#" class="text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">Sale</a>
                    </nav>

                    {{-- SEARCH BAR --}}
                    <div class="flex-1 max-w-2xl mx-8">
                        <form action="{{ route('shop.index') }}" method="get" class="relative">
                            <input
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Search for dresses, tops, shoes, bags..."
                                class="w-full rounded-full border-2 border-[#e6d9f5] bg-white px-6 py-4 pr-14 text-base outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10 transition placeholder:text-[#a088c0]"
                            />
                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#5b1e7e] hover:text-[#e91e8c] transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- RIGHT ICONS --}}
                    <div class="flex items-center gap-1">
                        @auth
                            {{-- USER DROPDOWN MENU --}}
                            <div class="relative group">
                                <button class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>

                                {{-- DROPDOWN CONTENT --}}
                                <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-[#e6d9f5] py-2 z-50 transition-opacity duration-200">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-[#1b1b18] hover:bg-[#f8f6ff] hover:text-[#5b1e7e] transition">
                                        My Account
                                    </a>
                                    <div class="border-t border-[#e6d9f5] my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#1b1b18] hover:bg-[#f8f6ff] hover:text-[#e91e8c] transition">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>
                        @endauth

                        <a href="{{ route('wishlist.index') }}" class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            @if(($wishlistCount ?? 0) > 0)
                                <span class="wishlist-count absolute -top-1 -right-1 rounded-full bg-[#e91e8c] px-1.5 py-0.5 text-xs font-semibold text-white min-w-[18px] text-center">{{ $wishlistCount ?? 0 }}</span>
                            @endif
                        </a>

                        <a href="{{ route('cart.index') }}" class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            @if(($cartCount ?? 0) > 0)
                                <span class="cart-count absolute -top-1 -right-1 rounded-full bg-[#5b1e7e] px-1.5 py-0.5 text-xs font-semibold text-white min-w-[18px] text-center">{{ $cartCount ?? 0 }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            {{-- MOBILE MENU --}}
            <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-[#e6d9f5]">
                <div class="px-4 py-6 space-y-4">
                    <a href="{{ route('shop.index') }}" class="block text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">Shop All</a>
                    <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="block text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">New In</a>
                    <a href="#" class="block text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">Sale</a>

                    <div class="border-t border-[#e6d9f5] pt-4 space-y-2">
                        <p class="text-xs font-semibold text-[#6b4b8a] uppercase tracking-wider">Categories</p>
                        @foreach($categories ?? collect() as $category)
                            <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="block text-sm text-[#5b1e7e] hover:text-[#e91e8c] transition">{{ $category->name }}</a>
                        @endforeach
                    </div>

                    <div class="border-t border-[#e6d9f5] pt-4 space-y-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="block text-sm text-[#5b1e7e] hover:text-[#e91e8c] transition">My Account</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block text-sm text-[#5b1e7e] hover:text-[#e91e8c] transition">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block text-sm text-[#5b1e7e] hover:text-[#e91e8c] transition">Sign In</a>
                            <a href="{{ route('register') }}" class="block text-sm text-[#5b1e7e] hover:text-[#e91e8c] transition">Join</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        @endunless

        <main class="mx-auto max-w-7xl px-4 py-10">
            @if(session('success'))
                <div class="mb-6 rounded-3xl border border-[#c9f0dd] bg-[#f0fdf8] px-6 py-4 text-sm text-[#156f4d]">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-3xl border border-[#f8d7da] bg-[#fff5f7] px-6 py-4 text-sm text-[#842029]">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-[#e6d9f5] bg-gradient-to-b from-white via-[#faf5ff] to-[#fff5f9] py-8">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 text-sm text-[#6b4b8a] md:flex-row md:items-center md:justify-between">
                <p>Feka Clothing © {{ date('Y') }}. Premium womenswear for modern wardrobes.</p>
                <p>Designed for elegant browsing and effortless shopping.</p>
            </div>
        </footer>

        <script>
            function toggleMobileMenu() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const menu = document.getElementById('mobile-menu');
                const button = event.target.closest('button[onclick="toggleMobileMenu()"]');

                if (!button && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });

            // User menu toggle function
            window.toggleUserMenu = function() {
                const menu = document.getElementById('user-menu');
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            };

            // Close user menu when clicking outside
            document.addEventListener('click', function(event) {
                const menu = document.getElementById('user-menu');
                const button = event.target.closest('button[onclick="toggleUserMenu()"]');

                if (!button && menu && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        </script>
    </body>
</html>

