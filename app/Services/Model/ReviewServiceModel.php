<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\User;

class ReviewServiceModel
{
    const TYPE_TO_GUEST = 'to_guest';
    const TYPE_TO_HOST = 'to_host';

    /**
     * @param User $oUser
     * @param string $type
     * @return string
     */
    public function getMessageByType(User $oUser, string $type)
    {
        $message = '';
        switch ($type) {
            case self::TYPE_TO_GUEST:
                $message = 'Tell us how was your experience at %s’s space';
                break;
            case self::TYPE_TO_HOST:
                $message = 'How do you find %s as guest?';
                break;
            default:
                break;
        }
        $message = sprintf($message, $oUser->first_name);
        return $message;
    }
}
