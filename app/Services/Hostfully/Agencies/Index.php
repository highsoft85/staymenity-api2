<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Agencies;

use App\Services\Hostfully\BaseHostfullyService;

class Index extends BaseHostfullyService
{
    const UID = 'uid';
    const STATE = 'state';
    const COUNTRY_CODE = 'countryCode';
    const ADDRESS_1 = 'address1';
    const WEBSITE = 'website';
    const ZIP_CODE = 'zipCode';
    const PHONE_NUMBER = 'phoneNumber';
    const DEFAULT_CHECK_IN_TIME = 'defaultCheckInTime';
    const DEFAULT_CHECK_OUT_TIME = 'defaultCheckOutTime';
    const AGENCY_EMAIL_ADDRESS = 'agencyEmailAddress';

    // {
    //  "uid": "4f6a3a22-6d57-450d-8b46-44abb3b01994",
    //  "state": "California",
    //  "countryCode": "US",
    //  "address1": "159 Main Road",
    //  "website": "http://vacation-rental.com/",
    //  "city": "Marin",
    //  "zipCode": "94256",
    //  "name": "The Marin Cottage",
    //  "phoneNumber": "+1 (604) 4565-435",
    //  "defaultCheckInTime": 15,
    //  "defaultCheckOutTime": 11,
    //  "agencyEmailAddress": "john@vrmc.org"
    // }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        $data = $this->apiGet('/agencies', []);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }
}
