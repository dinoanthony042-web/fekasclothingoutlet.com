@extends('layouts.admin')

@section('title', 'Edit Hero Slider')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Edit Hero Slider</h1>
    <p class="mt-1 text-sm text-gray-600">Update hero slider image and content</p>
</div>

<form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PATCH')

    <div class="space-y-6">
        <!-- Image Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Image</h2>

            <!-- Current Image -->
            <div class="mb-6">
                <p class="mb-2 text-sm font-medium text-gray-900">Current Image:</p>
                <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="h-48 rounded-lg object-cover">
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image_upload" class="block text-sm font-medium text-gray-900">Replace with New Images</label>
                <div class="mt-2">
                    <input type="file" id="image_upload" name="image_upload[]" accept="image/*" multiple
                        class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <p class="mt-1 text-xs text-gray-600">Supported formats: JPG, JPEG, PNG, GIF, WebP (Max 5MB each). The first image will be used.</p>
                @error('image_upload')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('image_upload.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Previews -->
            <div id="preview-container" class="mt-6">
                <p class="mb-3 text-sm font-medium text-gray-900">Selected Images:</p>
                <div id="previews" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4"></div>
                <p id="no-images" class="text-sm text-gray-500">No new images selected</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Content</h2>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-900">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $slider->title) }}" placeholder="e.g., Summer Collection"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div class="mb-4">
                <label for="subtitle" class="block text-sm font-medium text-gray-900">Subtitle</label>
                <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" placeholder="e.g., Discover New Styles"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('subtitle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter slider description..."
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description', $slider->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- CTA Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Call to Action</h2>

            <!-- CTA Text -->
            <div class="mb-4">
                <label for="cta_text" class="block text-sm font-medium text-gray-900">CTA Button Text</label>
                <input type="text" id="cta_text" name="cta_text" value="{{ old('cta_text', $slider->cta_text) }}" placeholder="e.g., Shop Now"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('cta_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- CTA URL -->
            <div class="mb-4">
                <label for="cta_url" class="block text-sm font-medium text-gray-900">CTA Button URL</label>
                <input type="url" id="cta_url" name="cta_url" value="{{ old('cta_url', $slider->cta_url) }}" placeholder="https://example.com/collection"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('cta_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Settings Section -->
        <div class="pb-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Settings</h2>

            <!-- Order -->
            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-900">Display Order</label>
                <input type="number" id="order" name="order" value="{{ old('order', $slider->order) }}" min="0"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-600">Lower numbers appear first</p>
                @error('order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="is_active" class="ml-3 text-sm font-medium text-gray-900">Active (visible on homepage)</label>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex gap-3 pt-6">
        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 font-semibold text-white transition hover:bg-blue-700">
            Update Slider
        </button>
        <a href="{{ route('admin.sliders.index') }}" class="rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-900 transition hover:bg-gray-50">
            Cancel
        </a>
    </div>
</form>

<script>
    const selectedFiles = [];

    document.getElementById('image_upload').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        selectedFiles.push(...files);
        renderPreviews();
    });

    function renderPreviews() {
        const previewsContainer = document.getElementById('previews');
        const noImagesMsg = document.getElementById('no-images');
        previewsContainer.innerHTML = '';

        if (selectedFiles.length === 0) {
            noImagesMsg.style.display = 'block';
            return;
        }

        noImagesMsg.style.display = 'none';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                wrapper.innerHTML = `
                    <div class="relative rounded-lg overflow-hidden border border-gray-300 bg-gray-100">
                        <img src="${event.target.result}" alt="Preview" class="h-32 w-full object-cover">
                        <button type="button" data-index="${index}" class="remove-image absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/50 transition-colors duration-200">
                            <span class="text-white text-3xl font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-200">×</span>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-600 truncate">${file.name}</p>
                `;
                previewsContainer.appendChild(wrapper);

                // Attach remove handler
                wrapper.querySelector('.remove-image').addEventListener('click', function(e) {
                    e.preventDefault();
                    removeImage(index);
                });
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        renderPreviews();
        // Reset the file input to allow re-selecting the same files
        document.getElementById('image_upload').value = '';
    }
</script>
@endsection
