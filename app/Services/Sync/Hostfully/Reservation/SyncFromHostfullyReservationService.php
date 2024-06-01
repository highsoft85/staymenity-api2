<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\Reservation;

use App\Models\HostfullyListing;
use App\Models\HostfullyReservation;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\Type;
use App\Models\User;
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
use App\Services\Model\UserReservationServiceModel;
use App\Services\Sync\Hostfully\Listing\SyncFromHostfullyListingService;
use App\Services\Sync\Hostfully\User\SyncFromHostfullyGuestService;
use Carbon\Carbon;

class SyncFromHostfullyReservationService
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
        $data = $this->data($oListing);
        /** @var Reservation $oReservation */
        $oReservation = Reservation::create($data);
        $oModel = $this->saveData($oReservation, $oListing);
        $this->setToCalendar($oReservation);
        slackInfo(['reservation_id' => $oReservation->id, 'listing' => $oReservation->listing->title], 'NEW SYNC RESERVATION');
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
        $oReservation = $oModel->reservation;
        if ($this->force) {
            $oReservation->delete();
            $data = $this->data($oListing);
            /** @var Reservation $oReservation */
            $oReservation = Reservation::create($data);
            $this->setToCalendar($oReservation);
        } else {
            $data = $this->data($oListing, false);
            $oReservation->update($data);
        }
        $oModel = $this->saveData($oReservation, $oListing);
        return $oModel;
    }

    /**
     * @param Reservation $oReservation
     * @param Listing $oListing
     * @return HostfullyReservation
     */
    private function saveData(Reservation $oReservation, Listing $oListing)
    {
        $oModel = $oReservation->hostfully;
        if (is_null($oModel)) {
            $oModel = HostfullyReservation::create([
                'uid' => $this->data[Leads::UID],
                'reservation_id' => $oReservation->id,
                'last_sync_at' => now(),
                'external' => $this->data,
            ]);
        } else {
            $oModel->update([
                'last_sync_at' => now(),
            ]);
        }

        $this->actionByStatus($oReservation, $this->data[Leads::STATUS]);
        $oReservation->syncHostfullySetActive();
        return $oModel;
    }

    /**
     * @param Listing $oListing
     * @param bool $isCreate
     * @return array
     */
    private function data(Listing $oListing, bool $isCreate = true)
    {
        // обновлять брони ничего нельзя
        $data = [];

        if ($isCreate) {
            $oUser = $this->user();
            $oUser->update([
                'timezone' => $oListing->timezone,
            ]);

            $start_at = Carbon::createFromFormat('Y-m-d', $this->data[Leads::CHECK_IN_DATE])->startOfDay();
            $finish_at = Carbon::createFromFormat('Y-m-d', $this->data[Leads::CHECK_OUT_DATE])->startOfDay();
            //
            $days = $start_at->diffInDays($finish_at);
            $price = $days * $oListing->price_per_day;

            $data = array_merge($data, [
                'user_id' => $oUser->id,
                'listing_id' => $oListing->id,
                'guests_size' => $this->getGuestSize(),
                'total_price' => $price + $oListing->cleaning_fee,
                'price' => $price,
                'service_fee' => $oListing->cleaning_fee ?? 0,
                'start_at' => $start_at,
                'finish_at' => reservationFinishAt($finish_at),
                'server_start_at' => $start_at->copy()->timezone(config('app.timezone')),
                'server_finish_at' => reservationFinishAt($finish_at)->copy()->timezone(config('app.timezone')),
                'free_cancellation_at' => $start_at->copy()->timezone(config('app.timezone'))->addYear(),
                'is_agree' => 1,
                'source' => Reservation::SOURCE_HOSTFULLY,
                'status' => Reservation::STATUS_ACCEPTED,
                'message' => $this->data[Leads::NOTES],
                'timezone' => $oListing->timezone,
                'accepted_at' => now(),
                'code' => reservationCode(),
            ]);
        }

        return $data;
    }

    /**
     * @return int
     */
    private function getGuestSize()
    {
        $count = 0;
        if (isset($this->data[Leads::ADULT_COUNT])) {
            $count += (int)$this->data[Leads::ADULT_COUNT];
        }
        if (isset($this->data[Leads::CHILDREN_COUNT])) {
            $count += (int)$this->data[Leads::CHILDREN_COUNT];
        }
        return $count;
    }

    /**
     * @param Reservation $oReservation
     * @param string $status
     * @throws \Exception
     */
    private function actionByStatus(Reservation $oReservation, string $status)
    {
        $oService = (new ReservationServiceModel($oReservation))->setSyncDisabled();
        switch ($status) {
            // бронь через агента, который в лк
            case Leads::STATUS_BOOKING_BOOKED_BY_AGENT;
                if ($oReservation->typeIsCancelled()) {
                    // если пришел тип BOOKED_BY_AGENT, а был отменен, то активировать
                    $oService->setAccepted();
                }
                break;
            case Leads::STATUS_BOOKING_BOOKED_BY_CUSTOMER;
                if ($oReservation->typeIsCancelled()) {
                    // если пришел тип BOOKED_BY_CUSTOMER, а был отменен, то активировать
                    $oService->setAccepted();
                }
                break;
            case Leads::STATUS_BOOKING_BOOKED_BY_OWNER;
                if ($oReservation->typeIsCancelled()) {
                    // если пришел тип BOOKED_BY_OWNER, а был отменен, то активировать
                    $oService->setAccepted();
                }
                break;
            // заблокирован через другой сервис
            case Leads::STATUS_BOOKING_BOOKED_EXTERNALLY;
                if ($oReservation->typeIsCancelled()) {
                    // если пришел тип BOOKED_EXTERNALLY, а был отменен, то активировать
                    $oService->setAccepted();
                }
                break;
            case Leads::STATUS_BOOKING_CANCELLED;
                if (!$oReservation->typeIsCancelled()) {
                    // если пришел тип CANCELLED, но не был отменен, то отменить
                    $oService->setCancelled();
                }
                // случай был когда поставилась дата, но статус остался 3, в таком случае еще проверить на статус
                // это когда в тестовом режиме синхронизация шла сразу после оздания брони, а не после её оплаты
                if ($oReservation->typeIsCancelled() && $oReservation->status === Reservation::STATUS_ACCEPTED) {
                    $oService->setNotActive();
                }
                break;
            case Leads::STATUS_BOOKING_CANCELLED_BY_TRAVELER;
                if (!$oReservation->typeIsCancelled()) {
                    // если пришел тип CANCELLED_BY_TRAVELER, но не был отменен, то отменить
                    $oService->setCancelled();
                }
                break;
            case Leads::STATUS_BOOKING_CANCELLED_BY_OWNER;
                if (!$oReservation->typeIsCancelled()) {
                    // если пришел тип CANCELLED_BY_OWNER, но не был отменен, то отменить
                    $oService->setDeclined();
                }
                break;
            case Leads::STATUS_BOOKING_STAY;
                //$oService->setAccepted();
                break;
            case Leads::STATUS_BOOKING_ARCHIVED;
                $oService->setDeclined();
                break;
            default:
                break;
        }
    }

    /**
     * @param Reservation $oReservation
     * @throws \Exception
     */
    private function setToCalendar(Reservation $oReservation)
    {
        $oService = (new ReservationServiceModel($oReservation));
        $oListing = $oReservation->listing;

        // очистить прошлые брони, например если дата поменялась
        $oService->clearReservationUserCalendar();

        // вставить новые даты
        $start_at = Carbon::createFromFormat('Y-m-d', $this->data[Leads::CHECK_IN_DATE])->startOfDay();
        // чтобы не брался последний день до какого числа, поэтому конец прошлого дня
        $finish_at = Carbon::createFromFormat('Y-m-d', $this->data[Leads::CHECK_OUT_DATE])->subDay()->endOfDay();

        $oService->setToCalendarStartFinishFull($oListing, $start_at, $finish_at);
    }

    /**
     * @return User
     */
    private function user(): User
    {
        $oService = (new SyncFromHostfullyGuestService($this->data));
        if ($this->force) {
            $oService->setForce();
        }
        return $oService->sync();
    }
}
