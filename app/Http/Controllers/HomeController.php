<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('name')->get();
        $featured = Product::with('category')->where('is_featured', true)->take(6)->get();
        $newIn = Product::with('category')->where('is_new', true)->latest()->take(4)->get();
        $bestSellers = Product::with('category')->where('is_best_seller', true)->take(4)->get();
        $trending = Product::with('category')->where('is_featured', true)->latest()->take(4)->get();

        return view('home', compact('categories', 'featured', 'newIn', 'bestSellers', 'trending'));
    }
}
