@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Order details</p>
                <h1 class="mt-2 text-3xl font-semibold text-[#1b1b18]">Order #{{ $order->order_number }}</h1>
                <p class="mt-2 text-sm text-[#5e534c]">Placed on {{ $order->created_at->format('F j, Y') }}, {{ count($order->items) }} item(s).</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#1b1b18] px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#1b1b18] transition hover:bg-[#f7f2ee]">Back to orders</a>
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.5fr_0.6fr]">
        <div class="space-y-6">
            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Status</p>
                        <h2 class="mt-2 text-2xl font-semibold text-[#1b1b18]">{{ ucfirst($order->status) }}</h2>
                        <p class="mt-2 text-sm text-[#5e534c]">Payment status: {{ ucfirst($order->payment_status) }}</p>
                    </div>
                    <div class="rounded-full bg-[#eef6ff] px-4 py-2 text-sm font-semibold text-[#0f4db7]">{{ strtoupper($order->payment_method) }}</div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-3 text-sm text-[#5e534c]">
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Order total</p>
                        <p>₦{{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Items</p>
                        <p>{{ count($order->items) }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Transaction</p>
                        <p>{{ $order->transaction_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-[#1b1b18]">Shipping address</h2>
                    <span class="text-sm font-semibold text-[#5e534c]">Delivery: Free</span>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 text-sm text-[#5e534c]">
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Recipient</p>
                        <p>{{ $order->shipping_address['name'] }}</p>
                        <p>{{ $order->shipping_address['phone'] }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Address</p>
                        <p>{{ $order->shipping_address['street'] }}</p>
                        <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}</p>
                        <p>{{ $order->shipping_address['postcode'] }}, {{ $order->shipping_address['country'] }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <h2 class="text-xl font-semibold text-[#1b1b18]">Items in this order</h2>
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
            @php
                $discountAmount = $order->items->sum(function ($item) {
                    $originalPrice = $item->product?->price ?? $item->price;
                    return max(0, ($originalPrice - $item->price) * $item->quantity);
                });
            @endphp
            <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#FDF5F1] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order summary</p>
                <div class="mt-6 space-y-4 text-sm text-[#5a4570]">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold text-[#5b1e7e]">₦{{ number_format($order->total + $discountAmount, 2) }}</span>
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
                    <div class="flex items-center justify-between text-base font-semibold text-[#5b1e7e]">
                        <span>Total</span>
                        <span>₦{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
                <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order information</p>
                <div class="mt-6 text-sm text-[#5a4570] space-y-3">
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Order number</p>
                        <p>{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b1b18]">Payment reference</p>
                        <p>{{ $order->payment_reference ?? 'Not available' }}</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
