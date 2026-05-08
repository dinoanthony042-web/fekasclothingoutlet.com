@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="space-y-24 bg-gradient-to-b from-[#faf5ff] via-white to-[#fff5f9]">

    {{-- HERO (Luxury Editorial Style) --}}
    <section class="relative overflow-hidden">

        <div class="absolute inset-0 bg-gradient-to-b from-[#faf5ff] via-[#fff8fc] to-[#f5f1ff]"></div>

        <div class="relative grid gap-12 lg:grid-cols-2 items-center px-6 lg:px-16 py-24">

            {{-- TEXT --}}
            <div class="space-y-8">
               

                <h1 class="text-5xl lg:text-6xl font-semibold tracking-[-0.04em] leading-tight bg-gradient-to-r from-[#5b1e7e] to-[#e91e8c] bg-clip-text text-transparent">
                    Modern luxury for everyone.
                </h1>

                <p class="max-w-md text-lg leading-8 text-[#4a3a5a]">
                    Curated clothing and accessories for women, men, and children designed for effortless elegance.
                </p>

                <div class="flex gap-4 flex-wrap">
                    <a href="{{ route('shop.index') }}"
                       class="rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] px-9 py-4 text-sm uppercase tracking-[0.2em] text-white shadow-[0_25px_50px_-25px_rgba(91,30,126,0.5)] transition hover:scale-[1.02] hover:shadow-[0_35px_60px_-25px_rgba(91,30,126,0.6)]">
                        Shop collection
                    </a>

                    <a href="{{ route('shop.index', ['category' => 'dresses']) }}"
                       class="rounded-full border-2 border-[#e91e8c] bg-white px-9 py-4 text-sm uppercase tracking-[0.2em] text-[#e91e8c] transition hover:bg-[#ffe6f5] hover:border-[#c91670]">
                        Explore dresses
                    </a>
                </div>
            </div>

            {{-- IMAGE CAROUSEL --}}
            <div class="relative">
                <div class="absolute -inset-6 bg-gradient-to-br from-[#5b1e7e]/10 to-[#e91e8c]/10 blur-3xl opacity-60 rounded-[3rem]"></div>

                <div class="relative rounded-[3rem] overflow-hidden shadow-2xl">
                    <div class="hero-carousel relative h-[720px] w-full">
                        {{-- Women Slide --}}
                        <div class="hero-slide active absolute inset-0 opacity-100 transition-opacity duration-500">
                            <img src="{{ asset('images/woman.jpg') }}"
                                 class="h-full w-full object-cover" alt="Women Fashion" />
                        </div>

                        {{-- Men Slide --}}
                        <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                            <img src="https://cdn.pixabay.com/photo/2024/12/20/17/38/ai-generated-9280702_640.jpg"
                                 class="h-full w-full object-cover" alt="Men Fashion" />
                        </div>

                        {{-- Children Slide --}}
                        <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                            <img src="{{ asset('images/kid.jpg') }}"
                                 class="h-full w-full object-cover" alt="Children Fashion" />
                        </div>
                    </div>

                    {{-- Navigation Dots --}}
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3">
                        <button class="hero-dot active w-3 h-3 rounded-full bg-white/70 hover:bg-white transition" data-slide="0"></button>
                        <button class="hero-dot w-3 h-3 rounded-full bg-white/70 hover:bg-white transition" data-slide="1"></button>
                        <button class="hero-dot w-3 h-3 rounded-full bg-white/70 hover:bg-white transition" data-slide="2"></button>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- EDITORIAL STRIP --}}
    <section class="px-6 lg:px-16 grid lg:grid-cols-3 gap-6">

        <div class="p-10 rounded-[2.5rem] bg-white border border-[#e6d9f5] shadow-sm hover:shadow-xl transition hover:border-[#d9c9f0]">
            <p class="text-xs uppercase tracking-[0.35em] text-[#6b4b8a]">New arrivals</p>
            <h3 class="mt-4 text-2xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] bg-clip-text text-transparent">Weekly curated drops</h3>
            <p class="mt-3 text-sm text-[#5a4570] leading-7">Fresh pieces added every week for modern wardrobes.</p>
        </div>

        <div class="p-10 rounded-[2.5rem] bg-gradient-to-br from-[#faf5ff] to-[#fff5f9] border border-[#e6d9f5] shadow-sm hover:shadow-xl transition hover:border-[#d9c9f0]">
            <p class="text-xs uppercase tracking-[0.35em] text-[#6b4b8a]">Luxury essentials</p>
            <h3 class="mt-4 text-2xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] bg-clip-text text-transparent">Timeless silhouettes</h3>
            <p class="mt-3 text-sm text-[#5a4570] leading-7">Minimal, elegant pieces that never go out of style.</p>
        </div>

        <div class="p-10 rounded-[2.5rem] bg-gradient-to-br from-[#5b1e7e] to-[#8b2e9e] border border-[#6b2e9e] shadow-sm hover:shadow-xl transition text-white">
            <p class="text-xs uppercase tracking-[0.35em] text-[#e6c9ff]">Exclusive</p>
            <h3 class="mt-4 text-2xl font-semibold">Limited edition drops</h3>
            <p class="mt-3 text-sm text-[#f0e6ff] leading-7">Rare collections released in small quantities.</p>
        </div>

    </section>


    {{-- CATEGORY GRID (Editorial Minimal) --}}
    <section class="px-6 lg:px-16 space-y-10">

        <div class="flex justify-between items-end">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-[#6b4b8a]">Shop by category</p>
                <h2 class="text-3xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] bg-clip-text text-transparent mt-3">
                    Clothing for every style.
                </h2>
            </div>

            <a href="{{ route('shop.index') }}"
               class="text-xs uppercase tracking-[0.3em] text-[#5b1e7e] font-semibold hover:text-[#e91e8c] transition">
                View all
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Women Category --}}
            <a href="{{ route('shop.index', ['category' => 'women']) }}"
               class="group rounded-[2rem] bg-white border border-[#e6d9f5] p-6 text-center transition hover:-translate-y-1 hover:shadow-2xl hover:border-[#e91e8c] focus:outline-none focus:ring-2 focus:ring-[#5b1e7e]">
                <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-br from-[#faf5ff] to-[#fff5f9] flex items-center justify-center group-hover:scale-110 transition overflow-hidden">
                    <img src="{{ asset('images/woman.jpg') }}" class="h-full w-full object-cover rounded-full" alt="Women">
                </div>
                <h3 class="mt-4 text-lg font-semibold text-[#5b1e7e]">
                    Women
                </h3>
                <p class="mt-2 text-sm text-[#7a5a9a] leading-6">
                    Elegant clothing and accessories for women
                </p>
            </a>

            {{-- Men Category --}}
            <a href="{{ route('shop.index', ['category' => 'men']) }}"
               class="group rounded-[2rem] bg-white border border-[#e6d9f5] p-6 text-center transition hover:-translate-y-1 hover:shadow-2xl hover:border-[#e91e8c] focus:outline-none focus:ring-2 focus:ring-[#5b1e7e]">
                <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-br from-[#faf5ff] to-[#fff5f9] flex items-center justify-center group-hover:scale-110 transition overflow-hidden">
                    <img src="https://cdn.pixabay.com/photo/2024/12/20/17/38/ai-generated-9280702_640.jpg" class="h-full w-full object-cover rounded-full" alt="Men">
                </div>
                <h3 class="mt-4 text-lg font-semibold text-[#5b1e7e]">
                    Men
                </h3>
                <p class="mt-2 text-sm text-[#7a5a9a] leading-6">
                    Stylish clothing and accessories for men
                </p>
            </a>

            {{-- Children Category --}}
            <a href="{{ route('shop.index', ['category' => 'children']) }}"
               class="group rounded-[2rem] bg-white border border-[#e6d9f5] p-6 text-center transition hover:-translate-y-1 hover:shadow-2xl hover:border-[#e91e8c] focus:outline-none focus:ring-2 focus:ring-[#5b1e7e]">
                <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-br from-[#faf5ff] to-[#fff5f9] flex items-center justify-center group-hover:scale-110 transition overflow-hidden">
                    <img src="{{ asset('images/kid.jpg') }}" class="h-full w-full object-cover rounded-full" alt="Children">
                </div>
                <h3 class="mt-4 text-lg font-semibold text-[#5b1e7e]">
                    Children
                </h3>
                <p class="mt-2 text-sm text-[#7a5a9a] leading-6">
                    Comfortable and fun clothing for children
                </p>
            </a>

        </div>
    </section>


    {{-- TRENDING PRODUCTS --}}
    <section class="px-6 lg:px-16 space-y-10">

        <div class="flex justify-between items-end">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-[#6b4b8a]">Trending now</p>
                <h2 class="text-3xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] bg-clip-text text-transparent mt-3">
                    Most wanted pieces.
                </h2>
            </div>

            <a href="{{ route('shop.index') }}"
               class="text-xs uppercase tracking-[0.3em] text-[#5b1e7e] font-semibold hover:text-[#e91e8c] transition">
                Shop all
            </a>
        </div>

        <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
            @foreach($featured as $product)
                <div class="group transition hover:-translate-y-1">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

    </section>


    {{-- EMAIL SIGNUP (Luxury minimal block) --}}
    <section class="px-6 lg:px-16">

        <div class="rounded-[3rem] bg-gradient-to-r from-[#faf5ff] via-[#fff5f9] to-white p-12 border border-[#e6d9f5]">

            <div class="max-w-2xl space-y-6">
                <p class="text-xs uppercase tracking-[0.35em] text-[#6b4b8a]">
                    Join the club
                </p>

                <h2 class="text-3xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#e91e8c] bg-clip-text text-transparent">
                    Get 10% off your first order.
                </h2>

                <p class="text-sm text-[#5a4570] leading-7">
                    Early access to drops, styling notes, and exclusive offers.
                </p>

                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email"
                           placeholder="Enter your email"
                           class="w-full rounded-full border border-[#e6d9f5] px-6 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#5b1e7e] focus:border-transparent" />

                    <button class="rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] px-8 py-3 text-sm uppercase tracking-[0.2em] text-white hover:shadow-lg transition">
                        Subscribe
                    </button>
                </form>
            </div>

        </div>

    </section>


    {{-- BEST SELLERS --}}
    <section class="px-6 lg:px-16 space-y-10 pb-20">

        <div class="flex justify-between items-end">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-[#6b4b8a]">Best sellers</p>
                <h2 class="text-3xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] bg-clip-text text-transparent mt-3">
                    Loved by customers.
                </h2>
            </div>

            <a href="{{ route('shop.index') }}"
               class="text-xs uppercase tracking-[0.3em] text-[#5b1e7e] font-semibold hover:text-[#e91e8c] transition">
                View all
            </a>
        </div>

        <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-4">
            @foreach($bestSellers as $product)
                <div class="group transition hover:-translate-y-1">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.hero-carousel');
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');

    let currentSlide = 0;
    let autoPlayInterval;

    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
            slide.classList.add('opacity-0');
            slide.classList.remove('opacity-100');
        });
        dots.forEach(dot => dot.classList.remove('active'));

        // Show current slide
        slides[index].classList.add('active');
        slides[index].classList.remove('opacity-0');
        slides[index].classList.add('opacity-100');
        dots[index].classList.add('active');

        currentSlide = index;
    }

    function nextSlide() {
        const nextIndex = (currentSlide + 1) % slides.length;
        showSlide(nextIndex);
    }

    function prevSlide() {
        const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prevIndex);
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 3000); // Change slide every 3 seconds
    }

    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }

    // Event listeners
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            stopAutoPlay();
            startAutoPlay(); // Restart autoplay
        });
    });

    // Pause autoplay on hover
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);

    // Start autoplay
    startAutoPlay();
});
</script>

@endsection