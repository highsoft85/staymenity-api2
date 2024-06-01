<?php

declare(strict_types=1);

namespace App\Services\Image\Filters;

use Intervention\Image\Image;

interface FilterInterface
{
    /**
     * Applies filter to given image
     *
     * @param Image $image
     * @return Image
     */
    public function applyFilter(Image $image): Image;

    /**
     * @param string $path
     * @param string $size
     * @param string $filename
     * @return Image
     */
    public function resize(string $path, string $size, string $filename): Image;
}
