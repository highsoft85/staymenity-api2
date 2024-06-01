<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Chat;
use App\Models\ChatMessage;

class ChatMessageTransformer
{
    /**
     * @param ChatMessage $oItem
     * @return array
     */
    public function transform(ChatMessage $oItem)
    {
        return [
            'id' => $oItem->id,
            'user_id' => $oItem->userTrashed->id,
            'name' => $oItem->userTrashed->first_name,
            'image' => $oItem->userTrashed->image_square,
            'text' => $oItem->text,
            'send_at' => $oItem->send_at->toIso8601String(),
        ];
    }
}
