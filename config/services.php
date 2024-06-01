<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI')
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'ios_client_id' => env('GOOGLE_IOS_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI')
    ],

    'google_maps' => [
        'enabled' => env('GOOGLE_MAPS_ENABLED', false),
        'key' => env('GOOGLE_MAPS_API_KEY', null),
    ],
    'google_analytics' => [
        'enabled' => env('GOOGLE_ANALYTICS_ENABLED', false),
        'key' => env('GOOGLE_ANALYTICS_KEY', null),
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect' => env('APPLE_REDIRECT_URI')
    ],

    'nexmo' => [
        'client_key' => env('NEXMO_KEY'),
        'client_secret' => env('NEXMO_SECRET'),
        'from' => env('NEXMO_FROM'),
    ],

    'stripe' => [
        'version' => '2020-03-02',
        'public_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'test_public_key' => env('STRIPE_TEST_PUBLISHABLE_KEY'),
        'test_secret_key' => env('STRIPE_TEST_SECRET_KEY'),
        'test_customer_id' => 'cus_H3ilXcI1KG3ILL',
        //'account_id' => env('STRIPE_ACCOUNT_ID'),
        //'client_id' => env('STRIPE_CLIENT_ID'),
        /*
         * Stripe tests send requests to the Stripe API and their
         * execution can be time consuming. We can skip these tests
         * setting 'skip_tests' to true.
         */
        'skip_tests' => env('STRIPE_SKIP_TESTS', true),
    ],

    'yandex_map' => [
        'enabled' => env('YANDEX_MAP_ENABLED', false),
        'key' => env('YANDEX_MAP_API_KEY', null),
    ],

    'ip_api' => [
        'enabled' => env('IP_API_ENABLED', false),
    ],

    'autohost' => [
        'enabled' => env('AUTOHOST_ENABLED', false),
        'key' => env('AUTOHOST_KEY', null),
        'url' => env('AUTOHOST_URL', null),
    ],
];
