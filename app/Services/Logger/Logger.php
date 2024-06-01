<?php

declare(strict_types=1);

namespace App\Services\Logger;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    /**
     * @var Carbon|null
     */
    private $now = null;

    /**
     * @var MonologLogger|null
     */
    private $log = null;

    /**
     * @var string|null
     */
    private $file = null;

    /**
     * @var string|null
     */
    private $fileName = null;

    /**
     * @var string|null
     */
    private $name = null;

    /**
     * log - обычный
     * slack - слак канал
     *
     * @var string
     */
    private $type = 'log';

    /**
     *
     * withName - отправлять в slack с Name: $this->name \n
     * @var array
     */
    private $options = [
        'slack' => [
            'withName' => false,
        ],
    ];

    /**
     * Logger constructor.
     */
    public function __construct()
    {
        $this->now = Carbon::now();

        $this->fileName = $this->now->format('Y-m-d') . '.log';
    }

    /**
     * @param string $name
     * @return Logger
     */
    public function setName($name): Logger
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function log()
    {
        $this->name = !is_null($this->name) ? $this->name : '';

        $this->file = $this->fileName;

        $this->log = new MonologLogger('logger');

        try {
            $this->log->pushHandler(new StreamHandler($this->path(), MonologLogger::DEBUG));
        } catch (\Exception $e) {
            abort($e->getMessage());
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function slack()
    {
        $this->type = 'slack';

        return $this;
    }

    /**
     *
     * @param string $message
     * @param array $array
     * @return mixed
     */
    public function info($message, array $array = [])
    {
        return $this->send(__FUNCTION__, $message, $array);
    }

    /**
     *
     * @param string $message
     * @param array $array
     * @return mixed
     */
    public function error($message, array $array = [])
    {
        return $this->send(__FUNCTION__, $message, $array);
    }

    /**
     *
     * @param string $message
     * @param array $array
     * @return mixed
     */
    public function warning($message, array $array = [])
    {
        return $this->send(__FUNCTION__, $message, $array);
    }

    /**
     * @param string $type
     * @param string $message
     * @param array $array
     * @return mixed
     */
    private function send($type, $message, array $array = [])
    {
        if ($this->type === 'slack') {
            $s = $message;
            if (!empty($array)) {
                $s = $s . "\n" . print_r($array, true);
            }
            $this->sendSlack($type, $s);
        }
        if (!empty($array)) {
            return $this->log->{$type}($message, $array);
        } else {
            return $this->log->{$type}($message);
        }
    }

    /**
     * @param string $type
     * @param string $message
     */
    private function sendSlack($type, $message)
    {
        if ($this->options['slack']['withName']) {
            $message = "Name: " . $this->name . "\n" . $message;
        }
        switch ($type) {
            case 'info':
                Log::critical($message);
                break;
            case 'error':
                Log::critical($message);
                break;
            case 'warning':
                Log::critical($message);
                break;
            default:
                break;
        }
    }

    /**
     * @return mixed
     */
    public function start()
    {
        $name = !is_null($this->name) ? ' ' . $this->name . ' ' : $this->name;
        return $this->send('info', '-----------------------------' . $name . '-----------------------------');
    }

    /**
     * @return mixed
     */
    public function finish()
    {
        $name = !is_null($this->name) ? ' ' . $this->name . ' ' : $this->name;
        return $this->send('info', '-----------------------------' . $name . '-----------------------------');
    }

    /**
     * @return string
     */
    private function path()
    {
        return !is_null($this->name)
            ? storage_path('logs/' . $this->name . '/' . $this->file)
            : storage_path('logs/' . $this->file);
    }
}
