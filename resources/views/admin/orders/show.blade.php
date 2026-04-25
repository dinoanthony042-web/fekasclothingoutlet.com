@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Orders</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-gray-600 text-sm mt-1">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Status Update Section -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h2>
                <div class="flex items-center space-x-4">
                    <span class="px-4 py-2 rounded-full text-sm font-medium
                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        {{ ucfirst($order->status) }}
                    </span>

                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="flex items-center space-x-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium text-gray-700">Name:</span> {{ $order->user->name }}</p>
                        <p><span class="font-medium text-gray-700">Email:</span> {{ $order->user->email }}</p>
                        <p><span class="font-medium text-gray-700">Phone:</span> {{ $order->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium text-gray-700">Payment Method:</span> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                        <p><span class="font-medium text-gray-700">Total Amount:</span> <span class="text-2xl font-bold text-gray-900">₦{{ number_format($order->total, 2) }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Addresses -->
            @if($order->shipping_address || $order->billing_address)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                    @if($order->shipping_address)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p>{{ $order->shipping_address['street'] ?? '' }}</p>
                                <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}</p>
                                <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                            </div>
                        </div>
                    @endif

                    @if($order->billing_address)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h3>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p>{{ $order->billing_address['street'] ?? '' }}</p>
                                <p>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['zip'] ?? '' }}</p>
                                <p>{{ $order->billing_address['country'] ?? '' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Order Items -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div>
                                            <p class="font-medium">{{ $item->product->name ?? 'Deleted Product' }}</p>
                                            @if($item->size)
                                                <p class="text-gray-600 text-xs">Size: {{ $item->size }}</p>
                                            @endif
                                            @if($item->color)
                                                <p class="text-gray-600 text-xs">Color: {{ $item->color }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">₦{{ number_format($item->price, 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">₦{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between">
            <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Orders</a>
            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onclick="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Delete Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
