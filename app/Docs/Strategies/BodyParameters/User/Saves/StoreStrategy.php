<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Saves;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_saves_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'title' => [
                'description' => 'Заголовок списка',
                'required' => true,
                'value' => 'LA, swimming pool',
                'type' => 'string',
            ],
        ];
    }
}
