<?php

declare(strict_types=1);

use App\Models\Listing;
use App\Models\ListingSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListingsDumpTableSeeder extends Seeder
{
    /**
     * @return string[][]
     */
    private function data()
    {
        return [
            [
                'title' => 'Backyard 1',
                'point' => coordinatesNewYork(),
            ], [
                'title' => 'Backyard 2',
                'point' => coordinatesNewYork2(),
            ], [
                'title' => 'Backyard 3',
                'point' => coordinatesNewYork3(),
            ], [
                'title' => 'Backyard 4',
                'point' => coordinatesLosAngeles(),
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
            $oListing = \App\Models\Listing::where('name', $name)->first();
            if (is_null($oListing)) {
                $data['type_id'] = \App\Models\Type::first()->id;
                $oListing = factory(Listing::class)->create(collect($data)->only(['title'])->toArray());
                factory(ListingSetting::class)->create([
                    'listing_id' => $oListing->id,
                ]);
                $location = [];
                if (isset($data['point'])) {
                    $location['point'] = $data['point'];
                }
                $oListing->location()->create(array_merge(factory(\App\Models\Location::class)->raw(), $location));
            }
        }
    }
}
