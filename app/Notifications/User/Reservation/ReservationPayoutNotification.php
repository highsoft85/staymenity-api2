<?php

declare(strict_types=1);

namespace App\Notifications\User\Reservation;

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

class ReservationPayoutNotification extends Notification
    //implements ShouldQueue
{
    //use Queueable;

    const NAME = 'reservation-payout';

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
     * @var Reservation
     */
    private $oReservation;

    /**
     * DebugNotification constructor.
     * @param Reservation $oReservation
     */
    public function __construct(Reservation $oReservation)
    {
        $this->oReservation = $oReservation;
        $this->title = 'New payout';
        $this->message = 'You have a new payout. Amount: $' . number_format($oReservation->price, 2);
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
            'reservation_id' => $this->oReservation->id,
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
