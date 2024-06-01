<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ChangeCacheEvent;
use Illuminate\Support\Facades\Cache;

class ChangeCacheListener
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
     * @param  ChangeCacheEvent  $event
     * @return void
     */
    public function handle(ChangeCacheEvent $event)
    {
        is_null($event->tag) ? Cache::forget($event->name) : Cache::tags($event->tag)->forget($event->name);
    }
}
