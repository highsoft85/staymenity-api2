<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Channels\FirebaseNotificationsCounterChannel;
use App\Jobs\QueueCommon;
use App\Models\FirebaseNotification;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use Edujugon\PushNotification\Channels\ApnChannel;
use Edujugon\PushNotification\Channels\FcmChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class TestPushNotification extends Notification
    //implements ShouldQueue
{
    //use Queueable;

    const NAME = 'test-push';

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
     * DebugNotification constructor.
     */
    public function __construct()
    {
        $this->title = 'Staymenity';
        $this->message = 'Test push';
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
            //FcmChannel::class,
        ];
    }

    /**
     * @return array
     */
    public function tags()
    {
        return [
            //'slack',
            //'database',
            'user_id:' . $this->oUser->id,
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
            //'slack' => QueueCommon::QUEUE_NAME_NOTIFICATION,
            //'database' => QueueCommon::QUEUE_NAME_NOTIFICATION,
            //FirebaseDatabaseChannel::class => QueueCommon::QUEUE_NAME_NOTIFICATION,
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
            ->content(json_encode($this->data()));
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
            ->title('Staymenity') // выше и жирным
            ->body($this->message) // ниже
            ->extra($this->data());
    }
//
//    /**
//     * @param mixed|User $notifiable
//     * @return PushMessage
//     */
//    public function toFcm($notifiable)
//    {
//        return (new PushMessage())
//            ->title('Staymenity')
//            ->body($this->message)
//            ->extra($this->data());
//    }
}
