@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<script>
// Debug at load time
console.log('=== CART PAGE LOAD DEBUG ===');
console.log('localStorage.getItem("guest_cart"):', localStorage.getItem('guest_cart'));
console.log('All localStorage keys:', Object.keys(localStorage));
</script>
<div class="space-y-8">
    <div class="rounded-[2rem] border border-[#e6d9f5] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-[#6b4b8a]">Your cart</p>
                <h1 class="mt-2 text-3xl font-semibold bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] bg-clip-text text-transparent">Ready to checkout?</h1>
            </div>
            <a href="{{ route('shop.index') }}" class="rounded-full border border-[#5b1e7e] px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#5b1e7e] transition hover:bg-[#faf5ff]">Continue shopping</a>
        </div>
    </div>

    <div id="cart-container">
        @if(auth()->check())
            @if($cartItems->isEmpty())
            <div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-10 text-center text-sm text-[#6b4b8a]">
                Your cart is empty. Explore our latest collection and add your favorites.
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1.4fr_0.6fr]">
                <div class="space-y-6" id="cart-items-list">
                    @foreach($cartItems as $item)
                        <div class="rounded-[2rem] border border-[#e6d9f5] bg-white p-6 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]" data-cart-id="{{ $item->id }}">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <img src="{{ $item->product->images[0] ?? 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $item->product->name }}" class="h-32 w-32 flex-shrink-0 rounded-[1.5rem] object-cover" />
                                <div class="flex-1 space-y-3">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1b1b18]">{{ $item->product->name }}</h2>
                                            <p class="text-sm text-[#6e625d]">{{ $item->product->category->name }}</p>
                                        </div>
                                        <span class="text-lg font-semibold text-[#1b1b18] item-total" data-cart-id="{{ $item->id }}">₦{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-[#5e534c]">
                                        @if($item->size)
                                            <span>Size: {{ $item->size }}</span>
                                        @endif
                                        @if($item->color)
                                            <span>Color: {{ $item->color }}</span>
                                        @endif
                                        <span>Qty: <span class="quantity-display">{{ $item->quantity }}</span></span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <div class="flex items-center gap-2">
                                            <button type="button" class="decrement-btn w-8 h-8 rounded-full border border-[#e6d9f5] bg-[#faf5ff] flex items-center justify-center text-[#5b1e7e] hover:border-[#5b1e7e] transition" data-cart-id="{{ $item->id }}">
                                                <span class="text-lg leading-none">−</span>
                                            </button>
                                            <span class="quantity-display w-8 text-center text-sm font-medium">{{ $item->quantity }}</span>
                                            <button type="button" class="increment-btn w-8 h-8 rounded-full border border-[#e6d9f5] bg-[#faf5ff] flex items-center justify-center text-[#5b1e7e] hover:border-[#5b1e7e] transition" data-cart-id="{{ $item->id }}">
                                                    <span class="text-lg leading-none">+</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('cart.destroy', $item) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="rounded-full border border-[#e6d9f5] bg-[#faf5ff] px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#5b1e7e] hover:border-[#5b1e7e]">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
                        <p class="text-sm uppercase tracking-[0.25em] text-[#6b4b8a]">Order summary</p>
                        <div class="mt-6 space-y-4 text-sm text-[#5a4570]">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold text-[#5b1e7e] cart-subtotal">₦{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Estimated delivery</span>
                                <span>Free</span>
                            </div>
                            <div class="flex items-center justify-between text-base font-semibold text-[#5b1e7e]">
                                <span>Total</span>
                                <span class="cart-total">₦{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ auth()->check() ? route('checkout.index') : route('login') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:shadow-lg">
                            {{ auth()->check() ? 'Checkout securely' : 'Login to checkout' }}
                        </a>
                    </div>

                    <div class="rounded-[2rem] border border-[#e6d9f5] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">
                        <p class="text-sm uppercase tracking-[0.25em] text-[#6b4b8a]">Need help?</p>
                        <p class="mt-4 text-sm leading-7 text-[#5a4570]">Contact our styling team for guidance with fit, sizing and pairing.</p>
                    </div>
                </aside>
            </div>
            @endif
        @else
            <div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-10 text-center text-sm text-[#6b4b8a]">
                Loading your cart...
            </div>
        @endif
    </div>
</div>

<script>
// Guest Cart Management
const guestCartKey = 'guest_cart';

function getGuestCart() {
    const cart = localStorage.getItem(guestCartKey);
    return cart ? JSON.parse(cart) : [];
}

function getTotalQuantity() {
    const cart = getGuestCart();
    return cart.reduce((total, item) => total + item.quantity, 0);
}

function renderGuestCart() {
    const cart = getGuestCart();
    console.log('Guest cart items:', cart);
    const container = document.getElementById('cart-container');

    if (cart.length === 0) {
        console.log('Guest cart is empty');
        container.innerHTML = `
            <div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-10 text-center text-sm text-[#6b4b8a]">
                Your cart is empty. Explore our latest collection and add your favorites.
            </div>
        `;
        updateCartCount(0);
                    return;
                }

    console.log('Fetching product details for', cart.length, 'items');
    // Fetch product details for all items in cart
    const productPromises = cart.map(item =>
        fetch(`/api/products/${item.product_id}`)
            .then(r => {
                console.log(`API response for product ${item.product_id}:`, r.status, r.statusText);
                if (!r.ok) {
                    console.error(`API returned status ${r.status} for product ${item.product_id}`);
                    throw new Error(`HTTP error! status: ${r.status}`);
                }
                return r.json().then(data => {
                    console.log(`Product ${item.product_id} data:`, data);
                    return data;
                });
            })
            .catch(error => {
                console.error(`Error fetching product ${item.product_id}:`, error);
                return null;
            })
    );

    console.log('Created', productPromises.length, 'fetch promises');
    Promise.all(productPromises)
        .then(products => {
            console.log('Product data received:', products);
            
            if (!products || products.length === 0) {
                console.log('No products fetched');
                container.innerHTML = `
                    <div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-10 text-center text-sm text-[#6b4b8a]">
                        Unable to load cart items. Please refresh the page.
                    </div>
                `;
                return;
            }

        let cartHTML = '<div class="grid gap-8 lg:grid-cols-[1.4fr_0.6fr]"><div class="space-y-6" id="cart-items-list">';
        let subtotal = 0;

        cart.forEach((cartItem, index) => {
            const product = products[index];
            console.log(`Processing product ${index}:`, product);
            if (!product) {
                console.log(`Product ${index} is null/undefined, skipping`);
                return;
            }

            const price = parseFloat(product.price) || 0;
            const itemTotal = price * cartItem.quantity;
            subtotal += itemTotal;

            cartHTML += `
                <div class="rounded-[2rem] border border-[#e6d9f5] bg-white p-6 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]" data-product-id="${product.id}">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <img src="${product.images[0] || 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80'}" alt="${product.name}" class="h-32 w-32 flex-shrink-0 rounded-[1.5rem] object-cover" />
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <h2 class="text-xl font-semibold text-[#1b1b18]">${product.name}</h2>
                                    <p class="text-sm text-[#6e625d]">${product.category?.name || 'Fashion'}</p>
                                </div>
                                <span class="text-lg font-semibold text-[#1b1b18] item-total">₦${itemTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-[#5e534c]">
                                <span>Qty: <span class="quantity-display">${cartItem.quantity}</span></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="decrement-btn-guest w-8 h-8 rounded-full border border-[#e6d9f5] bg-[#faf5ff] flex items-center justify-center text-[#5b1e7e] hover:border-[#5b1e7e] transition" data-product-id="${product.id}">
                                        <span class="text-lg leading-none">−</span>
                                    </button>
                                    <span class="quantity-display w-8 text-center text-sm font-medium">${cartItem.quantity}</span>
                                    <button type="button" class="increment-btn-guest w-8 h-8 rounded-full border border-[#e6d9f5] bg-[#faf5ff] flex items-center justify-center text-[#5b1e7e] hover:border-[#5b1e7e] transition" data-product-id="${product.id}">
                                        <span class="text-lg leading-none">+</span>
                                    </button>
                                </div>
                                <button type="button" class="remove-from-cart-guest rounded-full border border-[#e6d9f5] bg-[#faf5ff] px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#5b1e7e] hover:border-[#5b1e7e]" data-product-id="${product.id}">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        cartHTML += '</div><aside class="space-y-6"><div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">';
        cartHTML += '<p class="text-sm uppercase tracking-[0.25em] text-[#6b4b8a]">Order summary</p>';
        cartHTML += '<div class="mt-6 space-y-4 text-sm text-[#5a4570]">';
        cartHTML += `<div class="flex items-center justify-between"><span>Subtotal</span><span class="font-semibold text-[#5b1e7e] cart-subtotal">₦${subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</span></div>`;
        cartHTML += '<div class="flex items-center justify-between"><span>Estimated delivery</span><span>Free</span></div>';
        cartHTML += `<div class="flex items-center justify-between text-base font-semibold text-[#5b1e7e]"><span>Total</span><span class="cart-total">₦${subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</span></div>`;
        cartHTML += '</div>';
        cartHTML += '<a href="{{ route("login") }}" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#5b1e7e] to-[#8b2e9e] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:shadow-lg">Login to checkout</a>';
        cartHTML += '</div><div class="rounded-[2rem] border border-[#e6d9f5] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.12)]">';
        cartHTML += '<p class="text-sm uppercase tracking-[0.25em] text-[#6b4b8a]">Need help?</p>';
        cartHTML += '<p class="mt-4 text-sm leading-7 text-[#5a4570]">Contact our styling team for guidance with fit, sizing and pairing.</p>';
        cartHTML += '</div></aside></div>';

        console.log('Cart HTML length:', cartHTML.length);
        console.log('Cart subtotal:', subtotal);
        console.log('Setting innerHTML...');
        container.innerHTML = cartHTML;
        console.log('innerHTML set successfully');
        updateCartCount(getTotalQuantity());

        // Attach event listeners
        document.querySelectorAll('.increment-btn-guest').forEach(btn => {
            btn.addEventListener('click', function() {
                updateGuestQuantity(parseInt(this.dataset.productId), 'increment');
            });
        });

        document.querySelectorAll('.decrement-btn-guest').forEach(btn => {
            btn.addEventListener('click', function() {
                updateGuestQuantity(parseInt(this.dataset.productId), 'decrement');
            });
        });

        document.querySelectorAll('.remove-from-cart-guest').forEach(btn => {
            btn.addEventListener('click', function() {
                removeGuestItem(parseInt(this.dataset.productId));
            });
        });
        })
        .catch(error => {
            console.error('Error rendering guest cart:', error);
            container.innerHTML = `
                <div class="rounded-[2rem] border border-[#e6d9f5] bg-[#faf5ff] p-10 text-center text-sm text-[#6b4b8a]">
                    Error loading cart items. Please refresh the page.
                </div>
            `;
        });
}

function updateCartCount(count) {
    document.querySelectorAll('.cart-count').forEach(element => {
        element.textContent = count;
    });
        }

function updateGuestQuantity(productId, action) {
    let cart = getGuestCart();
    const item = cart.find(i => i.product_id === productId);

    if (!item) return;

    if (action === 'increment') {
        item.quantity++;
    } else if (action === 'decrement' && item.quantity > 1) {
        item.quantity--;
    }

    localStorage.setItem(guestCartKey, JSON.stringify(cart));
    const count = getTotalQuantity();
    updateCartCount(count);
    renderGuestCart();
}

function removeGuestItem(productId) {
    let cart = getGuestCart();
    cart = cart.filter(item => item.product_id !== productId);
    localStorage.setItem(guestCartKey, JSON.stringify(cart));
    const count = getTotalQuantity();
    updateCartCount(count);
    renderGuestCart();
}

document.addEventListener('DOMContentLoaded', function() {
    const isAuthenticated = @json(auth()->check());
    console.log('=== CART PAGE DOMContentLoaded ===');
    console.log('User authenticated:', isAuthenticated);
    console.log('localStorage guest_cart:', localStorage.getItem('guest_cart'));

    // If user is authenticated, check for guest cart and merge it
    if (isAuthenticated) {
        console.log('User is authenticated, merging guest cart if exists');
        mergeGuestCartToUser();
    } else {
        // For guests, render cart from localStorage
        console.log('User is guest, calling renderGuestCart()');
        renderGuestCart();
        console.log('renderGuestCart() called');
    }

    if (isAuthenticated) {
        // Handle increment buttons for authenticated users
        document.querySelectorAll('.increment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.dataset.cartId;
                updateQuantity(cartId, 'increment');
        });
});

        // Handle decrement buttons for authenticated users
        document.querySelectorAll('.decrement-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.dataset.cartId;
                updateQuantity(cartId, 'decrement');
            });
        });
    }

    function mergeGuestCartToUser() {
        const guestCart = getGuestCart();
        console.log('Checking for guest cart to merge:', guestCart);
        if (guestCart.length === 0) return;

        console.log('Merging', guestCart.length, 'guest cart items to user cart');
        // Add each guest cart item to user cart
        const promises = guestCart.map(item => {
            const formData = new FormData();
            formData.append('product_id', item.product_id);
            formData.append('quantity', item.quantity);

            return fetch('/cart', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });
        });

        Promise.all(promises).then(responses => {
            console.log('Merge responses:', responses);
            // Clear guest cart after successful merge
            localStorage.removeItem(guestCartKey);
            console.log('Guest cart cleared, reloading page');
            // Reload page to show merged cart
            window.location.reload();
        }).catch(error => {
            console.error('Error merging guest cart:', error);
        });
    }

    function updateQuantity(cartId, action) {
        const url = action === 'increment'
            ? `/cart/${cartId}/increment`
            : `/cart/${cartId}/decrement`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            if (!response.ok) {
                const payload = await response.json().catch(() => null);
                if (response.status === 404) {
                    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                    if (cartItem) {
                        cartItem.remove();
                        updateCartTotals();
                    }
                    return null;
                }

                const message = payload?.message || `HTTP ${response.status}`;
                throw new Error(message);
            }
            return response.json();
        })
        .then(data => {
            if (!data) {
                return;
            }

            if (!data.success) {
                alert(data.message || 'Unable to update the cart.');
                return;
            }

            // Update quantity display
            const quantityDisplays = document.querySelectorAll(`[data-cart-id="${cartId}"] .quantity-display`);
            quantityDisplays.forEach(display => {
                display.textContent = data.quantity;
            });

            // Update item total
            const itemTotalElement = document.querySelector(`.item-total[data-cart-id="${cartId}"]`);
            if (itemTotalElement) {
                itemTotalElement.textContent = `₦${data.item_total.toFixed(2)}`;
            }

            // Update cart totals
            const cartSubtotalElement = document.querySelector('.cart-subtotal');
            const cartTotalElement = document.querySelector('.cart-total');
            if (cartSubtotalElement) {
                cartSubtotalElement.textContent = `₦${data.cart_total.toFixed(2)}`;
            }
            if (cartTotalElement) {
                cartTotalElement.textContent = `₦${data.cart_total.toFixed(2)}`;
            }

            // Update cart count in header
            updateCartCount(data.cart_count);
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            // Show user-friendly error message
            alert('An error occurred while updating the cart. Please refresh the page and try again.');
        });
    }

    function updateCartTotals() {
        // Recalculate totals when an item is removed
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(item => {
            const price = parseFloat(item.textContent.replace('₦', '').replace(',', ''));
            if (!isNaN(price)) {
                subtotal += price;
            }
        });

        const cartSubtotalElement = document.querySelector('.cart-subtotal');
        const cartTotalElement = document.querySelector('.cart-total');
        if (cartSubtotalElement) {
            cartSubtotalElement.textContent = `₦${subtotal.toFixed(2)}`;
        }
        if (cartTotalElement) {
            cartTotalElement.textContent = `₦${subtotal.toFixed(2)}`;
        }

        // Update cart count
        let totalItems = 0;
        document.querySelectorAll('.quantity-display').forEach(qty => {
            totalItems += parseInt(qty.textContent) || 0;
        });
        updateCartCount(totalItems);
    }
});
</script>
@endsection

