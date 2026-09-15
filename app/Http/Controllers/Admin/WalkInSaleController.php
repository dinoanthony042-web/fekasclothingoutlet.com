<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WalkInSaleController extends Controller
{
    public function create(): View
    {
        $products = Product::query()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('admin.sales.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:100',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $product = Product::query()->lockForUpdate()->findOrFail($validated['product_id']);
            $size = $validated['size'] ?? null;

            if ($size !== null && $size !== '' && !in_array($size, $product->sizes ?? [], true)) {
                throw ValidationException::withMessages([
                    'size' => 'The selected size is not available for this product.',
                ]);
            }

            $availableStock = $product->stockForSize($size ?: null);
            if ($availableStock < $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "{$product->name} only has {$availableStock} item(s) left in stock for the selected size.",
                ]);
            }

            $total = $product->discounted_price * $validated['quantity'];
            $saleAddress = [
                'name' => 'Walk-in customer',
                'phone' => '',
                'delivery_method' => 'walk_in',
                'street' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
                'country' => '',
                'delivery_fee' => 0,
            ];

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total' => $total,
                'status' => 'delivered',
                'payment_status' => 'completed',
                'payment_method' => 'cash',
                'shipping_address' => $saleAddress,
                'billing_address' => $saleAddress,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->discounted_price,
                'size' => $size ?: null,
                'color' => $validated['color'] ?? null,
            ]);

            $product->decrementStock($validated['quantity'], $size ?: null);

            return $order;
        });

        return redirect()->route('admin.sales.create')
            ->with('success', "Walk-in sale recorded successfully as {$order->order_number}.");
    }
}
