<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HeroSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sliders = HeroSlider::orderBy('order')->latest()->paginate(20);
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image_upload' => 'required|array|min:1',
            'image_upload.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        // Handle first image upload
        if ($request->hasFile('image_upload') && count($request->file('image_upload')) > 0) {
            $files = $request->file('image_upload');
            $firstFile = is_array($files) ? reset($files) : $files;
            $path = $firstFile->store('hero-sliders', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $validated['order'] = $validated['order'] ?? HeroSlider::max('order') + 1 ?? 0;
        $validated['is_active'] = $request->has('is_active');

        HeroSlider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Hero slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HeroSlider $slider): View
    {
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroSlider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroSlider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'image_upload' => 'nullable|array|min:1',
            'image_upload.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image_upload') && count($request->file('image_upload')) > 0) {
            // Delete old image if it's a storage URL
            if (str_contains($slider->image_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $slider->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $files = $request->file('image_upload');
            $firstFile = is_array($files) ? reset($files) : $files;
            $path = $firstFile->store('hero-sliders', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $validated['is_active'] = $request->has('is_active');

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Hero slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSlider $slider): RedirectResponse
    {
        // Delete image if it's a storage URL
        if (str_contains($slider->image_url, '/storage/')) {
            $path = str_replace('/storage/', '', $slider->image_url);
            Storage::disk('public')->delete($path);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Hero slider deleted successfully.');
    }
}
