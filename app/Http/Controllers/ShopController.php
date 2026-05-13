<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $styles = ['Casual', 'Corporate', 'Party', 'Streetwear', 'Traditional', 'English'];
        $colors = ['Blush', 'Black', 'Ivory', 'Nude', 'Gold'];
        $sizes = ['XS', 'S', 'M', 'L', '36', '37', '38', '39', '40'];

        $selectedCategory = null;
        $selectedParent = null;
        $subcategories = collect();
        $activeCategorySlug = null;

        if ($request->category) {
            $selectedCategory = Category::with('parent', 'children')->where('slug', $request->category)->first();

            if ($selectedCategory) {
                if ($selectedCategory->parent) {
                    $selectedParent = $selectedCategory->parent;
                    $subcategories = $selectedParent->children;
                } else {
                    $selectedParent = $selectedCategory;
                    $subcategories = $selectedCategory->children;
                }
            }
        }

        $selectedSubcategory = null;

        if (!$selectedParent && $request->subcategory) {
            $subcategoryQuery = Category::with('parent')->where('slug', $request->subcategory);
            if ($selectedCategory && $selectedCategory->parent_id === null) {
                $subcategoryQuery->where('parent_id', $selectedCategory->id);
            }

            $selectedSubcategory = $subcategoryQuery->first();

            if ($selectedSubcategory && $selectedSubcategory->parent) {
                $selectedParent = $selectedSubcategory->parent;
                $subcategories = $selectedParent->children;
            }
        }

        if ($selectedParent) {
            $activeCategorySlug = $selectedParent->slug;
        } elseif ($selectedCategory) {
            $activeCategorySlug = $selectedCategory->slug;
        } else {
            $activeCategorySlug = $request->category;
        }

        $products = Product::with(['category.discounts', 'discounts'])
            ->when($request->q, fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            }))
            ->when($request->subcategory, function ($query) use ($request, $selectedParent) {
                $query->whereHas('category', function ($query) use ($request, $selectedParent) {
                    $query->where('slug', $request->subcategory);
                    if ($selectedParent) {
                        $query->where('parent_id', $selectedParent->id);
                    }
                });
            })
            ->when(!$request->subcategory && $request->category, function ($query) use ($request, $selectedCategory) {
                $query->whereHas('category', function ($query) use ($request, $selectedCategory) {
                    if ($selectedCategory && $selectedCategory->parent_id === null) {
                        $query->where('slug', $request->category)
                            ->orWhere('parent_id', $selectedCategory->id);
                    } else {
                        $query->where('slug', $request->category);
                    }
                });
            })
            ->when($request->price_min, fn ($query) => $query->where('price', '>=', floatval($request->price_min)))
            ->when($request->price_max, fn ($query) => $query->where('price', '<=', floatval($request->price_max)))
            ->when($request->size, fn ($query) => $query->whereJsonContains('sizes', $request->size))
            ->when($request->color, fn ($query) => $query->whereJsonContains('colors', $request->color))
            ->when($request->style, fn ($query) => $query->whereJsonContains('styles', $request->style))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('shop.index', compact('categories', 'products', 'styles', 'colors', 'sizes', 'subcategories', 'activeCategorySlug'));
    }
}
