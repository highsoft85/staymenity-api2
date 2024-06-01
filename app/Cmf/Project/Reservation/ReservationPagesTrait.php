<?php

declare(strict_types=1);

namespace App\Cmf\Project\Reservation;

use App\Cmf\Core\Parameters\TableParameter;
use App\Models\Reservation;
use Illuminate\Http\Request;

trait ReservationPagesTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function future(Request $request)
    {
        $this->query = $this->futureQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_FUTURE;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_FUTURE,
        ];

        return $this->index($request);
    }

    /**
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    public function futureQuery(Request $request)
    {
        /** @var Reservation|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->futureNotBeginning()->active()->orderBy('start_at', 'asc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function process(Request $request)
    {
        $this->query = $this->processQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_PROCESS;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_PROCESS,
        ];

        return $this->index($request);
    }

    /**
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    public function processQuery(Request $request)
    {
        /** @var Reservation|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->beginning()->active()->orderBy('start_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function passed(Request $request)
    {
        $this->query = $this->passedQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_PASSED;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_PASSED,
        ];

        return $this->index($request);
    }

    /**
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    public function passedQuery(Request $request)
    {
        /** @var Reservation|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->passed()->active()->orderBy('start_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelled(Request $request)
    {
        $this->query = $this->cancelledQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_CANCELLED;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_CANCELLED,
        ];

        return $this->index($request);
    }

    /**
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    public function cancelledQuery(Request $request)
    {
        /** @var Reservation|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->cancelledOrDeclined()->orderBy('start_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function active(Request $request)
    {
        $this->query = $this->activeQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_ACTIVE;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_ACTIVE,
        ];

        return $this->index($request);
    }

    /**
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    public function activeQuery(Request $request)
    {
        /** @var Reservation|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->active()->orderBy('start_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function hostfully(Request $request)
    {
        $this->query = $this->hostfullyQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_HOSTFULLY;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_HOSTFULLY,
        ];

        return $this->index($request);
    }

    /**
     * @param Request $request
     * @return Reservation|\Illuminate\Database\Eloquent\Builder
     */
    public function hostfullyQuery(Request $request)
    {
        /** @var Reservation|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->where('source', Reservation::SOURCE_HOSTFULLY)->orderBy('start_at', 'asc');
        return $query;
    }
}
