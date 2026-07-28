@extends('layouts.app')

@section('title', 'Korapay Payment')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-[#E7DDD4] bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-[#1b1b18]">Complete your payment with Korapay</h1>
            <p class="mt-2 text-sm text-[#5e534c]">Your order has been created successfully. You will be redirected to Korapay shortly to finish payment.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-[1fr_0.9fr]">
            <div class="rounded-3xl border border-[#E7DDD4] bg-[#F7F1ED] p-5">
                <h2 class="text-base font-semibold text-[#1b1b18]">Order summary</h2>
                <div class="mt-4 text-sm text-[#5e534c] space-y-3">
                    <div class="flex justify-between">
                        <span>Order number</span>
                        <span class="font-semibold text-[#1b1b18]">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment method</span>
                        <span class="font-semibold text-[#1b1b18]">Korapay</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total</span>
                        <span class="font-semibold text-[#1b1b18]">₦{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Customer</span>
                        <span class="font-semibold text-[#1b1b18]">{{ $order->shipping_address['name'] }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-[#E7DDD4] bg-[#F7F1ED] p-5">
                <p class="text-sm text-[#5e534c]">If the checkout does not open automatically, click the button below to start the payment gateway.</p>
                <button id="korapay-launch" class="mt-4 inline-flex items-center justify-center rounded-full bg-[#1b1b18] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#403c39]">Start Korapay payment</button>
            </div>
        </div>
    </div>
</div>

<script src="https://korablobstorage.blob.core.windows.net/modal-bucket/korapay-collections.min.js"></script>
<script>
    const publicKey = @json($korapayPublicKey);
    const paymentReference = @json($order->payment_reference);
    const amount = @json((int) round($order->total * 100));
    const successUrl = @json($successUrl);
    const failureUrl = @json($failureUrl);
    const notificationUrl = @json($notificationUrl);
    const customerName = @json($order->shipping_address['name']);
    const customerEmail = @json($order->user->email);

    function openKorapayCheckout() {
        if (typeof window.Korapay === 'undefined') {
            console.error('Korapay checkout script did not load.');
            return;
        }

        window.Korapay.initialize({
            key: publicKey,
            reference: paymentReference,
            amount: amount,
            currency: 'NGN',
            customer: {
                name: customerName,
                email: customerEmail,
            },
            notification_url: notificationUrl,
            metadata: {
                order_id: @json($order->id),
                order_number: @json($order->order_number),
            },
            onSuccess: function () {
                window.location.href = successUrl;
            },
            onFailed: function () {
                window.location.href = failureUrl;
            },
        });
    }

    document.getElementById('korapay-launch').addEventListener('click', openKorapayCheckout);
    document.addEventListener('DOMContentLoaded', openKorapayCheckout);
</script>
@endsection
