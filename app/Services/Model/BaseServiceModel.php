<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Listing;
use App\Models\Location;
use App\Models\User;
use App\Services\Geocoder\GeocoderCitiesService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

abstract class BaseServiceModel
{
    /**
     * @param mixed|User|Listing $oItem
     * @param string $place_id
     * @param array $data
     * @return Location|null
     */
    public function baseSaveLocation($oItem, string $place_id, array $data = [])
    {
        try {
            if ($place_id === placeFake()) {
                $result = placeResultFake();
            } elseif ($place_id === placeFake2()) {
                $result = placeResultFake2();
            } else {
                $result = (new GeocoderCitiesService())->place($place_id);
            }
            $data = array_merge([
                'type' => Location::TYPE_DEFAULT,
                'zoom' => 11,
                'place_id' => $place_id,
                'title' => $result['title'],
                'text' => $result['description'],
                'address' => $result['description'],
                'country' => $result['country']['title'],
                'country_code' => $result['country']['code'],
                'point' => $result['point'],
                'locality' => $result['city'] ?? null,
                'province' => $result['state']['title'] ?? null,
                'province_code' => !is_null($result['state']['title'])
                    ? statesShortNames($result['state']['title'])
                    : null,
                'zip' => $result['zip'] ?? null,
            ], $data);
            if (!is_null($oItem->location)) {
                $data['latitude'] = $data['point'][0];
                $data['longitude'] = $data['point'][1];
                $data['point'] = new Point($data['point'][0], $data['point'][1], Location::SRID);
                $oItem->location()->update($data);
            } else {
                $oItem->location()->create($data);
            }
            /** @var Location $oLocation */
            $oLocation = $oItem->location()->first();
            return $oLocation;
        } catch (GooglePlacesApiException $e) {
            //dd($e->getErrorMessage());
            return null;
        }
    }

    /**
     * @param mixed|User|Listing $oItem
     * @param string $address
     * @param array $data
     * @return Location|null
     * @throws GooglePlacesApiException
     */
    public function baseSaveLocationByAddress($oItem, string $address, array $data = [])
    {
        $results = (new GeocoderCitiesService())->address($address);
        if (empty($results)) {
            return null;
        }
        $result = $results[0];
        return $this->baseSaveLocation($oItem, $result['place_id'], $data);
    }

    /**
     * @param mixed|User|Listing $oItem
     * @param array $point
     * @param array $data
     * @return Location|null
     * @throws GooglePlacesApiException
     */
    public function baseSaveLocationByPoint($oItem, array $point, array $data = [])
    {
        $address = (new GeocoderCitiesService())->addressByPoint($point);
        return $this->baseSaveLocationByAddress($oItem, $address, $data);
    }
}
