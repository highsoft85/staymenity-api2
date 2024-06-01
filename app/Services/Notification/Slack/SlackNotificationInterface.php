<?php

declare(strict_types=1);

namespace App\Services\Notification\Slack;

interface SlackNotificationInterface
{
    /**
     * @return array
     */
    public function config(): array;

    /**
     * @param string $message
     */
    public function send(string $message): void;

    /**
     * @param string $message
     */
    public function log(string $message): void;
}
