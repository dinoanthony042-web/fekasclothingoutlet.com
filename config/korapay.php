<?php

return [
    'public_key' => env('KORAPAY_PUBLIC_KEY'),
    'secret_key' => env('KORAPAY_SECRET_KEY'),
    'base_url' => env('KORAPAY_BASE_URL', 'https://api.korapay.com/merchant/api/v1'),
];
