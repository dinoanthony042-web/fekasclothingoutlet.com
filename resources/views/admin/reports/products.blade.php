@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Product Report</h1>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('admin.reports.products') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                    <option value="sales" {{ request('sort') === 'sales' ? 'selected' : '' }}>Number of Sales</option>
                    <option value="revenue" {{ request('sort') === 'revenue' ? 'selected' : '' }}>Revenue</option>
                    <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Stock Level</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Direction</label>
                <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                    <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('admin.reports.products') }}" class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($products->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">₦{{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $product->stock > 20 ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $product->stock > 5 && $product->stock <= 20 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $product->stock <= 5 ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->total_sales ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">₦{{ number_format($product->total_revenue ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 text-lg">No products found.</p>
        </div>
    @endif
</div>
@endsection
