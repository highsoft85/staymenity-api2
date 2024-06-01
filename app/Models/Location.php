<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use App\Services\Modelable\Typeable;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 *
 * @property int $id
 * @property int $status
 * @property float $latitude
 * @property float $longitude
 * @property int $locationable_id
 * @property string $locationable_type
 * @property int $zoom
 * @property string $place_id
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $placeholder
 *
 * @property string|null $text
 * @property string|null $address
 * @property string|null $country
 * @property string|null $locality
 * @property string|null $province
 * @property string|null $province_code
 * @property string|null $country_code
 * @property Point|null $point
 * @property string|null $zip
 *
 *
 * * * ACCESSORS (только camelCase, чтобы удобнее различать)
 * @property array $pointArray
 *
 *
 * * * METHODS
 * @method static inDistance(array $point, int $max_distance_in_miles = 50)
 * @see \App\Models\Location::scopeInDistance()
 *
 * @method static inContains(array $point1, array $point2)
 * @see \App\Models\Location::scopeInContains()
 *
 */
class Location extends Model
{
    use Statusable;
    use Typeable;
    use SpatialTrait;

    /**
     *
     */
    const SRID = 4326;
    const GEOMETRY_COLUMN = 'point';

    /**
     * В милях
     */
    const DEFAULT_DISTANCE = 50;

    /**
     * @var string
     */
    protected $table = 'locations';

    /**
     * @var array
     */
    protected $fillable = [
        'type', 'country_id', 'locationable_id', 'locationable_type',
        'point', 'area', 'latitude', 'longitude',
        'zoom', 'place_id', 'options', 'status',
        'title', 'text', 'address',  'locality',
        'province', 'province_code',
        'country', 'country_code',
        'zip'
    ];

    /**
     * @var string[]
     */
    protected $spatialFields = [
        'point',
        'area'
    ];

    /**
     *
     */
    const STATUS_NOT_ACTIVE = 0;

    /**
     *
     */
    const STATUS_ACTIVE = 1;


    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statuses = [
        self::STATUS_NOT_ACTIVE => 'Not Active',
        self::STATUS_ACTIVE => 'Active',
    ];

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $statusIcons = [
        self::STATUS_NOT_ACTIVE => [
            'class' => 'badge badge-default',
        ],
        self::STATUS_ACTIVE => [
            'class' => 'badge badge-success',
        ],
    ];

    const TYPE_DEFAULT = 'default';

    /**
     * The status attributes for model
     *
     * @var array
     */
    protected $types = [
        self::TYPE_DEFAULT => 'Default',
    ];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('created_at');
    }

    /**
     * @param array $point
     * @throws \Exception
     */
    public function setPointAttribute(array $point)
    {
        if (!isset($point[0]) || !isset($point[1])) {
            throw new \Exception('Array point is not correct, must be array of two coordinates');
        }
        $this->attributes['point'] = new Point($point[0], $point[1], self::SRID);
        $this->attributes['latitude'] = $point[0];
        $this->attributes['longitude'] = $point[1];
    }

    /**
     * @return array
     */
    public function getPointArrayAttribute(): array
    {
        return [$this->point->getLat(), $this->point->getLng()];
    }

    /**
     * (new Location())->inDistance(coordinatesNewYork(), 2000)->count()
     *
     * @param Builder $query
     * @param array $point
     * @param int $max_distance
     * @return mixed
     */
    public function scopeInDistance(Builder $query, array $point, int $max_distance = self::DEFAULT_DISTANCE)
    {
        $meters = milesToMeters($max_distance);
        // чтобы было поле distance в метрах
        $this->scopeDistanceValue($query, self::GEOMETRY_COLUMN, new Point($point[0], $point[1], self::SRID));

        // если это настоящая сущность, а не (new Location())
        if (!is_null($this->point)) {
            return $this->scopeDistanceExcludingSelf($query, self::GEOMETRY_COLUMN, new Point($point[0], $point[1], self::SRID), $meters);
        }
        return $this->scopeDistance($query, self::GEOMETRY_COLUMN, new Point($point[0], $point[1], self::SRID), $meters);
    }

    /**
     * (new Location())->inDistance(coordinatesNewYork(), 2000)->count()
     *
     * @param Builder $query
     * @param array $point1
     * @param array $point2
     * @return mixed
     */
    public function scopeInContains(Builder $query, array $point1, array $point2)
    {
        $polygon = new Polygon([new LineString([
            new Point($point1[0], $point1[1]),//40.8002962 -74.05025482
            new Point($point1[0], $point2[1]), // 40.59414233 -73.90605927
            new Point($point2[0], $point2[1]),
            new Point($point2[0], $point1[1]),
            new Point($point1[0], $point1[1]),
        ])], Location::SRID);
        return $this->scopeWithin($query, self::GEOMETRY_COLUMN, $polygon);
    }
}
