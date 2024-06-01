<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Image;
use App\Models\Listing;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;

class ImageTransformer
{
    /**
     * @param Image $oImage
     * @param string $size
     * @param string $model
     * @return array
     */
    public function transform(Image $oImage, string $size = ImageSize::SQUARE_XL, string $model = 'listing')
    {
        return [
            'id' => $oImage->id,
            'src' => imagePath($model, $size, $oImage, ImageType::MODEL),
            'is_main' => $oImage->is_main === 1,
        ];
    }

    /**
     * @param Image $oImage
     * @param string $size
     * @return array
     */
    public function transformListing(Image $oImage, string $size)
    {
        return $this->transform($oImage, $size, 'listing');
    }

    /**
     * @param Listing $oItem
     * @param string $size
     * @return array
     */
    public function transformListingImages(Listing $oItem, string $size = ImageSize::SQUARE_XL)
    {
        $aImages = [];
        $oImages = $oItem->modelImagesOrdered;
        foreach ($oImages as $oImage) {
            /** @var Image $oImage */
            $isMain = $oImage->is_main === 1;
            $image = (new ImageTransformer())->transformListing($oImage, $size);
            if ($isMain) {
                array_unshift($aImages, $image);
            } else {
                $aImages[] = $image;
            }
        }
        return $aImages;
    }
}
