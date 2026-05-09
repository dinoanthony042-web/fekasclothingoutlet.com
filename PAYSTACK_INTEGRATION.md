# Paystack Integration Guide

## Overview
Paystack has been integrated into your Fekas Clothing e-commerce platform. This guide explains how the payment system works and how to use it.

## Environment Setup

### Configuration Variables (.env)
```
PAYSTACK_PUBLIC_KEY=pk_test_c6217e3c9d9c7d7f3e3c2b1e1f2d3c4b5a6e7f8
PAYSTACK_SECRET=sk_test_420b34e468a81dde2923c49c524f22c52f8789e0
PAYSTACK_MERCHANT_EMAIL=support@fekasclothing.com
```

**Important:** Replace these test keys with your actual Paystack keys from your merchant dashboard:
- Get your keys at: https://dashboard.paystack.com/

### Steps to Get Your Keys:
1. Sign up at Paystack.com
2. Go to Settings → API Keys & Webhooks
3. Copy your **Public Key** and **Secret Key**
4. Update your `.env` file with these keys

## How It Works

### Payment Flow

1. **User adds items to cart** → Cart is stored in the database
2. **User proceeds to checkout** → Checkout form displays with payment method options
3. **User selects Paystack** → Form submission validates shipping info
4. **Order is created** → Order record created with `payment_status: 'pending'`
5. **Payment initialized** → User is redirected to Paystack checkout page
6. **User completes payment** → Paystack processes the payment
7. **Redirect back to app** → User is redirected to `/payment/verify?reference=XXX`
8. **Payment verified** → Order status updated to `payment_status: 'completed'` and `status: 'confirmed'`

### Database Fields Added

The `orders` table now includes:
- `payment_status` - Status of payment (pending, completed, failed)
- `payment_reference` - Paystack transaction reference
- `transaction_id` - Unique transaction ID from Paystack

## Files Created/Modified

### New Files
- `app/Services/PaystackService.php` - Handles all Paystack API interactions
- `app/Http/Controllers/PaymentController.php` - Handles payment verification and webhooks
- `config/paystack.php` - Configuration file for Paystack
- `database/migrations/2026_05_09_000000_add_payment_fields_to_orders_table.php` - Database migration

### Modified Files
- `app/Http/Controllers/CheckoutController.php` - Updated to handle Paystack payment flow
- `app/Models/Order.php` - Added payment fields and scopes
- `routes/web.php` - Added payment verification and webhook routes
- `resources/views/checkout/index.blade.php` - Added Paystack payment option
- `.env` - Added Paystack credentials

## Webhook Configuration

### Setting Up Webhooks in Paystack Dashboard

1. Go to https://dashboard.paystack.com/settings/developer
2. Click on API Keys & Webhooks
3. Add a new webhook URL: `https://yourdomain.com/webhooks/paystack`
4. Select events to listen for: `charge.success`, `charge.failed`
5. Save

The webhook endpoint at `/webhooks/paystack` will automatically handle payment confirmations even if the user doesn't return to your site.

## Testing

### Test Cards (Paystack Test Mode)

Use these test cards to test the payment flow:

| Card Number | Exp Date | CVC | Status |
|---|---|---|---|
| 4084 0343 6173 5632 | Any future date | Any 3 digits | Successful |
| 5060 9666 3624 7940 | Any future date | Any 3 digits | Successful |
| 5555 5555 5555 4444 | Any future date | Any 3 digits | Successful |

### Test Email
Use any email format in checkout, e.g., `test@example.com`

## API Methods

### PaystackService Class

```php
// Initialize a transaction
$paystack = new PaystackService();
$response = $paystack->initializeTransaction([
    'email' => 'customer@example.com',
    'amount' => 50000,  // in kobo (₦500.00)
    'reference' => 'unique_ref_123',
    'metadata' => ['order_id' => 1]
]);

// Verify a transaction
$response = $paystack->verifyTransaction('reference_code');

// Create payment link
$response = $paystack->createPaymentLink([
    'order_id' => $order->id,
    'email' => $user->email,
    'amount' => $order->total,
    'customer_name' => $order->shipping_address['name'],
    'customer_phone' => $order->shipping_address['phone']
]);

// Get authorization URL
$url = $paystack->getAuthorizationUrl($accessCode);
```

## Order Status Flow

### Payment Statuses
- **pending** - Order created, awaiting payment
- **completed** - Payment successfully received
- **failed** - Payment failed or rejected

### Order Statuses
- **pending** - Awaiting payment
- **confirmed** - Payment confirmed, processing order
- **processing** - Order is being prepared
- **shipped** - Order has been shipped
- **delivered** - Order delivered
- **cancelled** - Order cancelled

## Troubleshooting

### Payment Not Working?

1. **Verify Paystack keys are correct**
   ```
   php artisan tinker
   > config('paystack.secret_key')
   > config('paystack.public_key')
   ```

2. **Check if migrations ran successfully**
   ```
   php artisan migrate:status
   ```

3. **Enable debug mode in .env**
   ```
   APP_DEBUG=true
   ```

4. **Check logs**
   ```
   tail -f storage/logs/laravel.log
   ```

### Common Issues

| Issue | Solution |
|---|---|
| "Invalid public key" | Check PAYSTACK_PUBLIC_KEY in .env |
| "Unauthorized" | Check PAYSTACK_SECRET in .env |
| Payment redirect fails | Ensure APP_URL is set correctly in .env |
| Webhook not working | Verify webhook URL in Paystack dashboard matches your domain |

## Security Considerations

1. **Never commit `.env`** with real Paystack keys to version control
2. **Use HTTPS** in production for all payment pages
3. **Webhook validation** - The PaymentController verifies the webhook signature
4. **Amount validation** - Always verify the amount in the backend
5. **Reference uniqueness** - Each transaction must have a unique reference

## Going Live

### Steps to Go Live

1. **Get Production Paystack Keys**
   - Log into your Paystack dashboard
   - Switch from Test mode to Live mode
   - Get your Live Secret and Public keys

2. **Update .env**
   ```
   PAYSTACK_PUBLIC_KEY=pk_live_YOUR_LIVE_PUBLIC_KEY
   PAYSTACK_SECRET=sk_live_YOUR_LIVE_SECRET_KEY
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Set Production URL**
   ```
   APP_URL=https://yourdomain.com
   ```

4. **Configure Webhook**
   - Update webhook URL to your production domain
   - Test the webhook

5. **Test Full Payment Flow**
   - Create a test order
   - Process payment with live cards
   - Verify order confirmation

## Support

For Paystack support, visit: https://support.paystack.com
For app support, contact: support@fekasclothing.com
