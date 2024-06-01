<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Rule;
use League\Fractal\TransformerAbstract;

class RuleTransformer extends TransformerAbstract
{
    /**
     * @param Rule $oItem
     * @return array
     */
    public function transform(Rule $oItem)
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
