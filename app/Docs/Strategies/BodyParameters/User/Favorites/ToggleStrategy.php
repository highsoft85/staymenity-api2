<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Favorites;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;
use App\Models\UserSave;

class ToggleStrategy extends Strategy
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
        return $this->route_user_favorites_toggle;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'item_type' => [
                'description' => 'Тип объекта, поддерживается только `listing`',
                'required' => true,
                'value' => 'listing',
                'type' => 'string',
            ],
            'item_id' => [
                'description' => 'ID объекта, id листинга',
                'required' => true,
                'value' => $this->factoryListing(['user_id' => User::first()->id])->id,
                'type' => 'int',
            ],
            'user_save_id' => [
                'description' => 'ID списка, в который сохраняется листинг. Для удаления из избранных - можно не передавать. Листинг может быть только в одном списке.',
                'required' => false,
                'value' => $this->factoryUserSave(['user_id' => User::first()->id])->id,
                'type' => 'int',
            ],
        ];
    }
}
