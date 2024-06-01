<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class VerifyPhoneNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var User
     */
    private $oUser;

    /**
     * @var string
     */
    private $message = '';

    /**
     * DebugNotification constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $this->oUser = $notifiable;
        return [
            'nexmo',
            'slack',
            'database',
        ];
    }

    /**
     * @return array
     */
    public function tags()
    {
        return [
            'nexmo',
            'slack',
            'database',
            'user_id:' . $this->oUser->id ?? '',
        ];
    }

    /**
     * @param mixed $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->message);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())->content($this->message);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
