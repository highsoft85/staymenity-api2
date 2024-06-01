<?php

declare(strict_types=1);

namespace App\Mail\HostRequest;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewHostRequestMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'new-host-request';

    /**
     * @var array
     */
    protected $data;

    /**
     * NewHostRequestMail constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'New host request';
        return $this->view('emails.host_request.new')->with([
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'city' => $this->data['city'],
            'type' => $this->data['type'],
        ])->subject($title);
    }
}
