@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl rounded-3xl border border-[#e8dfd7] bg-white p-6 shadow-sm sm:p-8 lg:p-10">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#8b2e9e]">Customer policies</p>
        <h1 class="mt-3 text-3xl font-semibold text-[#1b1b18] sm:text-4xl">Terms, policies & customer care</h1>
        <p class="mt-4 text-base leading-7 text-[#5a4570]">
            Please read through our policies before placing an order. These terms explain how we process payments, deliver orders, handle returns, and resolve any issues.
        </p>
    </div>

    <div class="space-y-8">
        <section id="terms" class="rounded-2xl border border-[#f0e7de] bg-[#fcf8f4] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18]">Terms & conditions</h2>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                By accessing and purchasing from Fekas Clothing Outlet, you agree to be bound by these terms. We reserve the right to update or modify these terms at any time without prior notice.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                All orders placed with us are subject to product availability and confirmation of payment. By shopping with us, you acknowledge that you have read, understood, and agreed to our policies.
            </p>
        </section>

        <section id="payment" class="rounded-2xl border border-[#f0e7de] bg-[#fcf8f4] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18]">Payment policy</h2>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                Full payment is required before any order is processed or shipped. We only process orders after payment confirmation.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                All prices are listed in Nigerian Naira (₦) and may be updated without prior notice.
            </p>
        </section>

        <section id="shipping" class="rounded-2xl border border-[#f0e7de] bg-[#fcf8f4] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18]">Shipping & delivery policy</h2>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                Orders are processed within 24–72 hours after payment confirmation.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                Delivery timelines vary depending on your location. While we work with reliable courier services, Fekas Clothing Outlet is not responsible for delays caused by third-party delivery companies.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                Customers will receive updates once their order has been dispatched.
            </p>
        </section>

        <section id="returns" class="rounded-2xl border border-[#f0e7de] bg-[#fcf8f4] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18]">Returns & exchange policy</h2>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                All sales are final. We do not offer refunds.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                Exchanges are only accepted within 48 hours of delivery. Items must be unworn, unused, and in original condition with tags intact.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                Customers are responsible for the cost of returning items for exchange. Custom or specially ordered items are not eligible for exchange.
            </p>
        </section>

        <section class="rounded-2xl border border-[#f0e7de] bg-[#fcf8f4] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18]">Order issues & claims</h2>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                If you receive a wrong or defective item, you must notify us within 24 hours of delivery.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                All claims must be accompanied by clear proof (photos or videos). Claims made after this period may not be considered.
            </p>
        </section>

        <section class="rounded-2xl border border-[#f0e7de] bg-[#fcf8f4] p-6">
            <h2 class="text-xl font-semibold text-[#1b1b18]">Product disclaimer</h2>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                We make every effort to display our products as accurately as possible. However, slight differences in color, texture, or fit may occur due to lighting, photography, or screen display.
            </p>
            <p class="mt-3 text-sm leading-7 text-[#5a4570]">
                These variations do not qualify as defects.
            </p>
        </section>
    </div>
</div>
@endsection
