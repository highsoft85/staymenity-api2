<?php

declare(strict_types=1);

namespace App\Cmf\Core\Defaults;

use Illuminate\Http\Request;

trait TextableTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function textSave(Request $request, $id)
    {
        $oItem = $this->findByClass($this->class, $id);

        $key = method_exists($this->class, 'textableKey')
            ? (new $this->class())->textableKey()
            : 'text';

        if ($request->has($key)) {
            $text = $request->get($key);

            // замена url для изображений
            // если трейт с clearTextImages подключен, значить возможна загрузка
            if (method_exists($this, 'clearTextImages')) {
                $text = $this->clearTextImages($oItem, $request->get('uid'), $text);
            }
            // проверить ссылки url, если была вставлена ссылка, то скачиваем файл и подставляем другую ссылку
            if (method_exists($this, 'clearTextImagesByUrl')) {
                //$text = $this->clearTextImagesByUrl($oItem, $text);
            }
            $oItem->update([
                $key => $text,
            ]);
            // Проверить изображения по тексту, если их нет в тексте, то удалить Image
            if (method_exists($this, 'checkTextImages')) {
                $this->checkTextImages($oItem, $text);
            }

            return responseCommon()->success([
                'text' => $text,
            ], 'Данные успешно сохранены');
        }
        return responseCommon()->success([], 'Данные успешно сохранены');
    }
}
