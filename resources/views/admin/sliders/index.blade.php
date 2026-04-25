@extends('layouts.admin')

@section('title', 'Hero Sliders')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Hero Sliders</h1>
        <p class="mt-1 text-sm text-gray-600">Manage hero slider images for your homepage</p>
    </div>
    <a href="{{ route('admin.sliders.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700">
        + Add New Slider
    </a>
</div>

@if($sliders->isEmpty())
    <div class="rounded-lg border border-gray-200 bg-gray-50 px-6 py-12 text-center">
        <p class="text-gray-600">No hero sliders found. Create your first one to get started.</p>
        <a href="{{ route('admin.sliders.create') }}" class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700">
            Create First Slider
        </a>
    </div>
@else
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Image</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Order</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($sliders as $slider)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="h-20 w-32 rounded object-cover">
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="font-medium text-gray-900">{{ $slider->title ?? 'No Title' }}</p>
                                @if($slider->subtitle)
                                    <p class="text-sm text-gray-600">{{ $slider->subtitle }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $slider->order }}</td>
                        <td class="px-6 py-4">
                            @if($slider->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Active</span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Edit</a>
                                <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $sliders->links() }}
    </div>
@endif
@endsection
