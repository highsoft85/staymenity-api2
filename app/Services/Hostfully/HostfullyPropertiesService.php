<?php

declare(strict_types=1);

namespace App\Services\Hostfully;

use App\Models\HostfullyListing;
use App\Models\Listing;
use App\Services\Hostfully\Properties\Index;
use App\Services\Hostfully\Properties\Show;
use App\Services\Sync\Hostfully\Listing\SyncFromHostfullyListingService;
use App\Services\Sync\Hostfully\Listing\SyncToHostfullyListingService;

class HostfullyPropertiesService
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
     * @return array
     */
    public function get(): array
    {
        return (new Index())->__invoke($this->agencyUid);
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
     * @return HostfullyListing
     */
    public function syncFrom(array $data): HostfullyListing
    {
        return (new SyncFromHostfullyListingService($data))->sync();
    }

    /**
     * @param Listing $oItem
     * @return HostfullyListing
     */
    public function syncTo(Listing $oItem): HostfullyListing
    {
        $oService = (new SyncToHostfullyListingService($oItem));
        if ($this->force) {
            $oService->setForce();
        }
        return $oService->sync();
    }
}
