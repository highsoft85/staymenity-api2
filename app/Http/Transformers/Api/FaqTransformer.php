<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Balance;
use App\Models\Faq;
use App\Models\Review;
use App\Models\Type;
use GrahamCampbell\Markdown\Facades\Markdown;
use League\Fractal\TransformerAbstract;

class FaqTransformer extends TransformerAbstract
{
    /**
     * @param Faq $oItem
     * @return array
     */
    public function transform(Faq $oItem)
    {
        $html = Markdown::convertToHtml($oItem->description);
        $html = str_replace("\n", '', $html);
        return [
            //'id' => $oItem->id,
            'title' => $oItem->title,
            'description' => $html,
        ];
    }
}
