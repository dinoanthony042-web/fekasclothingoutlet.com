<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::ensureRootCategoriesExist()
            ->load(['children' => function ($query) {
                $query->orderBy('name');
            }]);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parentCategories = Category::ensureRootCategoriesExist();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $validated['parent_id'] = $validated['parent_id'] ?: null;
        $validated['name'] = trim($validated['name']);
        $validated['slug'] = Str::slug($validated['name']);

        if ($validated['parent_id'] === null) {
            $name = Str::title($validated['name']);
            if (!in_array($name, Category::rootCategoryNames(), true)) {
                return back()->withErrors(['name' => 'Main categories are fixed to Men, Women, and Kids.'])->withInput();
            }

            if (Category::whereNull('parent_id')->where('name', $name)->exists()) {
                return back()->withErrors(['name' => 'That main category already exists.'])->withInput();
            }

            $validated['name'] = $name;
            $validated['slug'] = Str::slug($name);
        } else {
            $parent = Category::find($validated['parent_id']);
            if ($parent && $parent->parent_id !== null) {
                return back()->withErrors(['parent_id' => 'Subcategories must be created under Men, Women, or Kids.'])->withInput();
            }
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $parentCategories = Category::ensureRootCategoriesExist()
            ->where('id', '!=', $category->id);

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $validated['parent_id'] = $validated['parent_id'] ?: null;
        $validated['name'] = trim($validated['name']);
        $validated['slug'] = Str::slug($validated['name']);

        if ($validated['parent_id'] === null) {
            if (!$category->isRootCategory()) {
                return back()->withErrors(['parent_id' => 'Only Men, Women, and Kids may be main categories.'])->withInput();
            }

            $name = Str::title($validated['name']);
            if (!in_array($name, Category::rootCategoryNames(), true)) {
                return back()->withErrors(['name' => 'Main categories are fixed to Men, Women, and Kids.'])->withInput();
            }

            if (Category::whereNull('parent_id')->where('name', $name)->where('id', '!=', $category->id)->exists()) {
                return back()->withErrors(['name' => 'That main category already exists.'])->withInput();
            }

            $validated['name'] = $name;
            $validated['slug'] = Str::slug($name);
        } else {
            if ($category->isRootCategory()) {
                return back()->withErrors(['parent_id' => 'Root categories cannot be moved under another category.'])->withInput();
            }

            $parent = Category::find($validated['parent_id']);
            if ($parent && $parent->parent_id !== null) {
                return back()->withErrors(['parent_id' => 'A subcategory must be attached directly to Men, Women, or Kids.'])->withInput();
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has children or products
        if ($category->children()->count() > 0 || $category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with subcategories or products.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
