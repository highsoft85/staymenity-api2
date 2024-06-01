<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Amenity;
use App\Models\Balance;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\FirebaseNotification;
use App\Models\Listing;
use App\Models\ListingSetting;
use App\Models\Location;
use App\Models\Option;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Reservation;
use App\Models\Rule;
use App\Models\Transfer;
use App\Models\Type;
use App\Models\User;
use App\Models\UserCalendar;
use App\Models\UserIdentity;
use App\Models\UserSave;
use App\Notifications\User\LeaveReviewNotification;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Model\ListingServiceModel;
use App\Services\Payment\Stripe\PaymentMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Stripe\Token;

trait FactoryModelTrait
{
    /**
     * @param string $class
     * @param array $data
     * @return mixed
     */
    public function factoryMake(string $class, array $data = [])
    {
        return factory($class)->make($data);
    }

    /**
     * @param string $class
     * @param array $data
     * @return array
     */
    public function factoryRaw(string $class, array $data = []): array
    {
        return array_merge(factory($class)->raw(), $data);
    }

    /**
     * @param array $data
     * @return User
     */
    public function factoryUser(array $data = []): User
    {
        return factory(User::class)->create($data);
    }

    /**
     * @param array $data
     * @return User
     */
    public function factoryUserRegisterByEmail(array $data = [])
    {
        $raw = $this->factoryRaw(User::class);
        $save = array_merge([
            'email' => $raw['email'],
            'phone' => $raw['phone'],
            'phone_verified' => 1,
            'first_name' => $raw['first_name'],
            'last_name' => $raw['last_name'],
            'password' => '12345678',
            'role' => $data['role'] ?? User::ROLE_GUEST,
        ], $data);
        // success register
        $response = $this->apiPost('/auth/register', $save);
        $this->assertTrue($response['success']);
        $this->assertTrue(isset($response['data']['token']));

        $response = $this->apiGet('/user', [], null, true, $response['data']['token']);
        $this->assertTrue($response['success']);

        return User::find($response['data']['id']);
    }

    /**
     * @param array $data
     * @return User|array
     */
    public function factoryUserRegisterByMock(array $data = [])
    {
        return $this->userRegisterByMock($data, 'mock');
    }

    /**
     * @param array $data
     * @return User|array
     */
    public function factoryUserRegisterByMockSecond(array $data = [])
    {
        return $this->userRegisterByMock($data, 'mock-second');
    }

    /**
     * @param array $data
     * @param string $name
     * @return User|array
     */
    private function userRegisterByMock(array $data = [], string $name = 'mock')
    {
        $raw = $this->factoryRaw(User::class);
        $save = array_merge([
            'id' => Str::random(16),
            'email' => $raw['email'],
            'name' => $raw['first_name'] . ' ' . $raw['last_name'],
            'avatar' => null,
            'role' => User::ROLE_HOST,
            'access_token' => Str::random(16),
            //'user_id' => 1,
        ], $data);
        $response = $this->apiGet('/auth/socialite/' . $name . '/callback', $save);
        if (!isset($response['success'])) {
            return responseCommon()->apiError([], $response['message'])->getData(true);
        }
        $this->assertTrue($response['success']);
        $this->assertTrue(isset($response['data']['token']));

        $response = $this->apiGet('/user', [], null, true, $response['data']['token']);
        $this->assertTrue($response['success']);

        return User::find($response['data']['id']);
    }

    /**
     * @param array $data
     * @param array $details
     * @return User
     */
    public function factoryHost(array $data = [], array $details = []): User
    {
        $oUser = $this->factoryUser($data);
        $oUser->assignRole(User::ROLE_HOST);
        $oUser->update([
            'current_role' => User::ROLE_HOST,
        ]);
        $oUser->details()->create(array_merge([
            'test_customer_id' => stripeCustomerMy(),
        ], $details));
        return $oUser;
    }

    /**
     * @param array $data
     * @return User
     */
    public function factoryHostWithBalance(array $data = []): User
    {
        $oUser = $this->factoryUser($data);
        $oUser->assignRole(User::ROLE_HOST);
        $oUser->update([
            'current_role' => User::ROLE_HOST,
        ]);
        $this->factoryBalance([
            'user_id' => $oUser,
        ]);
        return $oUser;
    }

    /**
     * @param array $data
     * @param array $details
     * @return User
     */
    public function factoryGuest(array $data = [], array $details = []): User
    {
        $oUser = $this->factoryUser($data);
        $oUser->assignRole(User::ROLE_GUEST);
        $oUser->update([
            'current_role' => User::ROLE_GUEST,
        ]);
        $oUser->details()->create(array_merge([
            'test_customer_id' => stripeCustomerMyGuest(),
        ], $details));
        return $oUser;
    }

    /**
     * @param array $data
     * @param string|null $token
     * @return User
     */
    public function factoryGuestWithCard(array $data = [], ?string $token = null): User
    {
        $oUser = $this->factoryUser($data);
        $oUser->assignRole(User::ROLE_GUEST);
        $oUser->update([
            'current_role' => User::ROLE_GUEST,
        ]);
        return $oUser;
    }

    /**
     * @param User $oUser
     * @param string $token
     * @return User
     */
    public function factoryPaymentCardByToken(User $oUser, string $token)
    {
        if (is_null($oUser->balance)) {
            $this->factoryBalance([
                'user_id' => $oUser->id,
            ]);
        }
        return $oUser;
    }

    /**
     * @param array $data
     * @return Type
     */
    public function factoryType(array $data = []): Type
    {
        return factory(Type::class)->create($data);
    }

    /**
     * @param array $data
     * @return Type
     */
    public function factoryTypeOther(array $data = []): Type
    {
        $oType = Type::other()->first();
        if (!is_null($oType)) {
            return $oType;
        }
        $data = array_merge($data, [
            'name' => Type::NAME_OTHER,
        ]);
        return factory(Type::class)->create($data);
    }

    /**
     * @param array $data
     * @return Amenity
     */
    public function factoryAmenity(array $data = []): Amenity
    {
        return factory(Amenity::class)->create($data);
    }

    /**
     * @param array $data
     * @return Amenity
     */
    public function factoryAmenityOther(array $data = []): Amenity
    {
        $oAmenity = Amenity::other()->first();
        if (!is_null($oAmenity)) {
            return $oAmenity;
        }
        $data = array_merge($data, [
            'title' => Amenity::NAME_OTHER,
        ]);
        return factory(Amenity::class)->create($data);
    }

    /**
     * @param array $data
     * @return Rule
     */
    public function factoryRule(array $data = []): Rule
    {
        return factory(Rule::class)->create($data);
    }

    /**
     * @param array $data
     * @return Rule
     */
    public function factoryRuleOther(array $data = []): Rule
    {
        $oRule = Rule::other()->first();
        if (!is_null($oRule)) {
            return $oRule;
        }
        $data = array_merge($data, [
            'title' => Rule::NAME_OTHER,
        ]);
        return factory(Rule::class)->create($data);
    }

    /**
     * @param array $data
     * @return Listing
     */
    public function factoryListing(array $data = []): Listing
    {
        /** @var Listing $oListing */
        $oListing = factory(Listing::class)->create($data);
        $oListing->settings()->create([]);
        return $oListing;
    }

    /**
     * @param User $oHost
     * @param array $data
     * @param array $point
     * @return Listing
     */
    public function factoryListingWithPoint(User $oHost, array $data, array $point)
    {
        $oListing = $this->factoryUserListingActive($oHost, $data);
        $oListing->location()->create(array_merge($this->factoryRaw(Location::class), [
            'point' => $point,
        ]));
        return $oListing;
    }

    /**
     * @param User $oUser
     * @param array $data
     * @param array $settings
     * @return Listing
     */
    public function factoryUserListing(User $oUser, array $data = [], array $settings = []): Listing
    {
        $data = array_merge([
            'user_id' => $oUser->id,
        ], $data);
        /** @var Listing $oListing */
        $oListing = factory(Listing::class)->create($data);
        $oListing->settings()->create($settings);
        return $oListing;
    }

    /**
     * @param User $oUser
     * @param array $data
     * @param array $settings
     * @return Listing
     */
    public function factoryUserListingActive(User $oUser, array $data = [], array $settings = []): Listing
    {
        $data = array_merge([
            'user_id' => $oUser->id,
            'published_at' => now(),
            'status' => Listing::STATUS_ACTIVE,
        ], $data);
        /** @var Listing $oListing */
        $oListing = factory(Listing::class)->create($data);
        $oListing->settings()->create($settings);
        return $oListing;
    }

    /**
     * @param Listing $oListing
     * @param array $data
     * @return UserCalendar
     */
    public function factoryListingCalendar(Listing $oListing, array $data = []): UserCalendar
    {
        $data = array_merge([
            'user_id' => $oListing->user_id,
            'listing_id' => $oListing->id,
        ], $data);
        return $this->factoryUserCalendar($data);
    }

    /**
     * @param array $data
     * @return ListingSetting
     */
    public function factoryListingSetting(array $data = []): ListingSetting
    {
        return factory(ListingSetting::class)->create($data);
    }

    /**
     * @param array $data
     * @return Location
     */
    public function factoryLocation(array $data = []): Location
    {
        return factory(Location::class)->create($data);
    }

    /**
     * @param Listing $oListing
     * @param array $data
     * @return Listing
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function factoryListingLocationWithAddress(Listing $oListing, array $data = []): Listing
    {
        $address = null;
        if (isset($data['address'])) {
            $address = $data['address'];
        }
        $point = null;
        if (isset($data['point'])) {
            $point = $data['point'];
        }
        if (!is_null($point)) {
            $oGeo = (new GeocoderCitiesService());
            $address = $oGeo->addressByPoint($point);
        }
        if (!is_null($address)) {
            $oGeo = (new GeocoderCitiesService());
            $return = $oGeo->address($address);
            if (!isset($return[0]['place_id'])) {
                slackInfo($address, 'Address Not Found');
            }
            (new ListingServiceModel($oListing))->saveLocation($return[0]['place_id']);
        }
        return $oListing;
    }

    /**
     * @param array $data
     * @return UserSave
     */
    public function factoryUserSave(array $data = []): UserSave
    {
        return factory(UserSave::class)->create($data);
    }

    /**
     * @param array $data
     * @return UserCalendar
     */
    public function factoryUserCalendar(array $data = []): UserCalendar
    {
        return factory(UserCalendar::class)->create($data);
    }

    /**
     * @param array $data
     * @return Reservation
     */
    public function factoryReservation(array $data = []): Reservation
    {
        return factory(Reservation::class)->create($data);
    }

    /**
     * @param Listing $oListing
     * @param array $data
     * @return Reservation
     */
    public function factoryReservationListing(Listing $oListing, array $data = []): Reservation
    {
        $data = array_merge($data, [
            'listing_id' => $oListing->id,
        ]);
        return $this->factoryReservation($data);
    }

    /**
     * @param Listing $oListing
     * @param User $oUser
     * @param array $data
     * @return Reservation
     */
    public function factoryReservationListingFromUser(Listing $oListing, User $oUser, array $data = []): Reservation
    {
        $data = array_merge($data, [
            'listing_id' => $oListing->id,
            'user_id' => $oUser->id,
        ]);
        return $this->factoryReservation($data);
    }

    /**
     * @param Listing $oListing
     * @param User $oUser
     * @param array $data
     * @return Reservation
     */
    public function factoryReservationListingFromUserTomorrow(Listing $oListing, User $oUser, array $data = []): Reservation
    {
        // 6-9
        $hours = 3;
        $start = 6;
        $finish = $start + $hours;

        $price = $oListing->price * $hours;
        $data = array_merge($data, [
            'price' => $price,
            'total_price' => $price + Reservation::SERVICE_FEE,
            'free_cancellation_at' => now()->addHours(Reservation::FREE_CANCELLATION),
            'start_at' => now()->addDay()->startOfDay()->addHours($start),
            'finish_at' => now()->addDay()->startOfDay()->addHours($finish)->endOfHour(),
            'server_start_at' => now()->addDay()->startOfDay()->addHours($start),
            'server_finish_at' => now()->addDay()->startOfDay()->addHours($finish)->endOfHour(),
        ]);
        return $this->factoryReservationListingFromUser($oListing, $oUser, $data);
    }

    /**
     * @param Reservation $oReservation
     * @return Payment
     */
    public function factoryPaymentForReservation(Reservation $oReservation): Payment
    {
        $oPayment = $this->factoryPayment([
            'user_from_id' => $oReservation->user_id,
            'user_to_id' => $oReservation->listing->user_id,
            'amount' => $oReservation->total_price,
            'service_fee' => $oReservation->service_fee,
        ]);
        $oReservation->update([
            'payment_id' => $oPayment->id,
            'status' => Reservation::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);
        return $oPayment;
    }

    /**
     * @param Listing $oListing
     * @param User $oUser
     * @return Chat
     */
    public function factoryChatForTomorrowReservation(Listing $oListing, User $oUser)
    {
        $oHost = $oListing->user;
        $oReservation = $this->factoryReservationListingFromUserTomorrow($oListing, $oUser);
        // хоста создает чат
        $response = $this->apiPost('/user/chats', [
            'reservation_id' => $oReservation->id,
        ], $oHost);
        $this->assertTrue($response['success']);
        return Chat::find($response['data']['id']);
    }

    /**
     * @param Listing $oListing
     * @param User $oGuest
     * @param array $data
     * @return Reservation
     */
    public function factoryReservationWithPayment(Listing $oListing, User $oGuest, array $data = [])
    {
        $oReservation = $this->factoryReservationListingFromUser($oListing, $oGuest, $data);
        // добавляет свою карту
        $token = $this->factoryStripeToken();
        $response = $this->apiPost('/user/reservations/' . $oReservation->id . '/payment', [
            'last' => $token->card->last4,
            'brand' => $token->card->brand,
            'card_id' => $token->card->id,
            'token_id' => $token->id,
        ], $oGuest);
        $this->assertTrue($response['success']);
        return $oReservation;
    }

    /**
     * @param array $data
     * @return Payment
     */
    public function factoryPayment(array $data = [])
    {
        return factory(Payment::class)->create($data);
    }

    /**
     * @param array $data
     * @return Transfer
     */
    public function factoryTransfer(array $data = []): Transfer
    {
        return factory(Transfer::class)->create($data);
    }

    /**
     * @param Reservation $oReservation
     * @param array $data
     * @return Transfer
     */
    public function factoryTransferByReservation(Reservation $oReservation, array $data = []): Transfer
    {
        $data = array_merge([
            'amount' => $oReservation->price,
        ], $data);
        return factory(Transfer::class)->create($data);
    }

    /**
     * @param Reservation $oReservation
     * @param array $data
     * @return Payout
     */
    public function factoryPayoutByReservation(Reservation $oReservation, array $data = []): Payout
    {
        $data = array_merge([
            'amount' => $oReservation->price,
        ], $data);
        return factory(Payout::class)->create($data);
    }

    /**
     * @param User $oUser
     * @param array $data
     * @return Payment
     */
    public function factoryPaymentWithListing(User $oUser, array $data = [])
    {
        $oListing = $this->factoryUserListing($oUser);
        $oReservation = $this->factoryReservationListing($oListing);
        $oPayment = $this->factoryPayment($data);
        $oReservation->update([
            'payment_id' => $oPayment->id,
        ]);
        return $oPayment;
    }

    /**
     * @param array $data
     * @return Balance
     */
    public function factoryBalance(array $data = [])
    {
        return factory(Balance::class)->create($data);
    }
//
//    /**
//     * @return Token|null
//     */
//    public function factoryStripeToken()
//    {
//        $card = stripeCardDefault();
//        return (new StripePaymentService())
//            ->tokenCreateByCard($card['number'], $card['exp_month'], $card['exp_year'], $card['cvc']);
//    }

    /**
     * @param User $oUser
     * @return \Stripe\PaymentMethod|null
     */
    public function factoryStripeTokenForCustomer(User $oUser)
    {
        $card = stripeCardDefault();
        return (new PaymentMethodService())
            ->setUser($oUser)
            ->createPaymentMethodCard($card['number'], $card['exp_month'], $card['exp_year'], $card['cvc']);
    }

    /**
     * @return Token|null
     */
    public function factoryStripeToken()
    {
        $card = stripeCardDefault();
        return (new PaymentMethodService())
            ->tokenCreate($card['number'], $card['exp_month'], $card['exp_year'], $card['cvc']);
    }

    /**
     * @return Token|null
     */
    public function factoryStripeTokenVisaDebit()
    {
        $card = stripeCardDefaultVisaDebit();
        return (new PaymentMethodService())
            ->tokenCreate($card['number'], $card['exp_month'], $card['exp_year'], $card['cvc']);
    }

    /**
     * @param User $oUser
     * @return \Stripe\PaymentMethod|null
     */
    public function factoryStripePaymentMethodUser(User $oUser)
    {
        $card = stripeCardDefault();
        return (new PaymentMethodService())
            ->setUser($oUser)
            ->createPaymentMethodCard($card['number'], $card['exp_month'], $card['exp_year'], $card['cvc']);
    }

    /**
     * @return \Stripe\PaymentMethod|null
     */
    public function factoryStripePaymentMethod()
    {
        $card = stripeCardDefault();
        return (new PaymentMethodService())
            ->createPaymentMethodCard($card['number'], $card['exp_month'], $card['exp_year'], $card['cvc']);
    }
//
//    /**
//     * @return Token|null
//     */
//    public function factoryStripeTokenCardExpired()
//    {
//        $card = stripeCardDefault();
//        return (new StripePaymentService())
//            ->tokenCreateByCard(4000000000000069, $card['exp_month'], $card['exp_year'], $card['cvc']);
//    }
//
//    /**
//     * @return Token|null
//     */
//    public function factoryStripeTokenCardIncorrectCvc()
//    {
//        $card = stripeCardDefault();
//        return (new StripePaymentService())
//            ->tokenCreateByCard(4000000000000127, $card['exp_month'], $card['exp_year'], $card['cvc']);
//    }

    /**
     * @param User $oUser
     * @param array $data
     * @param array $extend
     * @return DatabaseNotification
     */
    public function factoryUserNotification(User $oUser, array $data = [], array $extend = []): DatabaseNotification
    {
        $data = array_merge([
            'id' => Str::uuid(),
            'type' => LeaveReviewNotification::class,
            'data' => array_merge([
                'type' => LeaveReviewNotification::NAME,
                'message' => 'Message',
            ], $extend),
            'read_at' => null,
        ], $data);
        /** @var DatabaseNotification $oNotification */
        $oNotification = $oUser->notifications()->create($data);
        return $oNotification;
    }

    /**
     * @param User $oUser
     * @param array $data
     * @return DatabaseNotification
     */
    public function factoryUserNotificationLeaveReview(User $oUser, array $data = []): DatabaseNotification
    {
        return $this->factoryUserNotification($oUser, $data, [
            'type' => LeaveReviewNotification::NAME,
            'message' => 'Tell us how was your experience at London space',
            'image' => 'https://api.staymenity.com/storage/images/user/5/model/square/pz9CvVf72UOl.jpg',
            'reservation_id' => 1,
        ]);
    }

    /**
     * @param User $oUser
     * @param array $data
     * @return FirebaseNotification
     */
    public function factoryUserFirebaseNotification(User $oUser, array $data = []): FirebaseNotification
    {
        $data = array_merge([
            'id' => Str::uuid(),
            'type' => LeaveReviewNotification::class,
            'data' => [
                'type' => LeaveReviewNotification::NAME,
                'message' => 'Message',
            ],
            'read_at' => null,
        ], $data);
        /** @var FirebaseNotification $oNotification */
        $oNotification = $oUser->firebaseNotifications()->create($data);
        return $oNotification;
    }

    /**
     * @param Listing $oListing
     * @param int $months
     */
    public function factoryUserCalendarLockWeekends(Listing $oListing, int $months = 4)
    {
        $oHost = $oListing->user;

        $now = now();
        $start = $now->copy();
        $end = $now->copy()->addMonths($months);

        while ($start->lte($end)) {
            if ($start->isWeekend()) {
                $oListing->calendarDates()->create([
                    'user_id' => $oHost->id,
                    'reservation_id' => null,
                    'type' => UserCalendar::TYPE_LOCKED,
                    'date_at' => $start,
                    'is_weekend' => $start->isWeekend(),
                ]);
            }
            $start->addDay();
        }
    }

    /**
     * @param Listing $oListing
     * @param int $months
     */
    public function factoryUserCalendarLockWeekdays(Listing $oListing, int $months = 4)
    {
        $oHost = $oListing->user;

        $now = now();
        $start = $now->copy();
        $end = $now->copy()->addMonths($months);

        while ($start->lte($end)) {
            if ($start->isWeekday()) {
                $oListing->calendarDates()->create([
                    'user_id' => $oHost->id,
                    'reservation_id' => null,
                    'type' => UserCalendar::TYPE_LOCKED,
                    'date_at' => $start,
                    'is_weekend' => $start->isWeekend(),
                ]);
            }
            $start->addDay();
        }
    }

    /**
     * @param array $data
     * @return Option
     */
    public function factoryOption(array $data): Option
    {
        return factory(Option::class)->create($data);
    }

    /**
     * @param array $data
     * @return Chat
     */
    public function factoryChat(array $data): Chat
    {
        return factory(Chat::class)->create($data);
    }

    /**
     * @param User $oUser
     * @param array $data
     * @return Chat
     */
    public function factoryChatToUser(User $oUser, array $data = []): Chat
    {
        $data = array_merge([
            'owner_id' => $oUser->id,
            'creator_id' => $oUser->id,
        ], $data);
        return factory(Chat::class)->create($data);
    }

    /**
     * @param Reservation $oReservation
     * @param array $data
     * @return Chat
     */
    public function factoryChatByReservation(Reservation $oReservation, array $data = []): Chat
    {
        $oHost = $oReservation->listing->user;
        $oGuest = $oReservation->user;
        $data = array_merge([
            'owner_id' => $oReservation->listing->user_id,
            'listing_id' => $oReservation->listing->id,
            'creator_id' => $oGuest->id,
        ], $data);
        /** @var Chat $oChat */
        $oChat = factory(Chat::class)->create($data);
        $oHost->chats()->attach($oChat);
        $oGuest->chats()->attach($oChat);
        return $oChat;
    }

    /**
     * @param array $data
     * @return ChatMessage
     */
    public function factoryChatMessage(array $data): ChatMessage
    {
        return factory(ChatMessage::class)->create($data);
    }

    /**
     * @param User $oUser
     * @param array $data
     * @return UserIdentity
     */
    public function factoryUserIdentity(User $oUser, array $data = []): UserIdentity
    {
        $data = array_merge($data, [
            'user_id' => $oUser->id,
        ]);
        return factory(UserIdentity::class)->create($data);
    }
}
