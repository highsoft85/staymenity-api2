<?php

declare(strict_types=1);

namespace App\Console\Commands\Cases;

use App\Models\Type;

class CasesLosAngelesCommand extends BaseCasesCommand
{
    /**
     *
     */
    const SIGNATURE = 'cases:los-angeles';

    /**
     * The name and signature of the console command.
     *
     * php artisan cases:los-angeles
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
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function create()
    {
        $oHost = $this->createHost('host-la-0@example.net');

        $oType = Type::where('name', 'pool')->first();
        if (is_null($oType)) {
            throw new \Exception('Pool type not found');
        }

        $oListing1 = $this->createListing($oHost, $oType, '1956 East 69th Street, Florence-Firestone, CA 90001, United States of America', [
            storage_path('tests/listings/la/pool/1.jpg'),
        ]);
        $oListing2 = $this->createListing($oHost, $oType, '8999 Montrose Avenue, Westminster, CA 92683, United States of America', [
            storage_path('tests/listings/la/pool/2.jpg'),
        ]);
        $oListing3 = $this->createListing($oHost, $oType, '18612 Culver Drive, Irvine, CA 92612, United States of America', [
            storage_path('tests/listings/la/pool/3.jpg'),
        ]);
        $oListing4 = $this->createListing($oHost, $oType, '13341 166th Street, Cerritos, CA 90703, United States of America', [
            storage_path('tests/listings/la/pool/4/1.jpg'),
            storage_path('tests/listings/la/pool/4/2.jpg'),
            storage_path('tests/listings/la/pool/4/3.jpg'),
            storage_path('tests/listings/la/pool/4/4.jpg'),
            storage_path('tests/listings/la/pool/4/5.jpg'),
            storage_path('tests/listings/la/pool/4/6.jpg'),
        ]);

        $oGuest1 = $this->createGuest('guest-la-01@example.net', storage_path('tests/user/la/1.jpg'));
        // 14-16
        $start_at = now()->subDays(30)->startOfDay()->addHours(14);
        $finish_at = now()->subDays(30)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);
        $this->createReviewFromGuest($oReservation1, $oGuest1, [
            'rating' => 5,
            'description' => 'The room is perfect both for couples and solo travelers! The region is safe,quiet and close to all the shops,cafes and supermarkets! I highly recommend this app to all the travelers,who are searching for  a piece of quiete in noisy LA:) If you like to live with the people who can be your second family,you can rent this app,for sure:)',
        ]);
        $this->createReviewFromHost($oReservation1, $oGuest1, [
            'rating' => 5,
            'description' => 'Everything was great!',
        ]);
        $oListing4->refresh();

        $oGuest2 = $this->createGuest('guest-la-02@example.net', storage_path('tests/user/la/2.jpg'));
        // 14-16
        $start_at = now()->subDays(5)->startOfDay()->addHours(14);
        $finish_at = now()->subDays(5)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest2, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest2);
        $this->createReviewFromGuest($oReservation1, $oGuest2, [
            'rating' => 5,
            'description' => 'Very clean house, very friendly people and lovely neighborhood',
        ]);
        $this->createReviewFromHost($oReservation1, $oGuest2, [
            'rating' => 5,
            'description' => 'Everything was great!',
        ]);
        $oListing4->refresh();

        $oGuest3 = $this->createGuest('guest-la-03@example.net', storage_path('tests/user/la/3.jpg'));
        // 14-16
        $start_at = now()->subDays(6)->startOfDay()->addHours(14);
        $finish_at = now()->subDays(6)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest3, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest3);
        $this->createReviewFromGuest($oReservation1, $oGuest3, [
            'rating' => 4,
            'description' => 'Marco and Rosy were awesome! My daughter and I loveddddd the pool, Marco even brought out pool toys for her to use! They were nice and answered all my questions about things to do in the area. I’d definitely stay again!',
        ]);
        $this->createReviewFromHost($oReservation1, $oGuest3, [
            'rating' => 5,
            'description' => 'Everything was great!',
        ]);
        $oListing4->refresh();

        $oGuest4 = $this->createGuest('guest-la-04@example.net', storage_path('tests/user/la/4.jpg'));
        // 14-16
        $start_at = now()->subDays(35)->startOfDay()->addHours(14);
        $finish_at = now()->subDays(35)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest4, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest4);
        $this->createReviewFromGuest($oReservation1, $oGuest4, [
            'rating' => 5,
            'description' => 'I’d definitely stay again!',
        ]);
        $this->createReviewFromHost($oReservation1, $oGuest4, [
            'rating' => 5,
            'description' => 'Everything was great!',
        ]);
        $oListing4->refresh();

        $oGuest5 = $this->createGuest('guest-la-05@example.net', storage_path('tests/user/la/5.jpg'));
        // 14-16
        $start_at = now()->subDays(4)->startOfDay()->addHours(14);
        $finish_at = now()->subDays(4)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest5, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest5);
        $this->createReviewFromGuest($oReservation1, $oGuest5, [
            'rating' => 5,
            'description' => 'Top tier host is all I can say. You guys win Thank you for the hospitality, helpfulness & cleanliness. I will be back for sure.',
        ]);
        $this->createReviewFromHost($oReservation1, $oGuest5, [
            'rating' => 5,
            'description' => 'Everything was great!',
        ]);
        $oListing4->refresh();
    }
}
