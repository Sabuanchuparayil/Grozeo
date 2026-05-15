<?php

$frontendUrl = env('FRONTEND_URL', 'https://grozeo.in');
$allowedOrigins = array_filter(
    array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', $frontendUrl))),
    fn ($origin) => $origin !== ''
);

if (empty($allowedOrigins)) {
    $allowedOrigins = [$frontendUrl];
}

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Set CORS_ALLOWED_ORIGINS as a comma-separated list of domains:
    | CORS_ALLOWED_ORIGINS=https://grozeo.in,https://admin.grozeo.in
    |
    */
    'paths' => ['api/*', 'backoffice/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
        'X-CSRF-TOKEN',
        'X-Correlation-ID',
    ],

    'exposed_headers' => [
        'X-Correlation-ID',
        'X-Response-Time',
    ],

    'max_age' => 86400,

    'supports_credentials' => true,
];
