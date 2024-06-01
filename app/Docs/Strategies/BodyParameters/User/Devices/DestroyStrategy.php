<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Devices;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Device;
use App\Models\Listing;
use App\Docs\Strategy;

class DestroyStrategy extends Strategy
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
        return $this->route_user_devices_destroy;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'token' => [
                'description' => 'Токен устройства',
                'required' => true,
                'value' => '::token::',
                'type' => 'string',
            ],
            'type' => [
                'description' => 'Тип устройства' . '<br>' .
                    '<b>Доступные:</b><br>' .
                    '• `' . Device::TYPE_WEB . '` <br>' .
                    '• `' . Device::TYPE_IOS . '` <br>' .
                    '',
                'required' => true,
                'value' => Device::TYPE_WEB,
                'type' => 'string',
            ],
        ];
    }
}
