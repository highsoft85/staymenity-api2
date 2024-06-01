<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\TestEvent;
use App\Jobs\Mail\User\SendMailUserRecommendJob;
use App\Jobs\QueueCommon;
use App\Jobs\TestJob;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class TestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TestEvent $event
     * @return void
     */
    public function handle(TestEvent $event)
    {
        if (QueueCommon::commandSyncIsEnabled()) {
            $batch = Bus::batch([
                [new TestJob()],
            ])->then(function (Batch $batch) {
                // All jobs completed successfully...
            })->catch(function (Batch $batch, \Throwable $e) {
                // First batch job failure detected...
            })->finally(function (Batch $batch) {
                // The batch has finished executing...
            })->name('Test Job')->onQueue(QueueCommon::QUEUE_NAME_SYNC)->dispatch();
        } else {
            TestJob::dispatchNow();
        }
    }
}
