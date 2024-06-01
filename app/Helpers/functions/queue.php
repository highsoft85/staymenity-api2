<?php

declare(strict_types=1);

use App\Jobs\QueueCommon;
use App\Jobs\Webhook\WebhookSyncToJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;

if (!function_exists('eventSyncQueue')) {
    /**
     * @param string $class
     * @param string $title
     * @param ...$args
     */
    function eventSyncQueue(string $class, string $title, ...$args)
    {
        if (QueueCommon::commandSyncIsEnabled()) {
            try {
                Bus::batch([
                    new $class($args),
                ])->then(function (Batch $batch) {
                    // All jobs completed successfully...
                })->catch(function (Batch $batch, Throwable $e) {
                    // First batch job failure detected...
                })->finally(function (Batch $batch) {
                    // The batch has finished executing...
                })->onQueue(QueueCommon::QUEUE_NAME_SYNC)
                    ->name($title)
                    ->dispatch();
            } catch (Throwable $e) {
                //
            }
        } else {
            $class::dispatchNow($args);
        }
    }
}
