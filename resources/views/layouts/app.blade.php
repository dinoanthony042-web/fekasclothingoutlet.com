<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Feka Clothing is a premium  destination for modern dresses, bags, accessories and curated luxury essentials.">

    <title>@yield('title', 'Fekas Clothing Outlet') | Fekas Clothing Outlet</title>

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

<div class="mx-auto max-w-7xl px-2 sm:px-3">

        <div class="flex items-center justify-between py-2 sm:py-3 md:py-4 gap-1 sm:gap-2">

            {{-- LOGO (BIGGER BUT RESPONSIVE) --}}
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('images/fekasdark.png') }}"
                     alt="Feka"
                     class="h-16 sm:h-20 md:h-32 w-auto">
            </a>

            {{-- NAVIGATION (HIDDEN ON MOBILE) --}}
            <nav class="hidden lg:flex items-center space-x-4 xl:space-x-8">
                <div class="relative group">
                    <a href="{{ route('shop.index') }}"
                       class="text-xs sm:text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition flex items-center gap-1">
                        Shop
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>

                    {{-- MEGA MENU --}}
                    <div class="absolute top-full left-0 w-screen max-w-6xl bg-white border border-[#e6d9f5] rounded-b-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="grid grid-cols-3 lg:grid-cols-5 gap-6 lg:gap-8 p-6 lg:p-8">
                            @foreach($categories ?? collect() as $category)
                                <div>
                                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                       class="block text-xs lg:text-sm font-semibold text-[#5b1e7e] hover:text-[#e91e8c] mb-2 lg:mb-3">
                                        {{ $category->name }}
                                    </a>
                                    <p class="text-xs text-[#6b4b8a] leading-5 line-clamp-2">
                                        {{ $category->description }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('shop.index', ['sort' => 'newest']) }}"
                   class="text-xs sm:text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">
                    New In
                </a>

                <a href="{{ route('shop.index', ['sale' => 1]) }}"
                   class="text-xs sm:text-sm font-medium text-[#1b1b18] hover:text-[#5b1e7e] transition">
                    Sale
                </a>
            </nav>

            {{-- SEARCH BAR (RESPONSIVE) --}}
            <div class="flex-1 max-w-full sm:max-w-xs md:max-w-lg lg:max-w-2xl mx-1 sm:mx-2 md:mx-4 lg:mx-6">
                <form action="{{ route('shop.index') }}" method="get" class="relative w-full">
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search..."
                        class="w-full rounded-full border-2 border-[#e6d9f5] bg-white px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 pr-8 sm:pr-10 md:pr-14 text-xs sm:text-sm md:text-base outline-none focus:border-[#5b1e7e] focus:ring-4 focus:ring-[#5b1e7e]/10 transition placeholder:text-[#a088c0]"
                    />

                    <button type="submit"
                        class="absolute right-2 sm:right-3 md:right-4 top-1/2 -translate-y-1/2 text-[#5b1e7e] hover:text-[#e91e8c] transition">
                        <svg class="w-4 sm:w-5 md:w-6 h-4 sm:h-5 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- RIGHT ICONS (RESPONSIVE) --}}
            <div class="flex items-center gap-0.5 sm:gap-1 flex-shrink-0">

                @auth
                <div class="relative" id="profile-menu-wrapper">
                    <button id="profile-menu-button" class="relative p-2 sm:p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition inline-flex items-center gap-2" type="button" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Open profile menu</span>
                        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    <div id="profile-menu" class="absolute right-0 top-full mt-2 w-40 sm:w-44 overflow-hidden rounded-2xl border border-[#e6d9f5] bg-white shadow-xl opacity-0 invisible transition-all duration-200 z-50">
                        <a href="{{ route('dashboard') }}" class="block px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-[#1b1b18] hover:bg-[#f5f0ff]">Dashboard</a>
                        <a href="{{ route('orders.index') }}" class="block px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-[#1b1b18] hover:bg-[#f5f0ff]">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-[#f2ebff]">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-[#5b1e7e] hover:bg-[#f5f0ff]">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="group relative p-2 sm:p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
                @endauth

                <a href="{{ route('wishlist.index') }}" class="group relative p-2 sm:p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @auth
                        @php
                            $wishlistCount = auth()->user()->wishlists()->count();
                        @endphp
                        @if($wishlistCount > 0)
                            <span class="wishlist-count absolute -top-1 -right-1 rounded-full bg-[#e91e8c] px-1.5 py-0.5 text-xs font-semibold text-white min-w-[18px] text-center">{{ $wishlistCount }}</span>
                        @endif
                    @endauth
                </a>

                <a href="{{ route('cart.index') }}" class="group relative p-2 sm:p-3 text-[#6f6b67] hover:text-[#5b1e7e] transition">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </a>

            </div>

        </div>
    </div>

    {{-- HORIZONTAL SCROLLING CATEGORY NAVBAR --}}
    <nav class="sticky top-[50px] sm:top-[60px] md:top-[70px] lg:top-[80px] z-40 border-b border-[#e6d9f5] bg-white/95 backdrop-blur overflow-x-auto scrollbar-hide">
        <div class="flex gap-2 px-2 sm:px-4 py-2 sm:py-3 min-w-min">
            <a href="{{ route('shop.index') }}" class="flex items-center gap-1 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-[#e6d9f5] {{ empty($activeCategorySlug) ? 'bg-[#f7f0ff] text-[#5b1e7e]' : 'bg-white text-[#5b1e7e]' }} font-medium text-xs sm:text-sm whitespace-nowrap hover:border-[#5b1e7e] hover:shadow-sm transition">
                🛍️ Shop All
            </a>
            
            @foreach($categories as $category)
                @if($category->parent_id === null)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="flex items-center gap-1 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border {{ $activeCategorySlug === $category->slug ? 'border-[#5b1e7e] bg-[#f7f0ff] text-[#5b1e7e]' : 'border-[#e6d9f5] bg-white text-[#5b1e7e]' }} font-medium text-xs sm:text-sm whitespace-nowrap hover:bg-[#f0e6ff] transition">
                        {{ $category->name }}
                    </a>
                @endif
            @endforeach
            
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="flex items-center gap-1 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-[#e6d9f5] bg-white text-[#5b1e7e] font-medium text-xs sm:text-sm whitespace-nowrap hover:bg-[#f0e6ff] transition">
                ✨ New In
            </a>
            
            <a href="#" class="flex items-center gap-1 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-[#ffe6f0] bg-white text-[#e91e8c] font-medium text-xs sm:text-sm whitespace-nowrap hover:bg-[#ffe6f0] transition">
                🔥 Sale
            </a>
        </div>
    </nav>


</header>

@endunless

<main class="mx-auto max-w-7xl px-2 sm:px-4 py-6 sm:py-8 md:py-10">
    @if(session('success'))
        <div class="mb-4 sm:mb-6 rounded-md bg-green-50 border border-green-100 p-3 sm:p-4 text-xs sm:text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-4">
            <div>
                <h2 class="text-base font-semibold text-white">Fekas Clothing Outlet</h2>
                <p class="mt-3 text-sm text-[#e6d9f5] max-w-sm">Curated fashion, premium quality and fast delivery for the modern wardrobe.</p>
                <div class="mt-5 flex gap-3">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-[#5b1e7e] transition" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-[#5b1e7e] transition" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.205.625c-.713.304-1.32.71-1.903 1.293-.583.583-.989 1.19-1.293 1.903-.295.7-.493 1.57-.553 2.848C.014 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.553 2.848.304.713.71 1.32 1.293 1.903.583.583 1.19.989 1.903 1.293.7.295 1.57.493 2.848.553C8.333 23.986 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.261 2.848-.553.713-.304 1.32-.71 1.903-1.293.583-.583.989-1.19 1.293-1.903.295-.7.493-1.57.553-2.848.058-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.261-2.148-.553-2.848-.304-.713-.71-1.32-1.293-1.903-.583-.583-1.19-.989-1.903-1.293-.7-.295-1.57-.493-2.848-.553C15.667.014 15.26 0 12 0zm0 2.16c3.203 0 3.585.009 4.849.070 1.171.054 1.805.244 2.227.408.56.217.96.477 1.382.896.419.42.679.822.896 1.381.164.422.354 1.056.408 2.227.061 1.264.07 1.646.07 4.849s-.009 3.585-.07 4.849c-.054 1.171-.244 1.805-.408 2.227-.217.56-.477.96-.896 1.382-.42.419-.822.679-1.381.896-.422.164-1.056.354-2.227.408-1.264.061-1.646.07-4.849.07s-3.585-.009-4.849-.07c-1.171-.054-1.805-.244-2.227-.408-.56-.217-.96-.477-1.382-.896-.419-.42-.679-.822-.896-1.381-.164-.422-.354-1.056-.408-2.227-.061-1.264-.07-1.646-.07-4.849s.009-3.585.07-4.849c.054-1.171.244-1.805.408-2.227.217-.56.477-.96.896-1.382.42-.419.822-.679 1.381-.896.422-.164 1.056-.354 2.227-.408 1.264-.061 1.646-.07 4.849-.07zm0 3.678a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998zm5.817-10.427a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                        </svg>
                    </a>
                 <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-[#5b1e7e] transition"
   aria-label="X">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M18.244 2H21.5l-7.11 8.13L22 22h-5.95l-4.66-6.11L6.06 22H2.8l7.6-8.69L2 2h6.1l4.21 5.55L18.244 2zm-1.04 18h1.8L7.12 3.9H5.2L17.204 20z"/>
    </svg>
</a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-[#5b1e7e] transition" aria-label="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">Quick links</h3>
                <ul class="mt-3 space-y-2 text-sm text-[#e6d9f5]">
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white transition">Shop</a></li>
                    <li><a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="hover:text-white transition">New arrivals</a></li>
                    <li><a href="{{ route('shop.index', ['sale' => 1]) }}" class="hover:text-white transition">Sale</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">Support</h3>
                <ul class="mt-3 space-y-2 text-sm text-[#e6d9f5]">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition">About us</a></li>
                    <li><a href="{{ url('/') }}" class="hover:text-white transition">Shipping info</a></li>
                    <li><a href="{{ url('/') }}" class="hover:text-white transition">Returns</a></li>
                    <li><a href="{{ url('/') }}" class="hover:text-white transition">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">Contact</h3>
                <p class="mt-3 text-sm text-[#e6d9f5]">Email: support@fekasclothingoutlet.com</p>
                <p class="mt-2 text-sm text-[#e6d9f5]">Phone: +234 800 000 0000</p>
                <p class="mt-4 text-sm text-[#e6d9f5]">Subscribe to our newsletter for exclusive offers and new arrivals.</p>
                <form class="mt-3 flex gap-2">
                    <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg bg-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 text-sm" />
                    <button type="submit" class="px-4 py-2 rounded-lg bg-white text-[#5b1e7e] font-semibold hover:bg-[#e6d9f5] transition text-sm">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="mt-10 border-t border-white/20 pt-6 text-center text-sm text-[#e6d9f5]">
            © {{ date('Y') }} Fekas Clothing Outlet. All rights reserved.
        </div>
    </div>
</footer>

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

(function() {
    const button = document.getElementById('profile-menu-button');
    const menu = document.getElementById('profile-menu');
    const wrapper = document.getElementById('profile-menu-wrapper');

    if (!button || !menu || !wrapper) {
        return;
    }

    function closeMenu() {
        menu.classList.add('opacity-0', 'invisible');
        menu.classList.remove('opacity-100', 'visible');
        button.setAttribute('aria-expanded', 'false');
    }

    function openMenu() {
        menu.classList.remove('opacity-0', 'invisible');
        menu.classList.add('opacity-100', 'visible');
        button.setAttribute('aria-expanded', 'true');
    }

    button.addEventListener('click', function(event) {
        event.stopPropagation();
        if (menu.classList.contains('visible')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    document.addEventListener('click', function(event) {
        if (!wrapper.contains(event.target)) {
            closeMenu();
        }
    });

    wrapper.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });
})();
</script>

</body>
</html>