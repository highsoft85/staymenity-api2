<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Properties;

use App\Services\Hostfully\BaseHostfullyService;

class Index extends BaseHostfullyService
{
    /**
     * @param string $agencyUid
     * @return array
     */
    public function __invoke(string $agencyUid): array
    {
        $data = $this->apiGet('/properties', [
            'limit' => 10,
            'offset' => 0,
            'agencyUid' => $agencyUid,
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data['propertiesUids'] ?? [];
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
