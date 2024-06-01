<?php

declare(strict_types=1);

namespace App\Services\HealthCheck;

use App\Services\Hostfully\Agencies\Index;
use App\Services\Hostfully\BaseHostfullyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class Hostfully
{
    /**
     *
     */
    const CACHE_NAME = 'health-check:hostfully';

    /**
     * @return false
     */
    public function isActive()
    {
        $value = Cache::get(self::CACHE_NAME);
        if (is_null($value)) {
            return false;
        }
        if (!isset($value['status'])) {
            return false;
        }
        return $value['status'] === true;
    }

    /**
     * @return Carbon|null
     */
    private function getTime()
    {
        $value = Cache::get(self::CACHE_NAME);
        if (is_null($value)) {
            return null;
        }
        if (!isset($value['time'])) {
            return null;
        }
        return Carbon::parse($value['time']);
    }

    /**
     * @param bool $status
     */
    private function set(bool $status)
    {
        Cache::put(self::CACHE_NAME, [
            'status' => $status,
            'time' => now()->format('Y-m-d H:i:s'),
        ], 3600);
    }

    /**
     *
     */
    public function check()
    {
        $status = $this->isActive();
        try {
            $data = (new Index())->__invoke();
            if (!$status) {
                slackInfo([], 'HOSTFULLY IS ENABLED');
            }
            $this->set(true);
        } catch (\Exception $e) {
            if ($status) {
                slackInfo([], 'HOSTFULLY IS DISABLED');
            }
            $this->set(false);
        }
    }
}
