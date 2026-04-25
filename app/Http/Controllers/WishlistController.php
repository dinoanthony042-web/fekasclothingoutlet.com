<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlist = Auth::user()->wishlists()->with('product')->get();

        return view('wishlist.index', compact('wishlist'));
    }

    public function store(Request $request, Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        $wishlistCount = Auth::user()->wishlists()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Added to your wishlist.',
                'wishlist_count' => $wishlistCount,
                'action' => 'added'
            ], 200);
        }

        return back()->with('success', 'Added to your wishlist.');
    }

    public function destroy(Request $request, Product $product)
    {
        Auth::user()->wishlists()->where('product_id', $product->id)->delete();

        $wishlistCount = Auth::user()->wishlists()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist.',
                'wishlist_count' => $wishlistCount,
                'action' => 'removed'
            ], 200);
        }

        return back()->with('success', 'Product removed from wishlist.');
    }
}
