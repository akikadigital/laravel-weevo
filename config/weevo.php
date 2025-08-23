<?php
return [
    'env' => env('WEEVO_ENV', 'sandbox'), // sandbox or production
    'debug' => env('WEEVO_DEBUG', true),
    'sandbox' => [
        'api_key' => env('WEEVO_SANDBOX_API_KEY', 'T3st123'),
        'api_secret' => env('WEEVO_SANDBOX_API_SECRET', 'T3st123'),
        'url' => 'https://api.weevo.ke/api/v1',
    ],
    'production' => [
        'api_key' => env('WEEVO_API_KEY', 'T3st123'),
        'api_secret' => env('WEEVO_API_SECRET', 'T3st123'),
        'url' => 'https://api.weevo.ke/api/v1',
    ],
];

/**
 * Environment Variables
 * Copy the following variables in ENV and pass values
 * WEEVO_ENV=
 * WEEVO_DEBUG=
 * WEEVO_SANDBOX_API_KEY=
 * WEEVO_SANDBOX_API_SECRET=
 * WEEVO_API_KEY=
 * WEEVO_API_SECRET=
 */
