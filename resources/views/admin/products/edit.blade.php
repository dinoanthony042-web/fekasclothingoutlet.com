@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Products</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (₦)</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="md:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }} data-parent="{{ $category->name }}">
                                    {{ $category->name }}
                                </option>
                                @foreach($category->children as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_id', $product->category_id) == $subcategory->id ? 'selected' : '' }} data-parent="{{ $category->name }}">
                                        &nbsp;&nbsp;{{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age Range (for Children only) -->
                <div id="age_range_container" class="hidden">
                    <label for="age_range" class="block text-sm font-medium text-gray-700">Age Range</label>
                    <select name="age_range" id="age_range"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select age range</option>
                        <option value="0-2 years" {{ old('age_range', $product->age_range) == '0-2 years' ? 'selected' : '' }}>0-2 years</option>
                        <option value="3-5 years" {{ old('age_range', $product->age_range) == '3-5 years' ? 'selected' : '' }}>3-5 years</option>
                        <option value="6-8 years" {{ old('age_range', $product->age_range) == '6-8 years' ? 'selected' : '' }}>6-8 years</option>
                        <option value="9-11 years" {{ old('age_range', $product->age_range) == '9-11 years' ? 'selected' : '' }}>9-11 years</option>
                        <option value="12-14 years" {{ old('age_range', $product->age_range) == '12-14 years' ? 'selected' : '' }}>12-14 years</option>
                        <option value="15-17 years" {{ old('age_range', $product->age_range) == '15-17 years' ? 'selected' : '' }}>15-17 years</option>
                    </select>
                    @error('age_range')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sizes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sizes</label>
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        @php
                            $sizeOptions = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                            $oldSizes = old('sizes', $product->sizes ?? []);
                        @endphp
                        @foreach($sizeOptions as $size)
                            <div class="flex items-center">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}" id="size-{{ strtolower($size) }}"
                                       {{ in_array($size, $oldSizes) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="size-{{ strtolower($size) }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $size }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('sizes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Colors -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Colors</label>
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        @php
                            $colorOptions = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray', 'Brown', 'Navy'];
                            $oldColors = old('colors', $product->colors ?? []);
                        @endphp
                        @foreach($colorOptions as $color)
                            <div class="flex items-center">
                                <input type="checkbox" name="colors[]" value="{{ $color }}" id="color-{{ strtolower($color) }}"
                                       {{ in_array($color, $oldColors) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="color-{{ strtolower($color) }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $color }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('colors')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Styles -->
                <div>
                    <fieldset>
                        <legend class="block text-sm font-medium text-gray-700">Styles</legend>
                        <p class="mt-1 text-sm text-gray-500">Select one or more styles for this product.</p>
                        @php
                            $styleOptions = ['Traditional', 'English', 'Casual', 'Formal'];
                            $oldStyles = old('styles', $product->styles ?? []);
                        @endphp
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            @foreach($styleOptions as $style)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 transition hover:border-indigo-500">
                                    <input type="checkbox" name="styles[]" value="{{ $style }}" {{ in_array($style, $oldStyles) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>{{ $style }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('styles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </div>

                <!-- Upload Images -->
                <div class="md:col-span-2">
                    <label for="image_uploads" class="block text-sm font-medium text-gray-700">Product Images (optional)</label>
                    <input type="file" name="image_uploads[]" id="image_uploads" multiple accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image_uploads')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('image_uploads.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Leave empty to keep existing images. Upload 2 to 5 new images only if you want to replace them.</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-700">Existing Images</p>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        @foreach($product->images ?? [] as $image)
                            <img src="{{ $image }}" alt="Product image" class="h-32 w-full rounded-lg object-cover border border-gray-200">
                        @endforeach
                    </div>
                </div>

                <!-- Product Flags -->
                <div class="md:col-span-2">
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Product Flags</legend>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 text-sm text-gray-900">Featured Product</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_new" id="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_new" class="ml-2 text-sm text-gray-900">New Product</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_best_seller" id="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_best_seller" class="ml-2 text-sm text-gray-900">Best Seller</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('admin.products.index') }}" class="mr-3 text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const ageRangeContainer = document.getElementById('age_range_container');

    function toggleAgeRange() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const parentName = selectedOption ? selectedOption.getAttribute('data-parent') : '';
        if (parentName === 'Children') {
            ageRangeContainer.classList.remove('hidden');
        } else {
            ageRangeContainer.classList.add('hidden');
        }
    }

    categorySelect.addEventListener('change', toggleAgeRange);
    toggleAgeRange(); // Initial check
});
</script>
@endsection
