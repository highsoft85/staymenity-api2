<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Settings\Notifications;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class UpdateStrategy extends Strategy
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
        return $this->route_user_settings_notifications_update;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'name' => [
                'description' => 'Имя настройки. Возможные варианты `mail`, `push`, `messages`',
                'required' => true,
                'value' => 'mail',
                'type' => 'string',
            ],
            'enable' => [
                'description' => 'Включить/выключить уведомление. `1` - включить, `0` - выключить',
                'required' => true,
                'value' => 1,
                'type' => 'int',
            ],
        ];
    }
}
