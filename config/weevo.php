<?php

return [
    'env' => env('WEEVO_ENV', 'sandbox'), // sandbox or production
    'debug' => env('WEEVO_DEBUG', true),
    'url' => [
        'production' => env('WEEVO_PRODUCTION_URL', 'https://api.weeko.ke/v1/'),
        'sandbox' => env('WEEVO_SANDBOX_URL', 'https://weevo-api.test/v1/'),
    ],
];
