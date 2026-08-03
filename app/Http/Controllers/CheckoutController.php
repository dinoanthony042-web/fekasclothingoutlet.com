<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\BrevoMailer;
use App\Services\KorapayService;
use App\Services\PaystackService;
use App\Services\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected PaystackService $paystackService;
    protected KorapayService $korapayService;
    protected BrevoMailer $brevoMailer;
    protected ShippingCalculator $shippingCalculator;

    public function __construct(PaystackService $paystackService, KorapayService $korapayService, BrevoMailer $brevoMailer, ShippingCalculator $shippingCalculator)
    {
        $this->paystackService = $paystackService;
        $this->korapayService = $korapayService;
        $this->brevoMailer = $brevoMailer;
        $this->shippingCalculator = $shippingCalculator;
    }

    public function index(): View
    {
        $user = Auth::user();
        /** @var User $user */
        $cartItems = $user->carts()->with('product.category')->get();

        return view('checkout.index', compact('cartItems'));
    }

    public function store(Request $request): RedirectResponse|View
    {
        $rules = [
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:30',
            'payment_method' => 'required|string|in:card,paypal,paystack,korapay',
            'delivery_method' => 'required|string|in:delivery,pickup',
        ];

        if ($request->input('delivery_method') !== 'pickup') {
            $rules = array_merge($rules, [
                'shipping_street' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:100',
                'shipping_state' => 'required|string|max:100',
                'shipping_postcode' => 'required|string|max:20',
                'shipping_country' => 'required|string|max:100',
            ]);
        }

        $data = $request->validate($rules);

        $user = Auth::user();
        /** @var User $user */
        $deliveryZone = $request->input('delivery_zone', 'outside_abuja');
        $abujaLocation = trim((string) $request->input('abuja_location', ''));
        $shippingAddress = trim((string) ($data['shipping_street'] ?? ''));

        $shipping = $request->input('delivery_method') === 'pickup'
            ? ['amount' => 0, 'zone' => 'pickup', 'label' => 'Pickup']
            : $this->shippingCalculator->calculateShipping(
                $data['shipping_city'] ?? '',
                $data['shipping_state'] ?? '',
                $data['shipping_country'] ?? '',
                $shippingAddress,
                $deliveryZone,
                $abujaLocation
            );
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        Log::info('Checkout attempt', [
            'user_id' => $user->id,
            'cart_items_count' => $cartItems->count(),
            'payment_method' => $data['payment_method']
        ]);

        return DB::transaction(function () use ($user, $cartItems, $data, $request, $shipping, $deliveryZone, $abujaLocation) {
            $total = 0;
            foreach ($cartItems as $item) {
                $available = $item->product?->stockForSize($item->size) ?? 0;
                if (!$item->product || $available < $item->quantity) {
                    $name = $item->product?->name ?? 'This product';
                    return redirect()->route('cart.index')->with('error', "{$name} only has {$available} item(s) left in stock for the selected size.");
                }

                $total += $item->product->discounted_price * $item->quantity;
            }

            $total += $shipping['amount'];

            $shippingAddressPayload = [
                'name' => $data['shipping_name'],
                'phone' => $data['shipping_phone'],
                'delivery_method' => $data['delivery_method'],
                'street' => $data['shipping_street'] ?? '',
                'city' => $data['shipping_city'] ?? '',
                'state' => $data['shipping_state'] ?? '',
                'postcode' => $data['shipping_postcode'] ?? '',
                'country' => $data['shipping_country'] ?? '',
                'delivery_fee' => $shipping['amount'],
                'shipping_zone' => $shipping['zone'],
                'shipping_label' => $shipping['label'],
                'delivery_zone' => $deliveryZone,
                'abuja_location' => $abujaLocation,
            ];

            // Create order with pending status
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $shippingAddressPayload,
                'billing_address' => $shippingAddressPayload,
                'payment_method' => $data['payment_method'],
            ]);

            Log::info('Order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $total
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->discounted_price,
                    'size' => $item->size,
                    'color' => $item->color,
                ]);

                $item->product->decrementStock($item->quantity, $item->size);
            }

            $user->carts()->delete();

            // Handle payment based on method
            if ($data['payment_method'] === 'paystack') {
                return $this->initiatePaystackPayment($order);
            }

            if ($data['payment_method'] === 'korapay') {
                $paymentReference = 'korapay_order_' . $order->id . '_' . time();

                $order->update([
                    'payment_reference' => $paymentReference,
                ]);

                return view('checkout.korapay', [
                    'order' => $order->fresh(),
                    'korapayPublicKey' => config('korapay.public_key'),
                    'successUrl' => route('payment.korapay.verify'),
                    'failureUrl' => route('checkout.index'),
                    'notificationUrl' => route('webhooks.korapay'),
                ]);
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

