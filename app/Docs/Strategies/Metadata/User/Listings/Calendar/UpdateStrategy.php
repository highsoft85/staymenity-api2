<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Listings\Calendar;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class UpdateStrategy extends Strategy
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
        return $this->route_user_listings_calendar_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'user/listings/calendar',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'Сохранение изменений для календаря. <br>' .
                'Думаю по клику на дату делать проверку, если у даты `type=available`, то делать запрос c `type=locked` <br>' .
                'Если у даты `type=locked`, то делать запрос c `type=available` <br>' .
                'Пусть `booked` становятся только после бронирования через систему и хост не правил это.<br><br>' .
                'После успешного ответа можно подгрузить заново `/user/listings/{id}/calendar` и перерендерить ' .
                    'шаблон, если это не сильно дергать будет код, как вариант прелоадер кидать.<br><br>' .
                'Экшены отправлять только с одним `action`, тоже после успешного ответа можно подтягивать заново.' .
                '',
            'authenticated' => true,
        ];
    }
}
