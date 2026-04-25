@extends('layouts.admin')

@section('title', 'Edit Discount')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Flash Sale Discount</h1>
        <a href="{{ route('admin.discounts.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Discounts</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.discounts.update', $discount) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Discount Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $discount->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $discount->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                    <select name="type" id="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            onchange="updateValueLabel()">
                        <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>Percentage (%) Off</option>
                        <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (₦) Off</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Value -->
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                    <div class="relative mt-1">
                        <input type="number" name="value" id="value" step="0.01" min="0" value="{{ old('value', $discount->value) }}" required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="absolute right-3 top-2 text-gray-500" id="valueUnit">%</span>
                    </div>
                    @error('value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apply To -->
                <div class="md:col-span-2 border-t pt-4">
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700 mb-3">Apply Discount To</legend>
                        <div class="space-y-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-600 mb-2">
                                    <input type="radio" name="apply_to" id="category_id" value="category"
                                           onchange="toggleApplyTo()" {{ !$discount->product_id ? 'checked' : '' }}>
                                    <span class="ml-2">Category / Subcategory</span>
                                </label>
                                <select name="category_id" id="category_select"
                                        class="ml-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        {{ !$discount->product_id ? '' : 'disabled' }}>
                                    <option value="">-- Select a category --</option>
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            <option value="{{ $category->id }}" {{ old('category_id', $discount->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @foreach($category->children as $subcategory)
                                                <option value="{{ $subcategory->id }}" {{ old('category_id', $discount->category_id) == $subcategory->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;{{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-600 mb-2">
                                    <input type="radio" name="apply_to" id="product_id" value="product"
                                           onchange="toggleApplyTo()" {{ $discount->product_id ? 'checked' : '' }}>
                                    <span class="ml-2">Specific Product</span>
                                </label>
                                <select name="product_id" id="product_select"
                                        class="ml-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        {{ $discount->product_id ? '' : 'disabled' }}>
                                    <option value="">-- Select a product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $discount->product_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} - ₦{{ number_format($product->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="starts_at" id="starts_at" value="{{ old('starts_at', $discount->starts_at->format('Y-m-d')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('starts_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="ends_at" id="ends_at" value="{{ old('ends_at', $discount->ends_at->format('Y-m-d')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label for="is_active" class="flex items-center text-sm font-medium text-gray-700 mt-6">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="ml-2">Discount is Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('admin.discounts.index') }}" class="mr-3 text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Update Discount
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateValueLabel() {
    const type = document.getElementById('type').value;
    const unit = document.getElementById('valueUnit');
    unit.textContent = type === 'percentage' ? '%' : '₦';
}

function toggleApplyTo() {
    const categoryRadio = document.getElementById('category_id');
    const productRadio = document.getElementById('product_id');
    const categorySelect = document.getElementById('category_select');
    const productSelect = document.getElementById('product_select');

    if (categoryRadio.checked) {
        categorySelect.disabled = false;
        productSelect.disabled = true;
        categorySelect.name = 'category_id';
        productSelect.name = '';
    } else if (productRadio.checked) {
        categorySelect.disabled = true;
        productSelect.disabled = false;
        categorySelect.name = '';
        productSelect.name = 'product_id';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateValueLabel();
    toggleApplyTo();
});
</script>
@endsection
