<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\BrevoMailer;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected PaystackService $paystackService;
    protected BrevoMailer $brevoMailer;

    public function __construct(PaystackService $paystackService, BrevoMailer $brevoMailer)
    {
        $this->paystackService = $paystackService;
        $this->brevoMailer = $brevoMailer;
    }

    public function index(): View
    {
        $cartItems = Auth::user()->carts()->with('product.category')->get();

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
            'payment_method' => 'required|string|in:card,paypal,paystack',
        ]);

        $user = Auth::user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        \Log::info('Checkout attempt', [
            'user_id' => $user->id,
            'cart_items_count' => $cartItems->count(),
            'payment_method' => $data['payment_method']
        ]);

        return DB::transaction(function () use ($user, $cartItems, $data) {
            $total = 0;
            foreach ($cartItems as $item) {
                if (!$item->product || $item->product->stock < $item->quantity) {
                    $available = $item->product?->stock ?? 0;
                    $name = $item->product?->name ?? 'This product';

                    return redirect()->route('cart.index')->with('error', "{$name} only has {$available} item(s) left in stock.");
                }

                $total += $item->product->price * $item->quantity;
            }

            // Create order with pending status
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
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

            \Log::info('Order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $total
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

                $item->product->decrement('stock', $item->quantity);
            }

            $user->carts()->delete();

            // Handle payment based on method
            if ($data['payment_method'] === 'paystack') {
                return $this->initiatePaystackPayment($order);
            }

            // For other payment methods (card, paypal), mark as confirmed for now
            $order->update([
                'payment_status' => 'completed',
                'status' => 'confirmed'
            ]);

            $this->brevoMailer->sendOrderConfirmation($order);

            return redirect()->route('orders.index')->with('success', 'Your order has been placed successfully.');
        });
    }

    /**
     * Initiate Paystack payment for an order
     */
    protected function initiatePaystackPayment(Order $order): RedirectResponse
    {
        $paymentData = $this->paystackService->createPaymentLink([
            'order_id' => $order->id,
            'email' => $order->user->email,
            'amount' => $order->total,
            'customer_name' => $order->shipping_address['name'],
            'customer_phone' => $order->shipping_address['phone'],
            'callback_url' => route('payment.verify'),
        ]);

        if (!$paymentData['status']) {
            $order->delete();
            return redirect()->route('checkout.index')->with('error', 'Failed to initialize payment: ' . $paymentData['message']);
        }

        // Store payment reference
        $order->update([
            'payment_reference' => $paymentData['data']['reference'],
        ]);

        // Redirect to Paystack checkout
        $authorizationUrl = $this->paystackService->getAuthorizationUrl($paymentData['data']['access_code']);

        return redirect($authorizationUrl);
    }
}

