<?php

declare(strict_types=1);

namespace App\Services\Image\Filters;

use Illuminate\Support\Facades\File;

trait FilterCommonTrait
{
    /**
     * Проверить на существование директории
     * @param string $path
     */
    public function checkDirectory(string $path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 493, true);
        }
    }
}
