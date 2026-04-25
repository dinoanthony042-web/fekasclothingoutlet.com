@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="grid gap-10 lg:grid-cols-[1.3fr_0.9fr]">
    <div class="space-y-8">
        <!-- Product Gallery Component -->
        <x-product-gallery :product="$product" />

        <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
            <div class="flex flex-wrap items-center gap-4 text-sm uppercase tracking-[0.25em] text-[#7d6d66]">
                <span>{{ $product->category->name }}</span>
                <span class="text-[#d3c4bd]">•</span>
                <span>{{ implode(', ', $product->styles ?? []) }}</span>
            </div>
            <h1 class="mt-4 text-4xl font-semibold text-[#1b1b18]">{{ $product->name }}</h1>
            <p class="mt-4 max-w-2xl text-base leading-8 text-[#5e534c]">{{ $product->description }}</p>

            <div class="mt-8 flex items-center gap-6" data-product-container>
                <span class="text-3xl font-semibold text-[#1b1b18]">₦{{ number_format($product->price, 2) }}</span>
                <span class="rounded-full bg-[#F1ECE8] px-4 py-2 text-sm uppercase tracking-[0.18em] text-[#766459]">{{ $product->stock }} in stock</span>
            </div>

            <form action="{{ route('cart.store') }}" method="post" class="mt-8 grid gap-6 add-to-cart-form" data-product-container x-data="productForm()" @submit="validateForm($event)">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @if($product->sizes)
                    <div>
                        <label class="block text-sm font-semibold text-[#4f433d]">Size</label>
                        <div class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                            @foreach($product->sizes as $size)
                                <label class="cursor-pointer rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-center text-sm text-[#1b1b18] transition hover:border-black peer-checked:border-[#1b1b18] peer-checked:bg-[#1b1b18] peer-checked:text-white">
                                    <input type="radio" name="size" value="{{ $size }}" class="sr-only peer" @checked(old('size') === $size) required>
                                    {{ $size }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($product->colors)
                    <div>
                        <label class="block text-sm font-semibold text-[#4f433d]">Color</label>
                        <div class="mt-3 flex flex-wrap gap-3">
                            @foreach($product->colors as $color)
                                <label class="cursor-pointer rounded-full border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm text-[#1b1b18] transition hover:border-black peer-checked:border-[#1b1b18] peer-checked:bg-[#1b1b18] peer-checked:text-white">
                                    <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" @checked(old('color') === $color) required>
                                    {{ $color }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-[#4f433d]">Quantity</label>
                    <input type="number" name="quantity" value="1" min="1" class="mt-3 w-24 rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" />
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#1b1b18] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-[#403c39] add-to-cart-btn" :disabled="!isFormValid" :class="{ 'opacity-50 cursor-not-allowed': !isFormValid }">Add to cart</button>
                    <a href="{{ route('wishlist.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#1b1b18] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-[#1b1b18] transition hover:bg-[#f5f0ec]">Add to wishlist</a>
                </div>

                <!-- Form validation script -->
                <script>
                    function productForm() {
                        return {
                            selectedSize: @json(old('size')),
                            selectedColor: @json(old('color')),

                            get isFormValid() {
                                const hasSizes = @json(count($product->sizes ?? [])) > 0;
                                const hasColors = @json(count($product->colors ?? [])) > 0;

                                if (hasSizes && !this.selectedSize) return false;
                                if (hasColors && !this.selectedColor) return false;
                                return true;
                            },

                            validateForm(event) {
                                if (!this.isFormValid) {
                                    event.preventDefault();
                                    alert('Please select all required options (size and color) before adding to cart.');
                                    return false;
                                }
                            },

                            init() {
                                // Listen for radio button changes
                                this.$el.addEventListener('change', (e) => {
                                    if (e.target.name === 'size') {
                                        this.selectedSize = e.target.value;
                                    } else if (e.target.name === 'color') {
                                        this.selectedColor = e.target.value;
                                    }
                                });
                            }
                        }
                    }
                </script>
            </form>
        </div>

        <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Reviews</p>
                    <p class="text-2xl font-semibold text-[#1b1b18]">Customer feedback</p>
                </div>
                <div class="rounded-full bg-[#f6f0ec] px-4 py-2 text-sm font-semibold text-[#6e625d]">Average {{ number_format($product->averageRating(), 1) }}/5</div>
            </div>
            <div class="mt-6 space-y-5">
                @forelse($reviews as $review)
                    <div class="rounded-[1.75rem] bg-[#f9f4f0] p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-[#1b1b18]">{{ $review->user->name }}</p>
                                <p class="text-sm text-[#7d6d66]">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="rounded-full bg-[#1b1b18] px-4 py-2 text-sm font-semibold text-white">{{ $review->rating }}★</span>
                        </div>
                        <p class="mt-4 text-sm leading-7 text-[#5e534c]">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-sm text-[#6e625d]">This product has no reviews yet. Be the first to leave feedback.</p>
                @endforelse
            </div>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Product details</p>
            <ul class="mt-6 space-y-4 text-sm leading-7 text-[#5e534c]">
                <li><strong class="text-[#1b1b18]">Category:</strong> {{ $product->category->name }}</li>
                <li><strong class="text-[#1b1b18]">Styles:</strong> {{ implode(', ', $product->styles ?? []) }}</li>
                <li><strong class="text-[#1b1b18]">Colors:</strong> {{ implode(', ', $product->colors ?? []) }}</li>
                <li><strong class="text-[#1b1b18]">Sizes:</strong> {{ implode(', ', $product->sizes ?? []) }}</li>
            </ul>
        </div>

        <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#F9F4F0] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Trending now</p>
            <p class="mt-4 text-sm leading-7 text-[#5e534c]">Find your next favorite look from our bestselling designs and curated essentials.</p>
        </div>
    </aside>
</div>

<section class="mt-14 space-y-6">
    <div class="flex items-end justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Related</p>
            <h2 class="mt-2 text-3xl font-semibold text-[#1b1b18]">Complete the look</h2>
        </div>
        <a href="{{ route('shop.index') }}" class="text-sm font-semibold uppercase tracking-[0.25em] text-[#1b1b18]">View shop</a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($related as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endsection
