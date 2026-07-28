<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class KorapayService
{
    protected string $baseUrl;
    protected ?string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('korapay.secret_key');
        $this->baseUrl = config('korapay.base_url', 'https://api.korapay.com/merchant/api/v1');

        if (!$this->secretKey) {
            throw new Exception('Korapay secret key is not configured. Please set KORAPAY_SECRET_KEY in your .env file and clear config cache.');
        }
    }

    public function verifyCharge(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/charges/{$reference}");

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data'],
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Failed to verify Korapay charge',
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
