<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\Listing;

use App\Models\HostfullyListing;
use App\Models\Listing;
use App\Models\Type;
use App\Models\User;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Hostfully\Models\Properties;
use App\Services\Hostfully\Properties\Store;
use App\Services\Hostfully\Properties\Update;
use App\Services\Model\ListingServiceModel;

class SyncFromHostfullyListingService
{
    /**
     * @var array
     */
    private $data;

    /**
     * SyncHostfullyListingService constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return HostfullyListing
     */
    public function sync(): HostfullyListing
    {
        /** @var HostfullyListing|null $oModel */
        $oModel = HostfullyListing::where('uid', $this->data[Properties::UID])->first();
        if (is_null($oModel)) {
            $oModel = $this->store();
        } else {
            $oModel = $this->update($oModel);
        }
        return $oModel;
    }

    /**
     * @return HostfullyListing
     */
    private function store(): HostfullyListing
    {
        $data = $this->data();
        $oListing = Listing::create($data);
        $oModel = $this->saveData($oListing);

        $this->saveLocation($oListing);

        $oListing->refresh();
        $this->updateTimezone($oListing);
        return $oModel;
    }

    /**
     * @param HostfullyListing $oModel
     * @return HostfullyListing
     */
    private function update(HostfullyListing $oModel): HostfullyListing
    {
        $data = $this->data();
        $oListing = $oModel->listing;
        $oListing->update($data);
        $oModel = $this->saveData($oListing);

        $this->saveLocation($oListing);

        $oListing->refresh();
        $this->updateTimezone($oListing);
        return $oModel;
    }

    /**
     * @param Listing $oListing
     * @return HostfullyListing
     */
    private function saveData(Listing $oListing)
    {
        $oModel = $oListing->hostfully;
        if (is_null($oModel)) {
            $oModel = HostfullyListing::create([
                'uid' => $this->data[Properties::UID],
                'listing_id' => $oListing->id,
                'last_sync_at' => now(),
                'external' => $this->data,
            ]);
        } else {
            $oModel->update([
                'uid' => $this->data[Properties::UID],
                'last_sync_at' => now(),
                'external' => $this->data,
            ]);
        }
        return $oModel;
    }

    /**
     * @return string
     */
    private function transformAddress()
    {
        return $this->data[Properties::STATE] . ', ' . $this->data[Properties::CITY] . ', ' . $this->data[Properties::ADDRESS_1];
    }

    /**
     * @return array
     */
    private function data()
    {
        /** @var User $oUser */
        $oUser = User::where('email', config('hostfully.user.email'))->first();
        return [
            'user_id' => $oUser->id,
            'creator_id' => $oUser->id,
            'owner_id' => $oUser->id,
            'type_id' => Type::other()->first()->id,
            'title' => $this->data[Properties::NAME],
            'price' => $this->data[Properties::BASE_DAILY_RATE] / 24,
            'price_per_day' => $this->data[Properties::BASE_DAILY_RATE],
            'guests_size' => $this->data[Properties::BASE_GUESTS],
            'deposit' => $this->data[Properties::SECURITY_DEPOSIT_AMOUNT],
            'cleaning_fee' => $this->data[Properties::CLEANING_FEE_AMOUNT],
            'rent_time_min' => $this->data[Properties::MINIMUM_STAY] * 24,
        ];
    }

    /**
     * @param Listing $oListing
     * @return \App\Models\Location|null
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function saveLocation(Listing $oListing)
    {
        $address = $this->transformAddress();
        return (new ListingServiceModel($oListing))->baseSaveLocationByAddress($oListing, $address);
    }

    /**
     * @param Listing $oListing
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function updateTimezone(Listing $oListing)
    {
        $timezone = (new GeocoderCitiesService())->timezoneByPlace($oListing->location->place_id);
        $oListing->update([
            'timezone' => $timezone,
        ]);
    }
}
