<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Http\Controllers\Api\Auth\Socialite\Facebook;
use App\Http\Transformers\Api\Common\ImageTransformerTrait;
use App\Models\Listing;
use App\Models\Review;
use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserSave;
use App\Models\UserSetting;
use App\Services\Firebase\FirebaseCounterMessagesService;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Firebase\FirebaseCounterNotificationTypeService;
use App\Services\Model\UserServiceModel;
use App\Services\Socialite\AppleAccountService;
use App\Services\Socialite\FacebookAccountService;
use App\Services\Socialite\GoogleAccountService;
use App\Services\Socialite\MockAccountService;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class UserTransformer extends TransformerAbstract
{
    use ImageTransformerTrait;

    /**
     * @param User $oItem
     * @return array
     */
    public function transform(User $oItem)
    {
        return [
            'id' => $oItem->id,
            'current_role' => $this->currentRole($oItem),
            'first_name' => $oItem->first_name,
            'last_name' => $oItem->last_name,
            'phone' => $oItem->phone,
            'email' => $oItem->email,
            'image' => $oItem->image_square,
            'birthday_at' => $this->birthdayAt($oItem),
            'description' => $this->description($oItem),
            'location' => $this->location($oItem),
            'gender' => $oItem->gender,
            'social_accounts' => $this->accounts($oItem),
            'rating' => $this->rating($oItem),
            'reviews' => $this->reviews($oItem),
            'reviews_length' => $this->reviewsLength($oItem),
            'registered_at' => $this->registeredAt($oItem),
            'registered_at_formatted' => $this->registeredAt($oItem, true),
            'saves' => $this->saves($oItem),
            'integrations' => $this->integrations($oItem),
            'settings' => $this->settings($oItem),
            'balance' => $this->balance($oItem),
            'firebase' => $this->firebase($oItem),
            'identity_verification_status' => $this->identityVerificationStatus($oItem),
            //'has_access' => true,
            'listings_accessible' => $this->listingsAccessible($oItem),
            'has_payout_connect' => $oItem->hasPayoutConnect(),
            'has_image' => $this->hasUserImage($oItem),
            'is_banned' => $oItem->isBanned(),
            'is_need_password' => !$oItem->isHasPassword(),
            'is_email_verified' => $oItem->isEmailVerified(),
            'is_phone_verified' => $oItem->isPhoneVerified(),
            'is_identity_verified' => $oItem->isIdentityVerified(),
        ];
    }

    /**
     * @param User $oItem
     * @param string|null $role
     * @return array
     */
    public function transformDetail(User $oItem, ?string $role = null)
    {
        return [
            'id' => $oItem->id,
            'first_name' => $oItem->first_name,
            'last_name' => $oItem->last_name,
            //'phone' => $oItem->phone,
            //'email' => $oItem->email,
            'image' => $oItem->image_square,
            //'birthday_at' => $this->birthdayAt($oItem),
            'description' => $this->description($oItem),
            'location' => $this->location($oItem),
            'gender' => $oItem->gender,
            'rating' => $this->rating($oItem, $role),
            'listings' => $this->listings($oItem, $role),
            'reviews' => $this->reviews($oItem, $role),
            'reviews_length' => $this->reviewsLength($oItem, $role),
            'registered_at' => $this->registeredAt($oItem),
            'registered_at_formatted' => $this->registeredAt($oItem, true),
            'is_email_verified' => $oItem->isEmailVerified(),
            'is_phone_verified' => $oItem->isPhoneVerified(),
            'is_identity_verified' => $oItem->isIdentityVerified(),
        ];
    }

    /**
     * @param User $oItem
     * @return array
     */
    public function transformHost(User $oItem)
    {
        return [
            'id' => $oItem->id,
            'first_name' => $oItem->first_name,
            'last_name' => $oItem->last_name,
            'description' => $this->description($oItem),
            'phone' => $oItem->phone,
            'email' => $oItem->email,
            'image' => $oItem->image_square,
            'rating' => $this->ratingForHost($oItem),
            'reviews_length' => $this->reviewsLengthForHost($oItem),
            'is_identity_verified' => $oItem->isIdentityVerified(),
        ];
    }

    /**
     * @param User $oItem
     * @return array
     */
    public function transformMention(User $oItem)
    {
        if ($oItem->source === User::SOURCE_HOSTFULLY && is_null($oItem->first_name)) {
            return [
                'id' => $oItem->id,
                'first_name' => $oItem->email,
                'last_name' => $oItem->last_name,
                'image' => config('image.url') . '/img/services/hostfully.png',
            ];
        }
        return [
            'id' => $oItem->id,
            'first_name' => $oItem->first_name,
            'last_name' => $oItem->last_name,
            'image' => $oItem->image_square,
        ];
    }

    /**
     * @param User $oItem
     * @return string|null
     */
    private function birthdayAt(User $oItem)
    {
        if (is_null($oItem->birthday_at)) {
            return null;
        }
        return Carbon::parse($oItem->birthday_at)->format('m/d/Y');
    }

    /**
     * @param User $oItem
     * @return string|null
     */
    private function description(User $oItem)
    {
        return $oItem->details->description ?? null;
    }

    /**
     * @param User $oItem
     * @param string|null $role
     * @return array
     */
    private function rating(User $oItem, ?string $role = null)
    {
        if (!is_null($role)) {
            // рейтинг для текущией роли, для просмотра самим пользователем
            if ($role === User::ROLE_GUEST) {
                $value = $oItem->ratingsToAverageByReview();
                $value = round($value, 2);
                $count = $oItem->ratingsToCountByReview();
                return (new RatingTransformer())->transform($value, $count);
            }
            if ($role === User::ROLE_HOST) {
                return $this->ratingForHost($oItem);
            }
            return (new RatingTransformer())->transform(0, 0);
        } else {
            // рейтинг для текущией роли, для просмотра самим пользователем
            if ($oItem->current_role === User::ROLE_GUEST) {
                $value = $oItem->ratingsToAverageByReview();
                $value = round($value, 2);
                $count = $oItem->ratingsToCountByReview();
                return (new RatingTransformer())->transform($value, $count);
            }
            if ($oItem->current_role === User::ROLE_HOST) {
                return $this->ratingForHost($oItem);
            }
            return (new RatingTransformer())->transform(0, 0);
        }
    }

    /**
     * @param User $oItem
     * @return array
     */
    private function ratingForHost(User $oItem)
    {
        $oListings = $this->getUserListingsForReviews($oItem);
        $allRating = 0;
        $allCount = 0;
        $ratings = [];
        foreach ($oListings as $oListing) {
            $count = $oListing->ratingsToCountByReview();
            if ($count !== 0) {
                $ratings[] = $oListing->ratingsToAverageByReview();
                $allCount = $allCount + $count;
            }
        }
        if (!empty($ratings)) {
            $allRating = array_sum($ratings) / count($ratings);
            $allRating = round($allRating, 2);
        }
        return (new RatingTransformer())->transform($allRating, $allCount);
    }

    /**
     * @param User $oItem
     * @return array|null
     */
    private function location(User $oItem)
    {
        if (is_null($oItem->location)) {
            return null;
        }
        return (new LocationTransformer())->transform($oItem->location);
    }

    /**
     * @param User $oItem
     * @return array
     */
    private function accounts(User $oItem)
    {
        $oSocialAccounts = $oItem->socialAccounts;

        $accounts = [];
        $providers = [
            GoogleAccountService::NAME => 'Google',
            FacebookAccountService::NAME => 'Facebook',
            AppleAccountService::NAME => 'Apple',
            //MockAccountService::NAME => 'Mock',
        ];
        $countConnected = 0;
        foreach ($providers as $name => $provider) {
            $has = $oSocialAccounts->where('provider', $name)->first();
            if (!is_null($has)) {
                $countConnected = $countConnected + 1;
            }
            $accounts[] = [
                'name' => $name,
                'title' => $provider,
                'connected' => !is_null($has),
                'can_disconnect' => true,
            ];
        }
        foreach ($accounts as $key => $account) {
            if ($account['connected'] && $countConnected === 1) {
                if (!(new UserServiceModel($oItem))->canLoginByPhoneOrEmail()) {
                    $accounts[$key]['can_disconnect'] = false;
                }
            }
        }
        return $accounts;
    }

    /**
     * @param User $oItem
     * @return Listing[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getUserListingsForReviews(User $oItem)
    {
        return $oItem->listingsActive()->with('reviewsActiveOrdered')->get();
    }

    /**
     * @param User $oItem
     * @param string|null $role
     * @return array
     */
    private function reviews(User $oItem, ?string $role = null)
    {
        if (!is_null($role)) {
            if ($role === User::ROLE_GUEST) {
                return $this->reviewsForGuest($oItem);
            }
            if ($role === User::ROLE_HOST) {
                return $this->reviewsForHost($oItem);
            }
        } else {
            if ($oItem->current_role === User::ROLE_GUEST) {
                return $this->reviewsForGuest($oItem);
            }
            if ($oItem->current_role === User::ROLE_HOST) {
                return $this->reviewsForHost($oItem);
            }
        }
        return [];
    }

    /**
     * @param User $oItem
     * @return array
     */
    private function reviewsForHost(User $oItem)
    {
        $oListings = $this->getUserListingsForReviews($oItem);
        $reviews = [];
        foreach ($oListings as $oListing) {
            $aReviews = $oListing->reviewsActiveOrdered()->get()->transform(function (Review $item) {
                return (new ReviewTransformer())->transform($item);
            })->toArray();
            foreach ($aReviews as $aReview) {
                $reviews[] = $aReview;
            }
        }
        $reviews = collect($reviews)
            ->sortByDesc('published_at')
            ->take(4)
            ->values()
            ->toArray();

        return $reviews;
    }

    /**
     * @param User $oItem
     * @return array
     */
    private function reviewsForGuest(User $oItem)
    {
        return $oItem->reviewsActiveOrdered()->take(4)->get()->transform(function (Review $item) {
            return (new ReviewTransformer())->transform($item);
        })->toArray();
    }


    /**
     * @param User $oItem
     * @param string|null $role
     * @return int
     */
    private function reviewsLength(User $oItem, ?string $role = null)
    {
        if (!is_null($role)) {
            if ($role === User::ROLE_GUEST) {
                return $oItem->reviewsActiveOrdered()->count();
            }
            if ($role === User::ROLE_HOST) {
                return $this->reviewsLengthForHost($oItem);
            }
        } else {
            if ($oItem->current_role === User::ROLE_GUEST) {
                return $oItem->reviewsActiveOrdered()->count();
            }
            if ($oItem->current_role === User::ROLE_HOST) {
                return $this->reviewsLengthForHost($oItem);
            }
        }
        return 0;
    }

    /**
     * @param User $oItem
     * @return int
     */
    private function reviewsLengthForHost(User $oItem)
    {
        $oListings = $this->getUserListingsForReviews($oItem);
        $reviews = 0;
        foreach ($oListings as $oListing) {
            $count = $oListing->reviewsActiveOrdered()->count();
            $reviews = $reviews + $count;
        }
        return $reviews;
    }

    /**
     * @param User $oItem
     * @param bool $formatted
     * @return string
     */
    private function registeredAt(User $oItem, bool $formatted = false)
    {
        $date = null;
        if (!is_null($oItem->registered_at)) {
            $date = $oItem->registered_at;
        } else {
            $date = $oItem->created_at;
        }
        if (is_null($date)) {
            $date = now();
        }
        if ($formatted) {
            return $date->format('Y');
        }
        return $date->toDateTimeString();
    }

    /**
     * @param User $oItem
     * @return mixed
     */
    private function settings(User $oItem)
    {
        $settings['notifications'] = [
            UserSetting::NOTIFICATION_MAIL => 1,
            UserSetting::NOTIFICATION_PUSH => 1,
            UserSetting::NOTIFICATION_MESSAGES => 1,
        ];
        if (!is_null($oItem->settings)) {
            $settings['notifications'][UserSetting::NOTIFICATION_MAIL] = $oItem->settings->notification_mail;
            $settings['notifications'][UserSetting::NOTIFICATION_PUSH] = $oItem->settings->notification_push;
            $settings['notifications'][UserSetting::NOTIFICATION_MESSAGES] = $oItem->settings->notification_messages;
        }
        return $settings;
    }

    /**
     * @param User $oItem
     * @return array
     */
    private function saves(User $oItem)
    {
        return $oItem->savesActive()->ordered()->get()->transform(function (UserSave $item) {
            return (new UserSaveTransformer())->transform($item);
        })->toArray();
    }

    /**
     * @param User $oItem
     * @return array
     */
    private function integrations(User $oItem)
    {
        return [
            'hostfully' => [
                'agency_uid' => $oItem->details->hostfully_agency_uid ?? null,
                'active' => !is_null($oItem->details) && $oItem->details->hostfully_status === 1,
            ]
        ];
    }

    /**
     * @param User $oItem
     * @return string
     */
    public function currentRole(User $oItem)
    {
        $aRoles = $oItem->roles()->pluck('name')->toArray();
        // не часты должен быть случай
        if (empty($aRoles)) {
            return 'default';
        }
        $role = $aRoles[0];
        if (!is_null($oItem->current_role) && in_array($oItem->current_role, $aRoles)) {
            return $oItem->current_role;
        } else {
            if (in_array(User::ROLE_HOST, $aRoles)) {
                return User::ROLE_HOST;
            }
            if (in_array(User::ROLE_GUEST, $aRoles)) {
                return User::ROLE_GUEST;
            }
            if (in_array($role, [User::ROLE_ADMIN])) {
                return User::ROLE_HOST;
            }
            return $role;
        }
    }

    /**
     * @param User $oItem
     * @return array
     *
     * @deprecated
     */
    private function balance(User $oItem)
    {
        $oBalance = $oItem->balance;
        if (is_null($oBalance)) {
            return (new BalanceTransformer())->transformEmpty();
        }
        return (new BalanceTransformer())->transform($oBalance);
    }

    /**
     * @param User $oUser
     * @param string|null $role
     * @return array
     */
    public function listings(User $oUser, ?string $role = null)
    {
        if (!is_null($role) && $role === User::ROLE_GUEST) {
            return [];
        } else {
            $oListings = $oUser->listingsActive()->get();
            return $oListings->transform(function (Listing $item) {
                return (new ListingTransformer())->transformCard($item);
            })->toArray();
        }
    }

    /**
     * @param User $oUser
     * @return array
     */
    public function identityVerificationStatus(User $oUser)
    {
        /** @var UserIdentity|null $oIdentity */
        $oIdentity = $oUser->identities()->first();
        if (is_null($oIdentity)) {
            return [
                'name' => UserIdentity::staticStatusIcons()[UserIdentity::STATUS_NOT_VERIFIED]['name'],
                'title' => UserIdentity::staticStatuses()[UserIdentity::STATUS_NOT_VERIFIED],
                'errors' => null,
            ];
        } else {
            return [
                'name' => $oIdentity->statusName,
                'title' => $oIdentity->statusText,
                'errors' => $oIdentity->errorsObject,
            ];
        }
    }

    /**
     * @param User $oUser
     * @return array
     */
    public function firebase(User $oUser)
    {
        $oChats = $oUser->chatsActive;
        $counterMessages = [];
        foreach ($oChats as $oChat) {
            $counterMessages[] = [
                'channel' => (new FirebaseCounterMessagesService())->setUser($oUser)->setChat($oChat)->getChannel(),
                'enabled' => config('firebase.enabled'),
            ];
        }
        $channels = [];
        $channels['counter_notifications'] = [
            'channel' => (new FirebaseCounterNotificationsService())->setUser($oUser)->getChannel(),
            'enabled' => config('firebase.enabled'),
        ];
        $channels['counter_messages'] = $counterMessages;
        $channels['last_notification_type'] = [
            'channel' => (new FirebaseCounterNotificationTypeService())->setUser($oUser)->getChannel(),
            'enabled' => config('firebase.enabled'),
        ];
//        /** @var UserIdentity|null $oUserIdentity */
//        $oUserIdentity = $oUser->identities()->first();
//        if (!is_null($oUserIdentity) && $oUserIdentity->isPending()) {
//
//        }
        return $channels;
    }

    /**
     * @param User $oUser
     * @return array
     */
    public function listingsAccessible(User $oUser)
    {
        return (new UserServiceModel($oUser))->getListingsAccessible();
    }
}
