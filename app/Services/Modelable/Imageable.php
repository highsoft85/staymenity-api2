<?php

declare(strict_types=1);

namespace App\Services\Modelable;

use App\Models\Image;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;
use App\Services\Image\Path\ImagePathModelService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Trait Imageable
 * @package App\Services\Modelable
 *
 * @property string $image_square
 * @property string $image_xs
 * @property string $image_xl
 * @property string $image_square_xl
 *
 * @property mixed $textImages
 * @property mixed $modelImages
 * @property mixed $modelImagesOrdered
 *
 *
 * @property mixed $imagesIdentityFront
 * @property mixed $imagesIdentityBack
 * @property mixed $imagesIdentitySelfie
 */
trait Imageable
{
    private $morphKey = 'imageable';

    /**
     * @return mixed
     */
    public function images()
    {
        return $this->morphMany(Image::class, $this->morphKey);
    }

    /**
     * @return mixed
     */
    public function modelImages()
    {
        return $this
            ->morphMany(Image::class, $this->morphKey)
            ->where('type', ImageType::MODEL);
    }

    /**
     * @return mixed
     */
    public function imagesIdentityFront()
    {
        return $this
            ->morphMany(Image::class, $this->morphKey)
            ->where('type', ImageType::IDENTITY_TYPE_FRONT);
    }

    /**
     * @return mixed
     */
    public function imagesIdentityBack()
    {
        return $this
            ->morphMany(Image::class, $this->morphKey)
            ->where('type', ImageType::IDENTITY_TYPE_BACK);
    }

    /**
     * @return mixed
     */
    public function imagesIdentitySelfie()
    {
        return $this
            ->morphMany(Image::class, $this->morphKey)
            ->where('type', ImageType::IDENTITY_TYPE_SELFIE);
    }

    /**
     * @return mixed
     */
    public function modelImagesOrdered()
    {
        return $this->modelImages()->orderBy('priority', 'desc');
    }

    /**
     * @return string
     */
    public function getImageOriginalAttribute(): string
    {
        return $this->getImageModelPath(ImageSize::ORIGINAL);
    }

    /**
     * @return string
     */
    public function getImageSquareAttribute(): string
    {
        return $this->getImageModelPath(ImageSize::SQUARE);
    }

    /**
     * @return string
     */
    public function getImageXsAttribute(): string
    {
        return $this->getImageModelPath(ImageSize::XS);
    }

    /**
     * @return string
     */
    public function getImageXlAttribute(): string
    {
        return $this->getImageModelPath(ImageSize::XL);
    }

    /**
     * @return string
     */
    public function getImageSquareXlAttribute(): string
    {
        return $this->getImageModelPath(ImageSize::SQUARE_XL);
    }

    /**
     * @param string $size
     * @return string
     */
    public function getImageModelPath(string $size = ImageSize::SQUARE): string
    {
        $model = $this->getImageTypeModel();
        return (new ImagePathModelService())->getMain($model, $size, $this);
    }

    /**
     * @param string $type
     * @return Image[]|null
     */
    public function getIdentityImagesByType(string $type)
    {
        switch ($type) {
            case ImageType::IDENTITY_TYPE_FRONT:
                return $this->imagesIdentityFront()->get();
            case ImageType::IDENTITY_TYPE_BACK:
                return $this->imagesIdentityBack()->get();
            case ImageType::IDENTITY_TYPE_SELFIE:
                return $this->imagesIdentitySelfie()->get();
        }
        return null;
    }

    /**
     * @return string
     */
    public function getImageTypeModel(): string
    {
        $sPath = get_class($this);
        $sPath = substr($sPath, strrpos($sPath, '\\') + 1);
        $sClass = $sPath . Str::studly('_controller');
        $sClass = 'App\Cmf\Project\\' . $sPath . '\\' . $sClass;
        if (class_exists($sClass)) {
            $oClass = new $sClass();
            $model = $oClass::NAME;
        } else {
            $model = get_class($this);
            $model = substr($model, strrpos($model, '\\') + 1);
            $model = strtolower($model);
        }
        return $model;
    }
}
