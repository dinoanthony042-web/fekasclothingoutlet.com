<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected PaystackService $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Handle payment verification and completion
     */
    public function verify(Request $request): RedirectResponse
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('orders.index')->with('error', 'Invalid payment reference.');
        }

        // Verify with Paystack
        $response = $this->paystackService->verifyTransaction($reference);

        if (!$response['status']) {
            return redirect()->route('orders.index')->with('error', 'Payment verification failed: ' . $response['message']);
        }

        $paymentData = $response['data'];

        // Check if payment was successful
        if ($paymentData['status'] === 'success') {
            return DB::transaction(function () use ($paymentData, $reference) {
                $orderId = $paymentData['metadata']['order_id'];
                $order = Order::find($orderId);

                if (!$order) {
                    return redirect()->route('orders.index')->with('error', 'Order not found.');
                }

                // Update order with payment details
                $order->update([
                    'payment_status' => 'completed',
                    'payment_reference' => $reference,
                    'transaction_id' => $paymentData['reference'],
                    'status' => 'confirmed',
                ]);

                return redirect()->route('orders.index')->with('success', 'Payment completed successfully! Your orders are being processed.');
            });
        } else {
            // Payment failed
            $order = Order::find($paymentData['metadata']['order_id']);
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'payment_reference' => $reference,
                ]);
            }

            return redirect()->route('orders.index')->with('error', 'Payment was not successful. Please try again.');
        }
    }

    /**
     * Handle webhook from Paystack
     */
    public function webhook(Request $request)
    {
        // Verify Paystack signature
        $signature = $request->header('x-paystack-signature');
        $secret = config('paystack.secret_key');
        
        $hash = hash_hmac('sha512', $request->getContent(), $secret);
        
        if ($hash !== $signature) {
            return response()->json(['status' => false, 'message' => 'Invalid signature'], 403);
        }

        $event = $request->json('event');
        $data = $request->json('data');

        if ($event === 'charge.success') {
            $this->handleSuccessfulPayment($data);
        }

        return response()->json(['status' => true]);
    }

    /**
     * Handle successful payment from webhook
     */
    protected function handleSuccessfulPayment(array $data): void
    {
        $reference = $data['reference'];
        $order = Order::where('payment_reference', $reference)->first();

        if ($order) {
            $order->update([
                'payment_status' => 'completed',
                'transaction_id' => $data['reference'],
                'status' => 'confirmed',
            ]);
        }
    }
}
