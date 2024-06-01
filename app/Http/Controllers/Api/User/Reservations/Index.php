<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Reservations;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Requests\Api\User\Reservations\IndexRequest;
use App\Http\Requests\Api\User\Reservations\StoreRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\ReservationTransformer;
use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\UserReservationServiceModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends ApiController
{
    /**
     * @param IndexRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(IndexRequest $request)
    {
        $oUser = $this->authUser($request);

        if ($request->exists('admin-visual')) {
            $data = $request->query->all();
        } else {
            $data = $request->validated();
        }
        $limit = $data['limit'] ?? 4;
        $type = $data['type'] ?? Reservation::SEARCH_TYPE_UPCOMING;

        $query = null;

        if ($oUser->hasRole(User::ROLE_HOST)) {
            if ($oUser->current_role === User::ROLE_HOST || is_null($oUser->current_role)) {
                $query = $this->getReservationsByHost($oUser, $type);
            }
        }
        if ($oUser->hasRole(User::ROLE_GUEST)) {
            if ($oUser->current_role === User::ROLE_GUEST || is_null($oUser->current_role)) {
                $query = $this->getReservationsByGuest($oUser, $type);
            }
        }
        if (is_null($query)) {
            return responseCommon()->apiDataSuccessWithPagination([], null);
        }
        $query = $this->queryByType($query, $type);

        if ($type === Reservation::SEARCH_TYPE_UPCOMING) {
            $query = $query->ordered();
        } else {
            $query = $query->orderedForPassed();
        }

        if (isset($data['start_at']) && isset($data['finish_at'])) {
            $start_at = Carbon::parse($data['start_at']);
            $finish_at = Carbon::parse($data['finish_at']);
            $query = $this->queryByDate($query, $start_at, $finish_at);
        }

        /** @var LengthAwarePaginator $oResult */
        $oResult = $query->paginate($limit);
        $aItems = $oResult->values()->transform(function (Reservation $item) use ($oUser) {
            return (new ReservationTransformer())->transformByUser($item, $oUser);
        })->toArray();
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }

    /**
     * @param User $oUser
     * @param string $type
     * @return Builder
     */
    private function getReservationsByGuest(User $oUser, string $type)
    {
        /** @var Builder $query */
        $query = $oUser
            ->reservations()
            ->whereHas('listing', function (Builder $q) {
                $q->whereNull('deleted_at');
            });

        return $query;
    }

    /**
     * Хост увидит только оплаченные, значит там и чат будет
     *
     * @param User $oUser
     * @param string $type
     * @return Builder
     */
    private function getReservationsByHost(User $oUser, string $type)
    {
        $aId = $oUser
            ->listingsActive()
            ->with([
                'listingsActive.modelImages',
            ])
            ->pluck('id')
            ->toArray();
        $query = Reservation::where(function ($q) {
            $q->where('source', Reservation::SOURCE_HOSTFULLY)
                ->orWhereNotNull('payment_id');
        })
            ->whereIn('listing_id', $aId);

        // хост видит в Upcoming только оплаченные, т.е. accepted, т.е. active
        if ($type === Reservation::SEARCH_TYPE_UPCOMING) {
            $query = $query->active();
        }

        return $query;
    }

    /**
     * @param Builder|mixed|Reservation $query
     * @param string $type
     * @return Builder
     */
    private function queryByType($query, string $type)
    {
        switch ($type) {
            case Reservation::SEARCH_TYPE_PREVIOUS:
                $query->passed()->active();
                break;
            case Reservation::SEARCH_TYPE_CANCELLED:
                $query->cancelledOrDeclined();
                break;
            case Reservation::SEARCH_TYPE_UPCOMING:
                $query->futureNotBeginning();
                break;
            default:
                break;
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param Carbon $start_at
     * @param Carbon $finish_at
     * @return Builder
     */
    private function queryByDate(Builder $query, Carbon $start_at, Carbon $finish_at)
    {
        $query->whereBetween('start_at', [$start_at, $finish_at]);
        return $query;
    }
}
