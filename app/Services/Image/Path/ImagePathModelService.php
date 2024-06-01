<?php

declare(strict_types=1);

namespace App\Services\Image\Path;

use App\Models\Image;
use App\Services\Image\ImageType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagePathModelService extends ImagePathService
{

    /**
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @return array
     */
    public function getImages(string $key, string $size, ?object $model = null): array
    {
        $oImages = $model->modelImages->sortByDesc('is_main');
        $aImages = [];
        foreach ($oImages as $oImage) {
            $aImages[] = $this->image($key, $size, $oImage, ImageType::MODEL);
        }
        return $aImages;
    }

    /**
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @return array
     */
    public function getImagesWithDefault(string $key, string $size, ?object $model = null): array
    {
        $oImages = $model->modelImages->sortByDesc('is_main');
        $aImages = [];
        if (count($oImages) === 0) {
            $aImages[] = $this->imageWithDefault($key, $size, null, ImageType::MODEL);
        }
        foreach ($oImages as $oImage) {
            $aImages[] = $this->imageWithDefault($key, $size, $oImage, ImageType::MODEL);
        }
        return $aImages;
    }

    /**
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @return string
     */
    public function getMain(string $key, string $size, ?object $model = null): string
    {
        return $this->main($key, $size, $model, ImageType::MODEL);
    }

    /**
     * @param string $key
     * @param string $size
     * @param Image|null $model
     * @return string
     */
    public function getImage(string $key, string $size, Image $model = null): string
    {
        return $this->image($key, $size, $model, ImageType::MODEL);
    }

    /**
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @return bool
     */
    public function checkMainImage(string $key, string $size, ?object $model = null): bool
    {
        return $this->checkMain($key, $size, $model, ImageType::MODEL);
    }

    /**
     * @param Image $oImage
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @return bool
     */
    public function checkOriginalImage(Image $oImage, string $key, string $size, ?object $model = null): bool
    {
        return $this->checkOriginal($oImage, $key, $size, $model, ImageType::MODEL);
    }

    /**
     * @param string $key
     * @param string $size
     * @return string
     */
    public function getDefault(string $key, string $size)
    {
        return $this->default($key, $size);
    }
}
