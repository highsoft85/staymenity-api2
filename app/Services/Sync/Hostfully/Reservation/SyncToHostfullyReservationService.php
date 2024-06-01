<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\Reservation;

use App\Models\HostfullyListing;
use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\Leads\Show;
use App\Services\Hostfully\Leads\Store;
use App\Services\Hostfully\Leads\Update;
use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Transformers\LeadTransformer;

class SyncToHostfullyReservationService
{
    /**
     * @var Reservation
     */
    private $oReservation;

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
     * @param Reservation $oItem
     */
    public function __construct(string $agencyUid, Reservation $oItem)
    {
        $this->agencyUid = $agencyUid;
        $this->oReservation = $oItem;
    }

    /**
     *
     */
    public function setForce()
    {
        $this->force = true;
    }

    /**
     * @return HostfullyReservation
     */
    public function sync(): HostfullyReservation
    {
        $oModel = $this->oReservation->hostfully;
        if (is_null($oModel)) {
            $oModel = $this->store();
        } else {
            $oModel = $this->update($oModel->uid);
        }
        return $oModel;
    }

    /**
     * @return HostfullyReservation
     */
    private function store(): HostfullyReservation
    {
        $data = $this->data();
        $return = $this->storeAndBooking($data);
        $oModel = $this->saveData($return);
        return $oModel;
    }

    /**
     * @param string $uid
     * @return HostfullyReservation
     */
    private function update(string $uid): HostfullyReservation
    {
        if ($this->force) {
            $data = $this->data();
            $return = $this->storeAndBooking($data);
        } else {
//            if ($this->oReservation->typeIsCancelled()) {
//                (new Update())->__invoke($uid, [
//                    Leads::STATUS => Leads::STATUS_BOOKING_CANCELLED,
//                ]);
//            }
            $data = $this->data(false);
            $return = (new Update())->__invoke($uid, $data);
        }
        $oModel = $this->saveData($return);
        return $oModel;
    }

    /**
     * Создать и захолдить а после сразу обновить (забукать)
     *
     * @param array $data
     * @return array
     */
    private function storeAndBooking(array $data)
    {
        $data = array_merge($data, [
            Leads::LEAD_TYPE => Leads::TYPE_INQUIRY,
            Leads::STATUS => Leads::STATUS_INQUIRY_ON_HOLD,
        ]);
        $return = (new Store())->__invoke((new LeadTransformer())->transformFromV2ToV1Create($data));

        $return = (new Update())->__invoke($return[Leads::UID], (new LeadTransformer())->transformFromV2ToV1Update([
            Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
            Leads::STATUS => Leads::STATUS_BOOKING_BOOKED_BY_AGENT,
        ]));
        if ($this->oReservation->typeIsCancelled()) {
            $return = (new Update())->__invoke($return[Leads::UID], (new LeadTransformer())->transformFromV2ToV1Update([
                Leads::LEAD_TYPE => $this->getLeadType(),
                Leads::STATUS => $this->getStatus(),
            ]));
        }
        return $return;
    }

    /**
     * @param array $data
     * @return HostfullyReservation
     */
    private function saveData(array $data): HostfullyReservation
    {
        $oModel = $this->oReservation->hostfully;
        if (is_null($oModel)) {
            $oModel = HostfullyReservation::create([
                'uid' => $data[Leads::UID],
                'reservation_id' => $this->oReservation->id,
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
        $this->oReservation->syncHostfullySetActive();
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
            Leads::LEAD_TYPE => $this->getLeadType(),
            Leads::STATUS => $this->getStatus(),

            //Leads::LEAD_TYPE => Leads::TYPE_BOOKING,
            //Leads::STATUS => Leads::STATUS_BOOKING_BOOKED_BY_AGENT,
        ];

        if ($isCreate) {
            $data = array_merge($data, [
                'agencyUid' => $this->agencyUid,
                Leads::PROPERTY_UID => $this->oReservation->listing->hostfully->uid,
                Leads::CHECK_IN_DATE => $this->oReservation->start_at->copy()->startOfDay()->format('Y-m-d'),
                Leads::CHECK_OUT_DATE => $this->oReservation->finish_at->copy()->addDay()->startOfDay()->format('Y-m-d'),
                Leads::EMAIL => $this->oReservation->user->email,
                //
                Leads::FIRST_NAME => $this->oReservation->user->first_name,
                Leads::LAST_NAME => $this->oReservation->user->last_name,
                Leads::CHILDREN_COUNT => 0,
                Leads::ADULT_COUNT => (int)$this->oReservation->guests_size,
                Leads::PHONE_NUMBER => $this->oReservation->user->phone,
                Leads::CITY => $this->oReservation->listing->location->locality,
                Leads::STATE => $this->oReservation->listing->location->province,
                // ругается из-за countryCode "IOException during parsing request body" когда TYPE_BLOCK
                //Leads::COUNTRY_CODE => $this->oReservation->listing->location->country_code,
                Leads::BOOKED => !is_null($this->oReservation->accepted_at)
                    ? $this->oReservation->accepted_at->format('Y-m-d H:i')
                    : $this->oReservation->created_at->format('Y-m-d H:i'),
                Leads::NOTES => $this->notes(),
            ]);
            $data = (new LeadTransformer())->transformFromV2ToV1Create($data);
        } else {
            $data = array_merge($data, [
                //
            ]);
            $data = (new LeadTransformer())->transformFromV2ToV1Update($data);
        }

        return $data;
    }

    /**
     * @return string
     */
    private function getLeadType()
    {
        if ($this->oReservation->typeIsCancelled()) {
            return $this->cancelledLeadType();
        }
        if ($this->oReservation->isActive()) {
            return $this->bookedLeadType();
        }
        return '';
    }

    /**
     * @return string
     */
    private function getStatus()
    {
        if ($this->oReservation->typeIsCancelled()) {
            return $this->cancelledStatus();
        }
        if ($this->oReservation->isActive()) {
            return $this->bookedStatus();
        }
        return '';
    }

    /**
     * @return string
     */
    private function bookedLeadType()
    {
        return Leads::TYPE_BOOKING;
    }

    /**
     * @return string
     */
    private function bookedStatus()
    {
        return Leads::STATUS_BOOKING_BOOKED_BY_AGENT;
    }

    /**
     * @return string
     */
    private function cancelledLeadType()
    {
        return Leads::TYPE_BOOKING;
    }

    /**
     * @return string
     */
    private function cancelledStatus()
    {
        return Leads::STATUS_BOOKING_CANCELLED;
    }


    /**
     * @return string
     */
    private function notes(): string
    {
        $hours = $this->oReservation->hours;
        if ($hours === 1) {
            $hoursText = $hours . ' hour';
        } else {
            $hoursText = $hours . ' hours';
        }
        return $this->oReservation->reservationTimeFormat . "\n" . '(' . $hoursText . ')';
    }
}
