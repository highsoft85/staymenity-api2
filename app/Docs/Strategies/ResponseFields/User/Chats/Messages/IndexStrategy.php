<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Chats\Messages;

use App\Http\Transformers\Api\ChatMessageTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Chat;
use App\Models\ChatMessage;
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
        return $this->route_user_chats_messages_index;
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
                'description' => 'ID чата',
            ],
            'user_id' => [
                'type' => 'int',
                'description' => 'ID юзера кто написал',
            ],
            'name' => [
                'type' => 'string',
                'description' => 'Имя кто написал',
            ],
            'image' => [
                'type' => 'string',
                'description' => 'Изображение кто написал',
            ],
            'text' => [
                'type' => 'string',
                'description' => 'Текст сообщения',
            ],
            'send_at' => [
                'type' => 'string',
                'description' => 'Дата отправки в формате 2019-02-01T03:45:27+00:00 по ISO_8601, на выходе преобразовывать в локальную дату, например moment("2019-02-01T03:45:27+00:00", moment.ISO_8601)->format("hh:mm");',
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        /** @var Chat $oChat */
        $oChat = $this->user()->chatsActive()->first();
        /** @var ChatMessage $oMessage */
        $oMessage = $oChat->messagesActive()->first();
        return (new ChatMessageTransformer())->transform($oMessage);
    }
}
