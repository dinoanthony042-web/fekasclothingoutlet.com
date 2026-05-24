@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition w-full sm:w-auto justify-center sm:justify-start">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Product
        </a>
    </div>

    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @if($product->images && count($product->images) > 0)
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ $product->images[0] }}" alt="{{ $product->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($product->description, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $product->category->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ₦{{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-xs font-semibold {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_featured ? 'bg-purple-100 text-purple-800' : ($product->is_new ? 'bg-green-100 text-green-800' : ($product->is_best_seller ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ $product->is_featured ? 'Featured' : ($product->is_new ? 'New' : ($product->is_best_seller ? 'Best Seller' : 'Regular')) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-gray-500">
                            No products found. <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:text-blue-800">Create one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="bg-white px-3 sm:px-4 py-3 border-t border-gray-200">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <div class="md:hidden space-y-3">
        @forelse($products as $product)
        <div class="bg-white rounded-2xl border border-gray-200 p-3 space-y-3">
            <div class="flex gap-3">
                @if($product->images && count($product->images) > 0)
                    <img class="h-16 w-16 rounded-lg object-cover flex-shrink-0" src="{{ $product->images[0] }}" alt="{{ $product->name }}">
                @else
                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-sm">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-600 line-clamp-1">{{ $product->description }}</p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_featured ? 'bg-purple-100 text-purple-800' : ($product->is_new ? 'bg-green-100 text-green-800' : ($product->is_best_seller ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $product->is_featured ? 'Featured' : ($product->is_new ? 'New' : ($product->is_best_seller ? 'Best Seller' : 'Regular')) }}
                        </span>
                        <span class="inline-flex items-center justify-center h-6 px-2 text-xs font-semibold rounded-full {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs border-t pt-2">
                <div>
                    <span class="text-gray-500">Category</span>
                    <p class="font-semibold text-gray-900">{{ $product->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Price</span>
                    <p class="font-semibold text-gray-900">₦{{ number_format($product->price, 0) }}</p>
                </div>
            </div>
            <div class="flex gap-2 pt-2 border-t">
                <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 px-2 py-1.5 text-xs bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-center font-medium">Edit</a>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="flex-1" onsubmit="return confirm('Delete?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-2 py-1.5 text-xs bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500 text-sm py-8 bg-white rounded-2xl border border-gray-200">
            <p>No products found.</p>
            <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">Create one now</a>
        </div>
        @endforelse

        @if($products->hasPages())
        <div class="bg-white px-3 py-3 border border-gray-200 rounded-2xl">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
