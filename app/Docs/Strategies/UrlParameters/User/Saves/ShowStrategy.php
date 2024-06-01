<?php

declare(strict_types=1);

namespace App\Docs\Strategies\UrlParameters\User\Saves;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserSave;

class ShowStrategy extends Strategy
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
        return $this->route_user_saves_show;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID списка',
                'required' => true,
                'value' => $this->factoryUserSave(['user_id' => User::first()->id])->id,
            ],
        ];
    }
}
