<?php

declare(strict_types=1);

namespace App\Services\Notification\Slack;

abstract class SlackCommonNotification
{
    /**
     * @return string|array|null
     */
    protected function env()
    {
        return $this->getConfig()['env'];
    }

    /**
     * @return string|array|null
     */
    protected function channel()
    {
        return $this->getConfig()['url'];
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        // чтобы larastan не ругался
        /** @var mixed $var */
        $var = $this;
        return $var->config();
    }
}
