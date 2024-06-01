<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Verifications\Identities;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

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
        return $this->route_user_verifications_identities_destroy;
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
                'value' => User::first()->identities()->first()->id,
            ],
        ];
    }
}
