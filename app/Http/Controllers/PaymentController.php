<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\BrevoMailer;
use App\Services\KorapayService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected PaystackService $paystackService;
    protected KorapayService $korapayService;
    protected BrevoMailer $brevoMailer;

    public function __construct(PaystackService $paystackService, KorapayService $korapayService, BrevoMailer $brevoMailer)
    {
        $this->paystackService = $paystackService;
        $this->korapayService = $korapayService;
        $this->brevoMailer = $brevoMailer;
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

                $shouldSendConfirmation = $order->payment_status !== 'completed';

                // Update order with payment details
                $order->update([
                    'payment_status' => 'completed',
                    'payment_reference' => $reference,
                    'transaction_id' => $paymentData['reference'],
                    'status' => 'confirmed',
                ]);

                if ($shouldSendConfirmation) {
                    $this->brevoMailer->sendOrderConfirmation($order);
                }

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

    public function korapayVerify(Request $request): RedirectResponse
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('orders.index')->with('error', 'Invalid Korapay payment reference.');
        }

        $response = $this->korapayService->verifyCharge($reference);

        if (!$response['status']) {
            return redirect()->route('orders.index')->with('error', 'Korapay verification failed: ' . $response['message']);
        }

        $paymentData = $response['data'];
        $order = Order::where('payment_reference', $reference)->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        if ($paymentData['status'] === 'success') {
            return DB::transaction(function () use ($order, $paymentData, $reference) {
                $shouldSendConfirmation = $order->payment_status !== 'completed';

                $order->update([
                    'payment_status' => 'completed',
                    'transaction_id' => $paymentData['transaction_reference'] ?? $reference,
                    'status' => 'confirmed',
                ]);

                if ($shouldSendConfirmation) {
                    $this->brevoMailer->sendOrderConfirmation($order);
                }

                return redirect()->route('orders.index')->with('success', 'Payment completed successfully! Your order is being processed.');
            });
        }

        $order->update([
            'payment_status' => 'failed',
            'status' => 'failed',
        ]);

        return redirect()->route('orders.index')->with('error', 'Korapay payment was not successful. Please try again.');
    }

    public function korapayWebhook(Request $request)
    {
        $signature = $request->header('x-korapay-signature');
        $secret = config('korapay.secret_key');
        $payload = json_encode($request->input('data'));
        $hash = hash_hmac('sha256', $payload, $secret);

        if ($hash !== $signature) {
            return response()->json(['status' => false, 'message' => 'Invalid signature'], 403);
        }

        $event = $request->input('event');
        $data = $request->input('data', []);

        if ($event === 'charge.success') {
            $this->handleKorapaySuccessfulPayment($data);
        } elseif ($event === 'charge.failed') {
            $this->handleKorapayFailedPayment($data);
        }

        return response()->json(['status' => true]);
    }

    protected function handleKorapaySuccessfulPayment(array $data): void
    {
        $paymentReference = $data['payment_reference'] ?? null;

        if (!$paymentReference) {
            return;
        }

        $order = Order::where('payment_reference', $paymentReference)->first();

        if (!$order || $order->payment_status === 'completed') {
            return;
        }

        $order->update([
            'payment_status' => 'completed',
            'transaction_id' => $data['reference'] ?? $data['transaction_reference'] ?? null,
            'status' => 'confirmed',
        ]);

        $this->brevoMailer->sendOrderConfirmation($order);
    }

    protected function handleKorapayFailedPayment(array $data): void
    {
        $paymentReference = $data['payment_reference'] ?? null;

        if (!$paymentReference) {
            return;
        }

        $order = Order::where('payment_reference', $paymentReference)->first();

        if (!$order) {
            return;
        }

        $order->update([
            'payment_status' => 'failed',
            'status' => 'failed',
        ]);
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

        if ($order && $order->payment_status !== 'completed') {
            $order->update([
                'payment_status' => 'completed',
                'transaction_id' => $data['reference'],
                'status' => 'confirmed',
            ]);

            $this->brevoMailer->sendOrderConfirmation($order);
        }
    }
}
