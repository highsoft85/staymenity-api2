<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'url' => env('IMAGE_URL', ''),

    'hash' => env('IMAGE_HASH', ''),

    'public_directory' => env('IMAGE_DIRECTORY', 'storage/images'),

    'testing_directory' => env('IMAGE_TESTING_DIRECTORY', 'storage/testing/images'),
];
