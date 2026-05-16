<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;

class BrevoMailer
{
    protected string $apiKey;
    protected string $fromEmail;
    protected string $fromName;
    protected string $endpoint = 'https://api.brevo.com/v3/smtp/email';

    public function __construct()
    {
        $this->apiKey = config('services.brevo.key');
        $this->fromEmail = config('services.brevo.from_email', config('mail.from.address'));
        $this->fromName = config('services.brevo.from_name', config('mail.from.name'));
    }

    public function send(string $email, string $name, string $subject, string $htmlContent): bool
    {
        if (empty($this->apiKey) || empty($email)) {
            logger()->warning('Brevo API key or recipient email is missing.', compact('email', 'subject'));
            return false;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'api-key' => $this->apiKey,
        ])->post($this->endpoint, [
            'sender' => [
                'name' => $this->fromName,
                'email' => $this->fromEmail,
            ],
            'to' => [[
                'email' => $email,
                'name' => $name,
            ]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        if (!$response->successful()) {
            logger()->error('Brevo email failed', [
                'email' => $email,
                'subject' => $subject,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->successful();
    }

    public function sendRegistrationSuccess(User $user): bool
    {
        return $this->send(
            $user->email,
            $user->name,
            'Welcome to ' . config('app.name'),
            View::make('emails.registration-success', compact('user'))->render()
        );
    }

    public function sendOrderConfirmation(Order $order): bool
    {
        return $this->send(
            $order->user->email,
            $order->user->name,
            'Your order is confirmed: ' . $order->order_number,
            View::make('emails.order-confirmation', compact('order'))->render()
        );
    }

    public function sendOrderStatusUpdated(Order $order): bool
    {
        $statusMessage = $this->getStatusUpdateMessage($order->status);
        $subject = $this->getStatusUpdateSubject($order->status, $order->order_number);

        return $this->send(
            $order->user->email,
            $order->user->name,
            $subject,
            View::make('emails.order-status-updated', compact('order', 'statusMessage'))->render()
        );
    }

    protected function getStatusUpdateSubject(string $status, string $orderNumber): string
    {
        return match ($status) {
            'pending' => "Order {$orderNumber} is pending",
            'processing' => "Order {$orderNumber} is now processing",
            'shipped' => "Order {$orderNumber} has shipped",
            'delivered' => "Order {$orderNumber} is delivered",
            'cancelled' => "Order {$orderNumber} has been cancelled",
            default => "Order {$orderNumber} status updated",
        };
    }

    protected function getStatusUpdateMessage(string $status): string
    {
        return match ($status) {
            'pending' => 'Your order is pending and will be processed shortly.',
            'processing' => 'Your order is being prepared and will ship soon.',
            'shipped' => 'Your order has shipped and is on its way.',
            'delivered' => 'Your order has been delivered. We hope you enjoy your purchase.',
            'cancelled' => 'Your order has been cancelled. If you need help, please contact support.',
            default => 'Your order status has been updated.',
        };
    }
}
