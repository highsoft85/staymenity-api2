<?php

declare(strict_types=1);

namespace App\Services\Database;

use Illuminate\Support\Facades\Redis;

class RedisRateService
{
    /**
     *
     */
    const KEY = 'RATE_LIMIT';

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    private $redis;

    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $limit;

    /**
     * RedisRateService constructor.
     * @param string $name
     * @param int $limit
     * @param int $saveLimit
     */
    public function __construct(string $name, int $limit, int $saveLimit = 100)
    {
        $this->redis = Redis::connection();
        $this->key = self::KEY . ':' . $name . ':' . now()->format('Y-m-d-H');
        $this->limit = $limit - $saveLimit;
    }

    /**
     * @return int
     */
    public function get()
    {
        $value = $this->redis->get($this->key);
        return !is_null($value)
            ? (int)$value
            : 0;
    }

    /**
     *
     */
    public function increment()
    {
        $value = $this->get();
        $value = $value + 1;
        if ($value >= $this->limit) {
            slackInfo('Rate Limit for Request. Actual: ' . $value . '. Limit: ' . $this->limit . '.');
        }
        $this->set($value);
    }

    /**
     * @param int $value
     */
    private function set(int $value)
    {
        $this->redis->set($this->key, $value);
    }
}
