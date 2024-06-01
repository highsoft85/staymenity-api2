<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use App\Models\User;
use App\Services\Logger\Logger;
use App\Services\Notification\Firebase\FirebaseCountNotifications;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\DatabaseException;

abstract class FirebaseCounterService
{
    use FirebaseCounterLoggerTrait;

    /**
     * @var \Kreait\Firebase\Database
     */
    private $database;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var User
     */
    private $oUser;

    /**
     * FirebaseNotification constructor.
     */
    public function __construct()
    {
       //
    }

    /**
     * @param int $id
     * @return string
     */
    abstract protected function channel(int $id): string;

    /**
     * @return string
     */
    abstract protected function channelCounter(): string;

    /**
     * @param int $id
     * @return string
     */
    abstract protected function channelUserCounter(int $id): string;

    /**
     * @return $this
     */
    public function database()
    {
        $this->database = Firebase::database();
        $this->logger = (new Logger())->setName('firebase')->log();
        return $this;
    }

    /**
     * @param User $oUser
     * @return $this
     */
    public function setUser(User $oUser)
    {
        $this->oUser = $oUser;

        return $this;
    }

    /**
     * @param int $count
     */
    public function set(int $count)
    {
        try {
            $this->database
                ->getReference($this->channel($this->oUser->id))
                ->set($count);
            $this->log(__FUNCTION__, ['user_id' => $this->oUser->id, 'value' => $count]);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     *
     */
    public function clear()
    {
        $this->set(0);
    }

    /**
     *
     */
    public function increment()
    {
        try {
            $value = $this->database
                ->getReference($this->channel($this->oUser->id))
                ->getValue();
            $value = (int)$value + 1;
            $this->set($value);
            $this->log(__FUNCTION__, ['user_id' => $this->oUser->id, 'value' => $value]);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     * @param string|null $value
     */
    public function value(?string $value)
    {
        try {
            $this->database
                ->getReference($this->channel($this->oUser->id))
                ->set($value);
            $this->log(__FUNCTION__, ['user_id' => $this->oUser->id, 'value' => $value]);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     *
     */
    public function decrement()
    {
        try {
            $value = $this->database
                ->getReference($this->channel($this->oUser->id))
                ->getValue();

            $intValue = (int)$value;
            if ($intValue !== 0) {
                $intValue = (int)$value - 1;
            }
            $this->set($intValue);
            $this->log(__FUNCTION__, ['user_id' => $this->oUser->id, 'value' => $intValue]);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     * @param int $count
     */
    public function decrementCount(int $count)
    {
        try {
            $value = $this->database
                ->getReference($this->channel($this->oUser->id))
                ->getValue();

            $intValue = (int)$value - $count;
            if ($intValue < 0) {
                $intValue = 0;
            }
            $this->set($intValue);
            $this->log(__FUNCTION__, ['user_id' => $this->oUser->id, 'value' => $intValue]);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel($this->oUser->id);
    }

    /**
     *
     */
    public function clearChannel()
    {
        try {
            $this->database
                ->getReference($this->channel($this->oUser->id))
                ->remove();
            $this->log(__FUNCTION__);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     *
     */
    public function clearCounter()
    {
        try {
            $this->database
                ->getReference($this->channelCounter())
                ->remove();
            $this->log(__FUNCTION__);
        } catch (DatabaseException $e) {
            //
        }
    }

    /**
     *
     */
    public function clearUserCounter()
    {
        try {
            $this->database
                ->getReference($this->channelUserCounter($this->oUser->id))
                ->remove();
            $this->log(__FUNCTION__, ['user_id' => $this->oUser->id]);
        } catch (DatabaseException $e) {
            //
        }
    }
}
