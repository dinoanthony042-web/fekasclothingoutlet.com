<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        if (Auth::check()) {
            $cartItems = Auth::user()->carts()->with('product.category')->get();
        } else {
            // For guests, cart items will be managed via localStorage on the frontend
            $cartItems = collect();
        }

        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantityToAdd = $data['quantity'] ?? 1;

        // If user is authenticated, save to database
        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->where('size', $data['size'] ?? null)
                ->where('color', $data['color'] ?? null)
                ->first();

            $existingQuantity = $cartItem ? $cartItem->quantity : 0;
            $newQuantity = $existingQuantity + $quantityToAdd;

            if ($newQuantity > $product->stock) {
                $message = $product->stock > 0
                    ? "You can only add {$product->stock} of this item to your cart."
                    : 'This product is out of stock.';

                if ($request->expectsJson()) {
                    return response()->json([ 'success' => false, 'message' => $message ], 422);
                }

                return redirect()->back()->with('error', $message);
            }

            if ($cartItem) {
                $cartItem->quantity = $newQuantity;
            } else {
                $cartItem = new Cart([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'size' => $data['size'] ?? null,
                    'color' => $data['color'] ?? null,
                    'quantity' => $quantityToAdd,
                ]);
            }

            $cartItem->save();
            $cartCount = Auth::user()->carts()->sum('quantity');
        } else {
            // For guests, cart is managed via localStorage in JavaScript
            $cartCount = $request->input('cart_count', 1);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully.',
                'cart_count' => $cartCount,
                'is_guest' => !Auth::check()
            ], 200);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_unless($cart->user_id === Auth::id(), 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($data['quantity'] > $cart->product->stock) {
            $message = $cart->product->stock > 0
                ? "Only {$cart->product->stock} items are available in stock."
                : 'This product is out of stock.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $cart->update($data);

        $cartCount = Auth::user()->carts()->sum('quantity');
        $itemTotal = $cart->product->price * $cart->quantity;
        $cartTotal = Auth::user()->carts()->get()->sum(fn($item) => $item->product->price * $item->quantity);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'cart_count' => $cartCount,
                'item_total' => $itemTotal,
                'cart_total' => $cartTotal
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function increment(Request $request, Cart $cart)
    {
        abort_unless($cart->user_id === Auth::id(), 403);

        if ($cart->quantity >= $cart->product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the available stock for this product.',
                'quantity' => $cart->quantity,
            ], 422);
        }

        $cart->increment('quantity');

        $cartCount = Auth::user()->carts()->sum('quantity');
        $itemTotal = $cart->product->price * $cart->quantity;
        $cartTotal = Auth::user()->carts()->get()->sum(fn($item) => $item->product->price * $item->quantity);

        return response()->json([
            'success' => true,
            'quantity' => $cart->quantity,
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount
        ]);
    }

    public function decrement(Request $request, Cart $cart)
    {
        abort_unless($cart->user_id === Auth::id(), 403);

        if ($cart->quantity > 1) {
            $cart->decrement('quantity');
        }

        $cartCount = Auth::user()->carts()->sum('quantity');
        $itemTotal = $cart->product->price * $cart->quantity;
        $cartTotal = Auth::user()->carts()->get()->sum(fn($item) => $item->product->price * $item->quantity);

        return response()->json([
            'success' => true,
            'quantity' => $cart->quantity,
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount
        ]);
    }

    public function destroy(Request $request, Cart $cart = null)
    {
        // If authenticated user, use database cart
        if (Auth::check()) {
            // If no cart provided, try to find by product_id or matching variant
            if (!$cart) {
                $productId = $request->input('product_id');
                $size = $request->input('size');
                $color = $request->input('color');

                $query = Auth::user()->carts()->where('product_id', $productId);

                if ($size !== null) {
                    $query->where('size', $size);
                }

                if ($color !== null) {
                    $query->where('color', $color);
                }

                $cart = $query->first();
            }

            if (!$cart) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
                }
                abort(404);
            }

            abort_unless($cart->user_id === Auth::id(), 403);

            $cart->delete();
            $cartCount = Auth::user()->carts()->sum('quantity');
        } else {
            // For guests, cart count comes from frontend
            $cartCount = $request->input('cart_count', 0);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'cart_count' => $cartCount,
                'is_guest' => !Auth::check()
            ], 200);
        }

        return back()->with('success', 'Item removed from cart.');
    }
}
