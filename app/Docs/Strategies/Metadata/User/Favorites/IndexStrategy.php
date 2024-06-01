<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Favorites;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class IndexStrategy extends Strategy
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
        return $this->route_user_favorites_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/favorites',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Массивы с избранными, в каждом ключе находится массив, сейчас только `listings`. <br>' .
                'После авторизации пользователя, или после успешного ответа на `/user`, кидать запрос на получение этих массивов. <br>' .
                'Для каждой карточки листинга проверять, есть ли id листинга в этом массиве.<br><br>' .
                'Список всех избранных листингов смотрится через сохраненный список, т.е. по роуту GET /user/saves/{id}, где будет массив с объектами `data.listings`' .
                '',
            'authenticated' => true,
        ];
    }
}
