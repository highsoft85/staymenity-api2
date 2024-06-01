<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Chats;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserSave;

class DestroyStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_URL_PARAMETERS;

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
            'id' => [
                'type' => 'string',
                'description' => 'ID чата',
                'required' => true,
                'value' => 1,
            ],
        ];
    }
}
