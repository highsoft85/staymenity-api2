<?php

declare(strict_types=1);

if (!function_exists('coordinatesNewYork')) {
    /**
     * @return array
     */
    function coordinatesNewYork()
    {
        return [40.7127281, -74.0060152];
    }
}
if (!function_exists('coordinatesMapNewYork')) {
    /**
     * @return array
     */
    function coordinatesMapNewYork()
    {
        return [
            [
                'latitude' => 40.7437793,
                'longitude' => -74.0667795,
            ], [
                'latitude' => 40.6651418,
                'longitude' => -73.9435978,
            ],
        ];
    }
}
if (!function_exists('coordinatesNewYork2')) {
    /**
     * В < 1 милях от coordinatesNewYork()
     *
     * @return array
     */
    function coordinatesNewYork2()
    {
        return [40.7127281, -74.0020152];
    }
}
if (!function_exists('coordinatesNewYork3')) {
    /**
     * В < 5 милях от coordinatesNewYork()
     *
     * @return array
     */
    function coordinatesNewYork3()
    {
        return [40.6406581, -74.0140152];
    }
}
if (!function_exists('coordinatesNewYork4')) {
    /**
     *
     * @return array
     */
    function coordinatesNewYork4()
    {
        return [40.7184063, -73.6869473];
    }
}
if (!function_exists('coordinatesMapNewYork4')) {
    /**
     * @return array
     */
    function coordinatesMapNewYork4()
    {
        return [
            [
                'latitude' => 40.7672106,
                'longitude' => -73.7654254,
            ], [
                'latitude' => 40.6537371,
                'longitude' => -73.5490435,
            ],
        ];
    }
}
if (!function_exists('coordinatesLosAngeles')) {
    /**
     * @return array
     */
    function coordinatesLosAngeles()
    {
        return [32.71571100, -117.15461400];
    }
}
if (!function_exists('coordinatesLosAngelesDefault')) {
    /**
     * @return array
     */
    function coordinatesLosAngelesDefault()
    {
        return [34.0536909, -118.242766];
    }
}
if (!function_exists('coordinatesNewYorkCommon')) {
    /**
     * @param int $type
     * @return array
     */
    function coordinatesNewYorkCommon(int $type)
    {
        switch ($type) {
            case 0:
                // 7 East 9th Street, New York, NY 10003, United States of America
                return [40.73232796, -73.99538810];
            case 1:
                // 23-47 99th Street, New York, NY 11369, United States of America
                return [40.76724800, -73.87125685];
            case 2:
                // 75 Prologis Cargo Center, North Hangar Road, New York, NY 11430, United States of America
                return [40.66004019, -73.77837179];
            case 3:
                // 337 East 13th Street, New York, NY 10003, United States of America
                return [40.73108742, -73.98366148];
            case 4:
                // 160 West End Avenue, New York, NY 10023, United States of America
                return [40.77598422, -73.98504399];
            case 5:
                return [40.79882874, -73.93773185];
            case 6:
                return [40.72966977, -74.04094325];
            case 7:
                return [40.72938411, -74.00886403];
            case 8:
                return [40.75661316, -73.87474116];
            case 9:
                return [40.68258837, -73.85230357];
            case 10:
                return [40.63292568, -73.98824896];
            case 11:
                return [40.65976414, -74.10254867];
            case 12:
                return [41.01997414, -73.74001899];
            case 13:
                return [40.96170278, -73.70980659];
            case 14:
                return [40.78866499, -73.54226509];
            case 15:
                return [40.86002586, -73.99643663];
            case 16:
                return [40.79743782, -74.09806016];
            case 17:
                return [40.62606023, -74.31504014];
            case 18:
                return [40.68672018, -74.35349229];
            case 19:
                return [40.68672010, -74.35349229];
            case 20:
                return [40.66371777, -73.79318955];
        }
        return [40.78073762, -73.67508652];
    }
}

if (!function_exists('placeFake')) {
    /**
     * @return string
     */
    function placeFake()
    {
        return 'test--EiUyMi00NiA3OHRoIFN0cmVldCwgV29vZGhhdmVuLCBOWSwgVVNBIjASLgoUChIJ6a7WYOhdwokR3-oHKfNKPPUQFioUChIJx6lgnutdwokRTXrYkztn6eI';
    }
}
if (!function_exists('placeFake2')) {
    /**
     * @return string
     */
    function placeFake2()
    {
        return 'test--ChIJzZnT5sdEwokRQNLupyT-JzU';
    }
}
if (!function_exists('placeResultFake')) {
    /**
     * @return array
     */
    function placeResultFake()
    {
        return [
            'title' => '90-22 78th St',
            'description' => '90-22 78th St, Woodhaven, NY 11421, USA',
            'country' => [
                'title' => 'United States',
                'code' => 'US',
            ],
            'state' => [
                'title' => 'New York',
                'code' => 'NY',
            ],
            'city' => 'Woodhaven',
            'zip' => '11421',
            'coordinates' => [
                'latitude' => 40.6872293,
                'longitude' => -73.8630396,
            ],
            'point' => [
                40.6872293,
                -73.8630396,
            ],
        ];
    }
}
if (!function_exists('placeResultFake2')) {
    /**
     * @return array
     */
    function placeResultFake2()
    {
        return [
            'title' => '1095 E 15th St',
            'description' => '1095 E 15th St, Brooklyn, NY 11230, USA',
            'country' => [
                'title' => 'United States',
                'code' => 'US',
            ],
            'state' => [
                'title' => 'New York',
                'code' => 'NY',
            ],
            'city' => 'Midwood',
            'zip' => '11230',
            'coordinates' => [
                'latitude' => 40.6230297,
                'longitude' => -73.96075669999999,
            ],
            'point' => [
                40.6230297,
                -73.96075669999999,
            ],
        ];
    }
}
if (!function_exists('addressFake')) {
    /**
     * @return string
     */
    function addressFake()
    {
        return 'test--22-46+78th+St';
    }
}
if (!function_exists('addressFake2')) {
    /**
     * @return string
     */
    function addressFake2()
    {
        return 'test--1095 E 15th St';
    }
}
