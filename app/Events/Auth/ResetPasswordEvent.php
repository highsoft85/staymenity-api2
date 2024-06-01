<?php

declare(strict_types=1);

namespace App\Events\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var Authenticatable|User
     */
    public $user;

    /**
     * @var string
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * @param Authenticatable|User $user
     * @param string $token
     * @return void
     */
    public function __construct($user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
