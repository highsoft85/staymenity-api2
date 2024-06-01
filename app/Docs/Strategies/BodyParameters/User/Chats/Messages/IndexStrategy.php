<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Chats\Messages;

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
        return $this->route_user_chats_messages_index;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'latest' => [
                'description' => 'Загрузить 10 последних сообщений, 1 - активно',
                'required' => false,
                'value' => 1,
                'type' => 'int',
            ],
        ];
    }
}
