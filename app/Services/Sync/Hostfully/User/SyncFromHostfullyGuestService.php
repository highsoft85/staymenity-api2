<?php

declare(strict_types=1);

namespace App\Services\Sync\Hostfully\User;

use App\Models\HostfullyListing;
use App\Models\HostfullyUser;
use App\Models\Listing;
use App\Models\Type;
use App\Models\User;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Hostfully\Models\Leads;
use App\Services\Hostfully\Models\Properties;
use App\Services\Hostfully\Properties\Store;
use App\Services\Hostfully\Properties\Update;
use App\Services\Model\ListingServiceModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncFromHostfullyGuestService
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private bool $force = false;

    /**
     * SyncHostfullyListingService constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     *
     */
    public function setForce()
    {
        $this->force = true;
    }

    /**
     * @return User
     */
    public function sync(): User
    {
        $email = $this->emailByNames();
        /** @var User|null $oModel */
        $oModel = User::where('login', $email)->first();
        if (is_null($oModel)) {
            $oModel = $this->store();
        } else {
            $oModel = $this->update($oModel);
        }
        return $oModel;
    }

    /**
     * @return string
     */
    private function emailByNames(): string
    {
        if (!empty($this->data[Leads::EMAIL]) && $this->data[Leads::EMAIL] !== 'HIDDEN') {
            // чтобы пользователь смог зарегистрироваться со своим email, который был синхронизирован
            return 'hostfully_' . $this->data[Leads::EMAIL];
        }
        $firstName = $this->data[Leads::FIRST_NAME];
        if (empty($firstName)) {
            $firstName = Str::random(6);
        }
        $firstName = Str::lower($firstName);

        $lastName = $this->data[Leads::LAST_NAME];
        if (empty($lastName)) {
            $lastName = Str::random(6);
        }
        $lastName = Str::lower($lastName);
        return $firstName . '_' . $lastName . '_hostfully@staymenity.com';
    }

    /**
     * @return User
     */
    private function store(): User
    {
        $data = $this->data();
        $oModel = User::create($data);

        $this->updateTimezone($oModel);
        $oModel->assignRole(User::ROLE_GUEST);
        $this->saveData($oModel);
        return $oModel;
    }

    /**
     * @param User $oModel
     * @return User
     */
    private function update(User $oModel): User
    {
        $data = $this->data();
        if ($this->force) {
            $oModel->forceDelete();
            $oModel = User::create($data);
        } else {
            $oModel->update($data);
        }

        $this->updateTimezone($oModel);
        $oModel->assignRole(User::ROLE_GUEST);
        $this->saveData($oModel);
        return $oModel;
    }

    /**
     * @param User $oUser
     * @return HostfullyUser
     */
    private function saveData(User $oUser)
    {
        $oModel = $oUser->hostfully;
        if (is_null($oModel)) {
            $oModel = HostfullyUser::create([
                'lead_uid' => $this->data[Leads::UID],
                'user_id' => $oUser->id,
                'last_sync_at' => now(),
                'external' => $this->data,
            ]);
        } else {
            $oModel->update([
                'last_sync_at' => now(),
            ]);
        }
        return $oModel;
    }

    /**
     * @return array
     */
    private function data()
    {
        return [
            'login' => $this->emailByNames(),
            'email' => $this->emailByNames(),
            'phone' => null,
            'first_name' => $this->data[Leads::FIRST_NAME],
            'last_name' => $this->data[Leads::LAST_NAME],
            'password' => Hash::make(Str::random(10)),
            'register_by' => User::REGISTER_BY_HOSTFULLY,
            'is_has_password' => 0,
            'current_role' => User::ROLE_GUEST,
            'source' => User::SOURCE_HOSTFULLY,
            'status' => 1,
        ];
    }

    /**
     * @param User $oUser
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function updateTimezone(User $oUser)
    {
        $address = $this->transformAddress();
        $results = (new GeocoderCitiesService())->address($address);

        if (isset($results[0]['place_id'])) {
            $timezone = (new GeocoderCitiesService())->timezoneByPlace($results[0]['place_id']);
            $oUser->update([
                'timezone' => $timezone,
            ]);
        }
    }


    /**
     * @return string
     */
    private function transformAddress()
    {
        return $this->data[Leads::STATE] . ', ' . $this->data[Leads::CITY];
    }
}
