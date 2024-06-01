<?php

declare(strict_types=1);

namespace App\Notifications\Debug;

use App\Jobs\QueueCommon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class DebugNotification extends DebugCommonNotification implements ShouldQueue
{
    use Queueable;

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
        parent::__construct();
        $this->message = $message;
    }

    /**
     * @param AnonymousNotifiable $notifiable
     * @return array
     */
    public function via(AnonymousNotifiable $notifiable)
    {
        return ['slack'];
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['notification', 'notification:queue', QueueCommon::QUEUE_NAME_MAIL];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param AnonymousNotifiable $notifiable
     * @return SlackMessage
     */
    public function toSlack(AnonymousNotifiable $notifiable)
    {
        return (new SlackMessage())->from($this->host)->content($this->message);
    }
}
