<?php

declare(strict_types=1);

namespace App\Console\Commands\Cases;

use App\Models\Listing;
use App\Models\Type;
use App\Models\User;

class CasesLosAngelesManyCommand extends BaseCasesCommand
{
    /**
     *
     */
    const SIGNATURE = 'cases:los-angeles-many';

    /**
     * The name and signature of the console command.
     *
     * php artisan cases:los-angeles-many
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить пользователей';

    /**
     * @return array
     */
    private function data()
    {
        return [
            [
                'host' => 'host-la-1@example.net',
                'title' => 'Backyard',
                'image' => [
                    'listing-1.jpg',
                    'listing-2.jpg',
                    'listing-3.jpg',
                ],
                'type' => 'beautiful-backyard',
                'address' => 'Valley View Avenue, Yorba Linda, CA 92886, United States of America',
                'dates' => 'lock-weekends',
                'times' => [
                    '10:00' => '16:00',
                    '18:00' => '20:00',
                ],
            ], [
                'host' => 'host-la-2@example.net',
                'title' => 'Backyard',
                'image' => 'pool-1.jpg',
                'type' => 'pool',
                'address' => '882 West 47th Street, Los Angeles, CA 90037, United States of America',
                'dates' => 'lock-weekdays',
                'times' => [
                    '07:00' => '16:00',
                    '18:00' => '20:00',
                ],
            ], [
                'host' => 'host-la-3@example.net',
                'title' => 'Backyard',
                'image' => 'pool-2.jpg',
                'type' => 'pool',
                'address' => '669 Comet Avenue, Simi Valley, CA 93065, United States of America',
                'times' => [
                    '12:00' => '18:00',
                ],
            ], [
                'host' => 'host-la-4@example.net',
                'title' => 'Barbecue',
                'image' => 'barbecue.jpg',
                'type' => 'barbecue',
                'address' => '3523 Glenoaks Boulevard, Glendale, CA 91206, United States of America',
                'times' => [
                    '10:00' => '15:00',
                    '16:00' => '18:00',
                    '19:00' => '23:00',
                ],
            ], [
                'host' => 'host-la-5@example.net',
                'title' => 'Garden',
                'image' => 'garden-1.jpg',
                'type' => 'garden',
                'address' => '22655 Calvert Street, Los Angeles, CA 91367, United States of America',
            ], [
                'host' => 'host-la-6@example.net',
                'title' => 'Garden',
                'image' => 'garden-2.jpg',
                'type' => 'garden',
                'address' => '19647 Vista Hermosa Drive, Walnut, CA 91789, United States of America',
                'times' => [
                    '18:00' => '23:00',
                ],
            ], [
                'host' => 'host-la-7@example.net',
                'title' => 'Garden',
                'image' => 'garden-3.jpg',
                'type' => 'garden',
                'address' => 'Chino Avenue, Ontario, CA 91761, United States of America',
                'times' => [
                    '16:00' => '20:00',
                ],
            ], [
                'host' => 'host-la-8@example.net',
                'title' => 'Playground',
                'image' => 'playground-1.jpg',
                'type' => 'playground',
                'address' => 'East San Antonio Drive, Long Beach, CA 90807, United States of America',
                'times' => [
                    '19:00' => '23:00',
                ],
            ], [
                'host' => 'host-la-9@example.net',
                'title' => 'Playground',
                'image' => 'playground-2.jpg',
                'type' => 'playground',
                'address' => '1891 East 37th Street, Long Beach, CA 90807, United States of America',
                'dates' => 'lock-weekends',
                'times' => [
                    '08:00' => '23:00',
                ],
            ], [
                'host' => 'host-la-10@example.net',
                'title' => 'Volleyball',
                'image' => 'volleyball-1.jpg',
                'type' => 'volleyball',
                'address' => '14470 South Cairn Avenue, West Rancho Dominguez, CA 90220, United States of America',
                'dates' => 'lock-weekends',
                'times' => [
                    '08:00' => '10:00',
                    '12:00' => '14:00',
                    '18:00' => '22:00',
                ],
            ], [
                'host' => 'host-la-11@example.net',
                'title' => 'Private Lake',
                'image' => 'private-lake-1.jpg',
                'type' => 'private-lake',
                'address' => '11 Rolling Hills Drive, Pomona, CA 91766, United States of America',
                'times' => [
                    '08:00' => '16:00',
                ],
            ], [
                'host' => 'host-la-12@example.net',
                'title' => 'Private Lake',
                'image' => 'private-lake-2.jpg',
                'type' => 'private-lake',
                'address' => '2699 Spectacular Bid Street, Perris, CA 92571, United States of America',
                'times' => [
                    '15:00' => '20:00',
                ],
            ], [
                'host' => 'host-la-13@example.net',
                'title' => 'Other Backyard',
                'image' => 'other-1.jpg',
                'type' => 'other',
                'address' => '29511 Los Osos Drive, Laguna Niguel, CA 92677, United States of America',
                'dates' => 'lock-weekdays',
            ],
        ];
    }

    /**
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function create()
    {
        $bar = $this->bar(count($this->data()));
        foreach ($this->data() as $data) {
            $oHost = User::where('email', $data['host'])->first();
            //if (is_null($oHost)) {
                $oHost = $this->createHost($data['host'], $data['host']);
            //}
            $oType = Type::where('name', $data['type'])->first();
            if (is_null($oType)) {
                throw new \Exception($data['type'] . ' type not found');
            }
            $oListing = $this->createListing($oHost, $oType, $data['address']);
            if (isset($data['dates'])) {
                $this->createDates($oListing, $data['dates']);
            }
            if (isset($data['times'])) {
                $this->createTimes($oListing, $data['times']);
            }
            $this->uploadImage($oListing, $data['image']);
            $bar->advance();
        }
        $bar->finish();
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
