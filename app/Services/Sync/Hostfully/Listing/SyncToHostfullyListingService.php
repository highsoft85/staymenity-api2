<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\Listing;

use App\Models\HostfullyListing;
use App\Models\Listing;
use App\Services\Hostfully\Models\Properties;
use App\Services\Hostfully\Properties\Store;
use App\Services\Hostfully\Properties\Update;

class SyncToHostfullyListingService
{
    /**
     * @var Listing
     */
    private $oListing;

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
     * @param Listing $oItem
     */
    public function __construct(string $agencyUid, Listing $oItem)
    {
        $this->agencyUid = $agencyUid;
        $this->oListing = $oItem;
    }

    /**
     *
     */
    public function setForce()
    {
        $this->force = true;
    }

    /**
     * @return HostfullyListing
     */
    public function sync(): HostfullyListing
    {
        $oModel = $this->oListing->hostfully;
        if (is_null($oModel)) {
            $oModel = $this->store();
        } else {
            $oModel = $this->update($oModel->uid);
        }
        return $oModel;
    }

    /**
     * @return HostfullyListing
     */
    private function store(): HostfullyListing
    {
        $data = $this->data();
        $return = (new Store())->__invoke($data);
        $oModel = $this->saveData($return);
        return $oModel;
    }

    /**
     * @param string $uid
     * @return HostfullyListing
     */
    private function update(string $uid): HostfullyListing
    {
        $data = $this->data();
        if ($this->force) {
            $return = (new Store())->__invoke($data);
        } else {
            $return = (new Update())->__invoke($uid, $data);
        }
        $oModel = $this->saveData($return);
        return $oModel;
    }

    /**
     * @param array $data
     * @return HostfullyListing
     */
    private function saveData(array $data)
    {
        $oModel = $this->oListing->hostfully;
        if (is_null($oModel)) {
            $oModel = HostfullyListing::create([
                'uid' => $data[Properties::UID],
                'listing_id' => $this->oListing->id,
                'last_sync_at' => now(),
                'external' => $data,
            ]);
        } else {
            $oModel->update([
                'uid' => $data[Properties::UID],
                'last_sync_at' => now(),
                'external' => $data,
            ]);
        }
        return $oModel;
    }

    /**
     * @return array
     */
    private function data()
    {
        return [
            Properties::EXTERNAL_ID => $this->oListing->id,
            Properties::TYPE => $this->oListing->type->name_hostfully,
            Properties::NAME => $this->oListing->title,
            Properties::AGENCY_UID => config('hostfully.agencyUid'),
            Properties::BASE_GUESTS => 1,
            Properties::MAXIMUM_GUESTS => $this->oListing->guests_size,
            Properties::BASE_DAILY_RATE => (float)$this->oListing->price * 24,
            Properties::CITY => $this->oListing->location->locality,
            Properties::STATE => $this->oListing->location->province,
            Properties::ADDRESS_1 => $this->oListing->location->address,
            Properties::POSTAL_CODE => $this->oListing->location->zip,
            Properties::COUNTRY_CODE => $this->oListing->location->country_code,
            Properties::WEB_LINK => $this->oListing->getUrl(),
            Properties::LATITUDE => $this->oListing->location->latitude,
            Properties::LONGITUDE => $this->oListing->location->longitude,
            Properties::CLEANING_FEE_AMOUNT => (float)$this->oListing->cleaning_fee,
            Properties::MINIMUM_STAY => 1,
            Properties::PICTURE => envIsProduction()
                ? $this->oListing->image_square
                : 'https://images.staymenity.com/storage/images/listing/34/model/xl/IidQbzdsSvxQ.jpg'
        ];
    }
}
