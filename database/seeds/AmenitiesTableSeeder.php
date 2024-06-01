<?php

declare(strict_types=1);

use App\Models\Amenity;
use Illuminate\Database\Seeder;

/**
 * Class TermAmenityTableSeeder
 */
class AmenitiesTableSeeder extends Seeder
{
    /**
     * @return array
     */
    private function data()
    {
        return [
            [
                'title' => 'High-End Barbecue',
            ], [
                'title' => 'Basketball Court',
            ], [
                'title' => 'Tennis Court',
            ], [
                'title' => 'Volleyball Court',
            ], [
                'title' => 'Chefs Kitchen',
            ], [
                'title' => 'Fire Pit',
            ], [
                'title' => 'Guest House',
            ], [
                'title' => 'Home Office',
            ], [
                'title' => 'Unconventional Workspaces',
            ], [
                'title' => 'Pool',
            ], [
                'title' => 'Hot Tub',
            ], [
                'title' => 'Mansion',
            ], [
                'title' => 'Playground',
            ], [
                'title' => 'Private Beach',
            ], [
                'title' => 'Private Bowling Alley',
            ], [
                'title' => 'Private Gym',
            ], [
                'title' => 'Private Lake',
            ], [
                'title' => 'Private Movie Theatre',
            ], [
                'title' => 'Rooftop',
            ], [
                'title' => 'Farm',
            ], [
                'title' => 'Waterview',
            ], [
                'title' => 'Garden',
            ], [
                'title' => 'Wifi',
                'icon' => asset('svg/amenities/wi-fi.svg'),
            ], [
                'title' => 'Parking',
                'icon' => asset('svg/amenities/parking.svg'),
            ], [
                'title' => 'Drinking water',
                'icon' => asset('svg/amenities/water.svg'),
            ], [
                'title' => 'Electricity sockets',
                'icon' => asset('svg/amenities/sockets.svg'),
            ], [
                'title' => 'Bathroom',
                'icon' => asset('svg/amenities/bathroom.svg'),
            ], [
                'title' => 'Wheelchair accessible',
                'icon' => asset('svg/amenities/wheelchair-accessible.svg'),
            ],
//            [
//                'title' => 'Shower',
//            ], [
//                'title' => 'Bathroom Basics',
//            ], [
//                'title' => 'Parking',
//            ], [
//                'title' => 'Lounge Chairs',
//            ], [
//                'title' => 'Towels',
//            ], [
//                'title' => 'Speakers',
//            ], [
//                'title' => 'Pool Toys',
//            ], [
//                'title' => 'Yard Games',
//            ], [
//                'title' => 'Kayaks',
//            ], [
//                'title' => 'Sports Equipment',
//            ], [
//                'title' => 'Paddle Boards',
//            ], [
//                'title' => 'Outdoor Table Seating',
//            ], [
//                'title' => 'Cooking Basics',
//            ], [
//                'title' => 'Cooking Appliances',
//            ], [
//                'title' => 'Outdoor Refrigerator',
//            ], [
//                'title' => 'Access Property',
//            ], [
//                'title' => 'Private vs Shared Property',
//            ],
            [
                'name' => \App\Models\Amenity::NAME_OTHER,
                'title' => 'Other',
                'priority' => -1,
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data() as $data) {
            $name = isset($data['name']) ? $data['name'] : \Illuminate\Support\Str::slug($data['title']);
            $data['type'] = Amenity::TYPE_LISTING;
            $oItem = Amenity::where('name', $name)->first();
            if (isset($data['icon'])) {
                $icon = $data['icon'];
                $aIcon = explode('svg/amenities/', $icon);
                if (isset($aIcon[1])) {
                    $icon = str_replace('.svg', '', $aIcon[1]);
                }
                $data['icon'] = $icon;
            }
            if (is_null($oItem)) {
                factory(Amenity::class)->create($data);
            } else {
                $oItem->update($data);
            }
        }
        $oItems = Amenity::where('name', '<>', Amenity::NAME_OTHER)->get()->sortByDesc('title')->values();
        foreach ($oItems as $key => $oItem) {
            /** @var Amenity $oItem */
            $oItem->update([
                'priority' => $key * 10,
            ]);
        }
    }
}
