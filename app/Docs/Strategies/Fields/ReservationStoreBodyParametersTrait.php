<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Fields;

use App\Services\Model\UserReservationServiceModel;

trait ReservationStoreBodyParametersTrait
{
    /**
     * @return array
     */
    protected function parameterListingId()
    {
        return [
            'description' => 'ID листинга',
            'required' => true,
            'value' => 1,
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterStartAt()
    {
        return [
            'description' => 'Дата c в формате ' . UserReservationServiceModel::DATE_FORMAT,
            'required' => true,
            'value' => now()->format(UserReservationServiceModel::DATE_FORMAT),
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterFinishAt()
    {
        return [
            'description' => 'Дата по в формате ' . UserReservationServiceModel::DATE_FORMAT,
            'required' => true,
            'value' => now()->format(UserReservationServiceModel::DATE_FORMAT),
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterGuestsSize()
    {
        return [
            'description' => 'Количество гостей',
            'required' => true,
            'value' => 5,
            'type' => 'int',
        ];
    }

    /**
     * @return array
     */
    protected function parameterMessage()
    {
        return [
            'description' => 'Сообщение от гостя',
            'required' => false,
            'value' => 'Message',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterPrice()
    {
        return [
            'description' => 'Цена по расчетам за время по листингу',
            'required' => true,
            'value' => 100,
            'type' => 'int',
        ];
    }

    /**
     * @return array
     */
    protected function parameterServiceFee()
    {
        return [
            'description' => 'Какие-то сборы',
            'required' => true,
            'value' => 50,
            'type' => 'int',
        ];
    }

    /**
     * @return array
     */
    protected function parameterTotalPrice()
    {
        return [
            'description' => 'Общая цена',
            'required' => true,
            'value' => 50,
            'type' => 'int',
        ];
    }

    /**
     * @return array
     */
    protected function parameterPhone()
    {
        return [
            'description' => 'Телефон',
            'required' => true,
            'value' => '11111111111',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterPhoneVerified()
    {
        return [
            'description' => 'Подтвержден ли телефон',
            'required' => true,
            'value' => 1,
            'type' => 'int',
        ];
    }

    /**
     * @return array
     */
    protected function parameterFirstName()
    {
        return [
            'description' => 'Имя',
            'required' => true,
            'value' => 'Admin',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterLastName()
    {
        return [
            'description' => 'Фамилия',
            'required' => true,
            'value' => 'Admin',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterEmail()
    {
        return [
            'description' => 'Email пользователя',
            'required' => true,
            'value' => 'admin@admin.com',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterPassword()
    {
        return [
            'description' => 'Пароль пользователя',
            'required' => true,
            'value' => '1234567890',
            'type' => 'string',
        ];
    }
}
