<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /dashboard\nDisallow: /cart\nDisallow: /checkout\nDisallow: /orders\nDisallow: /wishlist\nDisallow: /login\nDisallow: /register\n\nSitemap: " . route('seo.sitemap') . "\n";

        return response($content)->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $products = Product::query()->latest('updated_at')->get();

        return response()->view('seo.sitemap', compact('products'))
            ->header('Content-Type', 'application/xml');
    }
}