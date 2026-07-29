@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Create Product</h1>
        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Products</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <strong class="block font-semibold">Please fix the following errors before continuing:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description"  class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" required rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (₦)</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', 0) }}" readonly aria-readonly="true"
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} data-parent="{{ $category->name }}">
                                    {{ $category->name }}
                                </option>
                                @foreach($category->children as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_id') == $subcategory->id ? 'selected' : '' }} data-parent="{{ $category->name }}">
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
                        <option value="0-2 years" {{ old('age_range') == '0-2 years' ? 'selected' : '' }}>0-2 years</option>
                        <option value="3-5 years" {{ old('age_range') == '3-5 years' ? 'selected' : '' }}>3-5 years</option>
                        <option value="6-8 years" {{ old('age_range') == '6-8 years' ? 'selected' : '' }}>6-8 years</option>
                        <option value="9-11 years" {{ old('age_range') == '9-11 years' ? 'selected' : '' }}>9-11 years</option>
                        <option value="12-14 years" {{ old('age_range') == '12-14 years' ? 'selected' : '' }}>12-14 years</option>
                        <option value="15-17 years" {{ old('age_range') == '15-17 years' ? 'selected' : '' }}>15-17 years</option>
                    </select>
                    @error('age_range')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sizes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Sizes</label>
                    <p class="mt-1 text-sm text-gray-500">Select available sizes. Shown with UK and Turkish equivalents for customer reference.</p>
                    <div id="size-options" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $sizeOptions = config('sizes.all_options');
                            $sizeMappings = config('sizes.mappings');
                            $shoeMappings = config('sizes.shoe_mappings', []);
                            $oldSizes = old('sizes', []);
                            $selectedCategoryId = old('category_id');
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
                                               value="{{ old('size_stock.'.$size, 0) }}"
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
                            $colorOptions = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray', 'Brown', 'Navy'];
                            $oldColors = old('colors', []);
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
                                           value="{{ old('color_stock.'.$color, 0) }}"
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
                            $oldStyles = old('styles', []);
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
                    <label for="image_uploads" class="block text-sm font-medium text-gray-700">Upload Product Images (2–5 images)</label>
                    <input type="file" name="image_uploads[]" id="image_uploads" multiple accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image_uploads')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('image_uploads.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Upload 2 to 5 product images. The first image will be displayed as the main product image.</p>

                    <!-- Preview & Progress -->
                    <div id="image-preview" class="mt-4 grid grid-cols-3 gap-3"></div>
                    <div id="upload-status" class="mt-4"></div>
                </div>


                <!-- Product Flags -->
                <div class="md:col-span-2">
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Product Flags</legend>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 text-sm text-gray-900">Featured Product</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_new" id="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_new" class="ml-2 text-sm text-gray-900">New Product</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_best_seller" id="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }}
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
                    Create Product
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
    const stockInput = document.getElementById('stock');
    const form = document.querySelector('form[action="{{ route('admin.products.store') }}"]');
    const fileInput = document.getElementById('image_uploads');
    const preview = document.getElementById('image-preview');
    const statusContainer = document.getElementById('upload-status');

    const oldSelectedSizes = @json(old('sizes', []));
    const oldSizeStockValues = @json(old('size_stock', []));
    const shoeMappings = @json($shoeMappings);
    const adultShoeSizeOptions = @json(config('sizes.adult_shoe_options'));
    const kidsShoeSizeOptions = @json(config('sizes.kids_shoe_options'));
    const standardSizeOptions = @json(config('sizes.all_options'));

    function getSelectedCategoryInfo() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const parentName = selectedOption ? selectedOption.getAttribute('data-parent') : '';
        const categoryName = selectedOption ? selectedOption.textContent.trim() : '';
        return { parentName, categoryName };
    }

    function toggleAgeRange() {
        const { parentName } = getSelectedCategoryInfo();
        if (parentName === 'Kids') {
            ageRangeContainer.classList.remove('hidden');
        } else {
            ageRangeContainer.classList.add('hidden');
        }
    }

    function renderSizeOption(size) {
        const id = `size-${size.toLowerCase()}`;
        const checked = oldSelectedSizes.includes(size) ? 'checked' : '';
        const quantity = oldSizeStockValues[size] !== undefined ? oldSizeStockValues[size] : '0';
        const mapping = shoeMappings[size] || null;
        const mappingText = mapping ? `UK: ${mapping.uk} • TR: ${mapping.turkish}` : '';

        return `
            <div class="flex flex-col gap-2 border border-gray-200 rounded-lg p-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start">
                        <input type="checkbox" name="sizes[]" value="${size}" id="${id}" ${checked}
                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <div class="ml-2">
                            <label for="${id}" class="block text-sm font-medium text-gray-900 cursor-pointer">${size}</label>
                            <p class="text-xs text-gray-500">${mappingText}</p>
                        </div>
                    </div>
                    <div class="w-24">
                        <input type="number" name="size_stock[${size}]" min="0" value="${quantity}"
                               class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Qty">
                    </div>
                </div>
            </div>
        `;
    }

    function updateSizeOptions() {
        const { parentName, categoryName } = getSelectedCategoryInfo();
        const isShoeCategory = categoryName === 'Shoes' || parentName === 'Shoes';
        const isAdultShoes = isShoeCategory && ['Men', 'Women'].includes(parentName);
        const isKidsShoes = isShoeCategory && parentName === 'Kids';

        if (!sizeContainer) {
            return;
        }

        const sizeValues = isAdultShoes
            ? adultShoeSizeOptions
            : isKidsShoes
                ? kidsShoeSizeOptions
                : standardSizeOptions;

        sizeContainer.innerHTML = sizeValues.map(renderSizeOption).join('');
        attachSizeStockListeners();
    }

    function updateTotalStock() {
        if (!stockInput) {
            return;
        }

        let total = 0;
        document.querySelectorAll('input[name^="size_stock["]').forEach((input) => {
            const match = input.name.match(/^size_stock\[(.+)\]$/);
            const size = match ? match[1] : null;
            const checkbox = size ? document.querySelector(`input[name="sizes[]"][value="${size}"]`) : null;
            const quantity = parseInt(input.value, 10) || 0;
            if (checkbox && checkbox.checked) {
                total += quantity;
            }
        });

        stockInput.value = total;
    }

    function attachSizeStockListeners() {
        document.querySelectorAll('input[name^="size_stock["]').forEach((input) => {
            input.addEventListener('input', updateTotalStock);
        });
        document.querySelectorAll('input[name="sizes[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', updateTotalStock);
        });
    }

    function clearPreview() {
        if (preview) {
            preview.innerHTML = '';
        }
        if (statusContainer) {
            statusContainer.innerHTML = '';
        }
    }

    function createPreviewCards(files) {
        if (!preview) {
            return;
        }
        Array.from(files).forEach((file) => {
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
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            toggleAgeRange();
            updateSizeOptions();
            updateTotalStock();
        });
    }

    if (categorySelect && categorySelect.value) {
        toggleAgeRange();
        updateSizeOptions();
        updateTotalStock();
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            clearPreview();
            if (fileInput.files.length > 0) {
                createPreviewCards(fileInput.files);
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            const files = fileInput ? fileInput.files : [];
            if (files.length < 2) {
                e.preventDefault();
                alert('Please select at least 2 images.');
                return;
            }

            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Uploading...';
            }

            const xhr = new XMLHttpRequest();
            const fd = new FormData(form);

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.addEventListener('progress', function(event) {
                if (!event.lengthComputable) return;
                const pct = Math.round((event.loaded / event.total) * 100);
                if (statusContainer) {
                    statusContainer.textContent = `Overall upload: ${pct}%`;
                }
                if (preview) {
                    const bars = preview.querySelectorAll('.bg-indigo-600');
                    bars.forEach(bar => bar.style.width = pct + '%');
                }
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Create Product';
                    }

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
    }
});
</script>
@endsection