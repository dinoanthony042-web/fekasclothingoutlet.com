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
        $categories = Category::whereNull('parent_id')->with('children')->get();
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
        ]);

        // Ensure only one of product_id or category_id is set
        $productId = $validated['product_id'] ?? null;
        $categoryId = $validated['category_id'] ?? null;

        if ($productId && $categoryId) {
            return back()->withErrors(['product_id' => 'You can only apply discount to either a product or a category, not both.']);
        }

        if (!$productId && !$categoryId) {
            return back()->withErrors(['product_id' => 'You must select either a product or a category for the discount.']);
        }

        Discount::create($validated);

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
        $categories = Category::whereNull('parent_id')->with('children')->get();
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
        ]);

        // Ensure only one of product_id or category_id is set
        $productId = $validated['product_id'] ?? null;
        $categoryId = $validated['category_id'] ?? null;

        if ($productId && $categoryId) {
            return back()->withErrors(['product_id' => 'You can only apply discount to either a product or a category, not both.']);
        }

        if (!$productId && !$categoryId) {
            return back()->withErrors(['product_id' => 'You must select either a product or a category for the discount.']);
        }

        $discount->update($validated);

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
