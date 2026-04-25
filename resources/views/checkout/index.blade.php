@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
    <section class="space-y-8 rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Secure checkout</p>
            <h1 class="mt-2 text-3xl font-semibold text-[#1b1b18]">Complete your order</h1>
        </div>

        <form action="{{ route('checkout.store') }}" method="post" class="space-y-6">
            @csrf
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-[#4f433d]">Full Name</label>
                    <input type="text" name="shipping_name" value="{{ old('shipping_name') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4f433d]">Phone</label>
                    <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
                </div>
            </div>

            <div class="grid gap-4">
                <label class="block text-sm font-semibold text-[#4f433d]">Street address</label>
                <input type="text" name="shipping_street" value="{{ old('shipping_street') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold text-[#4f433d]">City</label>
                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4f433d]">State</label>
                    <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#4f433d]">Postcode</label>
                    <input type="text" name="shipping_postcode" value="{{ old('shipping_postcode') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4f433d]">Country</label>
                <input type="text" name="shipping_country" value="{{ old('shipping_country') }}" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required />
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4f433d]">Payment method</label>
                <select name="payment_method" class="mt-2 w-full rounded-3xl border border-[#e4dad1] bg-[#f9f4f0] px-4 py-3 text-sm outline-none" required>
                    <option value="card">Card payment</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>

            <button type="submit" class="w-full rounded-full bg-[#1b1b18] px-6 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-[#403c39]">Place order</button>
        </form>
    </section>

    <aside class="space-y-6">
        <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#F7F1ED] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order summary</p>
            <div class="mt-6 space-y-4 text-sm text-[#5e534c]">
                @php
                    $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
                @endphp
                <div class="flex items-center justify-between">
                    <span>Subtotal</span>
                    <span class="font-semibold text-[#1b1b18]">₦{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>
                <div class="flex items-center justify-between text-base font-semibold text-[#1b1b18]">
                    <span>Total</span>
                    <span>₦{{ number_format($subtotal, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Secure experience</p>
            <p class="mt-4 text-sm leading-7 text-[#5e534c]">Checkout with confidence using secure payments and fast order processing.</p>
        </div>
    </aside>
</div>
@endsection
