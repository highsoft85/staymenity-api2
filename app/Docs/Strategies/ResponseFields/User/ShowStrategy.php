<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User;

use App\Http\Transformers\Api\UserTransformer;
use App\Models\User;
use App\Docs\Strategy;

class ShowStrategy extends Strategy
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
        return $this->route_user_show;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return $this->withCheckKeys([
            'id' => $this->fieldUserId(),
            'first_name' => [
                'type' => 'string|null',
                'description' => null,
            ],
            'last_name' => [
                'type' => 'string|null',
                'description' => null,
            ],
//            'phone' => [
//                'type' => 'string|null',
//                'description' => null,
//            ],
//            'email' => [
//                'type' => 'string|null',
//                'description' =>
//                    'Может быть `null`, например когда авторизация была через соц.сеть и соц.сеть не дала `email`, ' .
//                    'либо у него там не было `email`. В таком случае показывать форму для ввода `email` и ' .
//                    'желательно не пускать дальше.'
//                ,
//            ],
            'image' => [
                'type' => 'string',
                'description' => 'Если нет изображения у пользователя, то будет выведена ссылка на заглушку',
            ],
//            'birthday_at' => [
//                'type' => 'null|string',
//                'description' => 'Дата рождения в формате m/d/Y',
//            ],
            'description' => [
                'type' => 'null|string',
                'description' => 'Описание пользователя',
            ],
            'location' => [
                'type' => 'object',
                'description' => $this->locationUserFields(),
            ],
            'gender' => [
                'type' => 'null|int',
                'description' => 'Пол, если не указан, то null',
            ],
            'listings' => $this->fieldUserListingsCard(),
            'rating' => [
                'type' => 'object',
                'description' => $this->ratingFields(),
            ],
            'reviews' => [
                'type' => 'array of objects',
                'description' => $this->reviewsFields('Пока выводятся все отзывы'),
            ],
            'reviews_length' => $this->fieldUserReviewsLength(),
            'registered_at' => $this->fieldUserRegisteredAt(),
            'registered_at_formatted' => $this->fieldUserRegisteredAtFormatted(),
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
        return (new UserTransformer())->transformDetail(User::first());
    }
}
