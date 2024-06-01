<?php

declare(strict_types=1);

namespace App\Cmf\Core;

trait SettingsTrait
{
    /**
     * Сообщения об успехе
     *
     * @var array
     */
    public $toastText = [
        'store' => 'The record was created.',
        'update' => 'The record was updated.',
        'destroy' => 'The record was deleted.',
        'status' => 'Статус успешно изменен.',
    ];
}
