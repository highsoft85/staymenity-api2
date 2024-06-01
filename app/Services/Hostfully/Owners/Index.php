<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Owners;

use App\Services\Hostfully\BaseHostfullyService;

class Index extends BaseHostfullyService
{
    /**
     * @param string $agency
     * @param string $property
     * @return array
     */
    public function __invoke(string $agency, string $property): array
    {
        $data = $this->apiGet('/owners', [
            'propertyUid' => $agency,
            'agencyUid' => $property,
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }

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

}
