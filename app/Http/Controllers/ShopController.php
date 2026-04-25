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
        $categories = Category::orderBy('name')->get();
        $styles = ['Casual', 'Corporate', 'Party', 'Streetwear', 'Traditional', 'English'];
        $colors = ['Blush', 'Black', 'Ivory', 'Nude', 'Gold'];
        $sizes = ['XS', 'S', 'M', 'L', '36', '37', '38', '39', '40'];

        $products = Product::with('category')
            ->when($request->q, fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            }))
            ->when($request->category, fn ($query) => $query->whereHas('category', fn ($query) => $query->where('slug', $request->category)))
            ->when($request->price_min, fn ($query) => $query->where('price', '>=', floatval($request->price_min)))
            ->when($request->price_max, fn ($query) => $query->where('price', '<=', floatval($request->price_max)))
            ->when($request->size, fn ($query) => $query->whereJsonContains('sizes', $request->size))
            ->when($request->color, fn ($query) => $query->whereJsonContains('colors', $request->color))
            ->when($request->style, fn ($query) => $query->whereJsonContains('styles', $request->style))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('shop.index', compact('categories', 'products', 'styles', 'colors', 'sizes'));
    }
}
