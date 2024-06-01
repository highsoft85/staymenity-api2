<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields;

use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;

class UserStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
            'id' => [
                'type' => 'int',
                'description' => null,
            ],
            'current_role' => [
                'type' => 'string|null',
                'description' => 'Текущая роль, чтобы определять какой дашбоард показывать, полный список в About',
            ],
            'first_name' => [
                'type' => 'string|null',
                'description' => null,
            ],
            'last_name' => [
                'type' => 'string|null',
                'description' => null,
            ],
            'phone' => [
                'type' => 'string|null',
                'description' => null,
            ],
            'email' => [
                'type' => 'string|null',
                'description' =>
                    'Может быть `null`, например когда авторизация была через соц.сеть и соц.сеть не дала `email`, ' .
                    'либо у него там не было `email`. В таком случае показывать форму для ввода `email` и ' .
                    'желательно не пускать дальше.'
                ,
            ],
            'image' => [
                'type' => 'string',
                'description' => 'Если нет изображения у пользователя, то будет выведена ссылка на заглушку',
            ],
            'birthday_at' => [
                'type' => 'null|string',
                'description' => 'Дата рождения в формате m/d/Y',
            ],
            'description' => [
                'type' => 'null|string',
                'description' => 'Описание пользователя',
            ],
            'gender' => [
                'type' => 'null|int',
                'description' => 'Пол, если не указан, то null',
            ],
            'location' => [
                'type' => 'null|object',
                'description' => $this->locationFields(),
            ],
            'social_accounts' => [
                'type' => 'array of objects',
                'description' => $this->accountFields(),
            ],
            'rating' => [
                'type' => 'object',
                'description' => $this->ratingFields(),
            ],
            'reviews' => [
                'type' => 'array of objects',
                'description' => $this->reviewsFields('Пока выводятся все отзывы'),
            ],
            'integrations' => [
                'type' => 'object',
                'description' => 'Данные интеграции',
            ],
            'reviews_length' => [
                'type' => 'int',
                'description' => 'Общее количество отзывов',
            ],
            'registered_at' => $this->fieldUserRegisteredAt(),
            'registered_at_formatted' => $this->fieldUserRegisteredAtFormatted(),
            'saves' => $this->fieldUserSaves(),
            'settings' => $this->fieldUserSettings(),
            'balance' => $this->fieldUserBalance(),
            'firebase' => $this->fieldUserFirebase(),
            'identity_verification_status' => $this->fieldIdentityVerificationStatus(),
            'listings_accessible' => $this->fieldUserListingsAccessible(),
            'has_payout_connect' => $this->fieldUserHasPayoutConnect(),
            'has_image' => $this->fieldUserHasImage(),
            'is_banned' => [
                'type' => 'bool',
                'description' => 'Если `true`, то разлогинить пользователя, необходимо для web, т.к. прямого запроса нет, там авторизация через пакет',
            ],
            'is_need_password' => $this->fieldUserIsNeedPassword(),
            'is_email_verified' => [
                'type' => 'bool',
                'description' => 'Если `false`, то отправлять на редактирование профиля с вводом email',
            ],
            'is_phone_verified' => [
                'type' => 'bool',
                'description' => 'Если `false`, то отправлять на номер код, см. `Регистрация по email`',
            ],
            'is_identity_verified' => $this->fieldUserIsIdentityVerified(),
        ]);
    }

    /**
     * @return array
     */
    protected function transformerKeys()
    {
        return (new UserTransformer())->transform(User::first());
    }
}
