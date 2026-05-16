<div style="font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color:#1f2937; line-height:1.6;">
    <h1 style="font-size:24px; margin-bottom:16px;">Thank you for your order!</h1>
    <p style="margin-bottom:12px;">Hi {{ $order->user?->name ?? 'Customer' }},</p>
    <p style="margin-bottom:16px;">We have received your order <strong>#{{ $order->order_number }}</strong> and it is now being processed.</p>
    <ul style="margin-bottom:16px; padding-left:20px;">
        <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
        <li><strong>Total:</strong> ₦{{ number_format($order->total, 2) }}</li>
        <li><strong>Payment method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</li>
    </ul>
    <h2 style="font-size:18px; margin-bottom:12px;">Order details</h2>
    <ul style="margin-bottom:16px; padding-left:20px;">
        @foreach($order->items as $item)
            <li>{{ $item->product?->name ?? 'Product' }} ×{{ $item->quantity }} — ₦{{ number_format($item->price * $item->quantity, 2) }}</li>
        @endforeach
    </ul>
    <p style="margin-bottom:16px;"><strong>Shipping address:</strong><br>
        {{ $order->shipping_address['street'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }}<br>
        {{ $order->shipping_address['country'] ?? '' }}
    </p>
    <p style="margin-bottom:24px;"><a href="{{ route('orders.show', $order) }}" style="display:inline-block; padding:12px 20px; background:#111827; color:#ffffff; text-decoration:none; border-radius:8px;">View your order</a></p>
    <p style="color:#6b7280;">If you have any questions, reply to this email or visit our support page.</p>
</div>
