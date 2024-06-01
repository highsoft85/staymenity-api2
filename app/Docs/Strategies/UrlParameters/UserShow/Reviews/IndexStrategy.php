<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\UserShow\Reviews;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

class IndexStrategy extends Strategy
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
        return $this->route_user_show_reviews_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID',
                'required' => true,
                'value' => $this->factoryGuest()->id,
            ],
        ];
    }
}
