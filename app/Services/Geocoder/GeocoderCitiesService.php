<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

use App\Services\Environment;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Str;
use SKAgarwal\GoogleApi\PlacesApi;

class GeocoderCitiesService
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
     * @param string $address
     * @return array
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function address(string $address)
    {
        if (is_null($this->key) && config('app.env') === Environment::DOCUMENTATION) {
            return $this->fakeAddressesForDocumentation();
        }
        $googlePlaces = new PlacesApi($this->key);
        $response = $googlePlaces->placeAutocomplete($address);
        $aHits = $response['predictions'];
        return $aHits->transform(function ($item) {
            return $this->transformFromGoogle($item);
        })->toArray();
    }

    /**
     * @param string $address
     * @return array
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function city(string $address)
    {
        if (is_null($this->key) && config('app.env') === Environment::DOCUMENTATION) {
            return $this->fakeCityForDocumentation();
        }
        $googlePlaces = new PlacesApi($this->key);
        $response = $googlePlaces->placeAutocomplete($address, [
            'types' => '(cities)',
        ]);
        $aHits = $response['predictions'];
        return $aHits->transform(function ($item) {
            return $this->transformFromGoogle($item);
        })->toArray();
    }

    /**
     * @param string $place_id
     * @return array
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function place(string $place_id)
    {
        if (is_null($this->key) && config('app.env') === Environment::DOCUMENTATION) {
            return $this->fakePlaceForDocumentation();
        }
        $googlePlaces = new PlacesApi($this->key);
        $response = $googlePlaces->placeDetails($place_id);
        $result = $response['result'];
        return $this->transformPlaceDetail($result);
    }

    /**
     * @param string $place_id
     * @return string
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function timezoneByPlace(string $place_id)
    {
        if (is_null($this->key) && config('app.env') === Environment::DOCUMENTATION) {
            return config('app.timezone');
        }
        if ($place_id === placeFake()) {
            return config('app.timezone');
        }
        $googlePlaces = new PlacesApi($this->key);
        $response = $googlePlaces->placeDetails($place_id);
        $result = $response['result'];
        return CarbonTimeZone::createFromMinuteOffset($result['utc_offset'])->toRegionName(null, 0);
    }

    /**
     * @param array $point
     * @return string|null
     */
    public function addressByPoint(array $point)
    {
        if (is_null($this->key) && config('app.env') === Environment::DOCUMENTATION) {
            return null;
        }
        $request = "https://maps.googleapis.com/maps/api/geocode/json?";
        $params = [
            'latlng' => $point[0] . ',' . $point[1],
            'key' => $this->key,
            'language ' => 'en',
            'sessiontoken' => Str::random(16),
        ];
        $request .= http_build_query($params);
        $json = file_get_contents($request);
        $data = json_decode($json, true);
        return $data['results'][0]['formatted_address'] ?? null;
    }

    /**
     * @param array $data
     * @return array
     */
    public function transformFromGoogle(array $data = [])
    {
        $description = $data['description'];
        $original = $data['structured_formatting']['main_text'];
        return [
            'title' => $original,
            'description' => $description,
            'place_id' => $data['place_id'],
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function transformPlaceDetail(array $data = [])
    {
        //dd($data);
        $name = $data['name'];
        $address = $data['formatted_address'];
        $country = $this->getCountryFromPlaceDetails($data['address_components']);
        $state = $this->getStateFromPlaceDetails($data['address_components']);
        $zip = $this->getZipFromPlaceDetails($data['address_components']);
        $city = $this->getCityFromPlaceDetails($data['address_components']);
        if (is_null($city)) {
            $city = $this->getCityFromPlaceDetails($data['address_components'], ['locality']);
        }
        $latitude = $data['geometry']['location']['lat'];
        $longitude = $data['geometry']['location']['lng'];
        $coordinates = !is_null($latitude) && !is_null($longitude) ? [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ] : null;
        return [
            'title' => $name,
            'description' => $address,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'zip' => $zip,
            'coordinates' => $coordinates,
            'point' => [$latitude, $longitude],
        ];
    }

    /**
     * @param array $components
     * @return array|null
     */
    private function getCountryFromPlaceDetails(array $components = [])
    {
        $country = null;
        foreach ($components as $component) {
            if (in_array('country', $component['types'])) {
                $country = [
                    'title' => $component['long_name'],
                    'code' => $component['short_name'],
                ];
            }
        }
        return $country;
    }

    /**
     * @param array $components
     * @return array|null
     */
    private function getStateFromPlaceDetails(array $components = [])
    {
        $state = null;
        foreach ($components as $component) {
            if (in_array('administrative_area_level_1', $component['types'])) {
                $state = [
                    'title' => $component['long_name'],
                    'code' => $component['short_name'],
                ];
            }
        }
        return $state;
    }

    /**
     * @param array $components
     * @param array $types
     * @return array|null
     */
    private function getCityFromPlaceDetails(array $components = [], array $types = ['political', 'neighborhood'])
    {
        $city = null;
        $count = count($types);
        $current = 0;
        foreach ($components as $component) {
            foreach ($types as $type) {
                if (in_array($type, $component['types'])) {
                    $current++;
                    if ($current === $count) {
                        $city = $component['long_name'];
                    }
                }
            }
            $current = 0;
        }
        return $city;
    }

    /**
     * @param array $components
     * @return array|null
     */
    private function getZipFromPlaceDetails(array $components = [])
    {
        $state = null;
        foreach ($components as $component) {
            if (in_array('postal_code', $component['types'])) {
                $state = $component['short_name'];
            }
        }
        return $state;
    }

    /**
     * @return array
     */
    public function fakeAddressesForDocumentation()
    {
        // что возвращает гугл
        $data = [
            "description" => "222-46 78th Street, Brooklyn, NY, USA",
            "matched_substrings" => [
                0 => [
                    "length" => 18,
                    "offset" => 0,
                ],
            ],
            "place_id" => "EiUyMjItNDYgNzh0aCBTdHJlZXQsIEJyb29rbHluLCBOWSwgVVNBIjESLwoUChIJI7x_IPZPwokRkN-edtX3JjEQ3gEqFAoSCUn2x4FpRcKJEfjr6aYS8bao",
            "reference" => "EiUyMjItNDYgNzh0aCBTdHJlZXQsIEJyb29rbHluLCBOWSwgVVNBIjESLwoUChIJI7x_IPZPwokRkN-edtX3JjEQ3gEqFAoSCUn2x4FpRcKJEfjr6aYS8bao",
            "structured_formatting" => [
                "main_text" => "222-46 78th Street",
                "main_text_matched_substrings" => [
                    0 => [
                        "length" => 18,
                        "offset" => 0,
                    ],
                ],
                "secondary_text" => "Brooklyn, NY, USA",
            ],
            "terms" => [
                0 => [
                    "offset" => 0,
                    "value" => "222-46 78th Street",
                ],
                1 => [
                    "offset" => 20,
                    "value" => "Brooklyn",
                ],
                2 => [
                    "offset" => 30,
                    "value" => "NY",
                ],
                3 => [
                    "offset" => 34,
                    "value" => "USA",
                ],
            ],
            "types" => [
                0 => "street_address",
                1 => "geocode",
            ],
        ];
        return [
            $this->transformFromGoogle($data),
        ];
    }

    /**
     * @return array
     */
    public function fakePlaceForDocumentation()
    {
        $data = [
            "address_components" => [
                0 => [
                    "long_name" => "22",
                    "short_name" => "22",
                    "types" => [
                        0 => "street_number",
                    ],
                ],
                1 => [
                    "long_name" => "78th Street",
                    "short_name" => "78th St",
                    "types" => [
                        0 => "route",
                    ],
                ],
                2 => [
                    "long_name" => "North Bergen",
                    "short_name" => "North Bergen",
                    "types" => [
                        0 => "locality",
                        1 => "political",
                    ],
                ],
                3 => [
                    "long_name" => "Hudson County",
                    "short_name" => "Hudson County",
                    "types" => [
                        0 => "administrative_area_level_2",
                        1 => "political",
                    ],
                ],
                4 => [
                    "long_name" => "New Jersey",
                    "short_name" => "NJ",
                    "types" => [
                        0 => "administrative_area_level_1",
                        1 => "political",
                    ],
                ],
                5 => [
                    "long_name" => "United States",
                    "short_name" => "US",
                    "types" => [
                        0 => "country",
                        1 => "political",
                    ],
                ],
                6 => [
                    "long_name" => "07047",
                    "short_name" => "07047",
                    "types" => [
                        0 => "postal_code",
                    ],
                ],
            ],
            "adr_address" => '<span class="street-address">22 78th St</span>, <span class="locality">North Bergen</span>, <span class="region">NJ</span> <span class="postal-code">07047</span>, <span class="country-name">USA</span>',
            "formatted_address" => "22 78th St, North Bergen, NJ 07047, USA",
            "geometry" => [
                "location" => [
                    "lat" => 40.7973662,
                    "lng" => -73.9991572,
                ],
                "viewport" => [
                    "northeast" => [
                        "lat" => 40.798621130292,
                        "lng" => -73.997884069709,
                    ],
                    "southwest" => [
                        "lat" => 40.795923169709,
                        "lng" => -74.000582030292,
                    ],
                ],
            ],
            "icon" => "https://maps.gstatic.com/mapfiles/place_api/icons/v1/png_71/geocode-71.png",
            "name" => "22 78th St",
            "place_id" => "EicyMiA3OHRoIFN0LCBOb3J0aCBCZXJnZW4sIE5KIDA3MDQ3LCBVU0EiMBIuChQKEglXqnCf2PfCiRFBiHSzvcgCEhAWKhQKEgnrlL7y4PfCiRHnlTtfqB_nCw",
            "reference" => "EicyMiA3OHRoIFN0LCBOb3J0aCBCZXJnZW4sIE5KIDA3MDQ3LCBVU0EiMBIuChQKEglXqnCf2PfCiRFBiHSzvcgCEhAWKhQKEgnrlL7y4PfCiRHnlTtfqB_nCw",
            "types" => [
                0 => "street_address",
            ],
            "url" => "https://maps.google.com/?q=22+78th+St,+North+Bergen,+NJ+07047,+USA&ftid=0x89c2f7d89f70aa57:0x4f2f63a94dbb142",
            "utc_offset" => -240,
            "vicinity" => "North Bergen",
        ];
        return $this->transformPlaceDetail($data);
    }

    /**
     * @return array
     */
    public function fakeCityForDocumentation()
    {
        $aHits = [
            [
                "description" => "Barcelona, Spain",
                "matched_substrings" => [
                    0 => [
                        "length" => 5,
                        "offset" => 0,
                    ],
                ],
                "place_id" => "ChIJ5TCOcRaYpBIRCmZHTz37sEQ",
                "reference" => "ChIJ5TCOcRaYpBIRCmZHTz37sEQ",
                "structured_formatting" => [
                    "main_text" => "Barcelona",
                    "main_text_matched_substrings" => [
                        0 => [
                            "length" => 5,
                            "offset" => 0,
                        ],
                    ],
                    "secondary_text" => "Spain",
                ],
                "terms" => [
                    0 => [
                        "offset" => 0,
                        "value" => "Barcelona",
                    ],
                    1 => [
                        "offset" => 11,
                        "value" => "Spain",
                    ],
                ],
                "types" => [
                    0 => "locality",
                    1 => "political",
                    2 => "geocode",
                ],
            ],
        ];
        return collect($aHits)->transform(function ($item) {
            return $this->transformFromGoogle($item);
        })->toArray();
    }
}
