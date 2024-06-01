<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Fields\Reservation;

trait ReservationFieldsTrait
{
    /**
     * @return string[]
     */
    protected function reservationId()
    {
        return [
            'type' => 'int',
            'description' => 'ID брони',
        ];
    }

    /**
     * @return array
     */
    protected function reservationUser()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'id' => $this->fieldUserId(),
                'first_name' => $this->fieldUserFirstName(),
                'last_name' => $this->fieldUserLastName(),
                'image' => $this->fieldUserImage(),
            ], 'Юзер', false),
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationListing()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'id' => $this->fieldListingId(),
                'slug' => $this->fieldListingSlug(),
                'name' => $this->fieldListingName(),
                'title' => $this->fieldListingTitle(),
                'rating' => [
                    'type' => 'object',
                    'description' => $this->ratingFields(),
                ],
                'image' => $this->fieldListingImage(),
                // @todo доделать Card
                'guests_size' => $this->fieldListingGuestsSize(),
                'price' => $this->fieldListingPrice(),
                'address' => $this->fieldListingAddress(),
                'reviews_length' => $this->fieldListingReviewsLength(),
            ], 'Листинг', false),
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationListingForChat()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'id' => $this->fieldListingId(),
                'slug' => $this->fieldListingSlug(),
                'name' => $this->fieldListingName(),
                'title' => $this->fieldListingTitle(),
                'description' => $this->fieldListingDescription(),
                'type' => $this->fieldListingType(),
                'image' => $this->fieldListingImage(),
                'images' => $this->imagesField(),
                'images_xs' => $this->imagesXsField(),
            ], 'Листинг', false),
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationMessage()
    {
        return [
            'type' => 'string|null',
            'description' => 'Сообщение от гостя',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationGuestsSize()
    {
        return [
            'type' => 'int',
            'description' => 'Количество гостей, которое было забронировано',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationDate()
    {
        return [
            'type' => 'string',
            'description' => 'Дата в формете m-d-Y или Tomorrow',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationDateAt()
    {
        return [
            'type' => 'string',
            'description' => 'Дата в формате `2020-12-08T04:11:27-05:00` на фронте переводить в локальную дату Today/Tomorrow/m-d-Y',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationDateFormatted()
    {
        return [
            'type' => 'string',
            'description' => 'Дата в формете m-d-Y (Tomorrow если необходимо)',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationTime()
    {
        return [
            'type' => 'string',
            'description' => 'Время в формате 9 am - 3 pm',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationHasReview()
    {
        return [
            'type' => 'bool|null',
            'description' => 'Есть ли отзыв от этого юзера, проверять только во вкладке `Previous`, если false, то показывать кнопку с запросом на GET `/user/reservations/:id/review`, null если не надо вообще проверять',
        ];
    }

    /**
     * @return array
     */
    protected function reservationChatCanCreate()
    {
        return [
            'type' => 'bool',
            'description' => 'Можно ли хосту создать чат, для отмененных броней',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationChat()
    {
        return [
            'type' => 'object|null',
            'description' => 'Если `null`, значит чата нет для этой брони, если не `null`, то будет объект с `id`',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationPrice()
    {
        return [
            'type' => 'float',
            'description' => 'Цена только за часы, которую получит хост',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationHours()
    {
        return [
            'type' => 'int',
            'description' => 'Сколько часов выбрано',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationTotalPrice()
    {
        return [
            'type' => 'float',
            'description' => 'Цена с учетом service_fee, которую пользователь оплатил, выводить эту, на беке потом сделаю замену смотря кто запрашивает',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationFreeCancellationAt()
    {
        return [
            'type' => 'string',
            'description' => 'Дата в формате `2020-10-15 12:29:23`, на фронте парсить и при запросе проверять сколько еще бесплатной отмены осталось',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationFreeCancellationText()
    {
        return [
            'type' => 'string',
            'description' => 'Текст для отмены брони, например Free cancellation before 06 AM, May 06 (America/New_York)',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationStatus()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'name' => ['string', 'Ключ'],
                'title' => ['string', 'Значение'],
            ], 'Статус'),
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationType()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'name' => ['string', 'Ключ'],
                'title' => ['string', 'Значение'],
            ], 'Тип'),
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationStartAtDate()
    {
        return [
            'type' => 'string',
            'description' => 'Дата в формате Sat, Feb 15',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationStartAtTime()
    {
        return [
            'type' => 'string',
            'description' => 'Время в формате 9:00 AM',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationFinishAtDate()
    {
        return [
            'type' => 'string',
            'description' => 'Дата в формате Sat, Feb 15',
        ];
    }

    /**
     * @return string[]
     */
    protected function reservationFinishAtTime()
    {
        return [
            'type' => 'string',
            'description' => 'Время в формате 9:00 AM',
        ];
    }

    /**
     * @return array
     */
    protected function reservationTransformForChat()
    {
        return [
            'id' => $this->reservationId(),
            //'listing' => $this->reservationListingForChat(),
            'time' => $this->reservationTime(),
            'hours' => $this->reservationHours(),
            'date_formatted' => $this->reservationDateFormatted(),
            'start_at_date' => $this->reservationStartAtDate(),
            'start_at_time' => $this->reservationStartAtTime(),
            'finish_at_date' => $this->reservationFinishAtDate(),
            'finish_at_time' => $this->reservationFinishAtTime(),
            'type' => $this->reservationType(),
            'status' => $this->reservationStatus(),
        ];
    }
}
