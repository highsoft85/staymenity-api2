<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Favorites;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class ToggleStrategy extends Strategy
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
        return $this->route_user_favorites_toggle;
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
            'description' => 'Используется как для добавления, так и для удаления.<br>' .
                'Если у пользователя нет `saves`, то открывать окно с добавлением списка для избранных, ' .
                'после добавления списка сразу кидать запрос на `' . $this->url($this->route) . '` с `id`, который вернется после добавления в `data`. ' .
                ' Далее рекомендуется обновить данные юзера, чтобы подтянулись `saves`<br><br>' .
                '<b>Логика:</b><br>' .
                '- если id этого листинга нет в `favorites.listings`, то по клику показывать модальное окно с выбором сохраненного списка <br>' .
                '- дальше кидать на этот запрос с `user_save_id` с id выбранного списка <br>' .
                '- если id этого листинга есть в `favorites.listings`, то кидать на этот запрос можно <b>БЕЗ</b> `user_save_id`<br>' .
                '<br>' .
                'После всех манипуляций делать обновление `/api/user/favorites`' .
                ''
            ,
            'authenticated' => true,
        ];
    }
}
