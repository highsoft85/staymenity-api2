<?php

declare(strict_types=1);

namespace App\Services\Notification\Nexmo;

use App\Services\Environment;
use App\Services\Logger\Logger;
use App\Services\Notification\Slack\SlackDebugNotification;
use Nexmo\Laravel\Facade\Nexmo;

class NexmoSendNotification
{
    /**
     * @param string $phone
     * @param string $code
     */
    public function code(string $phone, string $code): void
    {
        if (config('nexmo.enabled')) {
            try {
                loggerNexmo(['phone' => $phone, 'code' => $code], 'SEND CODE');
                Nexmo::message()->send([
                    'to' => $phone,
                    'from' => config('services.nexmo.from'),
                    'text' => 'Staymenity code: ' . $code,
                ]);
                loggerNexmo([], 'SEND CODE SUCCESS');
            } catch (\Exception $e) {
                loggerNexmo(['message' => $e->getMessage()], 'SEND CODE ERROR');
                abort(404, $e->getMessage());
            }
        }
    }
}
