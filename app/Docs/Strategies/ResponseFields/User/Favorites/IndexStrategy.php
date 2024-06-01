<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Favorites;

use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_favorites_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'listings' => [
                'type' => 'array',
                'description' => 'Массив id листингов, которые находятся в списках',
            ],
        ];
    }
}
