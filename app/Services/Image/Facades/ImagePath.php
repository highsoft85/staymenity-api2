<?php

declare(strict_types=1);

namespace App\Services\Image\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Image\Path\ImagePathService as Image;

/**
 * @method static string default(string $key = 'default')
 * @method static string image(string $key, string $size, Image $model = null)
 * @method static string main(string $key, string $size, $model)
 * @method static string publicPath(string $key, string $size, $model)
 * @method static bool checkMain(string $key, string $size, $model)
 * @method static bool checkSize(string $key, string $size, $model)
 *
 * @see \App\Services\Image\ImagePath
 */
class ImagePath extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Image::class;
    }
}
