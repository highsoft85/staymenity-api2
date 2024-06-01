<?php

declare(strict_types=1);

namespace App\Jobs;

class QueueCommon
{
    /**
     * Название очереди
     */
    const QUEUE_NAME_MAIL = 'mail';

    /**
     * Название очереди
     */
    const QUEUE_NAME_SMS = 'sms';

    /**
     *
     */
    const QUEUE_NAME_NOTIFICATION = 'notification';

    /**
     *
     */
    const QUEUE_NAME_SYNC = 'sync';

    /**
     * @return string
     */
    public static function commandMail(): string
    {
        return 'queue:listen --queue=' . self::QUEUE_NAME_MAIL;
    }

    /**
     * Плюс можно поставить проверку работает ли очередь
     *
     * @return bool
     */
    public static function commandMailIsEnabled(): bool
    {
        return config('queue.channels.' . self::QUEUE_NAME_MAIL . '.enabled');
    }

    /**
     * @return string
     */
    public static function commandNotification(): string
    {
        return 'queue:listen --queue=' . self::QUEUE_NAME_NOTIFICATION;
    }

    /**
     * @return bool
     */
    public static function commandNotificationIsEnabled(): bool
    {
        return config('queue.channels.' . self::QUEUE_NAME_NOTIFICATION . '.enabled');
    }

    /**
     * @return string
     */
    public static function commandSms(): string
    {
        return 'queue:listen --queue=' . self::QUEUE_NAME_SMS;
    }

    /**
     * @return bool
     */
    public static function commandSmsIsEnabled(): bool
    {
        return config('queue.channels.' . self::QUEUE_NAME_SMS . '.enabled');
    }

    /**
     * @return bool
     */
    public static function commandSyncIsEnabled(): bool
    {
        return config('queue.channels.' . self::QUEUE_NAME_SYNC . '.enabled');
    }

    /**
     * @return array
     */
    public function commands(): array
    {
        return [
            self::QUEUE_NAME_MAIL => self::commandMail(),
            self::QUEUE_NAME_SMS => self::commandSms(),
            self::QUEUE_NAME_NOTIFICATION => self::commandNotification(),
            self::QUEUE_NAME_SYNC => self::commandSyncIsEnabled(),
        ];
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function commandIsEnabled(string $name): bool
    {
        return config('queue.channels.' . $name . '.enabled');
    }
}
