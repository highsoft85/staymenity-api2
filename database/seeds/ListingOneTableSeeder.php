<?php

declare(strict_types=1);

use App\Models\Listing;
use App\Models\ListingSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Type;

class ListingOneTableSeeder extends Seeder
{
    use \Tests\FactoryModelTrait;

    /**
     * @return array
     */
    private function data()
    {
        return [
            [
                'title' => 'Backyard',
                'image' => [
                    'listing-1.jpg',
                    'listing-2.jpg',
                    'listing-3.jpg',
                ],
                'type' => 'beautiful-backyard',
                'point' => coordinatesNewYorkCommon(1),
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
            if (!is_null($oListing)) {
                $data['name'] = $name . '-' . \Illuminate\Support\Str::random(10);
            }
            $data['type_id'] = \App\Models\Type::first()->id;
            if (isset($data['type'])) {
                $data['type_id'] = Type::where('name', $data['type'])->first()->id;
                unset($data['type']);
            }
            $image = null;
            if (isset($data['image'])) {
                $image = $data['image'];
                unset($data['image']);
            }
            $location = [];
            if (isset($data['point'])) {
                $location['point'] = $data['point'];
                unset($data['point']);
            }
            $oHost = $this->factoryHost();
            $oListing = $this->factoryUserListing($oHost, $data);
            if (config('app.env') !== \App\Services\Environment::DOCUMENTATION) {
                $this->factoryListingLocationWithAddress($oListing, [
                    'point' => $location['point'],
                ]);
            } else {
                $oListing->location()->create(array_merge(factory(\App\Models\Location::class)->raw(), $location));
            }
            if (!is_null($image) && config('app.env') !== \App\Services\Environment::DOCUMENTATION) {
                $this->uploadImage($oListing, $image);
            }
            $oListing->update([
                'published_at' => now(),
            ]);
        }
    }

    /**
     * @param Listing $oListing
     * @param array|string $image
     */
    private function uploadImage(Listing $oListing, $image)
    {
        if (is_array($image)) {
            foreach ($image as $img) {
                imageUpload(storage_path('tests/listings/' . $img), $oListing);
            }
        } else {
            imageUpload(storage_path('tests/listings/' . $image), $oListing);
        }
    }
}
