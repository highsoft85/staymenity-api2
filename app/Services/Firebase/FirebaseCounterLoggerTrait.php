<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use App\Services\Logger\Logger;

trait FirebaseCounterLoggerTrait
{
    /**
     * @param string $method
     * @param array $data
     */
    private function log(string $method, array $data = [])
    {
        /** @var Logger $logger */
        $logger = $this->logger;
        $logger->info($method, $data);
    }
}
