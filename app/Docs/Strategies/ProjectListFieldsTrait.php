<?php

declare(strict_types=1);

namespace App\Docs\Strategies;

use App\Http\Transformers\Api\AmenityTransformer;
use App\Http\Transformers\Api\LocationTransformer;
use App\Http\Transformers\Api\RuleTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\Rule;
use App\Models\User;
use App\Models\UserSetting;

trait ProjectListFieldsTrait
{
    /**
     * @return string
     */
    protected function amenitiesFields()
    {
        $example = (new AmenityTransformer())->transform(Amenity::first());
        $data = [
            'id' => ['int', 'ID услуги'],
            'name' => ['string', 'Ключ'],
            'title' => ['string', 'Название'],
            'icon' => ['string|null', 'Ссылка на иконку, если `null`, то ничего не выводить'],
            'icon_png_light' => ['string|null', 'Ссылка на иконку в png для светлой темы, если `null`, то ничего не выводить'],
            'icon_png_dark' => ['string|null', 'Ссылка на иконку в png для темной темы, если `null`, то ничего не выводить'],
        ];
        return $this->listFields($this->withCheckKeys($data, $example, __FUNCTION__), 'Услуги');
    }

    /**
     * @return string
     */
    protected function rulesFields()
    {
        $example = (new RuleTransformer())->transform(Rule::first());
        $data = [
            'id' => ['int', 'ID правила'],
            'name' => ['string', 'Ключ'],
            'title' => ['string', 'Название'],
            'icon' => ['string|null', 'Ссылка на иконку, если `null`, то ничего не выводить'],
            'icon_png_light' => ['string|null', 'Ссылка на иконку в png для светлой темы, если `null`, то ничего не выводить'],
            'icon_png_dark' => ['string|null', 'Ссылка на иконку в png для темной темы, если `null`, то ничего не выводить'],
        ];
        return $this->listFields($this->withCheckKeys($data, $example, __FUNCTION__), 'Правила');
    }

    /**
     * @return string
     */
    protected function ratingFields()
    {
        return $this->listFields([
            'value' => ['int|float', 'Среднее значение'],
            'value_formatted' => ['string', 'Среднее значение в формате 0.00'],
            'count' => ['int', 'Количество проголосовавших'],
        ], 'Рейтинг');
    }

    /**
     * @return string
     */
    protected function accountFields()
    {
        return $this->listFields([
            'name' => ['string', 'Ключ провайдера'],
            'title' => ['string', 'Заголовок провайдера'],
            'connected' => ['bool', 'Индикатор подключения'],
            'can_disconnect' => ['bool', 'Может ли пользователь отключить сеть, например если юзер зарегался через соц сеть, но не создал потом пароль и не сохранил телефон, т.е. других вариантов входа нет'],
        ], 'Социальные сети');
    }

    /**
     * @param string $description
     * @return string
     */
    protected function imagesFields(string $description = '')
    {
        return $this->listFields([
            'id' => ['int', 'ID изображения'],
            'src' => ['string', 'Url изображения, выводится сразу с хостом'],
            'is_main' => ['bool', 'Является ли изображение главным'],
        ], $description);
    }

    /**
     * @return array
     */
    protected function imagesField()
    {
        $description = 'Объект с `is_main=true` всегда стоит на первом месте.';
        return [
            'type' => 'array of objects',
            'description' => $this->imagesFields($description),
        ];
    }

    /**
     * @return array
     */
    protected function imagesXsField()
    {
        $description = 'Объект с `is_main=true` всегда стоит на первом месте.';
        return [
            'type' => 'array of objects',
            'description' => $this->imagesFields($description),
        ];
    }

    /**
     * @return string
     */
    protected function locationFields()
    {
        /** @var Listing $oListing */
        $oListing = Listing::first();
        $data = [
            'id' => ['int', 'ID локации'],
            'place_id' => ['string', 'Гугла place_id'],
            'primary_point' => ['array of floats', 'Точные координаты объекта, Первый элемент `latitude`, второй - `longitude`'],
            'point' => ['array of floats', 'Первый элемент `latitude`, второй - `longitude`'],
            'circle' => ['object', '`radius|int` - радиус в метрах, 8047 - 5 миль, `center|array of floats`- где первый элемент `latitude`, второй - `longitude`'],
            'zoom' => ['int', 'Зум для карты'],
            'title' => ['string|null', 'Название города, например Los Angeles'],
            'text' => ['string|null', 'Полный текст, например United States of America, California, San Diego County, San Diego'],
            'locality' => ['string|null', 'Город, например New York'],
            'province' => ['string|null', 'Область или штат, например New York'],
            'country' => ['string|null', 'Название страны, например United States'],
            'country_code' => ['string|null', 'Код страны, например US'],
            'state' => ['string|null', 'Штат, например California'],
            'state_code' => ['string|null', 'Короткое название штата, например CA'],
            'zip' => ['string|null', 'Индекс места'],
        ];
        if (!is_null($oListing->location)) {
            $example = (new LocationTransformer())->transform($oListing->location);
            return $this->listFields($this->withCheckKeys($data, $example, __FUNCTION__), 'Локация');
        } else {
            return $this->listFields($data, 'Локация');
        }
    }

    /**
     * @return string
     */
    protected function locationUserFields()
    {
        /** @var User $oUser */
        $oUser = User::first();
        $data = [
            'id' => ['int', 'ID локации'],
            'place_id' => ['string', 'Гугла place_id'],
            'point' => ['array of floats', 'Первый элемент `latitude`, второй - `longitude`'],
            'zoom' => ['int', 'Зум для карты'],
            'title' => ['string|null', 'Название города, например Los Angeles'],
            'text' => ['string|null', 'Полный текст, например United States of America, California, San Diego County, San Diego'],
            'locality' => ['string|null', 'Город, например New York'],
            'province' => ['string|null', 'Область или штат, например New York'],
            'country' => ['string|null', 'Название страны, например United States'],
            'country_code' => ['string|null', 'Код страны, например US'],
            'state' => ['string|null', 'Штат, например California'],
            'state_code' => ['string|null', 'Короткое название штата, например CA'],
            'zip' => ['string|null', 'Индекс места'],
        ];
        if (!is_null($oUser->location)) {
            $example = (new LocationTransformer())->transform($oUser->location);
            return $this->listFields($this->withCheckKeys($data, $example, __FUNCTION__), 'Локация');
        } else {
            return $this->listFields($data, 'Локация');
        }
    }

    /**
     * @return string
     */
    protected function hostFields()
    {
        /** @var User $oUser */
        $oUser = User::first();
        $example = (new UserTransformer())->transformHost($oUser);
        // чтобы rating был еще на уровень выше
        $this->positionUp();
        $data = [
            'id' => ['int', 'ID'],
            'first_name' => ['string|null', 'Имя хозяина'],
            'last_name' => ['string|null', 'Фамилия хозяина'],
            'description' => ['string|null', 'Описание хоста'],
            'image' => ['string', 'Изображение/Аватар'],
            'phone' => ['string|null', 'Телефон'],
            'email' => ['string|null', 'Электронная почта'],
            'rating' => ['object', $this->ratingFields()],
            'reviews_length' => [$this->fieldUserReviewsLength()['type'], $this->fieldUserReviewsLength()['description']],
            'is_identity_verified' => [$this->fieldUserIsIdentityVerified()['type'], $this->fieldUserIsIdentityVerified()['description']],
        ];
        return $this->listFields($this->withCheckKeys($data, $example, __FUNCTION__), 'Владелец листинга');
    }

    /**
     * @param string $description
     * @return mixed
     */
    protected function reviewsFields(string $description)
    {
        $this->positionUp();

        $data = [
            'id' => ['int', 'ID отзыва'],
            'user' => ['object', $this->listFields([
                'id' => ['int', 'ID пользователя'],
                'first_name' => ['string', 'Имя'],
                'last_name' => ['string', 'Фамилия'],
                'image' => ['string', 'Изображение/Аватар'],
            ])],
            'description' => ['string', 'Текст'],
            'published_at_formatted' => ['string', 'Форматированная дата, можно так и вставлять'],
        ];
        return $this->listFields($data, $description);
    }

    /**
     * @param string $description
     * @return mixed
     */
    protected function settingsFields(string $description)
    {
        $this->positionUp();

        $data = [
            'notifications' => ['object', $this->listFields([
                UserSetting::NOTIFICATION_MAIL => ['int', 'Mail'],
                UserSetting::NOTIFICATION_PUSH => ['int', 'Push notifications'],
                UserSetting::NOTIFICATION_MESSAGES => ['int', 'Text messages'],
            ])],
        ];
        return $this->listFields($data, $description);
    }

    /**
     * @param string $description
     * @return mixed
     */
    protected function savesFields(string $description)
    {
        $data = [
            'id' => ['int', 'ID'],
            'title' => ['string', 'Заголовок'],
            'image' => ['string', 'Изображение списка, берется по имющимся листингам'],
            'count' => ['int', 'Количество листингов в списке'],
        ];
        return $this->listFields($data, $description);
    }

    /**
     * @param string $description
     * @return mixed
     */
    protected function listingStatusField(string $description)
    {
        $data = [
            'name' => ['string', 'Ключ статуса'],
            'title' => ['string', 'Заголовок статуса'],
        ];
        return $this->listFields($data, $description);
    }

    /**
     * @param string $description
     * @return mixed
     */
    protected function timesFields(string $description)
    {
        $data = [
            'from' => ['string', 'Время в формате 04:10 PM'],
            'to' => ['string', 'Время в формате 04:10 PM'],
        ];
        return $this->listFields($data, $description);
    }
}
