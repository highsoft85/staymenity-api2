<?php

declare(strict_types=1);

namespace App\Events\Webhook;

use App\Listeners\Auth\RegisteredListener;
use App\Listeners\Webhook\WebhookSyncToListener;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class WebhookSyncToEvent
{
    use SerializesModels;

    /**
     * @var User
     */
    public User $oUser;

    /**
     * @var bool
     */
    public bool $status;

    /**
     * Create a new event instance.
     * @see WebhookSyncToListener
     *
     * @param User $oUser
     * @param bool $status
     * @return void
     */
    public function __construct(User $oUser, bool $status)
    {
        $this->oUser = $oUser;
        $this->status = $status;
    }
}
