<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('description', 'like', '%' . $request->q . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::ensureRootCategoriesExist()->load('children');
        $parentCategories = Category::ensureRootCategoriesExist();

        return view('admin.products.create', compact('categories', 'parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'size_stock' => 'nullable|array',
            'size_stock.*' => 'nullable|integer|min:0',
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

        $validated['size_stock'] = collect($validated['size_stock'] ?? [])
            ->only($validated['sizes'] ?? [])
            ->map(fn ($qty) => (int) $qty)
            ->all();

        if (!empty($validated['size_stock'])) {
            $validated['stock'] = array_sum($validated['size_stock']);
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['images'] = $this->buildImageList($request);

        try {
            $product = Product::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'product_id' => $product->id,
                    'redirect' => route('admin.products.index')
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
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
        $categories = Category::ensureRootCategoriesExist()->load('children');
        $parentCategories = Category::ensureRootCategoriesExist();

        return view('admin.products.edit', compact('product', 'categories', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'size_stock' => 'nullable|array',
            'size_stock.*' => 'nullable|integer|min:0',
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

        $validated['size_stock'] = collect($validated['size_stock'] ?? [])
            ->only($validated['sizes'] ?? [])
            ->map(fn ($qty) => (int) $qty)
            ->all();

        if (!empty($validated['size_stock'])) {
            $validated['stock'] = array_sum($validated['size_stock']);
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['images'] = $request->hasFile('image_uploads')
            ? $this->buildImageList($request)
            : $product->images;

        try {
            $product->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'product_id' => $product->id,
                    'redirect' => route('admin.products.index')
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    protected function buildImageList(Request $request): array
    {
        $images = [];

        if ($request->hasFile('image_uploads')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('image_uploads') as $upload) {
                if ($upload && $upload->isValid()) {
                    $path = $upload->store('products', 'public');
                    $absolutePath = Storage::disk('public')->path($path);

                    $image = $manager->read($absolutePath);
                    $image->resize(1600, 1600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $format = match (strtolower($upload->getClientOriginalExtension() ?: 'jpg')) {
                        'png' => 'png',
                        'webp' => 'webp',
                        default => 'jpg',
                    };

                    $image->save($absolutePath, quality: 82, format: $format);
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
