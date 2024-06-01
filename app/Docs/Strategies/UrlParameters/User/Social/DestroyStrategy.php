<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Social;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

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
        return $this->route_user_social_destroy;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'provider' => [
                'type' => 'string',
                'description' => 'Название провайдера. Полный список в About',
                'required' => true,
                'value' => 'google',
            ],
        ];
    }
}
