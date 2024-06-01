<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Fields;

trait UserFieldsTrait
{
    /**
     * @return array
     */
    public function fieldUserId()
    {
        return [
            'type' => 'int',
            'description' => 'ID юзера',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserFirstName()
    {
        return [
            'type' => 'string|null',
            'description' => null,
        ];
    }

    /**
     * @return array
     */
    public function fieldUserLastName()
    {
        return [
            'type' => 'string|null',
            'description' => null,
        ];
    }

    /**
     * @return array
     */
    public function fieldUserImage()
    {
        return [
            'type' => 'string',
            'description' => null,
        ];
    }

    /**
     * @return array
     */
    public function fieldUserRegisteredAt()
    {
        return [
            'type' => 'string',
            'description' => 'Дата регистрации в формате `2020-10-15 12:29:23`',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserIsNeedPassword()
    {
        return [
            'type' => 'bool',
            'description' => 'Нужно ли пользователю придумать себе пароль',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserHasImage()
    {
        return [
            'type' => 'bool',
            'description' => 'Есть ли у юзера загруженная фотка, проверка что не заглушка',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserHasPayoutConnect()
    {
        return [
            'type' => 'bool',
            'description' => 'Подключен ли у юзера страйп для вывода средств',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserListingsAccessible()
    {
        return [
            'type' => 'array',
            'description' => 'Массив id листингов, к которым у юзера есть доступ к геолокации',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserRegisteredAtFormatted()
    {
        return [
            'type' => 'string',
            'description' => 'Дата регистрации в формате `Y`, для подстановки пользователю',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserReviewsLength()
    {
        return [
            'type' => 'int',
            'description' => 'Общее количество отзывов',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserBalance()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'amount' => ['float', 'Баланс'],
                'status' => ['int', 'Статус баланса, 1 - активно, 0 - не активно'],
            ]),
        ];
    }

    /**
     * @return array
     */
    public function fieldUserFirebase()
    {
        return [
            'type' => 'object',
            'description' => 'Каналы для прослушки',
        ];
    }

    /**
     * @return array
     */
    public function fieldIdentityVerificationStatus()
    {
        return [
            'type' => 'object',
            'description' => $this->listFields([
                'name' => ['string', 'Ключ'],
                'title' => ['string', 'Текст'],
                'errors' => ['null|object', 'Ошибки по ключам'],
            ]),
        ];
    }

    /**
     * @return array
     */
    public function fieldUserIsIdentityVerified()
    {
        return [
            'type' => 'bool',
            'description' => 'Идентифицирован ли пользователь',
        ];
    }

    /**
     * @return array
     */
    public function fieldUserSettings()
    {
        return [
            'type' => 'array of objects',
            'description' => $this->settingsFields('Настройки пользователя. 1 - включено, 0 - выключено'),
        ];
    }

    /**
     * @return array
     */
    public function fieldUserSaves()
    {
        return [
            'type' => 'array of objects',
            'description' => $this->savesFields('Сохраненные списки пользователя'),
        ];
    }

    /**
     * @return array
     */
    public function fieldUserTransformerMention()
    {
        return [
            'id' => $this->fieldUserId(),

        ];
    }


    /**
     * @return array
     */
    protected function fieldUserListingsCard()
    {
        return [
            'type' => 'array of objects',
            'description' => $this->listFields($this->fieldsListingCard(), 'Вывод карточки такой же как для поиска и везде', false),
        ];
    }
}
