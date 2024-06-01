<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Chats;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class DestroyStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_METADATA;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_chats_destroy;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/chats',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => null,
            'authenticated' => true,
        ];
    }
}
