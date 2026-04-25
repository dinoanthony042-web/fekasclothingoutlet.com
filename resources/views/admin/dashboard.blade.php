@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900">Store Overview</h1>
        <p class="mt-2 text-gray-600">Manage your store, track orders, and view analytics.</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 text-sm font-medium mt-4 inline-block hover:text-blue-700">Manage Products →</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Categories</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalCategories }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-purple-600 text-sm font-medium mt-4 inline-block hover:text-purple-700">Manage Categories →</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-green-600 text-sm font-medium mt-4 inline-block hover:text-green-700">View Orders →</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">₦{{ number_format($totalRevenue, 0) }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.reports.sales') }}" class="text-yellow-600 text-sm font-medium mt-4 inline-block hover:text-yellow-700">View Reports →</a>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="grid gap-6 md:grid-cols-3">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Total Customers</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 mt-2">Active customers</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">This Month Sales</p>
            <p class="text-2xl font-bold text-gray-900">₦{{ number_format($thisMonthSales, 0) }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ now()->format('F') }} {{ now()->year }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Pending Orders</p>
            <p class="text-2xl font-bold text-orange-600">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-500 mt-2">Awaiting processing</p>
        </div>
    </div>

    <!-- Top Products and Recent Orders -->
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Products</h2>
            <div class="space-y-4">
                @forelse($topProducts as $product)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $product->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">₦{{ number_format($product->total_revenue, 2) }}</p>
                            <p class="text-xs text-gray-500">Revenue</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No sales data available.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.reports.products') }}" class="text-blue-600 text-sm font-medium mt-4 inline-block hover:text-blue-700">View All →</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h2>
            <div class="space-y-4">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                            <p class="text-sm text-gray-600">{{ $order->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">₦{{ number_format($order->total, 2) }}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No orders found.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 text-sm font-medium mt-4 inline-block hover:text-blue-700">View All →</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.products.create') }}" class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition">
                <p class="font-medium text-blue-600">Add Product</p>
                <p class="text-xs text-blue-500 mt-1">Create new item</p>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition">
                <p class="font-medium text-purple-600">Add Category</p>
                <p class="text-xs text-purple-500 mt-1">New category</p>
            </a>
            <a href="{{ route('admin.discounts.create') }}" class="p-4 bg-red-50 hover:bg-red-100 rounded-lg text-center transition">
                <p class="font-medium text-red-600">Create Discount</p>
                <p class="text-xs text-red-500 mt-1">Flash sale</p>
            </a>
            <a href="{{ route('admin.reports.sales') }}" class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition">
                <p class="font-medium text-green-600">View Reports</p>
                <p class="text-xs text-green-500 mt-1">Sales analytics</p>
            </a>
        </div>
    </div>
</div>
@endsection
