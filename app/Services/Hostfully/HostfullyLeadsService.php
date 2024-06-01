<?php

declare(strict_types=1);

namespace App\Services\Hostfully;

use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Services\Hostfully\Leads\Index;
use App\Services\Hostfully\Leads\Show;
use App\Services\Sync\Hostfully\Calendar\SyncFromHostfullyCalendarService;
use App\Services\Sync\Hostfully\Calendar\SyncToHostfullyCalendarService;
use App\Services\Sync\Hostfully\Reservation\SyncFromHostfullyReservationService;
use App\Services\Sync\Hostfully\Reservation\SyncToHostfullyReservationService;
use Carbon\Carbon;

class HostfullyLeadsService
{
    /**
     * @var bool
     */
    private bool $force = false;

    /**
     * @var string|null
     */
    private $agencyUid = null;

    /**
     * HostfullyLeadsService constructor.
     * @param string $agencyUid
     */
    public function __construct(string $agencyUid)
    {
        $this->agencyUid = $agencyUid;
    }

    /**
     *
     */
    public function setForce()
    {
        $this->force = true;
    }

    /**
     * @param string $propertyUid
     * @return array
     */
    public function get(string $propertyUid): array
    {
        return (new Index())->__invoke($this->agencyUid, $propertyUid);
    }

    /**
     * @param string $uid
     * @return array
     */
    public function show(string $uid): array
    {
        return (new Show())->__invoke($uid);
    }

    /**
     * @param array $data
     * @return HostfullyReservation
     */
    public function syncFrom(array $data): HostfullyReservation
    {
        $oService = (new SyncFromHostfullyReservationService($this->agencyUid, $data));
        if ($this->force) {
            $oService->setForce();
        }
        return $oService->sync();
    }

    /**
     * @param Reservation $oItem
     * @return HostfullyReservation
     */
    public function syncTo(Reservation $oItem): HostfullyReservation
    {
        $oService = (new SyncToHostfullyReservationService($this->agencyUid, $oItem));
        if ($this->force) {
            $oService->setForce();
        }
        return $oService->sync();
    }

    /**
     * @param array $data
     */
    public function syncCalendarFrom(array $data)
    {
        $oService = (new SyncFromHostfullyCalendarService($this->agencyUid, $data));
        if ($this->force) {
            $oService->setForce();
        }
        return $oService->sync();
    }

    /**
     * @param Listing $oListing
     * @param Carbon $date
     * @param bool $active
     */
    public function syncCalendarTo(Listing $oListing, Carbon $date, bool $active)
    {
        $oService = (new SyncToHostfullyCalendarService($this->agencyUid, $oListing, $date, $active));
        if ($this->force) {
            $oService->setForce();
        }
        return $oService->sync();
    }
}
