<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Devices;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Device;
use App\Models\Listing;
use App\Docs\Strategy;

class IndexStrategy extends Strategy
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
        return $this->route_user_devices_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'type' => [
                'description' => 'Тип устройства' . '<br>' .
                    '<b>Доступные:</b><br>' .
                    '• `' . Device::TYPE_WEB . '` <br>' .
                    '• `' . Device::TYPE_IOS . '` <br>' .
                    '',
                'required' => false,
                'value' => Device::TYPE_WEB,
                'type' => 'string',
            ],
        ];
    }
}
