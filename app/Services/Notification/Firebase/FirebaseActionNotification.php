<?php

declare(strict_types=1);

namespace App\Services\Notification\Firebase;

use App\Http\Transformers\Api\FirebaseNotificationTransformer;
use App\Http\Transformers\Api\NotificationTransformer;
use App\Models\FirebaseNotification;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

/**
 * Class FirebaseActionNotification
 * @package App\Services\Notification\Firebase
 *
 * @deprecated
 */
class FirebaseActionNotification
{

    const TYPE_MESSAGES = 'messages';
    const TYPE_NOTIFICATIONS = 'notifications';

    /**
     * @var \Kreait\Firebase\Database
     */
    private $database;

    /**
     * @var User
     */
    private $oUser;

    /**
     * @var string
     */
    private $type;

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
     * @param string $type
     * @return $this
     */
    private function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return $this
     */
    public function messages()
    {
        $this->type = self::TYPE_MESSAGES;

        return $this;
    }

    /**
     * @return $this
     */
    public function notifications()
    {
        $this->type = self::TYPE_NOTIFICATIONS;

        return $this;
    }

    /**
     * @param string $id
     * @return string
     */
    private function getUserPath(string $id)
    {
        return 'data/' . $this->type . '/user/' . $this->oUser->id . '/' . $id;
    }

    /**
     * @param FirebaseNotification $oItem
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function send(FirebaseNotification $oItem)
    {
        $data = $this->transform($oItem);
        $this->database
            ->getReference($this->getUserPath($data['id']))
            ->set($data);
    }

    /**
     * @param FirebaseNotification $oItem
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function delete(FirebaseNotification $oItem)
    {
        $data = $this->transform($oItem);
        $this->database
            ->getReference($this->getUserPath($data['id']))
            ->remove();
    }

    /**
     * @param FirebaseNotification $oItem
     * @return array
     */
    private function transform(FirebaseNotification $oItem)
    {
        return (new FirebaseNotificationTransformer())->transform($oItem);
    }
}
