<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Chats\Messages;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\Chats\Messages\IndexRequest;
use App\Http\Transformers\Api\ChatMessageTransformer;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\User\HaveNewMessageNotification;
use App\Services\Firebase\FirebaseCounterMessagesService;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Index extends ApiController
{
    /**
     *
     */
    const LATEST_LENGTH = 10;

    /**
     * @param IndexRequest $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function __invoke(IndexRequest $request, int $id)
    {
        $oUser = $this->authUser($request);

        $data = $request->all();

        /** @var Chat|null $oChat */
        $oChat = $oUser->chatsActive()->where('id', $id)->first();
        if (is_null($oChat)) {
            return responseCommon()->apiNotFound();
        }

        // последние 10
        if (isset($data['latest']) && (int)$data['latest'] === 1) {
            $oMessages = $this->getMessagesLatest($oChat);
        } elseif (isset($data['latest_other']) && (int)$data['latest_other'] === 1) {
            $oMessages = $this->getMessagesLatestOther($oChat);
        } else {
            $oMessages = $this->getMessages($oChat);
        }
        $this->clearNotifications($oUser, $oChat);
        if (!envIsDocumentation() && !isDeveloperMode()) {
            (new FirebaseCounterMessagesService())
                ->database()
                ->setUser($oUser)
                ->setChat($oChat)
                ->clear();
        }
        $aItems = $oMessages->transform(function (ChatMessage $item) {
            return (new ChatMessageTransformer())->transform($item);
        })->toArray();
        return responseCommon()->apiDataSuccess($aItems);
    }

    /**
     * @param ChatMessage[]|mixed $oMessages
     * @return mixed
     */
    private function readMessages($oMessages)
    {
        foreach ($oMessages as $oMessage) {
            if (is_null($oMessage->read_at)) {
                $oMessage->update([
                    'read_at' => now(),
                ]);
            }
        }
        return $oMessages;
    }

    /**
     * Прочитать уведомления, которые были от этого чата, т.к. юзер их прочитал уже
     *
     * @param User $oUser
     * @param Chat $oChat
     */
    private function clearNotifications(User $oUser, Chat $oChat)
    {
        $notifications = DB::table('notifications')
            ->whereNull('read_at')
            ->where('type', HaveNewMessageNotification::class)
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $oUser->id)
            ->whereJsonContains('data', ['chat_id' => $oChat->id])
            ->get();

        $array = [];
        if ($notifications->count() !== 0) {
            foreach ($notifications as $notification) {
                $oNotification = DatabaseNotification::find($notification->id);
                if (!is_null($oNotification)) {
                    $array[] = $oNotification->id;
                    $this->setReadNotification($oUser, $oNotification);
                }
            }
        }
        if (!empty($array) && !envIsDocumentation() && !isDeveloperMode()) {
            (new FirebaseCounterNotificationsService())
                ->database()
                ->setUser($oUser)
                ->decrementCount(count($array));
        }
    }

    /**
     * @param User $oUser
     * @param DatabaseNotification $oNotification
     */
    private function setReadNotification(User $oUser, DatabaseNotification $oNotification)
    {
        $oNotification->update([
            'read_at' => now(),
        ]);
    }

    /**
     * @param Chat $oChat
     * @return ChatMessage[]|Collection
     */
    private function getMessages(Chat $oChat)
    {
        /** @var ChatMessage[] $oMessages */
        $oMessages = $oChat->messagesActiveOrdered()->get();
        $oMessages = $this->readMessages($oMessages);
        return $oMessages;
    }

    /**
     * @param Chat $oChat
     * @return ChatMessage[]|Collection
     */
    private function getMessagesLatest(Chat $oChat)
    {
        $aId = $oChat
            ->messagesActiveOrderedReverse()
            ->take(self::LATEST_LENGTH)
            ->pluck('id')->toArray();

        /** @var ChatMessage[] $oMessages */
        $oMessages = $oChat->messagesActiveOrdered()->whereIn('id', $aId)->get();
        $oMessages = $this->readMessages($oMessages);
        return $oMessages;
    }

    /**
     * @param Chat $oChat
     * @return ChatMessage[]|Collection
     */
    private function getMessagesLatestOther(Chat $oChat)
    {
        $aId = $oChat
            ->messagesActiveOrderedReverse()
            ->take(self::LATEST_LENGTH)
            ->pluck('id')->toArray();

        /** @var ChatMessage[] $oMessages */
        $oMessages = $oChat->messagesActiveOrdered()->whereNotIn('id', $aId)->get();
        $oMessages = $this->readMessages($oMessages);
        return $oMessages;
    }
}
