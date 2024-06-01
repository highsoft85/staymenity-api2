<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\Calendar;

use App\Models\HostfullyListing;
use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\Type;
use App\Models\User;
use App\Models\UserCalendar;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Models\LeadsV1;
use App\Services\Hostfully\Models\Properties;
use App\Services\Hostfully\Properties\Show;
use App\Services\Hostfully\Properties\Store;
use App\Services\Hostfully\Properties\Update;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserCalendarServiceModel;
use App\Services\Model\UserReservationServiceModel;
use App\Services\Sync\Hostfully\Listing\SyncFromHostfullyListingService;
use App\Services\Sync\Hostfully\User\SyncFromHostfullyGuestService;
use Carbon\Carbon;

class SyncFromHostfullyCalendarService
{
    /**
     * @var array
     */
    private $data;

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
     * @param array $data
     */
    public function __construct(string $agencyUid, array $data)
    {
        $this->agencyUid = $agencyUid;
        $this->data = $data;
    }

    /**
     *
     */
    public function setForce()
    {
        $this->force = true;
    }

    /**
     * Сюда попадают только трансформированные данные с v1 в v2
     *
     * @return HostfullyReservation
     */
    public function sync(): HostfullyReservation
    {
        if (!isset($this->data[Leads::UID])) {
            throw new \Exception('Empty lead uid.');
        }
        if (!isset($this->data[Leads::PROPERTY_UID])) {
            throw new \Exception('Empty lead propertyUid.');
        }
        /** @var HostfullyListing|null $oModel */
        $oModel = HostfullyListing::where('uid', $this->data[Leads::PROPERTY_UID])->first();
        if (is_null($oModel)) {
            throw new \Exception('Empty hostfully listing for property, please sync listing before.');
        }
        $oListing = $oModel->listing;
        if (is_null($oListing)) {
            throw new \Exception('Empty listing for property, please sync listing before.');
        }
        /** @var HostfullyReservation|null $oModel */
        $oModel = HostfullyReservation::where('uid', $this->data[Leads::UID])->first();
        if (is_null($oModel)) {
            $oModel = $this->store($oListing);
        } else {
            $oModel = $this->update($oModel, $oListing);
        }
        return $oModel;
    }

    /**
     * @param Listing $oListing
     * @return HostfullyReservation
     */
    private function store(Listing $oListing): HostfullyReservation
    {
        $oModel = $this->saveData($oListing);
        $this->setToCalendar($oListing, $oModel);
        return $oModel;
    }
    // "notes": "",
    //        "agency": {
    //            "uid": "d89494f8-37e5-40dd-844b-0e847b9bcee4",
    //            "name": "AG.digital"
    //        },
    //        "adultCount": 1,
    //        "created": "2021-04-26 16:45:10.0",
    //        "source": "ORBIRENTAL_FORM",
    //        "checkInDate": "2021-04-29",
    //        "uid": "1fc14405-1bb8-48f6-8576-be4d966d7203",
    //        "checkOutDate": "2021-04-30",
    //        "quoteAmount": 3600,
    //        "property": {
    //            "city": "North Hayward",
    //            "webLink": "",
    //            "postalCode": "",
    //            "latitude": 37.68513,
    //            "externalID": "",
    //            "type": "HOUSE",
    //            "photos": [],
    //            "uid": "65580ee7-cdbb-4922-a84a-a8b503b8d9a4",
    //            "maximumGuests": 1,
    //            "cleaningFeeAmount": 0,
    //            "countryCode": "US",
    //            "securityDepositAmount": 0,
    //            "currency": "USD",
    //            "state": "",
    //            "baseGuests": 1,
    //            "floor": 0,
    //            "availabilityCalendarUrl": "https://sandbox.hostfully.com/calendar/29290.ics",
    //            "areaSize": 0,
    //            "minimumStay": 1,
    //            "longitude": -122.100735,
    //            "address2": "",
    //            "address1": "North Hayward",
    //            "bathrooms": 1,
    //            "picture": "https://sandbox.hostfully.com/img/littlehouse.png",
    //            "bedrooms": 1,
    //            "acceptInstantBook": false,
    //            "areaSizeUnit": "SQUARE_METERS",
    //            "name": "Basketball Court with pool",
    //            "baseDailyRate": 3600
    //        },
    //        "stayDetails": {
    //            "departureDate": "2021-04-30 11:00:00.0",
    //            "arrivalDate": "2021-04-29 15:00:00.0"
    //        },
    //        "modified": "2021-04-26 16:45:11.0",
    //        "status": "BLOCKED",
    //        "childrenCount": 0,
    //        "petCount": 0,
    //        "infantCount": 0

    /**
     * @param HostfullyReservation $oModel
     * @param Listing $oListing
     * @return HostfullyReservation
     */
    private function update(HostfullyReservation $oModel, Listing $oListing): HostfullyReservation
    {
        $oModel = $this->saveData($oListing);
        $this->setToCalendar($oListing, $oModel);
        return $oModel;
    }

    /**
     * @param Listing $oListing
     * @return HostfullyReservation
     */
    private function saveData(Listing $oListing)
    {
        /** @var HostfullyReservation|null $oModel */
        $oModel = HostfullyReservation::where('uid', $this->data[Leads::UID])->first();
        if (is_null($oModel)) {
            $oModel = HostfullyReservation::create([
                'uid' => $this->data[Leads::UID],
                'reservation_id' => null,
                'last_sync_at' => now(),
                'external' => $this->data,
            ]);
        } else {
            $oModel->update([
                'last_sync_at' => now(),
            ]);
        }
        return $oModel;
    }

    /**
     * @param Listing $oListing
     * @param HostfullyReservation $oHostfullyReservation
     * @throws \Exception
     */
    private function setToCalendar(Listing $oListing, HostfullyReservation $oHostfullyReservation)
    {
        // вставить новые даты
        $start_at = Carbon::createFromFormat('Y-m-d', $this->data[Leads::CHECK_IN_DATE])->startOfDay();
        // чтобы не брался последний день до какого числа, поэтому конец прошлого дня
        $finish_at = Carbon::createFromFormat('Y-m-d', $this->data[Leads::CHECK_OUT_DATE])->subDay()->endOfDay();

        $start = $start_at->copy();
        $end = $finish_at->copy();

        /** @var Carbon[] $dates */
        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }
        //slackInfo($dates);
        foreach ($dates as $date) {
            (new UserCalendarServiceModel($oListing->user, $oListing))
                ->setHostfullyReservation($oHostfullyReservation)
                ->setByDate(UserCalendar::TYPE_LOCKED, $date);
        }
    }
}
