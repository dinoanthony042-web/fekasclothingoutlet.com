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

    {{-- HORIZONTAL SCROLLING CATEGORY NAVBAR --}}
    <nav class="sticky top-[60px] md:top-[80px] z-40 border-b border-[#e6d9f5] bg-white/95 backdrop-blur overflow-x-auto scrollbar-hide">
        <div class="flex gap-2 px-4 py-3 min-w-min">
            <a href="{{ route('shop.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-full border border-[#e6d9f5] {{ empty($activeCategorySlug) ? 'bg-[#f7f0ff] text-[#5b1e7e]' : 'bg-white text-[#5b1e7e]' }} font-medium text-sm whitespace-nowrap hover:border-[#5b1e7e] hover:shadow-sm transition">
                🛍️ Shop All
            </a>
            
            @foreach($categories as $category)
                @if($category->parent_id === null)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="flex items-center gap-2 px-4 py-2 rounded-full border {{ $activeCategorySlug === $category->slug ? 'border-[#5b1e7e] bg-[#f7f0ff] text-[#5b1e7e]' : 'border-[#e6d9f5] bg-white text-[#5b1e7e]' }} font-medium text-sm whitespace-nowrap hover:bg-[#f0e6ff] transition">
                        {{ $category->name }}
                    </a>
                @endif
            @endforeach
            
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="flex items-center gap-2 px-4 py-2 rounded-full border border-[#e6d9f5] bg-white text-[#5b1e7e] font-medium text-sm whitespace-nowrap hover:bg-[#f0e6ff] transition">
                ✨ New In
            </a>
            
            <a href="#" class="flex items-center gap-2 px-4 py-2 rounded-full border border-[#ffe6f0] bg-white text-[#e91e8c] font-medium text-sm whitespace-nowrap hover:bg-[#ffe6f0] transition">
                🔥 Sale
            </a>
        </div>
    </nav>


</header>

@endunless

<main class="mx-auto max-w-7xl px-4 py-10">
    @yield('content')
</main>

<script>
// Simple smooth scroll for category navbar
document.querySelectorAll('.overflow-x-auto').forEach(container => {
    const scrollLeftBtn = document.createElement('button');
    scrollLeftBtn.innerHTML = '‹';
    const scrollRightBtn = document.createElement('button');
    scrollRightBtn.innerHTML = '›';
    
    // Optional: Add smooth scroll behavior
    container.style.scrollBehavior = 'smooth';
});
</script>

</body>
</html>