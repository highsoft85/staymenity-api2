<?php

declare(strict_types=1);

namespace App\Listeners\DbSnapshots;

use App\Services\Logger\Logger;
use Spatie\DbSnapshots\Events\CreatedSnapshot;

class CreatedSnapshotListener
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
     * @param CreatedSnapshot $event
     * @return void
     */
    public function handle(CreatedSnapshot $event)
    {
        $message = 'Shapshot was created: ' . $event->snapshot->name;
        slackInfo($message);
        $logger = (new Logger())->setName('snapshots')->log();
        $logger->info($message);
    }
}
