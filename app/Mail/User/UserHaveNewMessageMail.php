<?php

declare(strict_types=1);

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserHaveNewMessageMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'user-have-new-message';

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
        $title = 'Have a New Message';
        return $this->view('emails.user.haveNewMessage')->with([
            'title' => $title,
            'oUser' => $this->oUser,
        ])->subject($title);
    }
}
