<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable CAPTCHA
    |--------------------------------------------------------------------------
    */
    'enabled' => env('WIZ_CAPTCHA_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    */
    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    | cache is recommended for production. If your app uses Redis as cache,
    | CAPTCHA records will automatically use Redis.
    */
    'storage' => 'cache',

    'cache_store' => env('WIZ_CAPTCHA_CACHE_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'expire' => env('WIZ_CAPTCHA_EXPIRE', 300),

    'max_attempts' => env('WIZ_CAPTCHA_MAX_ATTEMPTS', 5),

    'case_sensitive' => false,

    /*
    |--------------------------------------------------------------------------
    | Character Set
    |--------------------------------------------------------------------------
    | Avoid confusing characters: 0/O, 1/I/l, 5/S, 8/B.
    */
    'characters' => 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789',

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'enabled' => true,
        'prefix' => 'captcha',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Presets
    |--------------------------------------------------------------------------
    */
    'presets' => [
        'default' => [
            'type' => 'text',
            'length' => 6,
            'width' => 180,
            'height' => 60,
            'font_size' => 5,
            'lines' => 5,
            'noise' => 80,
            'background' => [245, 245, 245],
        ],

        'number' => [
            'type' => 'number',
            'length' => 5,
            'width' => 160,
            'height' => 55,
            'font_size' => 5,
            'lines' => 4,
            'noise' => 60,
            'background' => [245, 245, 245],
        ],

        'math' => [
            'type' => 'math',
            'width' => 160,
            'height' => 55,
            'font_size' => 5,
            'lines' => 4,
            'noise' => 60,
            'min' => 1,
            'max' => 20,
            'operators' => ['+', '-'],
            'background' => [245, 245, 245],
        ],
    ],
];
