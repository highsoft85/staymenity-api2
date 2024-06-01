<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Saves;

use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;

class ShowStrategy extends Strategy
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
        return $this->route_user_saves_show;
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
            'title' => [
                'type' => 'string',
                'description' => 'Заголовок списка',
            ],
            'image' => [
                'type' => 'string',
                'description' => 'URL листинга или изображение по умолчанию',
            ],
            'listings' => [
                'type' => 'array ob objects',
                'description' => 'Структура объектов такая же как и в поиске',
            ],
        ];
    }
}
