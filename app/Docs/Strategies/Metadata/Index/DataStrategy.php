<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\Index;

use App\Docs\Strategy;

class DataStrategy extends Strategy
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
        return $this->route_data;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'groupName' => 'index',
            'groupDescription' => null,
            'title' => 'api/data',
            'description' => 'Данные кешируются для ускорения ответа. <br>Рекомендуется выполнить этот запрос при загрузки приложения, а после сохранить данные в какое-нибудь хранилище.',
            'authenticated' => false,
        ];
    }
}
