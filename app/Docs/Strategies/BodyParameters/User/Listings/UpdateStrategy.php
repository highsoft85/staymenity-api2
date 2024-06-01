<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Listings;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Services\Model\ListingServiceModel;

class UpdateStrategy extends Strategy
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
        return $this->route_user_listings_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'amenities' => [
                'description' => 'Массив ID, если есть кастомная, то `id` услуги с `name=other` должен тоже передаваться',
                'required' => false,
                'value' => [1],
                'type' => 'array',
            ],
            'amenities_other' => [
                'description' => 'Кастомная услуга, чтобы сохранить её - должен быть выбран `amenities[]` с `name = other`',
                'required' => false,
                'value' => 'Amenity',
                'type' => 'string',
            ],
            'rules' => [
                'description' => 'Массив ID, если есть кастомная, то `id` правила с `name=other` должен тоже передаваться',
                'required' => false,
                'value' => [1],
                'type' => 'array',
            ],
            'rules_other' => [
                'description' => 'Кастомное правило, чтобы сохранить его - должен быть выбран `rules[]` с `name = other`',
                'required' => false,
                'value' => 'Rule',
                'type' => 'string',
            ],
            'description' => [
                'description' => 'Описание листинга',
                'required' => false,
                'value' => 'Description',
                'type' => 'string',
            ],
            'title' => [
                'description' => 'Заголовок листинга',
                'required' => false,
                'value' => 'Title',
                'type' => 'string',
            ],
            'price' => [
                'description' => 'Цена',
                'required' => false,
                'value' => 28,
                'type' => 'int',
            ],
            'deposit' => [
                'description' => null,
                'required' => false,
                'value' => 28,
                'type' => 'int',
            ],
            'cleaning_fee' => [
                'description' => null,
                'required' => false,
                'value' => 28,
                'type' => 'int',
            ],
            'address_two' => [
                'description' => 'Второй адрес строкой. Все что пользователь введет',
                'required' => false,
                'value' => '308 Taylor Avenue, New York, NY 10473, United States of America',
                'type' => 'string',
            ],
            'images' => [
                'description' => 'Массив бинарных файлов',
                'required' => false,
                'value' => storage_path('tests/listings/barbecue.jpg'),
                'type' => 'file',
            ],
            'image_set_main' => [
                'description' => 'Сохранить первую загруженную фотку главной. Для отдельной загрузки баннера, при этом `images` так же отправляется массивом, только там только одно изображение должно быть',
                'required' => false,
                'value' => 1,
                'type' => 'int',
            ],
            'times[weekdays][0][from]' => [
                'description' => 'Дата с отдельного тайм-слота во вкладке `weekdays`, 0 - номер элемента. Обязателен, когда есть `to`. <br> Слоты перезаписываются каждый раз.',
                'required' => false,
                'value' => '03:03 PM',
                'type' => 'string',
            ],
            'times[weekdays][0][to]' => [
                'description' => 'Дата с отдельного тайм-слота во вкладке `weekdays`, 0 - номер элемента. Обязателен, когда есть `from`.',
                'required' => false,
                'value' => '03:04 PM',
                'type' => 'string',
            ],
            'times[weekends][0][from]' => [
                'description' => 'Дата с отдельного тайм-слота во вкладке `weekends`, 0 - номер элемента. Обязателен, когда есть `to`.',
                'required' => false,
                'value' => '03:03 PM',
                'type' => 'string',
            ],
            'times[weekends][0][to]' => [
                'description' => 'Дата с отдельного тайм-слота во вкладке `weekends`, 0 - номер элемента. Обязателен, когда есть `from`.',
                'required' => false,
                'value' => '03:04 PM',
                'type' => 'string',
            ],
            'status' => [
                'description' => 'Сохранить статус листингу, возможные варианты: `' . ListingServiceModel::STATUS_UNLIST . '`, `' . ListingServiceModel::STATUS_PUBLISH . '`',
                'required' => false,
                'value' => ListingServiceModel::STATUS_UNLIST,
                'type' => 'string',
            ],
        ];
    }
}
