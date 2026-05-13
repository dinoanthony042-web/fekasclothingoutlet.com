<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class PaystackService
{
    protected string $baseUrl = 'https://api.paystack.co';
    protected ?string $secretKey = null;

    public function __construct()
    {
        $this->secretKey = config('paystack.secret_key');

        if (!$this->secretKey) {
            throw new Exception('Paystack secret key is not configured. Please set PAYSTACK_SECRET in your .env file and clear config cache.');
        }
    }

    /**
     * Initialize a transaction
     */
    public function initializeTransaction(array $data): array
    {
        try {
            $payload = [
                'email' => $data['email'],
                'amount' => $data['amount'] * 100, // Paystack expects amount in kobo
                'reference' => $data['reference'],
                'metadata' => $data['metadata'] ?? [],
            ];

            if (!empty($data['callback_url'])) {
                $payload['callback_url'] = $data['callback_url'];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/transaction/initialize", $payload);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data'],
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Failed to initialize transaction',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data'],
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Failed to verify transaction',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a payment link for an order
     */
    public function createPaymentLink(array $data): array
    {
        $reference = 'order_' . $data['order_id'] . '_' . time();
        
        return $this->initializeTransaction([
            'email' => $data['email'],
            'amount' => $data['amount'],
            'reference' => $reference,
            'callback_url' => $data['callback_url'] ?? route('payment.verify'),
            'metadata' => [
                'order_id' => $data['order_id'],
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
            ],
        ]);
    }

    /**
     * Get authorization URL for payment
     */
    public function getAuthorizationUrl(string $accessCode): string
    {
        return "https://checkout.paystack.com/{$accessCode}";
    }
}
