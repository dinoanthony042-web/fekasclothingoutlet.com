@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-10">
    <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.35em] text-[#8c7d74]">Welcome</p>
                <h1 class="mt-2 text-3xl font-semibold text-[#1b1b18]">Hello, {{ auth()->user()->name }}</h1>
            </div>
            <a href="{{ route('shop.index') }}" class="rounded-full border border-[#1b1b18] px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-[#1b1b18] transition hover:bg-[#f7f2ee]">Continue shopping</a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#FDF5F1] p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Orders</p>
            <p class="mt-4 text-4xl font-semibold text-[#1b1b18]">{{ $orders->count() }}</p>
            <p class="mt-3 text-sm leading-7 text-[#5e534c]">Review your order history and track the latest shipping updates.</p>
        </div>
        <div class="rounded-[2rem] border border-[#E7DDD4] bg-white p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)]">
            <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Wishlist</p>
            <p class="mt-4 text-4xl font-semibold text-[#1b1b18]">{{ $wishlist->count() }}</p>
            <p class="mt-3 text-sm leading-7 text-[#5e534c]">Keep an eye on saved styles and buy the looks you love later.</p>
        </div>
       
    </div>

    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-[#1b1b18]">Recent orders</h2>
        @forelse($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="block rounded-[2rem] border border-[#E7DDD4] bg-white p-6 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.14)] transition hover:border-[#5b1e7e]">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-[#7d6d66]">Order #{{ $order->id }}</p>
                        <p class="mt-2 text-xl font-semibold text-[#1b1b18]">{{ ucfirst($order->status) }}</p>
                    </div>
                    <span class="rounded-full bg-[#F4EEE7] px-4 py-2 text-sm font-semibold text-[#5e534c]">₦{{ number_format($order->total, 2) }}</span>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-sm text-[#5e534c]">{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#5e534c]">{{ $order->created_at->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#5e534c]">{{ count($order->items) }} items</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-[2rem] border border-[#E7DDD4] bg-[#F9F4F0] p-10 text-sm text-[#5e534c]">No orders yet. Your first purchase is just a few clicks away.</div>
        @endforelse
    </div>
</div>
@endsection
