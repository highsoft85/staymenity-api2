<?php

declare(strict_types=1);

namespace App\Events\Auth;

use App\Listeners\Auth\RegisteredListener;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class RegisteredEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Authenticatable|User
     */
    public $user;

    /**
     * Create a new event instance.
     * @see RegisteredListener
     *
     * @param Authenticatable|User $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
