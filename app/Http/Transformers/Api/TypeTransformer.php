<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Type;
use League\Fractal\TransformerAbstract;

class TypeTransformer extends TransformerAbstract
{
    /**
     * @param Type $oItem
     * @return array
     */
    public function transform(Type $oItem)
    {
        return [
            'id' => $oItem->id,
            'name' => $oItem->name,
            'title' => $oItem->title,
        ];
    }

    /**
     * @param Type $oItem
     * @param string|null $title
     * @return array
     */
    public function transformOtherType(Type $oItem, ?string $title)
    {
        return [
            'id' => $oItem->id,
            'name' => $oItem->name,
            'title' => $title ?? 'Other',
        ];
    }
}
