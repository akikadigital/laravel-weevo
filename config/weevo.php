<?php
// Weevo API Configuration
$environment = env('WEEVO_ENV', 'sandbox');
$env_key = strtoupper($environment);

return [
    'env' => $environment, // sandbox or production
    'debug' => env('WEEVO_DEBUG', true),
    'username' => env('WEEVO_' . $env_key . '_USERNAME', ''),
    'api_key' => env('WEEVO_' . $env_key . '_API_KEY', ''),
    'api_secret' => env('WEEVO_' . $env_key . '_API_SECRET', ''),
    'url' => $environment == 'sandbox' ? 'https://weevo-api.test/v1/' : 'https://api.weeko.ke/v1/',
];
