<?php

declare(strict_types=1);

namespace App\Console\Commands\Listing;

use App\Console\Commands\Common\CommandTrait;
use App\Mail\Listing\NewListingMail;
use App\Mail\Listing\NewListingsMail;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Logger\Logger;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\ReviewServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ListingNewMailCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'listing:new-mail';

    /**
     * The name and signature of the console command.
     *
     * php artisan listing:new-mail
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Уведомление о новых листингах';

    /**
     * @var bool
     */
    private $log = false;

    /**
     * @var Logger|null
     */
    private $logger = null;

    /**
     * CheckQueuesCommand constructor.
     */
    public function __construct()
    {
        @parent::__construct();
        $this->logger = (new Logger())->setName('listing')->log();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->start();

        /** @var Listing[] $oListings */
        $oListings = Listing::where('created_at', '>=', now()->subHour())->get();

        $bar = $this->bar(count($oListings));
        if (count($oListings) !== 0) {
            slackInfo($oListings->pluck('id')->toArray(), 'NEW LISTINGS NOTIFICATION');
            if (config('staymenity.new_listing_enabled')) {
                $emails = explode(',', config('staymenity.new_listing_recipients'));
                foreach ($emails as $recipient) {
                    Mail::to($recipient)->send(new NewListingsMail($oListings));
                }
            }
        }

        $this->finish();
    }
}
