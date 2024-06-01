<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

use App\Services\Environment;
use Illuminate\Support\Str;

class GeocoderTimezoneService
{
    /**
     * @var string|null
     */
    private $key;

    /**
     * GeocoderCitiesService constructor.
     */
    public function __construct()
    {
        $this->key = config('services.google_maps.key');
    }

    /**
     * @param array $point
     * @return string|null
     */
    public function byCoordinates(array $point)
    {
        if (is_null($this->key) && config('app.env') === Environment::DOCUMENTATION) {
            return null;
        }
        $request = "https://maps.googleapis.com/maps/api/timezone/json?";
        $params = [
            'location' => $point[0] . ',' . $point[1],
            'timestamp' => now()->timestamp,
            'key' => $this->key,
            'language' => 'en',
            'sessiontoken' => Str::random(16),
        ];
        $request .= http_build_query($params);
        $json = file_get_contents($request);
        $data = json_decode($json, true);
        $timezone = $data['timeZoneId'] ?? null;
        if (is_null($timezone)) {
            slackInfo($data);
        }
        return $timezone;
    }
//
//    public function directions()
//    {
//        $response = \GoogleMaps::load('directions')
//            ->setParam([
//                'origin' => '47.28745496102738,39.71900608121079', // lat 47.28745496102738 lng 39.71900608121079
//                'destination' => '47.29092134136098,39.699672650459576', // lat 47.29092134136098 lng 39.699672650459576
//                // lat 47.29005308471468 lng 39.71332437783677
//                'waypoints' => [['location' => '47.29005308471468,39.71332437783677', 'stopover' => false]]
//            ])
//            ->get();
//        dd($response);
//    }
}
