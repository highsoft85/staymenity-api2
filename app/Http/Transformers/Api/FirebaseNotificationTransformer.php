<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\FirebaseNotification;
use App\Models\Review;
use App\Models\Type;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class FirebaseNotificationTransformer extends TransformerAbstract
{
    /**
     * @param FirebaseNotification $oItem
     * @return array
     */
    public function transform(FirebaseNotification $oItem)
    {
        $data = $oItem->dataArray;
        $type = null;
        $message = null;
        if (isset($data['type'])) {
            $type = $data['type'];
            unset($data['type']);
        }
        if (isset($data['message'])) {
            $message = $data['message'];
            unset($data['message']);
        }
        return [
            'id' => $oItem->id,
            'type' => $type,
            'message' => $message,
            'extend' => $data,
            'created_at' => $oItem->created_at->toDateTimeString(),
            'created_at_formatted' => $oItem->created_at->format('m-d-Y h:m A'),
        ];
    }

    /**
     * @param Review $oItem
     * @return array
     */
    private function user(Review $oItem)
    {
        return (new UserTransformer())->transformMention($oItem->user);
    }
}
