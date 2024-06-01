<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Index\Payout\Connect;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class SuccessStrategy extends Strategy
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
        return $this->route_payout_connect_success;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'token' => [
                'description' => 'Token',
                'required' => true,
                'value' => '::token::',
                'type' => 'string',
            ],
        ];
    }
}
