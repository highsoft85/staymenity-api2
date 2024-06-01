<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Events\Auth\RegisteredEvent;
use App\Events\Webhook\WebhookSyncToEvent;
use App\Exceptions\ResourceExceptionValidation;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Environment;
use App\Services\Firebase\FirebaseCounterMessagesService;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Geocoder\GeocoderIpService;
use App\Services\Image\ImageType;
use App\Services\Image\Upload\ImageUploadModelService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserServiceModel extends BaseServiceModel
{
    /**
     * @var User|mixed|null
     */
    private $oUser;

    /**
     * UserServiceModel constructor.
     * @param User|mixed|null $oUser
     */
    public function __construct($oUser = null)
    {
        $this->oUser = $oUser;
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $data['is_has_password'] = 1;

        if (!isset($data['email'])) {
            $data['login'] = $this->getPhoneByData($data);
            $data['email'] = null;
            $data['register_by'] = User::REGISTER_BY_PHONE;
        } else {
            $data['login'] = $data['email'];
            $data['register_by'] = User::REGISTER_BY_EMAIL;
        }
        if (!isset($data['password'])) {
            $data['is_has_password'] = 0;
            $data['password'] = Str::random(16);
        }

        /** @var User $oUser */
        $this->oUser = User::create([
            'phone' => isset($data['phone'])
                ? $this->getPhoneByData($data)
                : null,
            'login' => $data['login'],
            'email' => $data['email'] ?? null,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => Hash::make($data['password']),
            'register_by' => $data['register_by'],
            'is_has_password' => $data['is_has_password'],
            'status' => User::STATUS_ACTIVE,
        ]);
        $this->afterCreate();
        return $this->oUser;
    }

    /**
     * @param array $data
     */
    public function saveMainInfo(array $data = [])
    {
        $save = [];
        if (isset($data['first_name'])) {
            $save['first_name'] = $data['first_name'];
        }

        if (array_key_exists('last_name', $data)) {
            $save['last_name'] = $data['last_name'] ?? null;
        }
        if (array_key_exists('birthday_at', $data)) {
            $save['birthday_at'] = !empty($data['birthday_at'])
                ? Carbon::createFromFormat('m/d/Y', $data['birthday_at'])
                : null;
        }
        if (array_key_exists('gender', $data)) {
            $save['gender'] = $data['gender'];
        }
        if (isset($data['email'])) {
            // @todo проверка на уникальный email, есть в реквесте
            $save['email'] = $data['email'];
        }
        if (isset($data['phone'])) {
            $phone = preg_replace('/[^0-9]/', '', $data['phone']);
            if (strlen($phone) <= 9) {
                throw new ResourceExceptionValidation(__('validation.phone', ['attribute' => 'phone']));
            }
            $phone = $this->getPhoneByData($data);
            if ($phone !== $this->oUser->phone) {
                $save['phone_verified_at'] = null;
            }
            $save['phone'] = $phone;
            if (isset($data['phone_verified'])) {
                $save['phone_verified_at'] = now();
            }
        }
        if (isset($data['hostfully_agency_uid'])) {
            $this->saveDetails($data);
        }
        $this->oUser->update($save);
    }

    /**
     * @param array $data
     * @return string
     */
    public function getPhoneByData(array $data)
    {
        $phone = preg_replace('/[^0-9]/', '', $data['phone']);
        if (strlen($phone) === 10) {
            $phone = '1' . $phone;
        }
        return $phone;
    }

    /**
     * @param array $data
     */
    public function saveDetails(array $data = [])
    {
        $oDetails = $this->oUser->details;
        if (is_null($oDetails)) {
            $this->oUser->details()->create([]);
        }

        $details = [];
        if (isset($data['description'])) {
            $details['description'] = $data['description'];
        }
        if (isset($data['hostfully_agency_uid'])) {
            if (!healthCheckHostfully()->isActive()) {
                slackInfo([], 'HOSTFULLY IS NOT ACTIVE FOR UPDATE AGENCY UID');
            } else {
                if ($data['hostfully_agency_uid'] === 'null') {
                    event(new WebhookSyncToEvent($this->oUser, false));
                } else {
                    if (is_null($this->oUser->details->hostfully_agency_uid)) {
                        $details['hostfully_agency_uid'] = $data['hostfully_agency_uid'];
                        $this->oUser->details()->update($details);
                        event(new WebhookSyncToEvent($this->oUser, true));
                    }
                }
            }
        } else {
            $this->oUser->details()->update($details);
        }
    }

    /**
     * @param string $place_id
     */
    public function saveLocation(string $place_id)
    {
        $this->baseSaveLocation($this->oUser, $place_id);
    }

    /**
     * @param string|null $current
     * @param string $new
     * @return array
     */
    public function savePassword(?string $current = null, string $new)
    {
        if (!is_null($current)) {
            if (!Hash::check($current, $this->oUser->password)) {
                return responseCommon()->error([
                    'message' => 'These credentials do not match our records.'
                ]);
            }
        }
        $this->oUser->update([
            'password' => Hash::make($new),
        ]);
        if (is_null($current)) {
            $this->oUser->update([
                'is_has_password' => 1,
            ]);
        }
        $this->oUser->setRememberToken(Str::random(60));
        return responseCommon()->success();
    }

    /**
     * @return \App\Services\Transaction\Transaction
     */
    public function delete()
    {
        return transaction()->commitAction(function () {
            $oUser = $this->oUser;
            // удаление соц сетей
            $oListings = $oUser->listings;
            foreach ($oListings as $oListing) {
                (new ListingServiceModel($oListing))->delete();
            }
            $oUser->saves()->delete();
            $oUser->favorites()->delete();
            // не удаляем, есть софт делит
            //$oUser->details()->delete();
            $oUser->settings()->delete();
            $oUser->calendarDates()->delete();
            $oUser->reviews()->delete();
            // не удаляем, есть софт делит
            //$oUser->reservations()->delete();
            $oReservations = $oUser->reservations;
            foreach ($oReservations as $oReservation) {
                // если прошла, то ничего не делаем
                if ($oReservation->isPassed()) {
                    continue;
                }
                // если идет, то ничего не делаем
                if ($oReservation->isBeginning()) {
                    continue;
                }
                if ($oReservation->isCancelled() || $oReservation->isDeclined()) {
                    continue;
                }
                // иначе отклоняем
                (new ReservationServiceModel($oReservation))
                    ->setCancelledType(Reservation::CANCELLED_TYPE_BY_USER_DELETED)
                    ->setDeclined();
            }
            $oUser->visits()->delete();
            $oUser->tokens()->delete();
            $oUser->socials()->delete();
            $oUser->devices()->delete();
            $oUser->socialAccounts()->delete();
            $oUserIdentities = $oUser->identities()->get();
            foreach ($oUserIdentities as $oUserIdentity) {
                (new UserIdentityVerificationServiceModel($oUser, $oUserIdentity))->delete();
            }
            // не удаляем, есть софт делит
            //$oUser->balance()->delete();
            // удаление рейтингов
            $oUser->notifications()->delete();
            // удаление локаций
            $oUser->locations()->delete();
            // собрать изображения
            $oImages = $oUser->modelImages;
            $oUser->update([
                'status' => User::STATUS_NOT_ACTIVE,
            ]);
            (new FirebaseCounterMessagesService())->database()->setUser($oUser)->clearUserCounter();
            (new FirebaseCounterNotificationsService())->database()->setUser($oUser)->clearUserCounter();
            slackInfo($oUser->id, 'User DELETE');
            $oUser->delete();
            // удаление изображений после успешных удалений
            $type = ImageType::MODEL;
            $options = (new \App\Cmf\Project\User\UserController())->image[$type];
            foreach ($oImages as $oImage) {
                (new ImageUploadModelService())->delete($oUser, $oImage, $options);
            }
            return null;
        });
    }

    /**
     * @return \App\Services\Transaction\Transaction
     */
    public function forceDelete()
    {
        return transaction()->commitAction(function () {
            $oReservations = $this->oUser->reservations;
            foreach ($oReservations as $oReservation) {
                (new ReservationServiceModel($oReservation))->delete();
            }
            $this->delete();
            $this->oUser->forceDelete();
        });
    }

    /**
     * @param string $provider
     * @throws \Exception
     */
    public function deleteSocial(string $provider)
    {
        // нельзя удалить социальную сеть, если нет другого варианта входа в систему
        if (!$this->canLoginByPhoneOrEmail()) {
            //throw new \Exception('Cannot disconnect account without phone or email and password');
        }

        $oSocialAccount = $this->oUser->socialAccounts()->where('provider', $provider)->first();
        if (!is_null($oSocialAccount)) {
            $oSocialAccount->delete();
        }
    }

    /**
     * @return bool
     */
    public function canLoginByPhoneOrEmail()
    {
        if (empty($this->oUser->phone)) {
            if (!$this->oUser->isHasPassword()) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     */
    public function afterCreate()
    {
        $this->oUser->update([
            'registered_at' => now(),
        ]);
        if (is_null($this->oUser->details)) {
            $this->oUser->details()->create();
        }
        if (is_null($this->oUser->settings)) {
            $this->oUser->settings()->create();
        }
    }

    /**
     *
     */
    public function afterRegister()
    {
        event(new RegisteredEvent($this->oUser));
    }

    /**
     * @param string $role
     */
    public function setCurrentRole(string $role)
    {
        if (!$this->oUser->hasAnyRole([$role])) {
            $this->oUser->assignRole($role);
        }
        $this->oUser->update([
            'current_role' => $role,
        ]);
    }

    /**
     * @param string $phone
     * @return bool
     */
    public function checkPhoneUnique(string $phone)
    {
        $phone = $this->getPhoneByData([
            'phone' => $phone,
        ]);
        $query = User::where('phone', $phone);

        if (!is_null($this->oUser)) {
            $query->where('id', '<>', $this->oUser->id);
        }
        $count = $query->count();
        return $count === 0;
    }

    /**
     * @return bool
     */
    public function checkUserBeforeLogin()
    {
        if ($this->oUser->isBanned()) {
            return false;
        }
        if (!$this->oUser->isActive()) {
            return false;
        }
        return true;
    }

    /**
     * @param string $name
     * @return string
     */
    public function tokenGet(string $name = User::TOKEN_AUTH_NAME): string
    {
        $oToken = $this->oUser->tokens()->where('name', $name)->first();
        if (is_null($oToken)) {
            $token = $this->oUser->createToken($name);
            $token = $token->plainTextToken;
        } else {
            $this->oUser->tokens()->where('name', $name)->delete();
            $token = $this->oUser->createToken($name);
            $token = $token->plainTextToken;
        }
        return $token;
    }

    /**
     *
     */
    public function saveUserTimezoneByIp()
    {
        if (config('services.ip_api.enabled')) {
            $timezone = (new GeocoderIpService())->timezone(request()->ip());
            if (!is_null($timezone)) {
                $this->oUser->update([
                    'timezone' => $timezone,
                ]);
            }
        }
    }

    /**
     * @return bool
     */
    public function notificationByPushEnabled()
    {
        if (is_null($this->oUser->settings)) {
            return true;
        }
        return $this->oUser->settings->notification_push === 1;
    }

    /**
     * @return bool
     */
    public function notificationByMailEnabled()
    {
        if (is_null($this->oUser->settings)) {
            return true;
        }
        return $this->oUser->settings->notification_mail === 1;
    }

    /**
     * @return bool
     */
    public function notificationByMessagesEnabled()
    {
        if (is_null($this->oUser->settings)) {
            return true;
        }
        $hasPush = $this->notificationByPushEnabled();
        if (!$hasPush) {
            return false;
        }
        return $this->oUser->settings->notification_messages === 1;
    }

    /**
     *
     */
    public function setHasPayoutConnect()
    {
        $this->oUser->update([
            'has_payout_connect' => 1,
        ]);
    }

    /**
     * @param string|null $account
     * @return string|null
     */
    public function setStripeAccount(?string $account)
    {
        $save = [];
        if (config('app.env') === Environment::PRODUCTION) {
            $save['stripe_account'] = $account;
        } else {
            $save['test_stripe_account'] = $account;
        }
        $this->oUser->details()->update($save);
        $this->oUser->refresh();
        return $account;
    }

    /**
     * @return bool
     */
    public function hasFutureReservationsForListings()
    {
        $aId = $this->oUser->listingsActive()->pluck('id')->toArray();
        $oReservations = Reservation::futureNotPassed()
            ->whereIn('listing_id', $aId)
            ->get();
        if ($oReservations->count() !== 0) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasFutureReservations()
    {
        $oReservations = Reservation::futureNotPassed()
            ->where('user_id', $this->oUser->id)
            ->get();
        if ($oReservations->count() !== 0) {
            return true;
        }
        return false;
    }

    /**
     * @return array|null
     */
    public function defaultCoordinates()
    {
        // Los Angeles
        return [
            'latitude' => 34.0536909,
            'longitude' => -118.242766,
        ];
        // New York
//        return [
//            'latitude' => 40.6838480,
//            'longitude' => -73.8621292,
//        ];
        // по IP
//        return (new GeocoderIpService())->coordinates(request()->ip());
    }

    /**
     * @return array
     */
    public function getListingsAccessible(): array
    {
        $array = Reservation::where('user_id', $this->oUser->id)->whereIn('status', [
            Reservation::STATUS_ACCEPTED,
        ])->pluck('listing_id')->toArray();
        $array = array_unique($array);
        return $array;
    }
}
