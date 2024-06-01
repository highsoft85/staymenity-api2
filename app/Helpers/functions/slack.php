<?php

declare(strict_types=1);

if (!function_exists('slackInfo')) {
    /**
     * @param mixed $data
     * @param string|null $title
     */
    function slackInfo($data, ?string $title = null)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        if (!is_null($title)) {
            $data = $title . '::' . $data;
        }
        try {
            (new \App\Services\Notification\Slack\SlackDebugNotification())->send($data);
        } catch (\Exception $e) {
            //
        }
    }
}
