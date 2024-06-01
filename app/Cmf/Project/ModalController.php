<?php

declare(strict_types=1);

namespace App\Cmf\Project;

use App\Cmf\Project\User\UserController;
use App\Models\User;

class ModalController
{
    /**
     * Модальное окно, текст под Редактирование
     * для тура - Рафтинг 1 Августа
     *
     *
     * @param string $name
     * @param object|null $oItem
     * @return string
     */
    public function editTitle(string $name, ?object $oItem): string
    {
        $title = '#' . $oItem->id . ': ' . $oItem->title;
        switch ($name) {
            case UserController::NAME:
                /** @var User $oItem */
                $title = '#' . $oItem->id . ': ' . $oItem->first_name . ' ' . $oItem->last_name;
                break;
            default:
                break;
        }
        return $title;
    }
}
