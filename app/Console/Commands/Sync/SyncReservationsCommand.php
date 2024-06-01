<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Console\Commands\Common\CommandTrait;
use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\UserCalendar;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\HostfullyLeadsService;
use App\Services\Hostfully\HostfullyPropertiesService;
use App\Services\Hostfully\Leads\Show;
use App\Services\Hostfully\Models\Leads;
use App\Services\Logger\Logger;
use App\Services\Sync\Hostfully\Calendar\SyncToHostfullyCalendarService;
use App\Services\Sync\Hostfully\Reservation\SyncFromHostfullyReservationService;
use Illuminate\Console\Command;

class SyncReservationsCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'sync:reservations';

    /**
     * The name and signature of the console command.
     *
     * php artisan sync:reservations
     * php artisan sync:reservations --service=hostfully
     * php artisan sync:reservations --listing_id=3
     * php artisan sync:reservations --testing
     * php artisan sync:reservations --store
     * php artisan sync:reservations --from
     * php artisan sync:reservations --to
     * php artisan sync:reservations --to --listing_id=3
     * php artisan sync:reservations --to --listing_id=3 --force
     * php artisan sync:reservations --from --listing_id=4 --force
     * php artisan sync:reservations --to --listing_id=4 --reservation_id=3
     *
     * php artisan sync:reservations --from --listing_id=4 --reservation_id=44
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--service=hostfully : сервис}
        {--listing_id= : листинг}
        {--reservation_id= : бронь}
        {--from : из hostfully}
        {--to : в hostfully}
        {--force : перезаписать}
        {--first : сначала загрузить из}
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация';

    /**
     * @var bool
     */
    private bool $log = false;

    /**
     * @var bool
     */
    private bool $force  = false;

    /**
     * @var bool
     */
    private bool $first = false;

    /**
     * @var bool
     */
    private bool $reservations = false;

    /**
     * @var bool
     */
    private bool $from = false;

    /**
     * @var bool
     */
    private bool $to = false;

    /**
     * @var string
     */
    private string $service = 'hostfully';

    /**
     * @var string|int|null
     */
    private $listing_id;

    /**
     * @var string|int|null
     */
    private $reservation_id;

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
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->logger = loggerServiceSyncReservationByService($this->option('service'));

        $this->start();

        if (!config('hostfully.enabled')) {
            $this->log('SYNC ENABLED: false');
            $this->finish();
            return;
        }

        //
        $this->logger->info('data', [
            'listing_id' => $this->listing_id,
            'reservation_id' => $this->reservation_id,
            'is_from' => $this->from,
            'is_to' => $this->to,
            'is_force' => $this->force,
        ]);

        if ($this->first) {
            $this->to();
            $this->from();
            $this->finish();
            return;
        }

        if ($this->from) {
            $this->from();
        }
        if ($this->to) {
            $this->to();
        }

        $this->finish();
    }

    /**
     *
     */
    private function from()
    {
        $query = Listing::whereHas('hostfully');

        if (!is_null($this->listing_id)) {
            $query->whereIn('id', [$this->listing_id]);
        }

        /** @var Listing[] $oItems */
        $oItems = $query->get();

        //
        $this->logger->info('listings', $oItems->pluck('id')->toArray());
        $this->bar(count($oItems), true, 'Listings');

        foreach ($oItems as $oItem) {
            if (is_null($oItem->hostfully)) {
                continue;
            }
            if (is_null($oItem->user->details->hostfully_agency_uid)) {
                continue;
            }
            $oService = (new HostfullyLeadsService($oItem->user->details->hostfully_agency_uid));
            $aLeads = $oService->get($oItem->hostfully->uid);
            $aLeadsBooked = collect($aLeads)->reject(function ($item) {
                return $item[Leads::STATUS] === Leads::STATUS_BLOCKED;
            })->toArray();
            $aLeadsBlocked = collect($aLeads)->where('status', Leads::STATUS_BLOCKED)->toArray();
            $this->bar(count($aLeadsBooked), true, 'Leads');
            foreach ($aLeadsBooked as $aLead) {
                if (!is_null($this->reservation_id)) {
                    if ($this->fromSkipListingByReservationId($oItem, $aLead)) {
                        continue;
                    }
                }
                if ($aLead[Leads::STATUS] === Leads::STATUS_BLOCKED) {
                    continue;
                }
                if ($this->force) {
                    $oService->setForce();
                }
                $oService->syncFrom($aLead);
            }
            $oService = (new HostfullyLeadsService($oItem->user->details->hostfully_agency_uid));
            foreach ($aLeadsBlocked as $aLead) {
                if ($this->force) {
                    $oService->setForce();
                }
                $oService->syncCalendarFrom($aLead);
            }
            $this->fromCheckCancelled($oItem, $aLeadsBooked);
            $this->fromCheckBlockCancelled($oItem, $aLeadsBlocked);
        }
    }

    /**
     *
     */
    private function to()
    {
        $query = Listing::active()
            ->whereHas('hostfully');

        if (!is_null($this->listing_id)) {
            $query = Listing::whereHas('hostfully');
            $query->whereIn('id', [$this->listing_id]);
        }

        /** @var Listing[] $oItems */
        $oItems = $query->get();

        //
        $this->logger->info('listings', $oItems->pluck('id')->toArray());
        $this->bar(count($oItems), true, 'Listings');
        foreach ($oItems as $oItem) {
            if (is_null($oItem->user->details->hostfully_agency_uid)) {
                continue;
            }
            $query = $oItem
                ->reservations()
                ->where('source', Reservation::SOURCE_APP)
                ->futureNotBeginningAll();

            if (!is_null($this->reservation_id)) {
                $query->whereIn('id', [$this->reservation_id]);
            }

            /** @var Reservation[] $oReservations */
            $oReservations = $query->get();

            //
            $this->logger->info('reservations', $oReservations->pluck('id')->toArray());

            $this->bar(count($oReservations), true, 'Reservations');
            foreach ($oReservations as $oReservation) {
                $oService = (new HostfullyLeadsService($oItem->user->details->hostfully_agency_uid));
                if ($this->force) {
                    $oService->setForce();
                }
                $oService->syncTo($oReservation);
            }
        }
    }

    /**
     * @param Listing $oListing
     * @param array $aLead
     * @return bool
     */
    private function fromSkipListingByReservationId(Listing $oListing, array $aLead)
    {
        $query = $oListing->reservations();
        $query->where('id', $this->reservation_id);
        /** @var Reservation $oReservation */
        $oReservation = $query->first();
        if (is_null($oReservation)) {
            abort(404, 'Reservation id:' . $this->reservation_id . ' Not found');
        }
        if (is_null($oReservation->hostfully)) {
            abort(404, 'Reservation id:' . $this->reservation_id . ' Hostfully uid Not found');
        }
        if ($oReservation->hostfully->uid !== $aLead[Leads::UID]) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    private function cancelledLeadStatuses(): array
    {
        return [
            Leads::STATUS_BOOKING_CANCELLED,
            Leads::STATUS_BOOKING_CANCELLED_BY_OWNER,
            Leads::STATUS_BOOKING_CANCELLED_BY_TRAVELER,
            Leads::STATUS_BOOKING_ARCHIVED,
        ];
    }

    /**
     *
     * @param Listing $oListing
     * @param array $aLeads
     * @throws \Exception
     */
    private function fromCheckCancelled(Listing $oListing, array $aLeads)
    {
        $leads = collect($aLeads)->pluck('uid')->toArray();

        /** @var Reservation[] $oReservations */
        $oReservations = $oListing->reservations()
            ->whereHas('hostfully')
            ->active()
            ->futureNotBeginning()
            ->get();

        foreach ($oReservations as $oReservation) {
            $uid = $oReservation->hostfully->uid;
            if (!in_array($uid, $leads)) {
                try {
                    $aLead = (new Show())->__invoke($uid);
                    if (!empty($aLead) && in_array($aLead['status'], $this->cancelledLeadStatuses())) {
                        (new SyncFromHostfullyReservationService($oListing->user->details->hostfully_agency_uid, $aLead))->sync();
                    }
                } catch (\Exception $e) {
                    //
                }
            }
        }
    }

    /**
     *
     * @param Listing $oListing
     * @param array $aLeads
     * @throws \Exception
     */
    private function fromCheckBlockCancelled(Listing $oListing, array $aLeads)
    {
        $leads = collect($aLeads)->pluck('uid')->toArray();

        /** @var UserCalendar[] $oDates */
        $oDates = $oListing->calendarDates()
            ->active()
            ->whereNotNull('hostfully_reservation_id')
            ->get();

        foreach ($oDates as $oDate) {
            $oHostfully = $oDate->hostfully;
            $uid = $oHostfully->uid;
            if (!in_array($uid, $leads)) {
                try {
                    (new SyncToHostfullyCalendarService($oListing->user->details->hostfully_agency_uid, $oListing, $oDate->date_at, false))->sync();
                    $oDate->delete();
                    $oHostfully->delete();
                } catch (\Exception $e) {
                    slackInfo($e->getMessage());
                }
            }
        }
    }
}
