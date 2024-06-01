<?php

declare(strict_types=1);

namespace App\Services\Hostfully;

use App\Services\Hostfully\Models\Leads;

trait MockLeadsHostfullyTrait
{
    /**
     * @return array
     */
    protected function mockGetLeads()
    {
        $data = session()->get('testing-data');
        return [
            [
                "leadType" => $data[Leads::LEAD_TYPE] ?? "BOOKING",
                "uid" => hostfullyFakeLeadUid(),
                "propertyUid" => hostfullyFakePropertyUid(),
                "childrenCount" => 0,
                "adultCount" => 4,
                "petCount" => 0,
                "checkInDate" => $data[Leads::CHECK_IN_DATE] ?? now()->addDays(1)->format('Y-m-d'),
                "checkOutDate" => $data[Leads::CHECK_OUT_DATE] ?? now()->addDays(2)->format('Y-m-d'),
                "status" => $data[Leads::STATUS] ?? "BOOKED_BY_AGENT",
                "source" => "ORBIRENTAL_API",
                "firstName" => "Lennie",
                "lastName" => "West",
                "notes" => "21:00 - 22:00\n(1 hour)",
                "email" => "HIDDEN",
                "phoneNumber" => "HIDDEN",
                "externalBookingID" => null,
                "preferredCurrency" => null,
                "city" => null,
                "state" => null,
                "booked" => "2021-04-15 18 =>48",
                "preferredLocale" => null,
            ],
        ];
    }

    /**
     * @param string $uid
     * @return array
     */
    protected function mockGetLead(string $uid)
    {
        return [
            "leadType" => "BOOKING",
            "uid" => hostfullyFakeLeadUid(),
            "propertyUid" => hostfullyFakePropertyUid(),
            "childrenCount" => 0,
            "adultCount" => 4,
            "petCount" => 0,
            "checkInDate" => "2021-04-19",
            "checkOutDate" => "2021-04-20",
            "status" => "BOOKED_BY_AGENT",
            "source" => "ORBIRENTAL_API",
            "firstName" => "Lennie",
            "lastName" => "West",
            "notes" => "21:00 - 22:00\n(1 hour)",
            "email" => "HIDDEN",
            "phoneNumber" => "HIDDEN",
            "externalBookingID" => null,
            "preferredCurrency" => null,
            "city" => null,
            "state" => null,
            "booked" => "2021-04-15 18 =>48",
            "preferredLocale" => null,
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function mockCreateLead(array $data)
    {
        return [
//            "leadType" => $data['leadType'],
//            "uid" => hostfullyFakeLeadUid(),
//            "propertyUid" => hostfullyFakePropertyUid(),
//            "childrenCount" => 0,
//            "adultCount" => 4,
//            "petCount" => 0,
//            "checkInDate" => $data['checkInDate'],
//            "checkOutDate" => $data['checkOutDate'],
//            "status" => $data['status'],
//            "source" => "ORBIRENTAL_API",
//            "firstName" => "Lennie",
//            "lastName" => "West",
//            "notes" => "21:00 - 22:00\n(1 hour)",
//            "email" => "HIDDEN",
//            "phoneNumber" => "HIDDEN",
//            "externalBookingID" => null,
//            "preferredCurrency" => null,
//            "city" => null,
//            "state" => null,
//            "booked" => "2021-04-15 18:48",
//            "preferredLocale" => null,

            "notes" => "",
            "agency" => [
                "uid" => hostfullyFakeAgencyUid(),
                "name" => "AG.digital",
            ],
            "adultCount" => 2,
            "created" => "2021-04-23 07:30:50.0",
            "source" => "ORBIRENTAL_FORM",
            "checkInDate" => $data['checkInDate'],
            "uid" => hostfullyFakeLeadUid(),
            "checkOutDate" => $data['checkOutDate'],
            "quoteAmount" => 3102.5,
            "property" => [
                "city" => "San Francisco",
                "webLink" => "",
                "postalCode" => "94117",
                "latitude" => 37.768728,
                "externalID" => "",
                "type" => "SAMPLE",
                "photos" => [],
                "uid" => hostfullyFakePropertyUid(),
                "maximumGuests" => 8,
                "cleaningFeeAmount" => 150,
                "countryCode" => "US",
                "securityDepositAmount" => 500,
                "currency" => "USD",
                "state" => "California",
                "baseGuests" => 6,
                "floor" => 3,
                "availabilityCalendarUrl" => "https://sandbox.hostfully.com/calendar/29249.ics",
                "areaSize" => 3000,
                "minimumStay" => 1,
                "longitude" => -122.448056,
                "address2" => "",
                "address1" => "1485 Waller st",
                "bathrooms" => 2,
                "picture" => "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRfTA6qg3wFq2p6hf8jI9a68AR_tmA1mEmAnL3xijG6742a3r1B",
                "bedrooms" => 3,
                "acceptInstantBook" => false,
                "areaSizeUnit" => "SQUARE_FEET",
                "name" => "Victorian House (Sample)",
                "baseDailyRate" => 450,
            ],
            "stayDetails" => [
                "extraNotes" => "",
                "departureDate" => "2021-05-01 11:00:00.0",
                "arrivalDate" => "2021-04-26 15:00:00.0",
            ],
            "modified" => "2021-04-23 07:30:50.0",
            "email" => "dposkachei3@yandex.ru",
            "status" => $data['status'],
            "childrenCount" => 0,
            "petCount" => 0,
            "infantCount" => 0,
        ];
    }

    /**
     * @param string $uid
     * @param array $data
     * @return array
     */
    protected function mockUpdateLead(string $uid, array $data)
    {
        return [
//            "leadType" => $data['leadType'],
//            "uid" => hostfullyFakeLeadUid(),
//            "propertyUid" => hostfullyFakePropertyUid(),
//            "childrenCount" => 0,
//            "adultCount" => 4,
//            "petCount" => 0,
//            "checkInDate" => "2021-04-19",
//            "checkOutDate" => "2021-04-20",
//            "status" => $data['status'],
//            "source" => "ORBIRENTAL_API",
//            "firstName" => "Lennie",
//            "lastName" => "West",
//            "notes" => "21:00 - 22:00\n(1 hour)",
//            "email" => "HIDDEN",
//            "phoneNumber" => "HIDDEN",
//            "externalBookingID" => null,
//            "preferredCurrency" => null,
//            "city" => null,
//            "state" => null,
//            "booked" => "2021-04-15 18 =>48",
//            "preferredLocale" => null,

            "notes" => "",
            "agency" => [
                "uid" => hostfullyFakeAgencyUid(),
                "name" => "AG.digital",
            ],
            "adultCount" => 2,
            "created" => "2021-04-23 07:30:50.0",
            "source" => "ORBIRENTAL_FORM",
            "checkInDate" => "2021-04-19",
            "uid" => hostfullyFakeLeadUid(),
            "checkOutDate" => "2021-04-20",
            "quoteAmount" => 3102.5,
            "property" => [
                "city" => "San Francisco",
                "webLink" => "",
                "postalCode" => "94117",
                "latitude" => 37.768728,
                "externalID" => "",
                "type" => "SAMPLE",
                "photos" => [],
                "uid" => hostfullyFakePropertyUid(),
                "maximumGuests" => 8,
                "cleaningFeeAmount" => 150,
                "countryCode" => "US",
                "securityDepositAmount" => 500,
                "currency" => "USD",
                "state" => "California",
                "baseGuests" => 6,
                "floor" => 3,
                "availabilityCalendarUrl" => "https://sandbox.hostfully.com/calendar/29249.ics",
                "areaSize" => 3000,
                "minimumStay" => 1,
                "longitude" => -122.448056,
                "address2" => "",
                "address1" => "1485 Waller st",
                "bathrooms" => 2,
                "picture" => "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRfTA6qg3wFq2p6hf8jI9a68AR_tmA1mEmAnL3xijG6742a3r1B",
                "bedrooms" => 3,
                "acceptInstantBook" => false,
                "areaSizeUnit" => "SQUARE_FEET",
                "name" => "Victorian House (Sample)",
                "baseDailyRate" => 450,
            ],
            "stayDetails" => [
                "extraNotes" => "",
                "departureDate" => "2021-05-01 11:00:00.0",
                "arrivalDate" => "2021-04-26 15:00:00.0",
            ],
            "modified" => "2021-04-23 07:30:50.0",
            "email" => "dposkachei3@yandex.ru",
            "status" => $data['status'],
            "childrenCount" => 0,
            "petCount" => 0,
            "infantCount" => 0,
        ];
    }
}
