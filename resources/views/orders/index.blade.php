@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Order history</p>
                <h1 class="mt-2 text-3xl font-semibold text-[#1b1b18]">My orders</h1>
                <p class="mt-3 max-w-2xl text-sm text-[#5e534c]">Review your recent purchases, payment status, and delivery details in one place.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="rounded-full border border-[#1b1b18] px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#1b1b18] transition hover:bg-[#f7f2ee]">Continue shopping</a>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="rounded-[2rem] border border-[#f0e6ff] bg-[#faf5ff] p-10 text-center text-sm text-[#5b1e7e]">
            <p class="font-semibold">No orders yet.</p>
            <p class="mt-2">Your order history will appear here once a purchase is completed.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block rounded-[2rem] border border-[#E7DDD4] bg-white p-6 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)] hover:border-[#5b1e7e] transition">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order #{{ $order->order_number }}</p>
                            <h2 class="mt-2 text-2xl font-semibold text-[#1b1b18]">{{ ucfirst($order->status) }}</h2>
                        </div>
                        <div class="flex flex-col items-end gap-2 text-right">
                            <span class="rounded-full bg-[#F4EEE7] px-4 py-2 text-sm font-semibold text-[#5e534c]">₦{{ number_format($order->total, 2) }}</span>
                            <span class="text-sm text-[#5e534c]">{{ count($order->items) }} item(s)</span>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-3 text-sm text-[#5e534c]">
                        <div>
                            <p class="font-semibold text-[#1b1b18]">Placed on</p>
                            <p>{{ $order->created_at->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b1b18]">Delivery</p>
                            <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b1b18]">Payment</p>
                            <p>{{ ucfirst($order->payment_method) }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
