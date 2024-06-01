<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Amenity;
use League\Fractal\TransformerAbstract;

class AmenityTransformer extends TransformerAbstract
{
    /**
     * @param Amenity $oItem
     * @return array
     */
    public function transform(Amenity $oItem)
    {
        return [
            'id' => $oItem->id,
            'name' => $oItem->name,
            'title' => $oItem->title,
            'icon' => $oItem->iconSvg,
            'icon_png_light' => $oItem->iconPngLight,
            'icon_png_dark' => $oItem->iconPngDark,
        ];
    }
}
