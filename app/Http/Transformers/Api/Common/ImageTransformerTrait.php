<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api\Common;

use App\Http\Transformers\Api\ImageTransformer;
use App\Models\Image;
use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;

trait ImageTransformerTrait
{
    /**
     * @param Listing $oItem
     * @param string $size
     * @return array
     */
    protected function listingImages(Listing $oItem, string $size = ImageSize::SQUARE_XL)
    {
        return (new ImageTransformer())->transformListingImages($oItem, $size);
    }

    /**
     * @param User $oItem
     * @return bool
     */
    protected function hasUserImage(User $oItem)
    {
        return $oItem->modelImages()->count() !== 0;
    }
}
