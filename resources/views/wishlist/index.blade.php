@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Your wishlist</p>
        <h1 class="mt-2 text-3xl font-semibold text-[#1b1b18]">Save pieces you love.</h1>
    </div>

    @if($wishlist->isEmpty())
        <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#f9f4f0] p-12 text-center text-sm text-[#5e534c]">
            Your wishlist is empty. Add items from the shop to review them later.
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($wishlist as $item)
                <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-6 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                    <img src="{{ $item->product->images[0] ?? 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $item->product->name }}" class="h-64 w-full rounded-[1.5rem] object-cover" />
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('product.show', $item->product) }}" class="text-xl font-semibold text-[#1b1b18] hover:text-[#7b6c65]">{{ $item->product->name }}</a>
                        @if($item->product->isOnSale())
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-[#8c7d74] line-through">₦{{ number_format($item->product->price, 2) }}</span>
                                <span class="text-sm font-bold text-[#e91e8c]">₦{{ number_format($item->product->discounted_price, 2) }}</span>
                            </div>
                        @else
                            <p class="text-sm text-[#6e625d]">₦{{ number_format($item->product->price, 2) }}</p>
                        @endif
                        <div class="flex flex-wrap gap-3">
                            <form action="{{ route('cart.store') }}" method="post" class="inline add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}" />
                                <button type="submit" class="add-to-cart-btn rounded-full bg-[#1b1b18] px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-[#403c39]">Add to cart</button>
                            </form>
                            <button type="button" class="remove-wishlist-btn rounded-full border border-[#d1c4be] bg-[#faf7f4] px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#1b1b18] hover:border-[#1b1b18]" data-product-id="{{ $item->product->id }}">Remove</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(form);
                var button = form.querySelector('.add-to-cart-btn');
                var originalText = button.textContent;
                
                button.textContent = 'Adding...';
                button.disabled = true;
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        var cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cart_count;
                        }
                        
                        // Show success message
                        showMessage(data.message, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
            });
        });

        // Remove from wishlist functionality
        document.querySelectorAll('.remove-wishlist-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var productId = this.dataset.productId;
                var itemDiv = this.closest('.rounded-[2rem]');
                
                var url = '{{ route("wishlist.destroy", ":productId") }}'.replace(':productId', productId);
                
                if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update wishlist count
                            var wishlistCountElement = document.querySelector('.wishlist-count');
                            if (wishlistCountElement) {
                                wishlistCountElement.textContent = data.wishlist_count;
                            }
                            
                            // Remove the item from the page
                            itemDiv.remove();
                            
                            // Check if wishlist is now empty
                            var wishlistItems = document.querySelectorAll('.rounded-[2rem]');
                            if (wishlistItems.length === 1) { // Only the header remains
                                location.reload(); // Reload to show empty state
                            }
                            
                            // Show success message
                            showMessage(data.message, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred. Please try again.', 'error');
                    });
                }
            });
        });

        function showMessage(message, type) {
            // Remove existing messages
            var existingMessage = document.querySelector('.flash-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Create new message
            var messageDiv = document.createElement('div');
            messageDiv.className = 'flash-message mb-6 rounded-3xl px-6 py-4 text-sm';
            
            if (type === 'success') {
                messageDiv.classList.add('border', 'border-[#D8C9C2]', 'bg-[#FCF3EE]', 'text-[#422F2A]');
            } else {
                messageDiv.classList.add('border', 'border-[#E6B4B0]', 'bg-[#FDE7E4]', 'text-[#7D2E34]');
            }
            
            messageDiv.textContent = message;
            
            // Insert at the top of main content
            var main = document.querySelector('main');
            if (main) {
                main.insertBefore(messageDiv, main.firstChild);
                
                // Auto remove after 3 seconds
                setTimeout(function() {
                    if (messageDiv.parentNode) {
                        messageDiv.remove();
                    }
                }, 3000);
            }
        }
    });
</script>
@endsection
