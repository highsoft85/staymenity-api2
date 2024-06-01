<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Search\Address;

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
        return $this->route_search_address;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'search',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => 'После тогда как пользователь выберет что-то из этого поиска - отправлять ' .
                'на `/api/search/place?q={place_id}` и оттуда получить `city`, `state`, `zip`. ' .
                'Если что-то пошло не так, то ответ будет 404.' .
                '<br><br>' .
                '<b>Используется:</b><br>' .
                '- создание/редактирование листинга' .
                ''
            ,
            'authenticated' => false,
        ];
    }
}
