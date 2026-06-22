<?php

return [
    // Base URL of your Paydiver instance.
    'base_url' => env('PAYDIVER_BASE_URL', 'https://pay.kodbee.com'),

    // Merchant credentials (dashboard → API Keys).
    'api_key' => env('PAYDIVER_API_KEY'),
    'secret_key' => env('PAYDIVER_SECRET_KEY'),

    // Secret used to verify incoming webhook signatures.
    'webhook_secret' => env('PAYDIVER_WEBHOOK_SECRET'),

    // HTTP request timeout (seconds).
    'timeout' => (int) env('PAYDIVER_TIMEOUT', 30),
];
