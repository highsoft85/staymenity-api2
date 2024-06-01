<?php

declare(strict_types=1);

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'reset_password';

    /**
     * @var User|object
     */
    protected $oUser;

    /**
     * @var string
     */
    private $token;

    /**
     * Create a new message instance.
     *
     * @param object|User $oUser
     * @param string $token
     * @return void
     */
    public function __construct($oUser, string $token)
    {
        $this->oUser = $oUser;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'Reset link';
        return $this->view('emails.auth.reset_password')->with([
            'title' => $title,
            'oUser' => $this->oUser,
            //'token' => $this->oUser->emailConfirmToken,
            'token' => $this->token,
        ])->subject($title);
    }
}
