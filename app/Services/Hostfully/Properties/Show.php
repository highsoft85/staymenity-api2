<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Properties;

use App\Services\Hostfully\BaseHostfullyService;

class Show extends BaseHostfullyService
{
    /**
     * @param string $id
     * @return array
     */
    public function __invoke(string $id): array
    {
        $data = $this->apiGet('/properties/' . $id, []);
        if (!$this->isSuccess()) {
            abort(422, $this->getErrorMessage());
        }
        return $data;
    }


    // {
    //    "pricingRules": null,
    //    "bedCount": 1,
    //    "bedTypes": {
    //        "kingBed": 1,
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
    //    "isActive": true,
    //    "baseGuests": 1,
    //    "maximumGuests": 4,
    //    "baseDailyRate": 75.25,
    //    "city": "San Francisco",
    //    "state": "CA",
    //	  "address1": "159 Campolindo Road",
    //    "address2": "",
    //    "postalCode": "00446",
    //    "countryCode": "US",
    //    "bedrooms": 1,
    //    "bathrooms": "1.5",
    //    "picture": "http://platform.hostfully.com/img/littlehouse.png",
    //    "webLink": "hostfully.com",
    //    "cleaningFeeAmount": 10.0,
    //    "minimumStay": 2,
    //    "maximumStay": 10,
    //    "securityDepositAmount": 0.0,
    //    "externalID": null,
    //    "acceptInstantBook": false,
    //    "acceptBookingRequest": false,
    //	  "availabilityCalendarUrl" : "https://platform.hostfully.com/calendar/232.ics",
    //    "pricingRules": {
    //      "increaseRateLowerBound": 6,
    //      "decreaseRateHigherBound": 10,
    //      "increaseRate": 15,
    //      "decreaseRate": 20
    //    },
    //    "rentalCondition": null,
    //    "cancellationPolicy": "50% in the last 48 hours before check in, 90% before that",
    //    "floor": 0,
    //  	"areaSize": 30,
    //	  "areaSizeUnit": "SQUARE_METERS",
    //    "extraGuestFee": 0.0,
    //    "taxationRate": 0.0,
    //	  "longitude" : 48.1914945,
    //	  "latitude" : 19.1914945,
    //    "airBnBID": "1234",
    //    "flipkeyID": "1234",
    //    "homeAwayID": "1234",
    //    "currency": "USD",
    //    "currencySymbol": "$",
    //    "panoramicDataUrl": null,
    //		"panoramicDataUrl": "https://my.matterport.com/show/?m=123456",
    //	  "cancellationPolicy": "Here is the cancellation policy.",
    //	  "propertyURL": "https://platform.hostfully.com/vacation-rental-property/232/haight-place",
    //	  "guideBookUrl": "https://v2.hostfully.com/california-dreaming",
    //    "weekEndRatePercentAdjustment": 1.0,
    //    "bookingWindow": -1,
    //    "bookingWindowAfterCheckout": -1,
    //    "turnOverDays": 0,
    //    "bookingLeadTime": 48,
    //    "defaultCheckinTime": 15,
    //    "defaultCheckoutTime": 11,
    //    "wifiNetwork": "SSID",
    //    "wifiPassword": "12345678",
    //    "rentalLicenseNumberExpirationDate": "2018-03-13",
    //    "rentalLicenseNumber": "TL 90695 R1",
    //    "minimumWeekendStay": 3,
    //    "reviews": {
    //        "total": 1,
    //        "average": 4.0
    //    },
    //    "createdDate": 1592496125000,
    //    "percentUponReservation": 50,
    //    "fullPaymentTiming": 45,
    //    "listingLinks": {
    //        "airbnbUrl": null,
    //        "hostfullyUrl": "https://platform.hostfully.com/vacation-rental-property/232/haight-place",
    //        "bookingDotComUrl": null,
    //        "homeAwayUrl": null,
    //        "tripAdvisorUrl": null
    //    },
    //    "uid": "1851c84a-9212-4710-a6a4-5ad90bf6525f"
    // }
}
