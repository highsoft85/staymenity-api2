<?php

declare(strict_types=1);

namespace App\Cmf\Project\Listing;

use App\Cmf\Core\Parameters\TableParameter;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ListingPagesTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function active(Request $request)
    {
        $this->query = $this->activeQuery($request);
        $page = self::PAGE_ACTIVE;

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . $page;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => $page,
        ];

        return $this->index($request);
    }

    /**
     * @return Listing|Builder
     */
    public function activeQuery(Request $request)
    {
        /** @var Listing|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->active()->orderBy('created_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function popular(Request $request)
    {
        $this->query = $this->popularQuery($request);
        $page =  self::PAGE_POPULAR;

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . $page;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => $page,
        ];

        return $this->index($request);
    }

    /**
     * @return Listing|Builder
     */
    public function popularQuery(Request $request)
    {
        /** @var Listing|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query
            ->where('run_rating', '>=', 1)
            ->orderBy('run_rating', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function booked(Request $request)
    {
        $this->query = $this->bookedQuery($request);
        $page = self::PAGE_BOOKED;

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . $page;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => $page,
        ];

        return $this->index($request);
    }

    /**
     * @return Listing|Builder
     */
    public function bookedQuery(Request $request)
    {
        /** @var Listing|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->whereHas('reservations', function (\Illuminate\Database\Eloquent\Builder $q) {
            $q->beginning();
        })->orderBy('created_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleted(Request $request)
    {
        $this->query = $this->deletedQuery($request);
        $page = self::PAGE_DELETED;

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . $page;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => $page,
        ];

        return $this->index($request);
    }

    /**
     * @return Listing|Builder
     */
    public function deletedQuery(Request $request)
    {
        /** @var Listing|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->onlyTrashed()->orderBy('created_at', 'desc');
        return $query;
    }
}
