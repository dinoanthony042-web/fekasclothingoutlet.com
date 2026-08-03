@extends('layouts.admin')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900">Orders & Sales</h1>
            <p class="mt-1 text-sm text-gray-500">Manage orders, filter status, and review recent sales.</p>
        </div>
        <a href="{{ route('admin.reports.sales') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm w-full sm:w-auto text-center">
            View Sales Report
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID or Name"
                    class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
            </div>

            <div class="sm:col-span-1 lg:col-span-2 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-blue-700 transition">
                    Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-gray-200 text-gray-700 px-4 py-2 text-sm font-medium hover:bg-gray-300 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($orders->count() > 0)
        <div class="hidden lg:block bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Items</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 hidden md:table-cell">{{ $order->user?->email ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">₦{{ number_format($order->total, 0) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 hidden sm:table-cell">{{ $order->items->count() }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}{{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}{{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}{{ $order->status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}{{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 hidden md:table-cell">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:hidden space-y-3">
            @foreach($orders as $order)
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div>
                        <div class="font-semibold text-gray-900 text-sm">Order #{{ $order->id }}</div>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}{{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}{{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}{{ $order->status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}{{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Customer</p>
                        <p class="font-semibold text-gray-900">{{ $order->user?->name ?? 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Total</p>
                        <p class="font-semibold text-gray-900">₦{{ number_format($order->total, 0) }}</p>
                    </div>
                </div>

                <div class="mt-3 text-xs text-gray-600 space-y-1">
                    <p>Email: {{ $order->user?->email ?? 'N/A' }}</p>
                    <p>Items: {{ $order->items->count() }}</p>
                    @if($order->items->isNotEmpty())
                        <div class="mt-2 rounded-lg bg-gray-50 p-2">
                            @foreach($order->items as $item)
                                <p class="font-medium text-gray-700">
                                    {{ $item->product->name ?? 'Deleted Product' }}
                                    @if($item->size || $item->color)
                                        <span class="text-gray-500">
                                            @if($item->size) · Size: {{ $item->size }} @endif
                                            @if($item->color) · Color: {{ $item->color }} @endif
                                        </span>
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <a href="{{ route('admin.orders.show', $order) }}" class="block w-full text-center bg-blue-600 text-white rounded-xl px-4 py-2 mt-4 text-sm font-medium hover:bg-blue-700 transition">
                    View Details
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8 text-center">
            <p class="text-gray-500 text-base sm:text-lg">No orders found.</p>
        </div>
    @endif
</div>
@endsection
