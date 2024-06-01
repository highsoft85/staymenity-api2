<?php

declare(strict_types=1);

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisteredMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'register';

    /**
     * @var User|object
     */
    protected $oUser;

    /**
     * Create a new message instance.
     *
     * @param object|User $oUser
     */
    public function __construct($oUser)
    {
        $this->oUser = $oUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'Secure Your Staymenity Account';
        return $this->view('emails.auth.verify')->with([
            'title' => $title,
            'oUser' => $this->oUser,
            'token' => $this->oUser->emailConfirmToken,
        ])->subject($title);
    }
}
