<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

use App\Cmf\Core\Parameters\TableParameter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait UserPagesTrait
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function hosts(Request $request)
    {
        $this->query = $this->hostsQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_HOSTS;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_HOSTS,
        ];

        return $this->index($request);
    }

    /**
     * @return User|Builder
     */
    public function hostsQuery(Request $request)
    {
        /** @var User|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $q) {
            $q->where('name', User::ROLE_HOST);
        })
            ->where('source', User::SOURCE_APP)
            ->orderBy('created_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function guests(Request $request)
    {
        $this->query = $this->guestsQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_GUESTS;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_GUESTS,
        ];

        return $this->index($request);
    }

    /**
     * @return User|Builder
     */
    public function guestsQuery(Request $request)
    {
        /** @var User|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $q) {
            $q->where('name', User::ROLE_GUEST);
        })
            ->where('source', User::SOURCE_APP)
            ->orderBy('created_at', 'desc');
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleted(Request $request)
    {
        $this->query = $this->deletedQuery($request);

        $this->indexComponent[TableParameter::INDEX_EXPORT] = false;
        $this->indexComponent[TableParameter::INDEX_BREADCRUMBS] = self::NAME . '.' . self::PAGE_DELETED;
        $this->indexComponent[TableParameter::INDEX_SEARCH_FIELDS] = [
            'to' => self::PAGE_DELETED,
        ];

        return $this->index($request);
    }

    /**
     * @return User|Builder
     */
    public function deletedQuery(Request $request)
    {
        /** @var User|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->onlyTrashed()->orderBy('created_at', 'desc');
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
     * @return User|Builder
     */
    public function hostfullyQuery(Request $request)
    {
        /** @var User|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->where('source', User::SOURCE_HOSTFULLY)->orderBy('created_at', 'desc');
        return $query;
    }
}
