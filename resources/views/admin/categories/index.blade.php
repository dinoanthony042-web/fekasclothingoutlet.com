@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition w-full sm:w-auto justify-center sm:justify-start">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Category
        </a>
    </div>

    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subcategories</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="bg-white hover:bg-gray-50 transition">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ $category->children->count() }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $category->products->count() }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">
                            {{ Str::limit($category->description, 50) }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @if($category->children->count() > 0)
                        @foreach($category->children as $subcategory)
                        <tr class="bg-gray-50 hover:bg-gray-100 transition">
                            <td class="px-4 sm:px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 mr-2 rounded-full bg-slate-200 text-slate-600 text-xs font-semibold">›</span>
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">{{ $subcategory->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $subcategory->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-500">—</td>
                            <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $subcategory->products->count() }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 text-sm text-gray-700">
                                {{ Str::limit($subcategory->description, 50) }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.categories.edit', $subcategory) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $subcategory) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this subcategory?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 sm:px-6 py-4 text-center text-gray-500">
                            No categories found. <a href="{{ route('admin.categories.create') }}" class="text-blue-600 hover:text-blue-800">Create one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl border border-gray-200 p-4 space-y-3">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 text-sm">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $category->slug }}</p>
                </div>
                <div class="flex gap-2 ml-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-2 py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 transition">Delete</button>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs border-t pt-3">
                <div>
                    <span class="text-gray-500">Subcategories</span>
                    <p class="font-semibold text-gray-900">{{ $category->children->count() }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Products</span>
                    <p class="font-semibold text-gray-900">{{ $category->products->count() }}</p>
                </div>
            </div>
            <div class="text-xs text-gray-600">
                <p class="font-medium text-gray-700 mb-1">Description:</p>
                <p>{{ Str::limit($category->description, 80) }}</p>
            </div>
        </div>
        @if($category->children->count() > 0)
            <div class="ml-2 space-y-2">
                @foreach($category->children as $subcategory)
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-3 space-y-2">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 text-xs flex items-center gap-2">
                                <span class="text-xs">›</span>{{ $subcategory->name }}
                            </h4>
                            <p class="text-xs text-gray-500">{{ $subcategory->slug }}</p>
                        </div>
                        <div class="flex gap-1 ml-2">
                            <a href="{{ route('admin.categories.edit', $subcategory) }}" class="px-1.5 py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $subcategory) }}" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-1.5 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 transition">Del</button>
                            </form>
                        </div>
                    </div>
                    <div class="text-xs text-gray-600">
                        <span class="text-gray-500">Products:</span>
                        <span class="font-semibold">{{ $subcategory->products->count() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        @empty
        <div class="text-center text-gray-500 text-sm py-8">
            <p>No categories found.</p>
            <a href="{{ route('admin.categories.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">Create one now</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
