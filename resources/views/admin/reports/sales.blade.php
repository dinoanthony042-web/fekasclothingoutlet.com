@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Sales Report</h1>
        <a href="{{ route('admin.reports.sales.export', ['from_date' => $from_date->format('Y-m-d'), 'to_date' => $to_date->format('Y-m-d')]) }}" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Export CSV
        </a>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ $from_date->format('Y-m-d') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="to_date" value="{{ $to_date->format('Y-m-d') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Sales</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">₦{{ number_format($totalSales, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ $from_date->format('M d') }} - {{ $to_date->format('M d, Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Orders</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalOrders }}</p>
            <p class="text-sm text-gray-500 mt-2">Orders placed</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Avg Order Value</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">₦{{ number_format($averageOrderValue, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">Per order</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-600 uppercase tracking-wider">Order Status</h3>
            <div class="mt-4 space-y-2">
                @foreach($orderStatusDistribution as $status)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ ucfirst($status->status) }}</span>
                        <span class="font-medium text-gray-900">{{ $status->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Products by Sales -->
    <div class="bg-white rounded-lg shadow mb-8 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Top Products by Sales</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topProducts as $product)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->product->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->total_quantity }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">₦{{ number_format($product->total_sales, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No sales data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sales by Date Chart Data -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Sales by Date</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salesByDate as $sale)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">₦{{ number_format($sale->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No sales data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
