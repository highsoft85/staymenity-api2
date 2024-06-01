<?php

declare(strict_types=1);

namespace App\Mail\Listing;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewListingMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'listing-new';

    private $oListing = null;

    /**
     * NewListingMail constructor.
     * @param Listing $oListing
     */
    public function __construct(Listing $oListing)
    {
        $this->oListing = $oListing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'New Listing';
        return $this->view('emails.listing.newListing')->with([
            'title' => $title,
            'oListing' => $this->oListing,
        ])->subject($title);
    }
}
