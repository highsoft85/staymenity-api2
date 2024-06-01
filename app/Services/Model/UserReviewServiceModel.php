<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Http\Transformers\Api\ReviewTransformer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserReviewServiceModel
{
    /**
     * @var User
     */
    private $oUser;

    /**
     * UserReviewServiceModel constructor.
     * @param User $oUser
     */
    public function __construct(User $oUser)
    {
        $this->oUser = $oUser;
    }

    /**
     * Отзывы на хоста, значит тот кто их оставляет - гость
     *
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getReviewsByHost(int $page, int $limit)
    {
        // убрал, т.к. смотрим на юзера как на хоста, поэтому отзывы только на листинги
//        // отзывы на гостя
//        $oReviewsToUser = $this->oUser->reviewsActiveOrdered()->get();
//        $aReviewsToUser = $oReviewsToUser->transform(function (Review $item) {
//            return (new ReviewTransformer())->transformFromRole($item, User::ROLE_GUEST);
//        })->toArray();

        // отзывы на хоста листинги
        $oListings = $this->oUser->listingsActive()->get();
        $reviews = [];
        foreach ($oListings as $oListing) {
            $aReviews = $oListing->reviewsActiveOrdered()->get()->transform(function (Review $item) {
                return (new ReviewTransformer())->transformFromRole($item, User::ROLE_GUEST);
            })->toArray();
            foreach ($aReviews as $aReview) {
                $reviews[] = $aReview;
            }
        }
        $page = $page - 1;
        $count = count($reviews);
        $aItems = collect($reviews)
            ->sortByDesc('published_at')
            ->slice($page * $limit)
            ->take($limit)
            ->values()
            ->toArray();

        $oResult = new LengthAwarePaginator($aItems, $count, $limit);
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }

    /**
     * @return mixed
     */
    public function getQueryReviewsByGuest()
    {
        return $this->oUser->reviewsActiveOrdered();
    }
}
