<?php
// Weevo API Configuration
$environment = env('WEEVO_ENV', 'sandbox');
$env_key = strtoupper($environment);

return [
    'env' => $environment, // sandbox or production
    'debug' => env('WEEVO_DEBUG', true),
    'username' => env('WEEVO_' . $env_key . '_USERNAME', 'sandbox_user'),
    'api_key' => env('WEEVO_' . $env_key . '_API_KEY', 'T3st123'),
    'api_secret' => env('WEEVO_' . $env_key . '_API_SECRET', 'T3st123'),
    'url' => env('WEEVO_' . $env_key . '_URL', 'https://api.weevo.ke/api/v1'),
];

/**
 * Environment Variables
 * Copy the following variables in ENV and pass values
 * WEEVO_ENV=
 * WEEVO_DEBUG=
 * WEEVO_SANDBOX_USERNAME=
 * WEEVO_SANDBOX_API_KEY=
 * WEEVO_SANDBOX_API_SECRET=
 * WEEVO_SANDBOX_URL=
 * WEEVO_PRODUCTION_USERNAME=
 * WEEVO_PRODUCTION_API_KEY=
 * WEEVO_PRODUCTION_API_SECRET=
 * WEEVO_PRODUCTION_URL=
 */
