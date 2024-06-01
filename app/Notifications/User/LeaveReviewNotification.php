<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Channels\FirebaseDatabaseChannel;
use App\Channels\FirebaseNotificationsCounterChannel;
use App\Jobs\QueueCommon;
use App\Models\FirebaseNotification;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\ReviewServiceModel;
use Edujugon\PushNotification\Channels\ApnChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class LeaveReviewNotification extends Notification
    //implements ShouldQueue
{
    //use Queueable;

    const NAME = 'reservation-review';

    /**
     * @var User
     */
    private $oUser;

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var User
     */
    private $oFromUser = null;

    /**
     * @var Reservation
     */
    private $oReservation = null;

    /**
     * DebugNotification constructor.
     * @param Reservation $oReservation
     * @param string $type
     * @param User $oFromUser
     */
    public function __construct(Reservation $oReservation, string $type, User $oFromUser)
    {
        $this->oReservation = $oReservation;
        $this->type = $type;
        $this->oFromUser = $oFromUser;
        $this->title = 'Leave review';
        $this->message = (new ReviewServiceModel())->getMessageByType($this->oFromUser, $this->type);
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
            'reservation_id' => $this->oReservation->id,
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
            //FirebaseDatabaseChannel::class,
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
