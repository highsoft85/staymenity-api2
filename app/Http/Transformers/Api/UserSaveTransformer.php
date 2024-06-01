<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Cmf\Project\Listing\ListingController;
use App\Models\Listing;
use App\Models\Type;
use App\Models\UserSave;
use App\Services\Image\ImageSize;
use App\Services\Image\Path\ImagePathModelService;
use League\Fractal\TransformerAbstract;

class UserSaveTransformer extends TransformerAbstract
{
    /**
     * @param UserSave $oItem
     * @return array
     */
    public function transform(UserSave $oItem)
    {
        return [
            'id' => $oItem->id,
            'title' => $oItem->title,
            'image' => $this->image($oItem),
            'count' => $oItem->listings()->active()->count(),
        ];
    }

    /**
     * @param UserSave $oItem
     * @return array
     */
    public function transformDetail(UserSave $oItem)
    {
        return [
            'id' => $oItem->id,
            'title' => $oItem->title,
            'image' => $this->image($oItem),
            'listings' => $this->listings($oItem),
        ];
    }

    /**
     * @param UserSave $oItem
     * @return string
     */
    private function image(UserSave $oItem)
    {
        $image = (new ImagePathModelService())->getDefault(ListingController::NAME, ImageSize::SQUARE);
        $oListings = $oItem->listings()->active()->get();
        foreach ($oListings as $oListing) {
            $image = $oListing->image_square;
        }
        return $image;
    }

    /**
     * @param UserSave $oItem
     * @return array
     */
    private function listings(UserSave $oItem)
    {
        return $oItem->listings()->active()->get()->transform(function (Listing $item) {
            return (new ListingTransformer())->transformCard($item);
        })->toArray();
    }
}
