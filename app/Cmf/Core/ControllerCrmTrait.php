<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use App\Events\ChangeCacheEvent;
use App\Models\Image;
use App\Models\User;
use App\Services\Image\ImageService;
use App\Services\Image\ImageType;
use App\Services\Image\Upload\ImageUploadModelService;
use App\Services\Image\Upload\ImageUploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

trait ControllerCrmTrait
{

    /**
     * Validate mode
     *
     * @param Request $request
     * @param array $rules
     * @param array $attributes
     * @param string|null $method
     * @return mixed
     */
    public function validation(Request $request, array $rules, array $attributes, ?string $method = null)
    {
        $errors = property_exists($this, 'errors') ? $this->errors ?? [] : [];

        if (isset($rules[$method])) {
            $rules = $rules[$method];
        }
        return Validator::make($request->all(), $rules, $errors, $attributes);
    }

    /**
     * После любых изменений очистить кэш по этому ключу
     *
     * Если в aKeys user_1.members, то members - это тэг
     *
     * @param array $aKeys
     * @param null|object $oModel
     */
    public function afterChange(array $aKeys = [], $oModel = null): void
    {
        if (method_exists($this, 'thisAfterChange')) {
            $this->thisAfterChange($oModel);
        } else {
            if (empty($aKeys)) {
                return;
            }
            foreach ($aKeys as $value) {
                event(new ChangeCacheEvent($value));
            }
        }
    }


    /**
     * Правила для загрузки фотографий, с учетом каждой фотографии
     *
     * @param array $images
     * @param string $rulesKey
     * @param string $validatorKey
     * @param string $validatorAttribute
     * @return array
     */
    public function setRulesForUpload(array $images, string $rulesKey, string $validatorKey = 'images', string $validatorAttribute = 'image')
    {
        $attributes = $this->getAttributes() ?? [];
        $rules = $this->getRules();

        foreach ($images as $key => $image) {
            $rules[$rulesKey][$validatorKey . '.' . $key] = $rules[$rulesKey][$validatorKey];
            $attributes[$validatorKey . '.' . $key] = $attributes[$validatorAttribute] ?? '';
        }
        unset($rules[$rulesKey][$validatorKey]);
        return [
            'rules' => $rules,
            'attributes' => $attributes,
        ];
    }

    /**
     * Шаблоны для сущности
     *
     * @return mixed
     */
    protected function setViews()
    {
        return Cache::remember('views:' . property_exists($this, 'view') ? $this->view : '', 3600, function () {
            $view = $this->theme . '.content.' . (property_exists($this, 'view') ? $this->view : '');
            return [
                'create' => $this->theme . '.content.default.page.create',
                'show'   => $this->theme . '.content.default.page.show',
                'edit'   => $this->theme . '.content.default.page.edit',

                'index'  => $this->theme . '.content.default.table.index',
                'table'  => $this->theme . '.content.default.table.table',
                'modal'  => $this->theme . '.content.default.modals.container.',

                'customModal' => $this->theme . '.content.components.relationships',
            ];
        });
    }

    /**
     * @param Model $model
     */
    public function beforeDeleteForImages(Model $model)
    {
        $oImages = property_exists($model, 'images') ? $model->images : [];
        $id = property_exists($model, 'id') ? $model->id : null;

        if (count($oImages) !== 0 && !is_null($id)) {
            foreach ($oImages as $oImage) {
                if (method_exists($this, 'imageDestroy')) {
                    $request = new Request();
                    $this->imageDestroy($request, $id, $oImage->id);
                }
            }
        }
    }

    /**
     * Найти сущность по модели
     *
     * @param string|null|User|mixed $class
     * @param int $id
     * @return mixed
     */
    protected function findByClass($class, int $id)
    {
        if (method_exists($class, 'trashed')) {
            return $class::where('id', $id)->withTrashed()->first();
        }
        return $class::find($id);
    }

    /**
     * @param string $model
     * @return mixed
     */
    public static function getControllerByModelName(string $model)
    {
        $class = '\App\Cmf\Project\\' . Str::studly($model) . '\\' . Str::studly($model) . 'Controller';
        return new $class();
    }

    /**
     * @param string $class
     * @return mixed|null
     */
    public static function getModelNameByClass(string $class)
    {
        return (preg_match('#\\\\([a-zA-Z]+)$#', $class, $match) ? $match[1] : null);
    }

    /**
     * @param string $name
     * @param mixed $data
     */
    public function shareToView(string $name, $data)
    {
        View::share($name, $data);
    }

    /**
     * @param array $aOrderBy
     */
    public function setOrderBy(array $aOrderBy = []): void
    {
        $this->aOrderBy = $aOrderBy;
    }

    /**
     * @param array $aQuery
     */
    public function setQuery(array $aQuery = []): void
    {
        $this->aQuery = $aQuery;
    }

    /**
     * @param array $image
     */
    public function setImage(array $image = []): void
    {
        $this->image = array_merge($this->image, $image);
    }
}
