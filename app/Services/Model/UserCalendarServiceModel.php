<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserCalendar;
use App\Services\Sync\Hostfully\Calendar\SyncToHostfullyCalendarService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserCalendarServiceModel
{
    /**
     * @var User
     */
    private $oUser;

    /**
     * @var Listing
     */
    private $oListing;

    /**
     * @var Reservation|null
     */
    private $oReservation;

    /**
     * @var HostfullyReservation|null
     */
    private $oHostfullyReservation;

    /**
     * @var bool
     */
    private $sync;

    /**
     * UserCalendarService constructor.
     * @param User $oUser
     * @param Listing $oListing
     * @param Reservation|null $oReservation
     */
    public function __construct(User $oUser, Listing $oListing, ?Reservation $oReservation = null)
    {
        $this->oUser = $oUser;
        $this->oListing = $oListing;
        $this->oReservation = $oReservation;
        $this->sync = config('hostfully.enabled');
        //$this->sync = false;
    }

    /**
     * @param HostfullyReservation $oHostfullyReservation
     * @return $this
     */
    public function setHostfullyReservation(HostfullyReservation $oHostfullyReservation)
    {
        $this->oHostfullyReservation = $oHostfullyReservation;
        return $this;
    }

    /**
     * @param string $type
     * @param Carbon $date
     */
    public function setByDate(string $type, Carbon $date)
    {
        $query = $this->oListing
            ->calendarDatesByUser($this->oUser)
            ->active();
        if (!is_null($this->oReservation)) {
            $query->where('reservation_id', $this->oReservation->id);
        }
        if (!is_null($this->oHostfullyReservation)) {
            $query->where('hostfully_reservation_id', $this->oHostfullyReservation->id);
        }
        /** @var UserCalendar|null $oDate */
        $oDate = $query
            ->where('date_at', $date)
            ->first();

        if (!is_null($oDate)) {
            // если был заблокирован, а потом available, то удаляем
            if (in_array($oDate->type, [UserCalendar::TYPE_LOCKED]) && $type === UserCalendar::TYPE_AVAILABLE) {
                $this->unlockDate($oDate);
            } else {
                $oDate->update([
                    'type' => $type,
                ]);
            }
        } else {
            if ($type !== UserCalendar::TYPE_AVAILABLE) {
                $oDate = $this->oListing->calendarDates()->create([
                    'user_id' => $this->oUser->id,
                    'reservation_id' => !is_null($this->oReservation)
                        ? $this->oReservation->id
                        : null,
                    'hostfully_reservation_id' => !is_null($this->oHostfullyReservation)
                        ? $this->oHostfullyReservation->id
                        : null,
                    'type' => $type,
                    'date_at' => $date,
                    'is_weekend' => $date->isWeekend(),
                ]);
                if (is_null($this->oHostfullyReservation)) {
                    $this->oHostfullyReservation = $this->setSync($oDate, true);
                    if (!is_null($this->oHostfullyReservation)) {
                        $oDate->update([
                            'hostfully_reservation_id' => $this->oHostfullyReservation->id,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @param UserCalendar $oDate
     * @throws \Exception
     */
    private function unlockDate(UserCalendar $oDate)
    {
        $this->setSync($oDate, false);
        try {
            $oDate->delete();
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * @param UserCalendar $oDate
     * @param bool $active
     * @return null
     * @throws \Exception
     */
    private function setSync(UserCalendar $oDate, bool $active)
    {
        if (!$this->sync) {
            return null;
        }
        if (is_null($this->oListing->hostfully)) {
            return null;
        }
        // для брони не делать синхронизацию
        if (!is_null($this->oReservation)) {
            return null;
        }
        if (is_null($oDate->hostfully_reservation_id) && !$active) {
            return null;
        }
        if (is_null($this->oListing->user->details->hostfully_agency_uid)) {
            return null;
        }
        try {
            slackInfo(['listing_id' => $this->oListing->id, 'date' => $oDate->date_at->format('Y-m-d'), 'active' => $active], 'SYNC CALENDAR DAY');
            if (!healthCheckHostfully()->isActive()) {
                slackInfo([], 'HOSTFULLY IS NOT ACTIVE');
                return null;
            }
            $oModel = (new SyncToHostfullyCalendarService($this->oListing->user->details->hostfully_agency_uid, $this->oListing, $oDate->date_at, $active))->sync();
            if (!$active) {
                $oDate->hostfully()->delete();
//                $hostfully_reservation_id = $oDate->hostfully_reservation_id;
//                $oDates = $this->oListing
//                    ->calendarDatesByUser($this->oUser)
//                    ->active()
//                    ->where('hostfully_reservation_id', $hostfully_reservation_id)
//                    ->get();
//                if (count($oDates) <= 1) {
//                    $oDate->hostfully()->delete();
//                }
            }
            return $oModel;
        } catch (\Exception $e) {
            slackInfo([$e->getMessage(), $e->getFile(), $e->getLine()]);
            return null;
        }
    }

    /**
     * @param string $type
     * @param CarbonPeriod|Carbon[] $period
     */
    public function setByPeriod(string $type, CarbonPeriod $period)
    {
        foreach ($period as $date) {
            $this->setByDate($type, $date);
        }
    }

    /**
     * @param string $action
     */
    public function setByAction(string $action)
    {
        switch ($action) {
            case 'unlock_all':
                $oDates = $this->oListing
                    ->calendarDatesByUser($this->oUser)
                    ->locked()
                    ->get();
                foreach ($oDates as $oDate) {
                    $this->unlockDate($oDate);
                }
                break;
            case 'unlock_weekdays':
                $oDates = $this->oListing
                    ->calendarDatesByUser($this->oUser)
                    ->locked()
                    ->weekdays()
                    ->get();
                foreach ($oDates as $oDate) {
                    $this->unlockDate($oDate);
                }
                break;
            case 'unlock_weekends':
                $oDates = $this->oListing
                    ->calendarDatesByUser($this->oUser)
                    ->locked()
                    ->weekends()
                    ->get();
                foreach ($oDates as $oDate) {
                    $this->unlockDate($oDate);
                }
                break;
        }
    }

    private function updateBookedCount(UserCalendar $oDate)
    {
        $oListing = $oDate->listing;
    }
}
