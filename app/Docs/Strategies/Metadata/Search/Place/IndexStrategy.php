<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Search\Place;

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
        return $this->route_search_place;
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
            'description' => 'Если что-то пошло не так, то ответ будет 404.<br><br>' .
                '<b>Используется:</b><br>' .
                '- создание/редактирование листинга, после выбора адреса хостом, после `/search/address`' .
                ''
            ,
            'authenticated' => false,
        ];
    }
}
