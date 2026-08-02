@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="grid gap-6 sm:gap-8 lg:grid-cols-[1.2fr_0.8fr]">
    <section class="space-y-6 sm:space-y-8 rounded-lg sm:rounded-[2rem] border border-[#E7DDD4] bg-white p-4 sm:p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <div>
            <p class="text-xs sm:text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Secure checkout</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-semibold text-[#1b1b18]">Complete your order</h1>
        </div>

        <form action="{{ route('checkout.store') }}" method="post" class="space-y-4 sm:space-y-6">
            @csrf
            <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2">
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Full Name</label>
                    <input type="text" name="shipping_name" value="{{ old('shipping_name') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Phone</label>
                    <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                </div>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Delivery option</label>
                <div class="mt-2 flex flex-wrap gap-3">
                    <label class="flex items-center gap-2 rounded-full border border-[#e4dad1] bg-[#f9f4f0] px-3 py-2 text-xs sm:text-sm text-[#4f433d]">
                        <input type="radio" name="delivery_method" value="delivery" checked>
                        <span>Delivery</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-full border border-[#e4dad1] bg-[#f9f4f0] px-3 py-2 text-xs sm:text-sm text-[#4f433d]">
                        <input type="radio" name="delivery_method" value="pickup">
                        <span>Pickup</span>
                    </label>
                </div>
                <p class="mt-2 text-xs text-[#766459]">Choose delivery for home shipping or pickup for in-store collection.</p>
            </div>

            <div id="delivery-address-fields">
                <div class="grid gap-1 sm:gap-4">
                    <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Street address</label>
                    <input type="text" name="shipping_street" value="{{ old('shipping_street') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                </div>

                <div class="mt-4 grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">City</label>
                        <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">State</label>
                        <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Postcode</label>
                        <input type="text" name="shipping_postcode" value="{{ old('shipping_postcode') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Country</label>
                    <input type="text" name="shipping_country" value="{{ old('shipping_country') }}" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required />
                </div>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-[#4f433d]">Payment method</label>
                <select name="payment_method" class="mt-1 sm:mt-2 w-full rounded-2xl sm:rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm outline-none" required>
                    <!-- <option value="paystack">Paystack</option> -->
                    <option value="korapay">Korapay</option>
                    
                </select>
            </div>

            <button type="submit" class="w-full rounded-full bg-[#1b1b18] px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-[#403c39]">Place order</button>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('delivery-address-fields');
                const inputs = container ? Array.from(container.querySelectorAll('input[required]')) : [];

                const toggleAddressFields = () => {
                    const selected = document.querySelector('input[name="delivery_method"]:checked')?.value;
                    const isPickup = selected === 'pickup';

                    if (container) {
                        container.classList.toggle('hidden', isPickup);
                    }

                    inputs.forEach((input) => {
                        input.required = !isPickup;
                    });
                };

                document.querySelectorAll('input[name="delivery_method"]').forEach((radio) => {
                    radio.addEventListener('change', toggleAddressFields);
                });

                toggleAddressFields();
            });
        </script>
    </section>

    <aside class="space-y-4 sm:space-y-6">
        <div class="rounded-lg sm:rounded-[2rem] border border-[#E7DDD4] bg-[#F7F1ED] p-4 sm:p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
            <p class="text-xs sm:text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order summary</p>
            <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-4 text-xs sm:text-sm text-[#5e534c]">
                @php
                    $originalSubtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
                    $subtotal = $cartItems->sum(fn($item) => $item->product->discounted_price * $item->quantity);
                    $discountAmount = max(0, $originalSubtotal - $subtotal);
                @endphp
                <div class="flex items-center justify-between">
                    <span>Subtotal</span>
                    <span class="font-semibold text-[#1b1b18]">₦{{ number_format($originalSubtotal, 2) }}</span>
                </div>
                @if($discountAmount > 0)
                    <div class="flex items-center justify-between text-[#e91e8c]">
                        <span>Discount</span>
                        <span>-₦{{ number_format($discountAmount, 2) }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="flex items-center justify-between text-sm sm:text-base font-semibold text-[#1b1b18] border-t pt-2 sm:pt-3 mt-2 sm:mt-3">
                    <span>Total</span>
                    <span>₦{{ number_format($subtotal, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-lg sm:rounded-[2rem] border border-[#E7DDD4] bg-white p-4 sm:p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
            <p class="text-xs sm:text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Secure experience</p>
            <p class="mt-3 sm:mt-4 text-xs sm:text-sm leading-6 sm:leading-7 text-[#5e534c]">Checkout with confidence using secure payments and fast order processing.</p>
        </div>
    </aside>
</div>
@endsection
