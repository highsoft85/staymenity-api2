<?php

declare(strict_types=1);

namespace App\Services\Notification\Slack;

use App\Jobs\QueueCommon;
use App\Notifications\Debug\DebugNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SlackDebugNotification extends SlackCommonNotification implements SlackNotificationInterface
{
    /**
     *
     */
    private const STACK = 'slack-debug';

    /**
     * @return array
     */
    public function config(): array
    {
        return config('logging.channels.' . self::STACK);
    }

    /**
     * @param string $message
     */
    public function send(string $message): void
    {
        if (!checkEnv($this->env())) {
            return;
        }
        if (!config('logging.channels.slack-debug.enabled')) {
            return;
        }
        Notification::route('slack', $this->channel())->notifyNow((new DebugNotification($message)));
            //->onQueue(QueueCommon::QUEUE_NAME_NOTIFICATION));
    }

    /**
     * @param string $message
     */
    public function log(string $message): void
    {
        if (!checkEnv($this->env())) {
            return;
        }
        Log::channel(self::STACK)->debug($message);
    }

    /**
     * @param string $message
     */
    public function error(string $message): void
    {
        if (!checkEnv($this->env())) {
            return;
        }
        Log::channel(self::STACK)->error($message);
    }
}
