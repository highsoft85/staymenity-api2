<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\Index;

use App\Http\Controllers\Api\Index\Data;
use App\Docs\Strategy;
use Illuminate\Http\Request;

class DataStrategy extends Strategy
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
        return $this->route_data;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
            'config' => [
                'type' => 'object',
                'description' => $this->listFields([
                    Data::CONFIG_ENV => ['string', 'Окружение API'],
                    Data::CONFIG_RESERVATION_SERVICE_FEE => ['int', 'Плата за использование сервиса'],
                    Data::CONFIG_RESERVATION_FREE_CANCELLATION => ['int', 'Бесплатная отмена бронирования'],
                    Data::CONFIG_VERIFICATION_LIFETIME => ['int', 'Время жизни SMS кода верификации, в секундах'],
                    Data::CONFIG_RESERVATION_CANCELLATION_CHARGE => ['int', 'Сумма, которую вычтет сервис, если отмена брони будет после FREE_CANCELLATION'],
                    Data::CONFIG_LOCATION_DEFAULT => ['object', 'Координаты по умолчанию, использовать когда пользователь не разрешил отслеживать свою локацию'],
                    Data::CONFIG_TIMEZONE_DEFAULT => ['string', 'Таймзона сервера'],
                    Data::CONFIG_SOCIALS => ['object', 'Социальные сети'],
                    Data::CONFIG_IDENTITY_VERIFICATION_TEXTS => ['object', 'Тексты для Identity Verification страниц'],
                    Data::CONFIG_URL => ['object', 'Ссылки на сайт'],
                    Data::CONFIG_SEARCH => ['object', 'Параметры для поиска и фильтров'],
                ], 'Дополнительные конфигурации/константы для проекта'),
            ],
            'types' => [
                'type' => 'array of objects',
                'description' => null,
            ],
            'amenities' => [
                'type' => 'array of objects',
                'description' => null,
            ],
            'rules' => [
                'type' => 'array of objects',
                'description' => null,
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new Data())->__invoke(new Request())['data'];
    }
}
