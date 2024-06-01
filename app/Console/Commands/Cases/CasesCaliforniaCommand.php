<?php

declare(strict_types=1);

namespace App\Console\Commands\Cases;

use App\Models\Type;

class CasesCaliforniaCommand extends BaseCasesCommand
{
    /**
     *
     */
    const SIGNATURE = 'cases:california-one-many-reservations';

    /**
     * The name and signature of the console command.
     *
     * php artisan cases:california-one-many-reservations
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
        $oHost = $this->createHost('host-ca-1@example.net');

        $oType = Type::where('name', 'pool')->first();
        if (is_null($oType)) {
            throw new \Exception('Pool type not found');
        }
        $oListing4 = $this->createListing($oHost, $oType, '1604 Rainbow Drive, North Tustin, CA 92705, United States of America', [
            storage_path('tests/listings/ca/pool/1.jpg'),
        ]);

        $oGuest1 = $this->createGuest('guest-ca-1@example.net', storage_path('tests/user/la/1.jpg'));

        // 14-16
        $start_at = now()->addDays(1)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(1)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);

        // 14-16
        $start_at = now()->addDays(2)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(2)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);

        // 14-16
        $start_at = now()->addDays(3)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(3)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);

        // 14-16
        $start_at = now()->addDays(4)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(4)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);

        // 14-16
        $start_at = now()->addDays(5)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(5)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);

        // 14-16
        $start_at = now()->addDays(6)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(6)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);

        // 14-16
        $start_at = now()->addDays(7)->startOfDay()->addHours(14);
        $finish_at = now()->addDays(7)->startOfDay()->addHours(15);
        $oReservation1 = $this->createReservationByDates($oListing4, $oGuest1, $start_at, $finish_at);
        $this->createReservationPayment($oReservation1, $oGuest1);
    }
}
