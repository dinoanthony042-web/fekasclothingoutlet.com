<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $discounts = Discount::with(['product', 'category'])->latest()->paginate(20);
        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::all();
        $categories = Category::ensureRootCategoriesExist()->load('children');
        return view('admin.discounts.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'apply_all_categories' => 'boolean',
        ]);

        $productId = $validated['product_id'] ?? null;
        $categoryId = $validated['category_id'] ?? null;
        $categoryIds = $validated['category_ids'] ?? null;
        $applyAll = $validated['apply_all_categories'] ?? false;

        // Validate mutually exclusive targets
        if ($productId && ($categoryId || $categoryIds || $applyAll)) {
            return back()->withErrors(['product_id' => 'You can only apply discount to either a product or categories, not both.']);
        }

        if (!$productId && !$categoryId && !$categoryIds && !$applyAll) {
            return back()->withErrors(['product_id' => 'You must select either a product or one or more categories (or choose all categories) for the discount.']);
        }

        // Normalize data to store
        $data = $validated;
        if (!empty($categoryIds)) {
            $data['category_ids'] = array_values($categoryIds);
            // clear single category_id to avoid ambiguity
            $data['category_id'] = null;
        }

        $data['apply_all_categories'] = (bool) ($applyAll);

        Discount::create($data);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount): View
    {
        return view('admin.discounts.show', compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount): View
    {
        $products = Product::all();
        $categories = Category::ensureRootCategoriesExist()->load('children');
        return view('admin.discounts.edit', compact('discount', 'products', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'apply_all_categories' => 'boolean',
        ]);

        $productId = $validated['product_id'] ?? null;
        $categoryId = $validated['category_id'] ?? null;
        $categoryIds = $validated['category_ids'] ?? null;
        $applyAll = $validated['apply_all_categories'] ?? false;

        if ($productId && ($categoryId || $categoryIds || $applyAll)) {
            return back()->withErrors(['product_id' => 'You can only apply discount to either a product or categories, not both.']);
        }

        if (!$productId && !$categoryId && !$categoryIds && !$applyAll) {
            return back()->withErrors(['product_id' => 'You must select either a product or one or more categories (or choose all categories) for the discount.']);
        }

        $data = $validated;
        if (!empty($categoryIds)) {
            $data['category_ids'] = array_values($categoryIds);
            $data['category_id'] = null;
        }

        $data['apply_all_categories'] = (bool) ($applyAll);

        $discount->update($data);

        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Discount deleted successfully.');
    }
}
