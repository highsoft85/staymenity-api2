<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Channels\FirebaseDatabaseChannel;
use App\Channels\FirebaseMessagesCounterChannel;
use App\Channels\FirebaseNotificationsCounterChannel;
use App\Jobs\QueueCommon;
use App\Models\Chat;
use App\Models\FirebaseNotification;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\UserChatServiceModel;
use Edujugon\PushNotification\Channels\ApnChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class HaveNewMessageNotification extends Notification
    //implements ShouldQueue
{
    //use Queueable;

    const NAME = 'messages-new';

    /**
     * @var User
     */
    private $oUser;

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var User
     */
    private $oFromUser;

    /**
     * @var Chat
     */
    private $oChat;

    /**
     * DebugNotification constructor.
     * @param User $oFromUser
     * @param Chat $oChat
     * @param string $text
     */
    public function __construct(User $oFromUser, Chat $oChat, string $text)
    {
        $this->oFromUser = $oFromUser;
        $this->oChat = $oChat;
        $this->title = $oFromUser->first_name;
        $this->message = $text;
    }

    /**
     * @return Chat
     */
    public function getChat()
    {
        return $this->oChat;
    }

    /**
     * @return array
     */
    private function data()
    {
        return [
            'type' => self::NAME,
            'title' => $this->title,
            'message' => $this->message,
            'image' => $this->oFromUser->image_square,
            'chat_id' => $this->oChat->id,
        ];
    }

    /**
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $this->oUser = $notifiable;
        return [
            'slack',
            'database',
            FirebaseNotificationsCounterChannel::class,
            FirebaseMessagesCounterChannel::class,
            ApnChannel::class,
        ];
    }

    /**
     * @return array
     */
    public function tags()
    {
        return [
            'slack',
            'database',
            'user_id:' . $this->oUser->id ?? '',
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'slack' => QueueCommon::QUEUE_NAME_NOTIFICATION,
            'database' => QueueCommon::QUEUE_NAME_NOTIFICATION,
            FirebaseDatabaseChannel::class => QueueCommon::QUEUE_NAME_NOTIFICATION,
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->from(config('logging.channels.slack-debug.username'))
            ->content(json_encode(array_merge($this->data(), [
                'to_user' => [
                    'first_name' => $notifiable->first_name,
                    'email' => $notifiable->email,
                ],
            ])));
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->data();
    }

    /**
     * @param User $notifiable
     * @return \Illuminate\Database\Eloquent\Model|FirebaseNotification
     */
    public function toFirebaseDatabase(User $notifiable)
    {
        return $notifiable->firebaseNotifications()->create([
            'type' => LeaveReviewNotification::class,
            'data' => $this->data(),
        ]);
    }

    /**
     * Только увеличение счетчика
     *
     * @param User $notifiable
     * @return User
     */
    public function toFirebaseNotificationsCounter(User $notifiable)
    {
        return $notifiable;
    }

    /**
     * Только увеличение счетчика для сообщений
     *
     * @param User $notifiable
     * @return User
     */
    public function toFirebaseMessagesCounter(User $notifiable)
    {
        return $notifiable;
    }

    /**
     * @param mixed|User $notifiable
     * @return PushMessage
     */
    public function toApn($notifiable)
    {
        return (new PushMessage())
            ->title($this->title)
            ->body($this->message)
            ->extra($this->data());
    }
}
