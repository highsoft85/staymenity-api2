<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Location;
use League\Fractal\TransformerAbstract;

class LocationTransformer extends TransformerAbstract
{
    /**
     * @param Location $oItem
     * @return array
     */
    public function transform(Location $oItem)
    {
        $isState = $oItem->country_code === 'US' && !is_null($oItem->province);
        return [
            'id' => $oItem->id,
            'place_id' => $oItem->place_id,
            'point' => $this->point($oItem),
            'primary_point' => $this->primaryPoint($oItem),
            'circle' => $this->circle($oItem),
            'zoom' => $oItem->zoom,
            'title' => $oItem->title,
            'text' => $oItem->address,
            'locality' => $oItem->locality,
            'province' => $oItem->province,
            //'address' => $oItem->address,
            //'locality' => $oItem->locality,
            'country' => $oItem->country,
            'country_code' => $oItem->country_code,
            'state' => $isState ? $oItem->province : null,
            'state_code' => $isState ? statesShortNames($oItem->province) : null,
            'zip' => $oItem->zip,
        ];
    }

    /**
     * @param Location $oItem
     * @return string
     */
    public function getAddress(Location $oItem)
    {
        $isState = $oItem->country_code === 'US' && !is_null($oItem->province);
        if (!$isState) {
            return $oItem->title;
        }
        if (!is_null($oItem->province_code)) {
            return $oItem->title . ', ' . $oItem->province_code;
        }
        return $oItem->title . ', ' . statesShortNames($oItem->province);
    }

    /**
     * @param Location $oItem
     * @return string
     */
    public function getAddressHidden(Location $oItem)
    {
        $isState = $oItem->country_code === 'US' && !is_null($oItem->province);
        if (!$isState) {
            return $oItem->title;
        }
        if (!is_null($oItem->province_code)) {
            return $oItem->locality . ', ' . $oItem->province_code;
        }
        return $oItem->locality . ', ' . statesShortNames($oItem->province);
    }

    /**
     * @param Location $oItem
     * @return array
     */
    public function primaryPoint(Location $oItem)
    {
        return [
            (float)$oItem->point->getLat(),
            (float)$oItem->point->getLng(),
        ];
    }

    /**
     * @param Location $oItem
     * @return array
     */
    public function point(Location $oItem)
    {
        $lat = (float)$oItem->point->getLat();
        $lng = (float)$oItem->point->getLng();


        $lat = $this->saltToPoint($oItem, $lat);
        $lng = $this->saltToPoint($oItem, $lng);

        return [
            $lat, // 40.767197
            $lng, // -73.87097
        ];
    }

    /**
     * @param Location $oItem
     * @param float $item
     * @return float
     */
    private function saltToPoint(Location $oItem, float $item)
    {
        // последняя цифра из id шника
        $rest = (int)substr((string)$oItem->id, -1);
        if ($rest === 0) {
            $rest = 2;
        }
        if ($rest > 2) {
            $salt = ($rest / 10000);
        } else {
            $salt = ($rest / 1000);
        }
        if ($item % 2 === 0) {
            $item = $item + $salt;
        } else {
            $item = $item - $salt;
        }
        return $item;
    }

    /**
     * @param Location $oItem
     * @return array
     */
    public function circle(Location $oItem)
    {
        $lat = (float)$oItem->point->getLat();
        $lng = (float)$oItem->point->getLng();

        $lat = $this->saltToPoint($oItem, $lat);
        $lng = $this->saltToPoint($oItem, $lng);

        return [
            //'radius' => 8047, // 5 miles in metres,
            'radius' => 1609, // 5 miles in metres,
            'center' => [
                $lat,
                $lng,
            ],
        ];
    }
}
