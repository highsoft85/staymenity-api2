<?php

declare(strict_types=1);

namespace App\Console\Commands\Cases;

use App\Console\Commands\Common\CommandTrait;
use App\Http\Controllers\Api\User\Reservations\Payment;
use App\Http\Controllers\Api\User\Reservations\Store;
use App\Http\Requests\Api\User\Reservations\PaymentRequest;
use App\Http\Requests\Api\User\Reservations\StoreRequest;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingTime;
use App\Models\Reservation;
use App\Models\Rule;
use App\Models\Type;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserSetting;
use App\Services\Image\ImageType;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReservationServiceModel;
use App\Services\Model\UserReservationServiceModel;
use App\Services\Model\UserServiceModel;
use App\Services\Payment\StripePaymentService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Rennokki\Rating\Models\RaterModel;
use Tests\CommonApiTestTrait;
use Tests\FactoryModelTrait;

abstract class BaseCasesCommand extends Command
{
    use CommandTrait;
    use FactoryModelTrait;
    use CommonApiTestTrait;

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
     * @param string $email
     * @param string|null $image
     * @return User
     */
    protected function createHost(string $email, ?string $image = null)
    {
        $oHost = User::where('email', $email)->first();
        if (!is_null($oHost)) {
            (new UserServiceModel($oHost))->forceDelete();
        }
        $oHost = $this->factoryHost([
            'email' => $email,
            'register_by' => 'system',
        ], [
            'description' => 'I am confident in my abilities to produce and while I prepare for the worst, I do the work necessary to tilt the odds that the best will happen.',
        ]);
        if (!is_null($image)) {
            imageUploadUser(storage_path('tests/user/name/' . $image . '.jpg'), $oHost, ImageType::MODEL, true);
        } else {
            imageUploadUser(storage_path('tests/user/idi.jpg'), $oHost, ImageType::MODEL, true);
        }
        return $oHost;
    }

    /**
     * @param User $oHost
     * @param Type $oType
     * @param string $address
     * @param array $images
     * @return Listing
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    protected function createListing(User $oHost, Type $oType, string $address, array $images = [])
    {
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
        foreach ($images as $image) {
            imageUpload($image, $oListing, ImageType::MODEL, [], [], true);
        }

        $oListing = $this->factoryListingLocationWithAddress($oListing, [
            'address' => $address,
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

        $oListing->refresh();

        return $oListing;
    }

    /**
     * @param string $email
     * @param string $image
     * @return User
     */
    protected function createGuest(string $email, string $image)
    {
        $oUser = User::where('email', $email)->first();
        if (!is_null($oUser)) {
            (new UserServiceModel($oUser))->forceDelete();
        }
        $oGuest = $this->factoryGuest([
            'email' => $email,
        ], [
            'test_customer_id' => null,
        ]);
        imageUploadUser($image, $oGuest, ImageType::MODEL, true);
        return $oGuest;
    }

    /**
     * @param Listing $oListing
     * @param User $oGuest
     * @param int $subDays
     * @param int $hours
     * @throws \Stripe\Exception\ApiErrorException
     */
    protected function createReservation(Listing $oListing, User $oGuest, int $subDays, int $hours)
    {
        $oHost = $oListing->user;
        $price = $oListing->price * $hours;

        $start = 6;
        $finish = $start + $hours;
        $data = [
            'start_at' => now()->subDays($subDays)->startOfDay()->addHours($start)->format(UserReservationServiceModel::DATE_FORMAT),
            'finish_at' => now()->subDays($subDays)->startOfDay()->addHours($finish)->subMinute()->endOfHour()->format(UserReservationServiceModel::DATE_FORMAT),
            'price' => $price,
            'service_fee' => Reservation::SERVICE_FEE,
            'total_price' => $price + Reservation::SERVICE_FEE,
        ];
        $oReservation = $this->factoryReservationListingFromUser($oListing, $oGuest, $data);

        $method = $this->factoryStripePaymentMethodUser($oGuest);

        $oReservationService = (new ReservationServiceModel($oReservation));
        $oReservationService->paymentByMethod($oGuest, $oHost, $method->id);

        $oReservation->refresh();
        return $oReservation;
    }

    /**
     * @param Listing $oListing
     * @param User $oGuest
     * @param Carbon $start_at
     * @param Carbon $finish_at
     * @return Reservation
     */
    protected function createReservationByDates(Listing $oListing, User $oGuest, Carbon $start_at, Carbon $finish_at)
    {
        $response = cmfToInvoke($oGuest, Store::class, StoreRequest::class, [
            'listing_id' => $oListing->id,
            'start_at' => $start_at->format(UserReservationServiceModel::DATE_FORMAT),
            'finish_at' => $finish_at->format(UserReservationServiceModel::DATE_FORMAT),
            'guests_size' => $oListing->guests_size - 1,
            'message' => 'Message to Host',
        ]);
        return Reservation::find($response['data']['id']);
    }

    /**
     * @param Reservation $oReservation
     * @param User $oGuest
     * @return Reservation
     */
    protected function createReservationPayment(Reservation $oReservation, User $oGuest)
    {
        $oGuest->refresh();
        $aMethods = (new StripePaymentService())
            ->setUser($oGuest)
            ->getPaymentMethods();
        if (!empty($aMethods)) {
            $method = $aMethods[0]['payment_method_id'];
        } else {
            $method = $this->factoryStripePaymentMethodUser($oGuest)->id;
        }
        $response = cmfToInvoke($oGuest, Payment::class, PaymentRequest::class, [
            'payment_method_id' => $method,
        ], $oReservation->id);
        return $oReservation;
    }

    /**
     * @param Reservation $oReservation
     * @param User $oGuest
     * @param int $subDays
     * @param array $reviews
     */
    protected function createReview(Reservation $oReservation, User $oGuest, int $subDays, array $reviews)
    {
        $oListing = $oReservation->listing;
        $oHost = $oListing->user;
        // остзыв гостя на листинг
        $oListing->reviews()->create([
            'user_id' => $oGuest->id,
            'rating' => $reviews['listing']['rating'],
            'reservation_id' => $oReservation->id,
            'description' => $reviews['listing']['description'],
            'published_at' => now()->subDays($subDays + 1),
        ]);

        // остзыв хоста на гост на листинг
        $oGuest->reviews()->create([
            'user_id' => $oHost->id,
            'rating' => $reviews['host']['rating'],
            'reservation_id' => $oReservation->id,
            'description' => $reviews['host']['description'],
            'published_at' => now()->subDays($subDays + 1),
        ]);
    }

    /**
     * @param Reservation $oReservation
     * @param User $oGuest
     * @param array $data
     */
    protected function createReviewFromGuest(Reservation $oReservation, User $oGuest, array $data)
    {
        $oListing = $oReservation->listing;
        $oHost = $oListing->user;
        // остзыв гостя на листинг
        $faker = (new \Faker\Generator());
        $oListing->reviews()->create([
            'user_id' => $oGuest->id,
            'rating' => $data['rating'],
            'reservation_id' => $oReservation->id,
            'description' => $data['description'] ?? $faker->realText(rand(50, 500)),
            'published_at' => $oReservation->finish_at->copy()->addDay(),
        ]);
    }

    /**
     * @param Reservation $oReservation
     * @param User $oGuest
     * @param array $data
     */
    protected function createReviewFromHost(Reservation $oReservation, User $oGuest, array $data)
    {
        $oListing = $oReservation->listing;
        $oHost = $oListing->user;
        // остзыв хоста на гост на листинг
        $faker = (new \Faker\Generator());
        $oGuest->reviews()->create([
            'user_id' => $oHost->id,
            'rating' => $data['rating'],
            'reservation_id' => $oReservation->id,
            'description' => $data['description'] ?? $faker->realText(rand(50, 500)),
            'published_at' => $oReservation->finish_at->copy()->addDay(),
        ]);
    }

    /**
     * @param Listing $oListing
     * @param string $type
     */
    protected function createDates(Listing $oListing, string $type)
    {
        switch ($type) {
            case 'lock-weekends':
                $this->factoryUserCalendarLockWeekends($oListing, 2);
                break;
            case 'lock-weekdays':
                $this->factoryUserCalendarLockWeekdays($oListing, 2);
                break;
        }
    }

    /**
     * @param Listing $oListing
     * @param array $times
     */
    protected function createTimes(Listing $oListing, array $times)
    {
        $aTimes = [];
        foreach ($times as $from => $to) {
            $aTimes[] = [
                'from' => $from,
                'to' => $to,
            ];
        }
        (new ListingServiceModel($oListing))->saveTimes([
            ListingTime::TYPE_WEEKDAYS => $aTimes,
            ListingTime::TYPE_WEEKENDS => $aTimes,
        ]);
    }
}
