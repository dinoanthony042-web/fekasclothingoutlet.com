@props(['product'])
@php
    $isInWishlist = auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists();
    $isInCart = auth()->check() && auth()->user()->carts()->where('product_id', $product->id)->exists();
@endphp
<article class="flex flex-col h-full overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:border-slate-300" data-product-id="{{ $product->id }}" data-product-container>
    <div class="relative">
        <a href="{{ route('product.show', $product) }}" class="block overflow-hidden">
            <img src="{{ $product->images[0] ?? 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $product->name }}" class="h-64 w-full object-cover" />
        </a>
        <button type="button" class="wishlist-btn absolute top-3 right-3 rounded-full bg-white/80 p-2 text-lg transition hover:bg-white" data-product-id="{{ $product->id }}" data-in-wishlist="{{ $isInWishlist ? 'true' : 'false' }}">
            <span class="wishlist-icon {{ $isInWishlist ? 'text-red-500' : 'text-gray-400' }}">♥</span>
        </button>
    </div>

    <div class="flex flex-col flex-grow p-4">
        <div class="flex items-center justify-between text-xs uppercase tracking-[0.18em] text-slate-500 mb-2">
            <span>{{ $product->category->name ?? 'Fashion' }}</span>
        </div>
        <a href="{{ route('product.show', $product) }}" class="block text-base font-semibold text-slate-900 hover:text-slate-700 mb-2 min-h-[3rem] flex items-center">
            {{ $product->name }}
        </a>
        <div class="flex-grow mb-3">
            <p class="text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
        </div>
        <div class="flex items-center justify-between mt-3">
            <span class="text-lg font-bold text-slate-900">₦{{ number_format($product->price, 2) }}</span>
        </div>
        @if($isInCart && auth()->check())
            <form method="post" action="{{ route('cart.destroy') }}" class="mt-3 remove-from-cart-form">
                @csrf
                @method('delete')
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition remove-from-cart-btn">Remove from Cart</button>
            </form>
        @else
            <form method="post" action="{{ route('cart.store') }}" class="mt-3 add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-lg text-sm font-semibold hover:bg-slate-800 transition add-to-cart-btn">Add to Cart</button>
            </form>
        @endif
    </div>
</article>
