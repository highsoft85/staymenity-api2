<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\Calendar;

use App\Models\HostfullyListing;
use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\UserCalendar;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\Leads\Destroy;
use App\Services\Hostfully\Leads\Show;
use App\Services\Hostfully\Leads\Store;
use App\Services\Hostfully\Leads\Update;
use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Transformers\LeadTransformer;
use Carbon\Carbon;

class SyncToHostfullyCalendarService
{
    /**
     * @var Listing
     */
    private $oListing;

    /**
     * @var Carbon
     */
    private $date;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var bool
     */
    private bool $force = false;

    /**
     * @var string|null
     */
    private $agencyUid = null;

    /**
     * SyncHostfullyListingService constructor.
     * @param string $agencyUid
     * @param Listing $oListing
     * @param Carbon $date
     * @param bool $active
     */
    public function __construct(string $agencyUid, Listing $oListing, Carbon $date, bool $active)
    {
        $this->agencyUid = $agencyUid;
        $this->date = $date;
        $this->active = $active;
        $this->oListing = $oListing;
    }

    /**
     *
     */
    public function setForce()
    {
        $this->force = true;
    }

    /**
     * @return HostfullyReservation|null
     * @throws \Exception
     */
    public function sync()
    {
        $query = $this->oListing
            ->calendarDatesByUser($this->oListing->user)
            ->active();
        /** @var UserCalendar|null $oDate */
        $oDate = $query
            ->where('date_at', $this->date)
            ->first();

        if (is_null($oDate)) {
            throw new \Exception('Empty date.');
        }

        $oModel = $oDate->hostfully;
        if (is_null($oModel)) {
            if (!is_null($oDate->hostfully_reservation_id)) {
                $oDate->delete();
                return null;
            } else {
                $oModel = $this->store();
            }
        } else {
            if ($this->active) {
                //$this->update($oModel->uid);
            } else {
                $this->destroy($oModel->uid);
            }
        }
        return $oModel;
    }

    /**
     * @return HostfullyReservation
     */
    private function store()
    {
        $data = $this->data();
        $data = array_merge($data, [
            Leads::LEAD_TYPE => Leads::TYPE_BLOCK,
            Leads::STATUS => Leads::STATUS_BLOCKED,
        ]);
        $data = (new LeadTransformer())->transformFromV2ToV1CreateBlock($data);
        $return = (new Store())->__invoke($data);
//
//        $return = (new Update())->__invoke($return[Leads::UID], (new LeadTransformer())->transformFromV2ToV1Update([
//            Leads::LEAD_TYPE => Leads::TYPE_BLOCK,
//            Leads::STATUS => Leads::STATUS_BLOCKED,
//        ]));
        $oModel = $this->saveData($return);
        return $oModel;
    }

    /**
     * @param string $uid
     * @return array
     */
    private function update(string $uid)
    {
        $return = (new Update())->__invoke($uid, []);
        return [];
    }

    /**
     * @param string $uid
     * @return array
     */
    private function destroy(string $uid)
    {
        $return = (new Destroy())->__invoke($uid, []);
        return [];
    }

    /**
     * @param array $data
     * @return HostfullyReservation
     */
    private function saveData(array $data)
    {
        $oModel = null;
        if (is_null($oModel)) {
            $oModel = HostfullyReservation::create([
                'uid' => $data[Leads::UID],
                'listing_id' => $this->oListing->id,
                'last_sync_at' => now(),
                'external' => $data,
            ]);
        } else {
            $oModel->update([
                'uid' => $data[Leads::UID],
                'last_sync_at' => now(),
                'external' => $data,
            ]);
        }
        return $oModel;
    }

    /**
     * @param bool $isCreate
     * @return array
     */
    private function data(bool $isCreate = true)
    {
        $data = [
            // REQUIRED

            //Leads::LEAD_TYPE => Leads::TYPE_BLOCK,
            //Leads::STATUS => Leads::STATUS_BLOCKED,

            // рабочий
            //Leads::LEAD_TYPE => Leads::TYPE_INQUIRY,
            //Leads::STATUS => Leads::STATUS_INQUIRY_NEW,

            // рабочий, блокирует день и сразу добавляет в календарь
            Leads::LEAD_TYPE => Leads::TYPE_BLOCK,
            Leads::STATUS => Leads::STATUS_BLOCK_BLOCKED,

            //Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
            //Leads::STATUS => Leads::STATUS_BOOKING_BOOKED_BY_AGENT,
        ];

        if ($isCreate) {
            $data = array_merge($data, [
                'agencyUid' => $this->agencyUid,
                Leads::PROPERTY_UID => $this->oListing->hostfully->uid,
                Leads::CHECK_IN_DATE => $this->date->format('Y-m-d'),
                Leads::CHECK_OUT_DATE => $this->date->copy()->addDay()->format('Y-m-d'),
                Leads::EMAIL => $this->oListing->user->email,
                //
                //Leads::FIRST_NAME => $this->oListing->user->first_name,
                //Leads::LAST_NAME => $this->oListing->user->last_name,
                //Leads::CHILDREN_COUNT => 0,
                //Leads::ADULT_COUNT => (int)$this->oListing->guests_size,
                //Leads::PHONE_NUMBER => '18649261158',
                //Leads::CITY => $this->oListing->location->locality,
                //Leads::STATE => $this->oListing->location->province,
                // ругается из-за countryCode "IOException during parsing request body" когда TYPE_BLOCK
                //Leads::COUNTRY_CODE => $this->oReservation->listing->location->country_code,
                //Leads::BOOKED => now()->format('Y-m-d H:i'),
                //Leads::NOTES => "Notes",
            ]);
        } else {
            $data = array_merge($data, [
                //
            ]);
        }

        return $data;
    }
}
