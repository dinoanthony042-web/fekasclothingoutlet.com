<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cartItems = Auth::user()->carts()->with('product')->get();

        return view('checkout.index', compact('cartItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:30',
            'shipping_street' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postcode' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->reduce(fn ($carry, $item) => $carry + $item->product->price * $item->quantity, 0);

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => 'processing',
            'shipping_address' => [
                'name' => $data['shipping_name'],
                'phone' => $data['shipping_phone'],
                'street' => $data['shipping_street'],
                'city' => $data['shipping_city'],
                'state' => $data['shipping_state'],
                'postcode' => $data['shipping_postcode'],
                'country' => $data['shipping_country'],
            ],
            'billing_address' => [
                'name' => $data['shipping_name'],
                'phone' => $data['shipping_phone'],
                'street' => $data['shipping_street'],
                'city' => $data['shipping_city'],
                'state' => $data['shipping_state'],
                'postcode' => $data['shipping_postcode'],
                'country' => $data['shipping_country'],
            ],
            'payment_method' => $data['payment_method'],
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'size' => $item->size,
                'color' => $item->color,
            ]);
        }

        $user->carts()->delete();

        return redirect()->route('dashboard')->with('success', 'Your order has been placed successfully.');
    }
}
