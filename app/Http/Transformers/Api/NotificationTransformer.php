<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Review;
use App\Models\Type;
use App\Models\User;
use App\Services\Model\NotificationServiceModel;
use Carbon\Carbon;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    /**
     * @param DatabaseNotification $oItem
     * @return array
     */
    public function transform(DatabaseNotification $oItem)
    {
        $data = $oItem->data;
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
            //'created_at' => $oItem->created_at->toDateTimeString(),
            'created_at' => $oItem->created_at->toIso8601String(),
            'created_at_formatted' => (new NotificationServiceModel())
                ->getCreatedAtFormattedByUserTimezone($oItem, null),
        ];
    }

    /**
     * @param DatabaseNotification $oItem
     * @param User $oUser
     * @return array
     */
    public function transformByUser(DatabaseNotification $oItem, User $oUser)
    {
        $aItem = $this->transform($oItem);
        $aItem['created_at_formatted'] = (new NotificationServiceModel())
            ->getCreatedAtFormattedByUserTimezone($oItem, $oUser);
        return $aItem;
    }
}
