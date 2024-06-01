<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payment;

use App\Cmf\Core\Parameters\TableParameter;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait PaymentPagesTrait
{
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
     * @return Payment|Builder
     */
    public function activeQuery(Request $request)
    {
        /** @var Payment|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->activeNotCancelled()->orderBy('created_at', 'desc');
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
     * @return Payment|Builder
     */
    public function cancelledQuery(Request $request)
    {
        /** @var Payment|\Illuminate\Database\Eloquent\Builder $query */
        $query = $this->class::with($this->with);
        $query = $query->cancelled()->orderBy('created_at', 'desc');
        return $query;
    }
}
