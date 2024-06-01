<?php

declare(strict_types=1);

namespace App\Services\Hostfully\Models;

class LeadsV1
{
    const NOTES = 'notes';
    const UID = 'uid';

    // AGENCY
    const AGENCY = 'agency';
    const AGENCY_UID = 'uid';
    const AGENCY_NAME = 'name';

    const ADULT_COUNT = 'adultCount';
    const CREATED = 'created';
    const SOURCE = 'source';
    const CHECK_IN_DATE = 'checkInDate';
    const CHECK_OUT_DATE = 'checkOutDate';
    const QUOTE_AMOUNT = 'quoteAmount';
    const CHILDREN_COUNT = 'childrenCount';
    const STATUS = 'status';
    const EMAIL = 'email';

    // PROPERTY
    const PROPERTY = 'property';
    const PROPERTY_UID = 'uid';
    const PROPERTY_NAME = 'name';
    const PROPERTY_BASE_DAILY_RATE = 'baseDailyRate';
    const PROPERTY_CITY = 'city';
    const PROPERTY_WEB_LINK = 'webLink';
    const PROPERTY_POSTAL_CODE = 'postalCode';
    const PROPERTY_LATITUDE = 'latitude';
    const PROPERTY_LONGITUDE = 'longitude';
    const PROPERTY_EXTERNAL_ID = 'externalID';
    const PROPERTY_TYPE = 'type';
    const PROPERTY_PHOTOS = 'photos';
    const PROPERTY_MAXIMUM_GUESTS = 'maximumGuests';
    const PROPERTY_CLEANING_FEE_AMOUNT = 'cleaningFeeAmount';
    const PROPERTY_COUNTRY_CODE = 'countryCode';
    const PROPERTY_SECURITY_DEPOSIT_AMOUNT = 'securityDepositAmount';
    const PROPERTY_CURRENCY = 'currency';
    const PROPERTY_STATE = 'state';
    const PROPERTY_BASE_GUESTS = 'baseGuests';
    const PROPERTY_FLOOR = 'floor';
    const PROPERTY_AVAILABILITY_CALENDAR_URL = 'availabilityCalendarUrl';
    const PROPERTY_AREA_SIZE = 'areaSize';
    const PROPERTY_MINIMUM_STAY = 'minimumStay';
    const PROPERTY_ADDRESS_1 = 'address1';
    const PROPERTY_BATHROOMS = 'bathrooms';
    const PROPERTY_PICTURE = 'picture';
    const PROPERTY_BEDROOMS = 'bedrooms';
    const PROPERTY_ACCEPT_INSTANT_BOOK = 'acceptInstantBook';


    const STATUS_NEW = 'NEW';
    const STATUS_ON_HOLD = 'ON_HOLD';
    const STATUS_BOOKED = 'BOOKED';
    const STATUS_DECLINED = 'DECLINED';
    const STATUS_PAID_IN_FULL = 'PAID_IN_FULL';
    const STATUS_PENDING = 'PENDING';
    const STATUS_IGNORED = 'IGNORED';
    const STATUS_BLOCKED = 'BLOCKED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_CANCELLED_BY_TRAVELER = 'CANCELLED_BY_TRAVELER';
    const STATUS_CANCELLED_BY_OWNER = 'CANCELLED_BY_OWNER';
    const STATUS_HOLD_EXPIRED = 'HOLD_EXPIRED';

    // {
    //        "notes": "00:00 - 00:00\n(48 hours)",
    //        "agency": {
    //            "uid": "d89494f8-37e5-40dd-844b-0e847b9bcee4",
    //            "name": "AG.digital"
    //        },
    //        "adultCount": 3,
    //        "created": "2021-04-19 15:19:41.0",
    //        "source": "ORBIRENTAL_API",
    //        "checkInDate": "2021-07-04",
    //        "uid": "d42ca5d3-f81b-4c3d-9955-ec1fd7a7438d",
    //        "checkOutDate": "2021-07-06",
    //        "quoteAmount": 1104,
    //        "property": {
    //            "city": "Randall Manor",
    //            "webLink": "http://localhost/barbecue/champlin-streich-3",
    //            "postalCode": "10310",
    //            "latitude": 40.6377688,
    //            "externalID": "3",
    //            "type": "HOUSE",
    //            "photos": [],
    //            "uid": "ed8053ec-f87f-4095-8387-57dfecb303fc",
    //            "maximumGuests": 10,
    //            "cleaningFeeAmount": 0,
    //            "countryCode": "US",
    //            "securityDepositAmount": 0,
    //            "currency": "USD",
    //            "state": "New York",
    //            "baseGuests": 1,
    //            "floor": 0,
    //            "availabilityCalendarUrl": "https://sandbox.hostfully.com/calendar/29268.ics",
    //            "areaSize": 0,
    //            "minimumStay": 2,
    //            "longitude": -74.1054829,
    //            "address1": "23 Moody Pl, Staten Island, NY 10310, USA",
    //            "bathrooms": 0,
    //            "picture": "https://images.staymenity.com/storage/images/listing/34/model/xl/IidQbzdsSvxQ.jpg",
    //            "bedrooms": 0,
    //            "acceptInstantBook": false,
    //            "name": "Champlin-Streich",
    //            "baseDailyRate": 552
    //        },
    //        "stayDetails": {},
    //        "modified": "2021-04-19 15:19:42.0",
    //        "email": "08xmxp_6q3ct9_hostfully@staymenity.com",
    //        "status": "BOOKED",
    //        "childrenCount": 0,
    //        "petCount": 0,
    //        "infantCount": 0
    //    },
}
