<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Saves;

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
        return $this->route_user_saves_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
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
            'count' => [
                'type' => 'int',
                'description' => 'Количество листингов в этом списке',
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new UserSaveTransformer())->transform($this->factoryUserSave([
            'user_id' => User::first()->id,
        ]));
    }
}
