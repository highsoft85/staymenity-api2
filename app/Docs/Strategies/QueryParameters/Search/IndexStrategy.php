<?php

declare(strict_types=1);

namespace App\Docs\Strategies\QueryParameters\Search;

use App\Docs\Strategy;
use App\Services\Search\SearchService;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_QUERY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_search;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            SearchService::FIELD_MODE => [
                'type' => 'string',
                'description' => 'Тип поиска. <br>Для главной страницы использовать `' . SearchService::FIELD_MODE . '=' . SearchService::MODE_NEARBY . '` для поиска по ближайшим. <br>' .
                    'Игнорируется если есть `location`, например когда пользователь разрешил использовать его текущие координаты.',
                'required' => false,
                'value' => SearchService::MODE_NEARBY,
            ],
            SearchService::FIELD_DATE => [
                'type' => 'string',
                'description' => 'Значение в формате `m-d-Y`, для `Tomorrow` ничего не передавать.',
                'required' => false,
                'value' => '10-27-2020',
            ],
            'types[]' => [
                'type' => 'array',
                'description' => 'Массив ID типов',
                'required' => false,
                'value' => '1',
            ],
            'types_all' => [
                'type' => 'int',
                'description' => 'Индикатор, что выбраны все типы. Все = 1, не все = 0.',
                'required' => false,
                'value' => 0,
            ],
            'location[latitude]' => [
                'type' => 'float',
                'description' => 'Для поиска по текущим координатам, радиус 50 миль. Обязательно, если есть location.',
                'required' => false,
                'value' => 40.7127281,
            ],
            'location[longitude]' => [
                'type' => 'float',
                'description' => 'Для поиска по текущим координатам, радиус 50 миль. Обязательно, если есть location.',
                'required' => false,
                'value' => -74.0060152,
            ],
            'map_0[latitude]' => [
                'type' => 'float',
                'description' => '<b>!!! Баг документации</b>, передавать как <b>map[0][latitude]</b>. Для поиска по карте. <br>' .
                    '<b>map[0]</b> - координаты левого верхнего угла. Обязательно, если есть `map`.',
                'required' => false,
                'value' => 40.67855511,
            ],
            'map_0[longitude]' => [
                'type' => 'float',
                'description' => '<b>!!! Баг документации</b>, передавать как <b>map[0][longitude]</b>. Для поиска по карте. <br>' .
                    '<b>map[0]</b> - координаты левого верхнего угла. Обязательно, если есть `map`.',
                'required' => false,
                'value' => -73.87516022,
            ],
            'map_1[latitude]' => [
                'type' => 'float',
                'description' => '<b>!!! Баг документации</b>, передавать как <b>map[1][latitude]</b>. Для поиска по карте. <br>' .
                    '<b>map[1]</b> - координаты правого нижнего угла. Обязательно, если есть `map`.',
                'required' => false,
                'value' => 40.61760103,
            ],
            'map_1[longitude]' => [
                'type' => 'float',
                'description' => '<b>!!! Баг документации</b>, передавать как <b>map[1][longitude]</b>. Для поиска по карте. <br>' .
                    '<b>map[1]</b> - координаты правого нижнего угла. Обязательно, если есть `map`.',
                'required' => false,
                'value' => -73.69663239,
            ],
            'price[from]' => [
                'type' => 'int',
                'description' => 'Обязательно, если есть `price`.',
                'required' => false,
                'value' => 10,
            ],
            'price[to]' => [
                'type' => 'int',
                'description' => 'Обязательно, если есть `price`.',
                'required' => false,
                'value' => 50,
            ],
            SearchService::FIELD_VERIFIED => [
                'type' => 'int',
                'description' => 'Индикатор верификации хоста. 1 - включенный, выключенный индикатор не передавать',
                'required' => false,
                'value' => 1,
            ],
            SearchService::FIELD_GUESTS_SIZE => [
                'type' => 'int',
                'description' => 'Количество гостей, выключенный индикатор не передавать',
                'required' => false,
                'value' => 2,
            ],
            SearchService::FIELD_RENT_TIME_MIN => [
                'type' => 'int',
                'description' => 'Количество часов',
                'required' => false,
                'value' => 2,
            ],
            SearchService::FIELD_HOURS => [
                'type' => 'int',
                'description' => 'Количество часов, выключенный индикатор не передавать',
                'required' => false,
                'value' => 5,
            ],
            SearchService::FIELD_AMENITIES . '[]' => [
                'type' => 'array',
                'description' => 'Массив ID услуг',
                'required' => false,
                'value' => '1',
            ],
            SearchService::FIELD_RULES . '[]' => [
                'type' => 'array',
                'description' => 'Массив ID правил',
                'required' => false,
                'value' => '1',
            ],
            SearchService::FIELD_NO_DEPOSIT => [
                'type' => 'int',
                'description' => 'Включенный = 1, выключенный индикатор не передавать',
                'required' => false,
                'value' => null,
            ],
            SearchService::FIELD_NO_CLEANING_FEE => [
                'type' => 'int',
                'description' => 'Включенный = 1, выключенный индикатор не передавать',
                'required' => false,
                'value' => null,
            ],
            SearchService::FIELD_USER_SAVE_ID => [
                'type' => 'int',
                'description' => 'Поиск по карте в сохраненном списке, отбор будет только по листингам, которые ' .
                    'есть в этом сохраненном списке',
                'required' => false,
                'value' => 1,
            ],
            SearchService::FIELD_SIMILAR_ID => [
                'type' => 'int',
                'description' => 'ID листинга, для которых необходимо выбрать листинги',
                'required' => false,
                'value' => 1,
            ],
        ];
    }
}
