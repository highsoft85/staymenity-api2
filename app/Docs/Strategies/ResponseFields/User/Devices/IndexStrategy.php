<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Devices;

use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;

class IndexStrategy extends Strategy
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
        return $this->route_user_devices_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => null,
            ],
            'token' => [
                'type' => 'string',
                'description' => null,
            ],
            'type' => [
                'type' => 'string',
                'description' => null,
            ],
        ];
    }
}
