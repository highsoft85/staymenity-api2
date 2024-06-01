<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Properties;

use App\Services\Hostfully\BaseHostfullyService;

class Store extends BaseHostfullyService
{
    /**
     * @param array $data
     * @return array
     */
    public function __invoke(array $data): array
    {
        $data = $this->apiPost('/properties', $data);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }


    // {
    //    "pricingRules": null,
    //    "bedCount": 0,
    //    "bedTypes": {
    //        "kingBed": 0,
    //        "queenBed": 0,
    //        "doubleBed": 0,
    //        "singleBed": 0,
    //        "sofaBed": 0,
    //        "futonBed": 0,
    //        "floorMattress": 0,
    //        "bunkBed": 0,
    //        "toddlerBed": 0,
    //        "crib": 0,
    //        "hammockBed": 0,
    //        "airMattress": 0,
    //        "waterBed": 0
    //    },
    //    "type": "APARTMENT",
    //    "name": "ApiProperty",
    //    "isActive": false,
    //    "baseGuests": 1,
    //    "maximumGuests": 4,
    //    "baseDailyRate": 75.25,
    //    "city": "Sacramento",
    //    "state": "CA",
    //    "address1": "5th st.",
    //    "address2": null,
    //    "postalCode": "00446",
    //    "countryCode": "US",
    //    "bedrooms": 0,
    //    "bathrooms": "0",
    //    "picture": "https://test.hostfully.com/img/littlehouse.png",
    //    "webLink": null,
    //    "cleaningFeeAmount": 0.0,
    //    "minimumStay": 2,
    //    "maximumStay": 0,
    //    "securityDepositAmount": 0.0,
    //    "externalID": null,
    //    "acceptInstantBook": false,
    //    "acceptBookingRequest": false,
    //    "availabilityCalendarUrl": "https://test.hostfully.com/calendar/28710.ics?itp=1",
    //    "rentalCondition": null,
    //    "cancellationPolicy": null,
    //    "floor": 0,
    //    "areaSize": 0,
    //    "areaSizeUnit": null,
    //    "extraGuestFee": 0.0,
    //    "taxationRate": 0.0,
    //    "latitude": 38.5726979,
    //    "longitude": -121.504161,
    //    "airBnBID": null,
    //    "flipkeyID": null,
    //    "homeAwayID": null,
    //    "currency": "USD",
    //    "currencySymbol": "$",
    //    "panoramicDataUrl": null,
    //    "propertyURL": "https://test.hostfully.com/vacation-rental-property/28710/apiproperty",
    //    "guideBookUrl": null,
    //    "weekEndRatePercentAdjustment": 0.0,
    //    "bookingWindow": -1,
    //    "bookingWindowAfterCheckout": -1,
    //    "turnOverDays": 0,
    //    "bookingLeadTime": 48,
    //    "defaultCheckinTime": 15,
    //    "defaultCheckoutTime": 11,
    //    "wifiNetwork": null,
    //    "wifiPassword": null,
    //    "rentalLicenseNumber": null,
    //    "rentalLicenseNumberExpirationDate": null,
    //    "minimumWeekendStay": 0,
    //    "reviews": {
    //        "total": 0,
    //        "average": null
    //    },
    //    "createdDate": 1593084407658,
    //    "percentUponReservation": 50,
    //    "fullPaymentTiming": 45,
    //    "uid": "1cec8bea-2419-4e54-a765-4c7b620f6753"
    //}
}
