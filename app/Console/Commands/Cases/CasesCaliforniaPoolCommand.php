<?php

declare(strict_types=1);

namespace App\Console\Commands\Cases;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Amenity;
use App\Models\Reservation;
use App\Models\Rule;
use App\Models\Type;
use App\Models\User;
use App\Services\Image\ImageType;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserReservationServiceModel;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Tests\CommonApiTestTrait;
use Tests\FactoryModelTrait;

class CasesCaliforniaPoolCommand extends Command
{
    use CommandTrait;
    use FactoryModelTrait;
    use CommonApiTestTrait;

    /**
     *
     */
    const SIGNATURE = 'cases:california-pool';

    /**
     * The name and signature of the console command.
     *
     * php artisan cases:california-pool
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
     *  Не будет записывать в обычные логи, только по Logger
     *
     * @var bool
     */
    private $log = false;

    /**
     * ExportConverterUsersCommand constructor.
     */
    public function __construct()
    {
        @parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->start();

        $this->create();

        $this->finish();
    }

    /**
     *
     */
    public function create()
    {
        $hostEmail = 'host-ca-pool2@admin.com';
        $guestEmail = 'guest-ca-pool2@admin.com';

        $oUser = User::where('email', $hostEmail)->first();
        if (!is_null($oUser)) {
            (new UserServiceModel($oUser))->forceDelete();
        }
        $oUser = User::where('email', $guestEmail)->first();
        if (!is_null($oUser)) {
            (new UserServiceModel($oUser))->forceDelete();
        }
        $oHost = $this->factoryHost([
            'email' => $hostEmail,
        ], [
            'description' => 'I am confident in my abilities to produce and while I prepare for the worst, I do the work necessary to tilt the odds that the best will happen.',
        ]);
        imageUploadUser(storage_path('tests/user/thomas.png'), $oHost, ImageType::MODEL, true);
        $oType = Type::where('name', 'pool')->first();
        $oAmenities = Amenity::query()->inRandomOrder()->take(5)->get();
        $oOtherAmenity = Amenity::other()->first();

        $oRules = Rule::query()->inRandomOrder()->take(2)->get();
        $oOtherRule = Rule::other()->first();

        $oListing = $this->factoryUserListingActive($oHost, [
            'type_id' => $oType->id,
            'rent_time_min' => 2,
        ], [
            'amenities' => 'Every guest will be provided with a towel, sunchair and drinking water.',
            'rules' => 'Please leave all dirty dishes in the dishwasher when you check out. You dont need to wash them but we just ask that you leave our home neat. Please respect our neighbors and keep the noise level down. Always stay safe and lock all doors when you are in the house and when you leave the house. We are not responsible for any personal and/or business belongings. We are also not responsible for any accidents of any kind to you or any of the people staying with you.',
        ]);
        imageUpload(storage_path('tests/listings/pool-3.jpg'), $oListing, ImageType::MODEL, [], [], true);

        $oListing = $this->factoryListingLocationWithAddress($oListing, [
            'address' => 'Riviera Country Club, 1250 Capri Drive, Los Angeles, CA 90272, United States of America',
        ]);

        // добавление услуг
        foreach ($oAmenities as $oAmenity) {
            if ($oAmenity->id === $oOtherAmenity->id) {
                continue;
            }
            $oListing->amenities()->attach($oAmenity->id);
        }
        $oListing->amenities()->attach($oOtherAmenity->id);

        // добавление правил
        foreach ($oRules as $oRule) {
            if ($oRule->id === $oOtherRule->id) {
                continue;
            }
            $oListing->rules()->attach($oRule->id);
        }
        $oListing->rules()->attach($oOtherRule->id);

        $oGuest = $this->factoryGuest([
            'email' => $guestEmail,
        ]);
        imageUploadUser(storage_path('tests/user/idi2.jpg'), $oGuest, ImageType::MODEL, true);

        $price = $oListing->price * 3;

        $data = [
            'start_at' => now()->subDay()->startOfDay()->addHours(6)->format(UserReservationServiceModel::DATE_FORMAT),
            'finish_at' => now()->subDay()->startOfDay()->addHours(8)->endOfHour()->format(UserReservationServiceModel::DATE_FORMAT),
            'price' => $price,
            'service_fee' => Reservation::SERVICE_FEE,
            'total_price' => $price + Reservation::SERVICE_FEE,
        ];
        $oReservation = $this->factoryReservationListingFromUser($oListing, $oGuest, $data);

        $method = $this->factoryStripePaymentMethodUser($oGuest);

        $oReservationService = (new ReservationServiceModel($oReservation));
        $oReservationService->paymentByMethod($oGuest, $oHost, $method->id);

        // остзыв гостя на листинг
        $oListing->reviews()->create([
            'user_id' => $oGuest->id,
            'rating' => 5,
            'reservation_id' => $oReservation->id,
            'description' => 'Everything was great! Really friendly, polite and helpful staff with an excellent command of English. Breakfast was diverse with a lot of choices and always fresh',
            'published_at' => now(),
        ]);

        // остзыв хоста на гост на листинг
        $oGuest->reviews()->create([
            'user_id' => $oHost->id,
            'rating' => 4,
            'reservation_id' => $oReservation->id,
            'description' => 'Everything was great!',
            'published_at' => now(),
        ]);
    }
}
