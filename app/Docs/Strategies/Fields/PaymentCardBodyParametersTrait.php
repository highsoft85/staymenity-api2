<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Fields;

trait PaymentCardBodyParametersTrait
{
    /**
     * @return array
     */
    protected function parameterTokenId()
    {
        return [
            'description' => 'Токен из stripe',
            'required' => false,
            'value' => 'tok_1HmIAaIFDQsDl8swhraI3txh',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterBrand()
    {
        return [
            'description' => 'Бренд карты из stripe',
            'required' => false,
            'value' => 'Visa',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterLast()
    {
        return [
            'description' => 'Последние четыре цифры карты из stripe',
            'required' => false,
            'value' => '4242',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterCardId()
    {
        return [
            'description' => 'ID карты из stripe',
            'required' => false,
            'value' => 'card_1HmIAaIFDQsDl8swVaNUtQo3',
            'type' => 'string',
        ];
    }

    /**
     * @return array
     */
    protected function parameterPaymentMethodId()
    {
        return [
            'description' => 'ID метода оплаты из stripe',
            'required' => false,
            'value' => 'pm_***',
            'type' => 'string',
        ];
    }
}
