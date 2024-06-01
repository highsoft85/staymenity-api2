<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Review;
use App\Models\Type;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    /**
     * @param Review $oItem
     * @return array
     */
    public function transform(Review $oItem)
    {
        return [
            'id' => $oItem->id,
            'user' => $this->user($oItem),
            'description' => $oItem->description,
            'published_at' => $oItem->published_at->toDateTimeString(),
            'published_at_formatted' => $oItem->published_at->format('F Y'),
        ];
    }

    /**
     * @param Review $oItem
     * @param string $role
     * @return array
     */
    public function transformFromRole(Review $oItem, string $role)
    {
        return [
            'id' => $oItem->id,
            'user' => array_merge($this->user($oItem), [
                'role' => $role,
            ]),
            'description' => $oItem->description,
            'published_at' => $oItem->published_at->toDateTimeString(),
            'published_at_formatted' => $oItem->published_at->format('F Y'),
        ];
    }

    /**
     * @param Review $oItem
     * @return array
     */
    private function user(Review $oItem)
    {
        return (new UserTransformer())->transformMention($oItem->user);
    }
}
