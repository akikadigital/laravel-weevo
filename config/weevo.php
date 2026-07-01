<?php

return [
    'env' => env('WEEVO_ENV', 'production'),

    'debug' => env('WEEVO_DEBUG', false),

    'username' => env('WEEVO_USERNAME'),
    'api_key' => env('WEEVO_API_KEY'),
    'api_secret' => env('WEEVO_API_SECRET'),

    'verify_ssl' => env('WEEVO_VERIFY_SSL', true),

    'timeout' => env('WEEVO_TIMEOUT', 30),

    'retry' => [
        'times' => env('WEEVO_RETRY_TIMES', 2),
        'sleep' => env('WEEVO_RETRY_SLEEP', 500),
    ],

    'url' => [
        'sandbox' => env('WEEVO_SANDBOX_URL', 'https://sandbox.example.com/api'),
        'production' => env('WEEVO_PRODUCTION_URL', 'https://example.com/api'),
    ],
];
