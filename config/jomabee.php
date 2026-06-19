<?php

return [
    // Base URL of your Jomabee instance.
    'base_url' => env('JOMABEE_BASE_URL', 'https://pay.kodbee.com'),

    // Merchant credentials (dashboard → API Keys).
    'api_key' => env('JOMABEE_API_KEY'),
    'secret_key' => env('JOMABEE_SECRET_KEY'),

    // Secret used to verify incoming webhook signatures.
    'webhook_secret' => env('JOMABEE_WEBHOOK_SECRET'),

    // HTTP request timeout (seconds).
    'timeout' => (int) env('JOMABEE_TIMEOUT', 30),
];
