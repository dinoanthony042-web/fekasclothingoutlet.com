<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $reviews = $product->reviews()->with('user')->latest()->take(5)->get();

        return view('shop.product', compact('product', 'related', 'reviews'));
    }

    public function apiShow(Product $product): JsonResponse
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'images' => $product->images,
            'category' => [
                'id' => $product->category?->id,
                'name' => $product->category?->name
            ]
        ]);
    }
}
