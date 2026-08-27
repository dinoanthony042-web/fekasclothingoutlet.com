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
                    <textarea name="description" id="description" rows="4"
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
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" readonly aria-readonly="true"
                           class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">Total stock is auto-calculated from individual size quantities.</p>
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
                <div id="size-options" class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Sizes</label>
                    <p class="mt-1 text-sm text-gray-500">Select available sizes. Shown with UK and Turkish equivalents for customer reference.</p>
                    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $sizeOptions = config('sizes.all_options');
                            $sizeMappings = config('sizes.mappings');
                            $shoeMappings = config('sizes.shoe_mappings', []);
                            $oldSizes = old('sizes', $product->sizes ?? []);
                            $selectedCategoryId = old('category_id', $product->category_id);
                            $selectedCategoryName = null;
                            $selectedParentName = null;

                            if ($selectedCategoryId) {
                                foreach ($categories as $category) {
                                    if ((string) $category->id === (string) $selectedCategoryId) {
                                        $selectedCategoryName = $category->name;
                                        $selectedParentName = $category->name;
                                        break;
                                    }

                                    foreach ($category->children as $subcategory) {
                                        if ((string) $subcategory->id === (string) $selectedCategoryId) {
                                            $selectedCategoryName = $subcategory->name;
                                            $selectedParentName = $category->name;
                                            break 2;
                                        }
                                    }
                                }
                            }

                            if ($selectedCategoryName === 'Shoes' && in_array($selectedParentName, ['Men', 'Women'], true)) {
                                $sizeOptions = config('sizes.adult_shoe_options');
                                $sizeMappings = $shoeMappings;
                            } elseif ($selectedCategoryName === 'Shoes' && $selectedParentName === 'Kids') {
                                $sizeOptions = config('sizes.kids_shoe_options');
                                $sizeMappings = $shoeMappings;
                            } elseif ($selectedParentName === 'Women' && in_array(strtolower($selectedCategoryName ?? ''), ['dress', 'dresses', 'top', 'tops', 'blouse', 'shirt', 't-shirt'], true)) {
                                $sizeOptions = config('sizes.women_dress_options');
                                $sizeMappings = config('sizes.women_dress_mappings', []);
                            } else {
                                // Apply standard clothing size mappings to every other non-shoe category,
                                // so future categories like jumpsuits automatically inherit UK/Turkey equivalents.
                                $sizeOptions = config('sizes.all_options');
                                $sizeMappings = config('sizes.mappings');
                            }
                        @endphp
                        @foreach($sizeOptions as $size)
                            <div class="flex flex-col gap-2 border border-gray-200 rounded-lg p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start">
                                        <input type="checkbox" name="sizes[]" value="{{ $size }}" id="size-{{ strtolower($size) }}"
                                               {{ in_array($size, $oldSizes) ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <div class="ml-2">
                                            <label for="size-{{ strtolower($size) }}" class="block text-sm font-medium text-gray-900 cursor-pointer">
                                                {{ $size }}
                                            </label>
                                            @if(isset($sizeMappings[$size]))
                                                <p class="text-xs text-gray-500">
                                                    UK: {{ $sizeMappings[$size]['uk'] }} | TR: {{ $sizeMappings[$size]['turkish'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="w-24">
                                        <label for="size_stock_{{ $size }}" class="sr-only">Stock for {{ $size }}</label>
                                        <input type="number" name="size_stock[{{ $size }}]" id="size_stock_{{ $size }}" min="0"
                                               value="{{ old('size_stock.'.$size, $product->size_stock[$size] ?? 0) }}"
                                               class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Qty">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('sizes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Colors -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Colors</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $colorOptions = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray', 'Brown', 'Navy', 'Gold', 'Silver', 'Wine', 'Cream'];
                            $oldColors = old('colors', $product->colors ?? []);
                        @endphp
                        @foreach($colorOptions as $color)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center">
                                    <input type="checkbox" name="colors[]" value="{{ $color }}" id="color-{{ strtolower($color) }}"
                                           {{ in_array($color, $oldColors) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="color-{{ strtolower($color) }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $color }}
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label for="color_stock_{{ $color }}" class="sr-only">Stock for {{ $color }}</label>
                                    <input type="number" name="color_stock[{{ $color }}]" id="color_stock_{{ $color }}" min="0"
                                           value="{{ old('color_stock.'.$color, $product->color_stock[$color] ?? 0) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Qty">
                                </div>
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
                    <p class="mt-2 text-sm text-gray-500">Leave empty to keep existing images. Upload 2 to 5 new images only if you want to replace them. New images will be resized and compressed before upload to keep them loading faster.</p>

                    <!-- Preview & Progress -->
                    <div id="image-preview" class="mt-4 grid grid-cols-3 gap-3"></div>
                    <div id="upload-status" class="mt-4"></div>
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
    const sizeContainer = document.getElementById('size-options');

    const standardMappings = @json(config('sizes.mappings', []));
    const womenDressMappings = @json(config('sizes.women_dress_mappings', []));
    const womenDressSizeOptions = @json(config('sizes.women_dress_options'));

    function toggleAgeRange() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const parentName = selectedOption ? selectedOption.getAttribute('data-parent') : '';
        if (parentName === 'Children') {
            ageRangeContainer.classList.remove('hidden');
        } else {
            ageRangeContainer.classList.add('hidden');
        }
    }

    function toggleSizeVisibility() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const parentName = selectedOption ? selectedOption.getAttribute('data-parent') : '';
        const categoryName = selectedOption ? selectedOption.textContent.trim() : '';
        const name = (categoryName || '').toLowerCase();
        const parent = (parentName || '').toLowerCase();

        const isBag = name.includes('bag') || parent.includes('bag') || (parent === 'women' && name.includes('bag'));

        if (sizeContainer) {
            if (isBag) {
                sizeContainer.classList.add('hidden');
            } else {
                sizeContainer.classList.remove('hidden');
            }
        }
    }

    function updateSizeOptionsForWomenDress() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const parentName = selectedOption ? selectedOption.getAttribute('data-parent') : '';
        const categoryName = selectedOption ? selectedOption.textContent.trim() : '';
        const isShoeCategory = categoryName === 'Shoes' || parentName === 'Shoes';
        const isWomenDress = (parentName || '').toLowerCase() === 'women' && (categoryName || '').toLowerCase().match(/dress|tops?|blouse|shirt|t-?shirt/);

        if (!sizeContainer || isShoeCategory || !isWomenDress) {
            return;
        }

        const existingInputs = Array.from(document.querySelectorAll('#size-options input[name="sizes[]"]'));
        if (existingInputs.length === 0) {
            return;
        }

        const values = womenDressSizeOptions;
        const currentValues = new Set(existingInputs.map((input) => input.value));

        values.forEach((size) => {
            const input = document.querySelector(`#size-${size.toLowerCase()}`);
            if (!input) {
                const div = document.createElement('div');
                div.className = 'flex flex-col gap-2 border border-gray-200 rounded-lg p-3';
                div.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start">
                            <input type="checkbox" name="sizes[]" value="${size}" id="size-${size.toLowerCase()}" class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <div class="ml-2">
                                <label for="size-${size.toLowerCase()}" class="block text-sm font-medium text-gray-900 cursor-pointer">${size}</label>
                                <p class="text-xs text-gray-500">${womenDressMappings[size]?.label ?? ''}</p>
                            </div>
                        </div>
                        <div class="w-24">
                            <label for="size_stock_${size}" class="sr-only">Stock for ${size}</label>
                            <input type="number" name="size_stock[${size}]" id="size_stock_${size}" min="0" value="0"
                                   class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Qty">
                        </div>
                    </div>
                `;
                const container = document.getElementById('size-options').querySelector('div');
                if (container) {
                    container.appendChild(div);
                }
            }
        });

        existingInputs.forEach((input) => {
            if (!values.includes(input.value) && !currentValues.has(input.value)) {
                const row = input.closest('div.flex.flex-col.gap-2');
                if (row) {
                    row.remove();
                }
            }
        });
    }

    categorySelect.addEventListener('change', toggleAgeRange);
    categorySelect.addEventListener('change', toggleSizeVisibility);
    categorySelect.addEventListener('change', updateSizeOptionsForWomenDress);

    function updateTotalStock() {
        const stockInput = document.getElementById('stock');
        if (!stockInput) {
            return;
        }
        let total = 0;

        if (sizeContainer && sizeContainer.classList.contains('hidden')) {
            document.querySelectorAll('input[name^="color_stock["]').forEach((input) => {
                const match = input.name.match(/^color_stock\[(.+)\]$/);
                const color = match ? match[1] : null;
                const checkbox = color ? document.querySelector(`input[name="colors[]"][value="${color}"]`) : null;
                const quantity = parseInt(input.value, 10) || 0;
                if (quantity > 0 || (checkbox && checkbox.checked)) {
                    total += quantity;
                }
            });
        } else {
            document.querySelectorAll('input[name^="size_stock["]').forEach((input) => {
                const match = input.name.match(/^size_stock\[(.+)\]$/);
                const size = match ? match[1] : null;
                const checkbox = size ? document.querySelector(`input[name="sizes[]"][value="${size}"]`) : null;
                const quantity = parseInt(input.value, 10) || 0;
                if (checkbox && checkbox.checked) {
                    total += quantity;
                }
            });
        }

        stockInput.value = total;
    }

    function attachSizeStockListeners() {
        document.querySelectorAll('input[name^="size_stock["]').forEach((input) => {
            input.addEventListener('input', updateTotalStock);
        });
        document.querySelectorAll('input[name="sizes[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', updateTotalStock);
        });
        // Also watch color stock inputs and color checkboxes
        document.querySelectorAll('input[name^="color_stock["]').forEach((input) => {
            input.addEventListener('input', updateTotalStock);
        });
        document.querySelectorAll('input[name="colors[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', updateTotalStock);
        });
    }

    attachSizeStockListeners();
    updateTotalStock();
    toggleAgeRange(); // Initial check
    toggleSizeVisibility();
});

// Image preview + AJAX upload with progress (edit form)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action^="{{ route('admin.products.update', $product) }}"]');
    const fileInput = document.getElementById('image_uploads');
    const preview = document.getElementById('image-preview');
    const statusContainer = document.getElementById('upload-status');

    function clearPreview() {
        preview.innerHTML = '';
        statusContainer.innerHTML = '';
    }

    function resizeImage(file) {
        return new Promise((resolve) => {
            if (!file || !file.type || !file.type.startsWith('image/')) {
                resolve(file);
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const maxDimension = 1600;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxDimension) {
                            height = Math.round((height * maxDimension) / width);
                            width = maxDimension;
                        }
                    } else if (height > maxDimension) {
                        width = Math.round((width * maxDimension) / height);
                        height = maxDimension;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    const mimeType = file.type === 'image/png' ? 'image/png' : file.type === 'image/webp' ? 'image/webp' : 'image/jpeg';
                    const extension = mimeType === 'image/png' ? 'png' : mimeType === 'image/webp' ? 'webp' : 'jpg';

                    canvas.toBlob((blob) => {
                        if (blob) {
                            resolve(new File([blob], file.name.replace(/\.[^.]+$/, '') + '.' + extension, {
                                type: mimeType,
                                lastModified: Date.now()
                            }));
                        } else {
                            resolve(file);
                        }
                    }, mimeType, 0.82);
                };
                img.onerror = function() {
                    resolve(file);
                };
                img.src = e.target.result;
            };
            reader.onerror = function() {
                resolve(file);
            };
            reader.readAsDataURL(file);
        });
    }

    async function buildResizedFormData(form, files) {
        const formData = new FormData(form);
        formData.delete('image_uploads[]');

        const resizedFiles = await Promise.all(files.map((file) => resizeImage(file)));
        resizedFiles.forEach((file) => {
            formData.append('image_uploads[]', file, file.name);
        });

        return formData;
    }

    fileInput.addEventListener('change', function() {
        clearPreview();
        Array.from(fileInput.files).forEach((file, idx) => {
            const reader = new FileReader();
            const wrapper = document.createElement('div');
            wrapper.className = 'relative';

            const img = document.createElement('img');
            img.className = 'h-32 w-full rounded-lg object-cover border border-gray-200';
            img.alt = file.name;

            const progress = document.createElement('div');
            progress.className = 'w-full mt-2 bg-gray-100 rounded-full overflow-hidden';
            progress.innerHTML = '<div class="bg-indigo-600 h-2" style="width:0%"></div>';

            reader.onload = (e) => {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);

            wrapper.appendChild(img);
            wrapper.appendChild(progress);
            preview.appendChild(wrapper);
        });
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const files = Array.from(fileInput.files);
        if (files.length > 0 && files.length < 2) {
            alert('If replacing images, upload at least 2 images.');
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Uploading...';

        const xhr = new XMLHttpRequest();
        const fd = files.length > 0 ? await buildResizedFormData(form, files) : new FormData(form);

        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.upload.addEventListener('progress', function(event) {
            if (!event.lengthComputable) return;
            const pct = Math.round((event.loaded / event.total) * 100);
            statusContainer.textContent = `Overall upload: ${pct}%`;
            const bars = preview.querySelectorAll('.bg-indigo-600');
            bars.forEach(bar => bar.style.width = pct + '%');
        });

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Product';
                let body = xhr.responseText || '';
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(body || '{}');
                        if (data.success) {
                            window.location.href = data.redirect || '{{ route('admin.products.index') }}';
                            return;
                        }
                        alert(data.message || 'Upload completed, but server returned an unexpected response.');
                    } catch (err) {
                        console.error(err);
                        alert('Upload completed. Reloading...');
                        window.location.reload();
                    }
                } else {
                    try {
                        const data = JSON.parse(body || '{}');
                        if (data.errors) {
                            const messages = [];
                            for (const key in data.errors) {
                                messages.push(...data.errors[key]);
                            }
                            alert('Upload failed:\n' + messages.join('\n'));
                        } else if (data.message) {
                            alert('Upload failed: ' + data.message);
                        } else {
                            alert('Upload failed. Server returned status ' + xhr.status + '. See console for details.');
                            console.error('Upload failed', xhr.status, body);
                        }
                    } catch (err) {
                        console.error('Upload failed, non-JSON response', xhr.status, body);
                        alert('Upload failed. See console for details.');
                    }
                }
            }
        };

        xhr.send(fd);
    });
});
</script>
@endsection
