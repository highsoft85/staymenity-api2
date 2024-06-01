<?php

declare(strict_types=1);

namespace App\Services\Notification\Firebase;

use App\Models\User;
use Kreait\Laravel\Firebase\Facades\Firebase;

/**
 * Class FirebaseCountNotifications
 * @package App\Services\Notification\Firebase
 *
 * @deprecated
 */
class FirebaseCountNotifications
{
    /**
     * @var \Kreait\Firebase\Database
     */
    private $database;

    /**
     * @var User
     */
    private $oUser;

    /**
     * FirebaseNotification constructor.
     */
    public function __construct()
    {
        $this->database = Firebase::database();
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
     * @param int $id
     * @return string
     */
    private function channel(int $id)
    {
        return 'data/counter/user/' . $id . '/notifications';
    }

    /**
     * @param int $count
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function set(int $count)
    {
        $this->database
            ->getReference($this->channel($this->oUser->id))
            ->set($count);
    }

    /**
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function clear()
    {
        $this->set(0);
    }
}
