<?php

use LaravelCentrifugo\Guards\AuthGuard;

return [

    /*
    |--------------------------------------------------------------------------
    | Centrifugo Config
    |--------------------------------------------------------------------------
    |
    |
    */
    'guard'             => AuthGuard::class,
    'domain'            => env('LARAVEL_CENTRIFUGO_DOCKER_DOMAIN'),
    'path'              => env('LARAVEL_CENTRIFUGO_PREFIX', 'laravel-centrifugo'),
    'token'             => env('CENTRIFUGO_TOKEN_HMAC_SECRET_KEY'),
    'admin_password'    => env('CENTRIFUGO_ADMIN_PASSWORD'),
    'admin_secret'      => env('CENTRIFUGO_ADMIN_SECRET'),
    'api_key'           => env('CENTRIFUGO_API_KEY'),
    'url'               => env('CENTRIFUGO_APP_DOCKER_URL', 'http://localhost:8000'),
    'allowed_headers'    => [
        'Origin',
        'User-Agent',
        'Cookie',
        'Host',
        'Authorization',
        'X-Real-Ip',
        'X-Forwarded-For',
        'X-Request-Id',
    ],
    'salt' => env('APP_NAME', 'mxSQHzjyeG'),
];
