@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Order details</p>
                <h1 class="mt-2 text-3xl font-semibold text-[#1b1b18]">Order #{{ $order->order_number }}</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-full border border-[#1b1b18] px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#1b1b18] transition hover:bg-[#f7f2ee]">Back to dashboard</a>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.4fr_0.6fr]">
        <div class="space-y-6">
            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Status</p>
                        <p class="mt-2 text-xl font-semibold text-[#1b1b18]">{{ ucfirst($order->status) }}</p>
                    </div>
                    <span class="rounded-full bg-[#F4EEE7] px-4 py-2 text-sm font-semibold text-[#5e534c]">₦{{ number_format($order->total, 2) }}</span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-sm text-[#5e534c]">{{ $order->created_at->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#5e534c]">{{ count($order->items) }} item(s)</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#5e534c]">Payment: {{ ucfirst($order->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <h2 class="text-xl font-semibold text-[#1b1b18]">Shipping address</h2>
                <p class="mt-4 text-sm text-[#5e534c]">{{ $order->shipping_address['name'] }}</p>
                <p class="text-sm text-[#5e534c]">{{ $order->shipping_address['phone'] }}</p>
                <p class="mt-2 text-sm text-[#5e534c]">{{ $order->shipping_address['street'] }}</p>
                <p class="text-sm text-[#5e534c]">{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}, {{ $order->shipping_address['postcode'] }}</p>
                <p class="text-sm text-[#5e534c]">{{ $order->shipping_address['country'] }}</p>
            </div>

            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <h2 class="text-xl font-semibold text-[#1b1b18]">Items</h2>
                <div class="mt-6 space-y-4">
                    @foreach($order->items as $item)
                        <div class="rounded-3xl border border-[#E7DDD4] bg-[#faf5ff] p-5">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-[#1b1b18]">{{ $item->product->name ?? 'Product unavailable' }}</p>
                                    <p class="text-sm text-[#5e534c]">Qty: {{ $item->quantity }} @if($item->size) · Size: {{ $item->size }} @endif @if($item->color) · Color: {{ $item->color }} @endif</p>
                                </div>
                                <p class="text-sm font-semibold text-[#5b1e7e]">₦{{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#FDF5F1] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order summary</p>
                <div class="mt-6 space-y-4 text-sm text-[#5a4570]">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold text-[#5b1e7e]">₦{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="flex items-center justify-between text-base font-semibold text-[#5b1e7e]">
                        <span>Total</span>
                        <span>₦{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
