<?php

declare(strict_types=1);

namespace App\Events;

use App\Listeners\TestListener;
use Illuminate\Queue\SerializesModels;

class TestEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     * @see TestListener
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
