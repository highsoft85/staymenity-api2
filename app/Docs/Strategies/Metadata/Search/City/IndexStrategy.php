<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Search\City;

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
        return $this->route_search_city;
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
            'description' => '<b>Используется:</b><br>' .
                '- в поиске, автокомплит города' .
                '- ЛК гостя/хоста, автокомплит города для Lives in' .
                '',
            'authenticated' => false,
        ];
    }
}
