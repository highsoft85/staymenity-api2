<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled sync
    |--------------------------------------------------------------------------
    */
    'enabled' => env('HOSTFULLY_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Version API
    |--------------------------------------------------------------------------
    |
    | 'v1', 'v2', 'v1-2'
    |
    */
    'api' => [
        'version' => env('HOSTFULLY_API_VERSION', 'v1-2'),

        // для v1
        'key' => env('HOSTFULLY_KEY', 'SMNT-525556398629653956395'),

        // для v2
        'token' => env('HOSTFULLY_TOKEN', 'SMNT-525556398629653956395'),

        // v1 по сути v1.5
        //'url-v1' => env('HOSTFULLY_URL', 'https://api.hostfully.com/v1'),
        'url-v1' => env('HOSTFULLY_URL', 'https://sandbox-api.hostfully.com/v1'),

        // v2
        'url-v2' => env('HOSTFULLY_URL', 'https://sandbox-api.hostfully.com/v2'),
    ],

    //'agencyUid' => env('HOSTFULLY_AGENCY_UID', '4c3d6e8c-1912-4755-8fd8-ac5cc61abe58'),
    'agencyUid' => env('HOSTFULLY_AGENCY_UID', 'd89494f8-37e5-40dd-844b-0e847b9bcee4'),

    'user' => [
        'email' => env('HOSTFULLY_USER_EMAIL', 'hostfully@admin.com'),
    ],

    'webhooks' => [
        'url' => env('API_URL') . '/api/webhooks/hostfully',
    ]
];
