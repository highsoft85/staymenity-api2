<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

class GeocoderIpService
{
    /**
     * @param string|null $ip
     * @return array|null
     */
    public function coordinates(?string $ip = null)
    {
        $url = 'https://www.geoip-db.com/json';
        if (!is_null($ip)) {
            $url .= '/' . $ip;
        }
        if ($data = @file_get_contents($url)) {
            $json = json_decode($data, true);
            if ($json['country_code'] === 'Not found') {
                return null;
            }
            $latitude = $json['latitude'];
            $longitude = $json['longitude'];
            return [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        } else {
            return null;
        }
    }

    /**
     * @param string $ip
     * @return mixed|null
     */
    public function timezone(string $ip)
    {
        $url = 'http://ip-api.com/json/' . $ip;
        if ($data = @file_get_contents($url)) {
            $json = json_decode($data, true);
            return $json['timezone'];
        } else {
            return null;
        }
    }
}
