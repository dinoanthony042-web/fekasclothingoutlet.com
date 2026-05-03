<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Feka Clothing is a premium womenswear destination for modern dresses, bags, accessories and curated luxury essentials.">

    <title>@yield('title', 'Feka Clothing Outlet') | Fekas Clothing Outlet</title>

    <link rel="icon" href="{{ asset('storage/fekasdark.png') }}" type="image/png">
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
            <button class="md:hidden p-2 text-[#5b1e7e] flex-shrink-0" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- LOGO (BIGGER BUT RESPONSIVE) --}}
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('storage/fekasdark.png') }}"
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
                    <button class="group relative p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
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
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-[#e6d9f5]">
        <div class="px-4 py-6 space-y-4">
            <a href="{{ route('shop.index') }}" class="block">Shop</a>
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="block">New In</a>
            <a href="#" class="block">Sale</a>

            <div class="border-t pt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="block">My Account</a>
                @else
                    <a href="{{ route('login') }}" class="block">Sign In</a>
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
function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
}
</script>

</body>
</html>