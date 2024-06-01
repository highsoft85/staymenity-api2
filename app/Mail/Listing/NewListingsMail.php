<?php

declare(strict_types=1);

namespace App\Mail\Listing;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewListingsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *
     */
    const NAME = 'listings-new';

    /**
     * @var Listing[]
     */
    private $oListings = null;

    /**
     * NewListingMail constructor.
     * @param Listing[] $oListing
     */
    public function __construct($oListings)
    {
        $this->oListings = $oListings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = 'New Listings on Staymenity';
        return $this->view('emails.listing.newListings')->with([
            'title' => $title,
            'oListings' => $this->oListings,
        ])->subject($title);
    }
}
