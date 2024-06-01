<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Fields;

trait ListingFieldsTrait
{
    /**
     * @return array
     */
    public function fieldListingId()
    {
        return [
            'type' => 'int',
            'description' => 'ID листинга',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingSlug()
    {
        return [
            'type' => 'string',
            'description' => 'Сгенерированный url по {name}-{id}.',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingName()
    {
        return [
            'type' => 'string',
            'description' => null,
        ];
    }

    /**
     * @return array
     */
    public function fieldListingTitle()
    {
        return [
            'type' => 'string',
            'description' => null,
        ];
    }

    /**
     * @return array
     */
    public function fieldListingDescription()
    {
        return [
            'type' => 'string|null',
            'description' => null,
        ];
    }

    /**
     * @return array
     */
    public function fieldListingType()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'name' => ['string', 'Ключ'],
                'title' => ['string', 'Заголовок'],
            ], 'Если `name` = `other`, то в title будет кастомное название'),
        ];
    }

    /**
     * @return array
     */
    public function fieldListingImage()
    {
        return [
            'type' => 'string',
            'description' => 'Если не будет найдено изображение, то будет ссылка на заглушку.',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingGuestsSize()
    {
        return [
            'type' => 'int',
            'description' => 'Количество гостей.',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingAddress()
    {
        return [
            'type' => 'string|null',
            'description' => 'Форматированный адрес.',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingAddressTwo()
    {
        return [
            'type' => 'string|null',
            'description' => 'Адрес для как его вбил пользователь во втором поле',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingPrice()
    {
        return [
            'type' => 'int',
            'description' => 'Цена в $ в час.',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingReviewsLength()
    {
        return [
            'type' => 'int',
            'description' => 'Количество отзывов всего',
        ];
    }

    /**
     * @return array
     */
    public function fieldListingRentTimeMin()
    {
        return [
            'type' => 'int',
            'description' => 'Минимальное время бронирования в часах.',
        ];
    }

    /**
     * @return array
     */
    public function fieldsListingCard()
    {
        return [
            'id' => $this->fieldListingId(),
            'slug' => $this->fieldListingSlug(),
            'name' => $this->fieldListingName(),
            'title' => $this->fieldListingTitle(),
            'rating' => [
                'type' => 'object',
                'description' => $this->ratingFields(),
            ],
            'type' => $this->fieldListingType(),
            'image' => $this->fieldListingImage(),
            'images' => $this->imagesField(),
            'images_xs' => $this->imagesXsField(),
            'price' => $this->fieldListingPrice(),
            'guests_size' => $this->fieldListingGuestsSize(),
            'rent_time_min' => $this->fieldListingRentTimeMin(),
//            'distance' => [
//                'type' => 'string|null',
//                'description' => 'Расстояние до объекта.',
//            ],
            'address' => $this->fieldListingAddress(),
            'location' => [
                'type' => 'object',
                'description' => $this->locationFields(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function fieldsListingCardForHost()
    {
        $data = $this->fieldsListingCard();
        $data = array_merge($data, [
            'views' => [
                'type' => 'int',
                'description' => 'Количество просмотров',
            ],
            'status' => [
                'type' => 'object',
                'description' => $this->listingStatusField('Статус листинга'),
            ],
            'is_published' => [
                'type' => 'bool',
                'description' => 'Опубликован ли листинг',
            ],
        ]);
        return $data;
    }

    /**
     * @return array
     */
    public function fieldsListingDetail()
    {
        return [
            'id' => $this->fieldListingId(),
            'slug' => $this->fieldListingSlug(),
            'name' => $this->fieldListingName(),
            'title' => $this->fieldListingTitle(),
            'rating' => [
                'type' => 'object',
                'description' => $this->ratingFields(),
            ],
            'type' => $this->fieldListingType(),
            'image' => $this->fieldListingImage(),
            'images' => $this->imagesField(),
            'images_xs' => $this->imagesXsField(),
            'price' => $this->fieldListingPrice(),
            'deposit' => [
                'type' => 'int|null',
                'description' => 'Депозит',
            ],
            'cleaning_fee' => [
                'type' => 'int|null',
                'description' => 'Плата за уборку',
            ],
            'guests_size' => $this->fieldListingGuestsSize(),
            'rent_time_min' => $this->fieldListingRentTimeMin(),
//            'distance' => [
//                'type' => 'string|null',
//                'description' => 'Расстояние до объекта.',
//            ],
            'address' => $this->fieldListingAddress(),
            'description' => [
                'type' => 'string',
                'description' => 'Основной текст.',
            ],
            'amenities' => [
                'type' => 'array of objects',
                'description' => $this->amenitiesFields(),
            ],
            'amenities_description' => [
                'type' => 'string|null',
                'description' => 'Дополнительные услуги, если `!== null`, то выводить в блоке Extra amenities.',
            ],
            'rules' => [
                'type' => 'array of objects',
                'description' => $this->rulesFields(),
            ],
            'rules_description' => [
                'type' => 'string|null',
                'description' => 'Дополнительные правила, если `!== null`, то выводить в блоке Additional rules.',
            ],
            'cancellation' => [
                'type' => 'string|null',
                'description' => 'Условия отмены бронирования.',
            ],
            'location' => [
                'type' => 'object',
                'description' => $this->locationFields(),
            ],
            'host' => [
                'type' => 'object',
                'description' => $this->hostFields(),
            ],
            'times' => [
                'type' => 'array of objects',
                'description' => $this->timesFields('Ключи `weekdays` и `weekends` массивы объектов с данными вида `time[weekdays][0]`:'),
            ],
            'dates' => [
                'type' => 'array',
                'description' => 'Содержит только `locked`, который массив из дат в формате `Y-m-d`, для удобного заброса в датепикер для блокировки дат',
            ],
            'reviews' => [
                'type' => 'array of objects',
                'description' => $this->reviewsFields('Максимум 4 отзыва может быть'),
            ],
            'reviews_length' => [
                'type' => 'int',
                'description' => 'Количество отзывов всего',
            ],
        ];
    }

    /**
     * @return array
     */
    public function fieldsListingDetailForHost()
    {
        return [
            'id' => $this->fieldListingId(),
            'slug' => $this->fieldListingSlug(),
            'name' => $this->fieldListingName(),
            'title' => $this->fieldListingTitle(),
            'rating' => [
                'type' => 'object',
                'description' => $this->ratingFields(),
            ],
            'type' => $this->fieldListingType(),
            'image' => $this->fieldListingImage(),
            'images' => $this->imagesField(),
            'images_xs' => $this->imagesXsField(),
            'price' => $this->fieldListingPrice(),
            'deposit' => [
                'type' => 'int|null',
                'description' => 'Депозит',
            ],
            'cleaning_fee' => [
                'type' => 'int|null',
                'description' => 'Плата за уборку',
            ],
            'guests_size' => $this->fieldListingGuestsSize(),
            'rent_time_min' => $this->fieldListingRentTimeMin(),
//            'distance' => [
//                'type' => 'string|null',
//                'description' => 'Расстояние до объекта.',
//            ],
            'address' => $this->fieldListingAddress(),
            'address_two' => $this->fieldListingAddressTwo(),
            'description' => [
                'type' => 'string',
                'description' => 'Основной текст.',
            ],
            'amenities' => [
                'type' => 'array of objects',
                'description' => $this->amenitiesFields(),
            ],
            'amenities_description' => [
                'type' => 'string|null',
                'description' => 'Дополнительные услуги, если `!== null`, то выводить в блоке Extra amenities.',
            ],
            'rules' => [
                'type' => 'array of objects',
                'description' => $this->rulesFields(),
            ],
            'rules_description' => [
                'type' => 'string|null',
                'description' => 'Дополнительные правила, если `!== null`, то выводить в блоке Additional rules.',
            ],
            'cancellation' => [
                'type' => 'string|null',
                'description' => 'Условия отмены бронирования.',
            ],
            'location' => [
                'type' => 'object',
                'description' => $this->locationFields(),
            ],
            'host' => [
                'type' => 'object',
                'description' => $this->hostFields(),
            ],
            'times' => [
                'type' => 'array of objects',
                'description' => $this->timesFields('Ключи `weekdays` и `weekends` массивы объектов с данными вида `time[weekdays][0]`:'),
            ],
            'dates' => [
                'type' => 'array',
                'description' => 'Содержит только `locked`, который массив из дат в формате `Y-m-d`, для удобного заброса в датепикер для блокировки дат',
            ],
            'integrations' => [
                'type' => 'object',
                'description' => 'Данные интеграции',
            ],
            'reviews' => [
                'type' => 'array of objects',
                'description' => $this->reviewsFields('Максимум 4 отзыва может быть'),
            ],
            'reviews_length' => [
                'type' => 'int',
                'description' => 'Количество отзывов всего',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function listingTransformForChat()
    {
        return [
            'id' => $this->fieldListingId(),
            'slug' => $this->fieldListingId(),
            'name' => $this->fieldListingName(),
            'title' => $this->fieldListingTitle(),
            'description' => $this->fieldListingDescription(),
            'type' => $this->fieldListingType(),
            'image' => $this->fieldListingImage(),
            'images' => $this->imagesField(),
            'images_xs' => $this->imagesXsField(),
        ];
    }
}
