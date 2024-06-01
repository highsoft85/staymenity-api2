<?php

declare(strict_types=1);

if (!function_exists('distance')) {

    /**
     * Дистанция между двумя точками
     *
     *
     * @param array $point1
     * @param array $point2
     * @param string $unit
     * @return float
     */
    function distance(array $point1, array $point2, string $unit = 'M')
    {
        $lat1 = $point1[0];
        $lon1 = $point1[1];
        $lat2 = $point2[0];
        $lon2 = $point2[1];

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit === 'K') {
            return ($miles * 1.609344);
        } elseif ($unit === 'N') {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

if (!function_exists('metersToMiles')) {
    /**
     * @param int $value
     * @return float
     */
    function metersToMiles(int $value)
    {
        return $value * 0.000621371192;
    }
}

if (!function_exists('milesToMeters')) {
    /**
     * @param int $value
     * @return float
     */
    function milesToMeters(int $value)
    {
        return $value * 1609.344;
    }
}

if (!function_exists('statesShortNames')) {

    /**
     * Дистанция между двумя точками
     *
     *
     * @param string $state
     * @return string|null
     */
    function statesShortNames(string $state)
    {
        $us_state_abbrevs_names = [
            'AL' => 'ALABAMA',
            'AK' => 'ALASKA',
            'AS' => 'AMERICAN SAMOA',
            'AZ' => 'ARIZONA',
            'AR' => 'ARKANSAS',
            'CA' => 'CALIFORNIA',
            'CO' => 'COLORADO',
            'CT' => 'CONNECTICUT',
            'DE' => 'DELAWARE',
            'DC' => 'DISTRICT OF COLUMBIA',
            'FM' => 'FEDERATED STATES OF MICRONESIA',
            'FL' => 'FLORIDA',
            'GA' => 'GEORGIA',
            'GU' => 'GUAM GU',
            'HI' => 'HAWAII',
            'ID' => 'IDAHO',
            'IL' => 'ILLINOIS',
            'IN' => 'INDIANA',
            'IA' => 'IOWA',
            'KS' => 'KANSAS',
            'KY' => 'KENTUCKY',
            'LA' => 'LOUISIANA',
            'ME' => 'MAINE',
            'MH' => 'MARSHALL ISLANDS',
            'MD' => 'MARYLAND',
            'MA' => 'MASSACHUSETTS',
            'MI' => 'MICHIGAN',
            'MN' => 'MINNESOTA',
            'MS' => 'MISSISSIPPI',
            'MO' => 'MISSOURI',
            'MT' => 'MONTANA',
            'NE' => 'NEBRASKA',
            'NV' => 'NEVADA',
            'NH' => 'NEW HAMPSHIRE',
            'NJ' => 'NEW JERSEY',
            'NM' => 'NEW MEXICO',
            'NY' => 'NEW YORK',
            'NC' => 'NORTH CAROLINA',
            'ND' => 'NORTH DAKOTA',
            'MP' => 'NORTHERN MARIANA ISLANDS',
            'OH' => 'OHIO',
            'OK' => 'OKLAHOMA',
            'OR' => 'OREGON',
            'PW' => 'PALAU',
            'PA' => 'PENNSYLVANIA',
            'PR' => 'PUERTO RICO',
            'RI' => 'RHODE ISLAND',
            'SC' => 'SOUTH CAROLINA',
            'SD' => 'SOUTH DAKOTA',
            'TN' => 'TENNESSEE',
            'TX' => 'TEXAS',
            'UT' => 'UTAH',
            'VT' => 'VERMONT',
            'VI' => 'VIRGIN ISLANDS',
            'VA' => 'VIRGINIA',
            'WA' => 'WASHINGTON',
            'WV' => 'WEST VIRGINIA',
            'WI' => 'WISCONSIN',
            'WY' => 'WYOMING',
            'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
            'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
            'AP' => 'ARMED FORCES PACIFIC',
        ];
        $state = mb_strtoupper($state);
        $state = str_replace('-', '', $state);
        $key = array_search($state, $us_state_abbrevs_names);
        if ($key === false) {
            return null;
        }
        return $key;
    }
}
