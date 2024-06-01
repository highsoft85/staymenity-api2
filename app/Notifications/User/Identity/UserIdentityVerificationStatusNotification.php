<?php

declare(strict_types=1);

namespace App\Notifications\User\Identity;

use App\Channels\FirebaseDatabaseChannel;
use App\Channels\FirebaseMessagesCounterChannel;
use App\Channels\FirebaseNotificationsCounterChannel;
use App\Jobs\QueueCommon;
use App\Models\Chat;
use App\Models\FirebaseNotification;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Model\UserChatServiceModel;
use Edujugon\PushNotification\Channels\ApnChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class UserIdentityVerificationStatusNotification extends Notification
    //implements ShouldQueue
{
    //use Queueable;

    const NAME = 'identity-verification-status';

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
     * @var UserIdentity
     */
    private $oUserIdentity;

    /**
     * DebugNotification constructor.
     * @param UserIdentity $oUserIdentity
     */
    public function __construct(UserIdentity $oUserIdentity)
    {
        $this->oUserIdentity = $oUserIdentity;
        $this->title = 'New Identity Verification status';
        $this->message = 'New Identity Verification status: ' . $oUserIdentity->statusText;
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
            'user_identity_id' => $this->oUserIdentity->id,
            'image' => config('staymenity.avatar'),
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
            FirebaseNotificationsCounterChannel::class => QueueCommon::QUEUE_NAME_NOTIFICATION,
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
                'user' => [
                    'id' => $notifiable->id,
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
