<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Search;

use App\Http\Requests\Api\Search\IndexRequest;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Models\Location;
use App\Services\Environment;
use App\Services\Geocoder\GeocoderIpService;
use App\Services\Search\SearchService;
use Carbon\Carbon;
use Dingo\Api\Routing\Helpers;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Index
{
    /**
     * @param IndexRequest $request
     * @return array
     */
    public function __invoke(IndexRequest $request)
    {
        $data = $request->all();
        $limit = $request->get('limit') ?? 10;

        $oQuery = (new SearchService())->search($data, $request);

        /** @var LengthAwarePaginator $oResult */
        $oResult = $oQuery->orderedBySearch()->paginate($limit);
        $aItems = $oResult->values()->transform(function (Listing $item) {
            return (new ListingTransformer())->transformCard($item);
        })->toArray();
        //visitGAnalytics()->search($request)->run();
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }
}
