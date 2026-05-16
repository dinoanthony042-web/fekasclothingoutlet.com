<div style="font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color:#1f2937; line-height:1.6;">
    <h1 style="font-size:24px; margin-bottom:16px;">Order status updated</h1>
    <p style="margin-bottom:12px;">Hi {{ $order->user?->name ?? 'Customer' }},</p>
    <p style="margin-bottom:16px;">The status of your order <strong>#{{ $order->order_number }}</strong> has changed.</p>
    <p style="margin-bottom:16px; font-weight:600;">{{ $statusMessage }}</p>
    <ul style="margin-bottom:16px; padding-left:20px;">
        <li><strong>New status:</strong> {{ ucfirst($order->status) }}</li>
        <li><strong>Total:</strong> ₦{{ number_format($order->total, 2) }}</li>
    </ul>
    <p style="margin-bottom:24px;"><a href="{{ route('orders.show', $order) }}" style="display:inline-block; padding:12px 20px; background:#111827; color:#ffffff; text-decoration:none; border-radius:8px;">View your order</a></p>
    <p style="color:#6b7280;">Thank you for shopping with {{ config('app.name') }}.</p>
</div>
