<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Leads;

use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\MockLeadsHostfullyTrait;

class Index extends BaseHostfullyService
{
    use MockLeadsHostfullyTrait;

    /**
     * @param string $agencyUid
     * @param string $propertyUid
     * @return array
     */
    public function __invoke(string $agencyUid, string $propertyUid): array
    {
        if (envIsTesting()) {
            loggerHostfully('/leads', 'GET');
            loggerHostfully([], 'DATA');
            return $this->mockGetLeads();
        }
        $data = $this->setLeadsVersion()->apiGet('/leads', [
            'limit' => 100,
            'offset' => 0,
            'agencyUid' => $agencyUid,
            'propertyUid' => $propertyUid,
            // все будущие, addDay для погрешности таймзоны
            'checkin_from' => now()->addDay()->format('Y-m-d'),
        ]);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }

    // {
    //        "leadType": null,
    //        "uid": "a8a81a32-7a7c-488f-abb9-143159a83286",
    //        "propertyUid": "08ee0d3e-248b-46c7-9600-a0f556ba1efe",
    //        "childrenCount": 2,
    //        "adultCount": 3,
    //        "petCount": 0,
    //        "checkInDate": "2017-06-09",
    //        "checkOutDate": "2017-06-16",
    //        "status": "SAMPLE",
    //        "source": "ORBIRENTAL_API",
    //        "firstName": "Buzz",
    //        "lastName": "Aldrin",
    //        "notes" : null,
    //        "email": "Buzz@apollo11.gov",
    //        "phoneNumber": "+1-415-121-2311",
    //        "externalBookingID": null,
    //        "preferredCurrency": null,
    //        "city": "New Orleans",
    //        "state": "LA",
    //        "booked": null,
    //        "preferredLocale": null
    //    }

}
