<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('admin.products.create', compact('categories', 'parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'styles' => 'nullable|array',
            'age_range' => 'nullable|string',
            'image_uploads' => 'required|array|min:2|max:5',
            'image_uploads.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_best_seller' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['images'] = $this->buildImageList($request);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('admin.products.edit', compact('product', 'categories', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'styles' => 'nullable|array',
            'age_range' => 'nullable|string',
            'image_uploads' => 'nullable|array|min:2|max:5',
            'image_uploads.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_best_seller' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['images'] = $request->hasFile('image_uploads')
            ? $this->buildImageList($request)
            : $product->images;

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    protected function buildImageList(Request $request): array
    {
        $images = [];

        if ($request->hasFile('image_uploads')) {
            foreach ($request->file('image_uploads') as $upload) {
                if ($upload && $upload->isValid()) {
                    $path = $upload->store('products', 'public');
                    $images[] = asset(Storage::url($path));
                }
            }
        }

        return array_values(array_unique($images));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
