<?php

declare(strict_types=1);

namespace App\Services\Image;

class ImageSize
{
    const ORIGINAL = 'original';
    const SQUARE = 'square';
    const SQUARE_XL = 'square_xl';

    /**
     *
     */
    const XS = 'xs';
    //const SM = 'sm';
    //const MD = 'md';
    //const LG = 'lg';
    const XL = 'xl';

    /**
     *
     */
    const IMAGE_SIZE_XS_CONTENT = [
        'filter' => \App\Services\Image\Filters\SquareFilter::class,
        'options' => [
            'dimension' => '1:1',
            'size' => 20,
        ],
    ];

    /**
     *
     */
    const IMAGE_SIZE_XS_USER_CONTENT = [
        'filter' => \App\Services\Image\Filters\SquareFilter::class,
        'options' => [
            'dimension' => '1:1',
            'size' => 36,
        ],
    ];

    /**
     *
     */
    const IMAGE_SIZE_SQUARE_CONTENT = [
        'filter' => \App\Services\Image\Filters\SquareFilter::class,
        'options' => [
            'dimension' => '1:1',
            'size' => 360,
        ],
    ];

    /**
     *
     */
    const IMAGE_SIZE_SQUARE_XL_CONTENT = [
        'filter' => \App\Services\Image\Filters\SquareFilter::class,
        'options' => [
            'dimension' => '1:1',
            'size' => 720,
        ],
    ];

    /**
     *
     */
    const IMAGE_SIZE_XL_CONTENT = [
        'filter' => \App\Services\Image\Filters\SquareFilter::class,
        'options' => [
            'size' => 1280,
        ],
    ];
}
