<?php

declare(strict_types=1);

namespace App\Http\Composers;

use App\Services\Image\Facades\ImagePath;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait CommonComposersTrait
{
    protected $options;
    protected $site;

    protected function setCommon()
    {
       // $this->options = $this->remember('settings', function () {
       //     return Setting::with('images')->get();
       // });
        $this->site = remember('site', function () {
            return $this->getSiteInfo(collect([]));
        });
    }

    /**
     * @param View $view
     */
    protected function setCommonCompose(View $view)
    {
        $view->with('oComposerSite', $this->site);
        $view->with('oComposerOptions', $this->options);
    }

    /**
     * @param array|\Illuminate\Support\Collection $oOptions
     * @return object
     */
    private function getSiteInfo($oOptions)
    {
        $aCurrentOptions = [];

        $aCurrentOptions = $this->setDefaultOptions($aCurrentOptions);

        $oOptions = [];
        foreach ($aCurrentOptions as $key => $aCurrentOption) {
            $oOptions[$key] = (object)$aCurrentOption;
        }
        return (object)$oOptions;
    }

    /**
     * Шаблоны сущностей
     *
     * @param string $dir
     * @return array
     */
    public function getMenu($dir)
    {
        $directories = File::directories($dir);
        $controllers = [];
        $menu = [];
        foreach ($directories as $directory) {
            $files = File::allFiles($directory);
            foreach ($files as $file) {
                $str = 'Controller.php';
                $filename = $file->getFilename();
                if (Str::endsWith($filename, $str)) {
                    $name = Str::lower(str_replace($str, '', $filename));
                    $controllers[$name] = $file;
                }
            }
        }
        foreach ($controllers as $key => $controller) {
            $tValue = $this->getTitleCase($key);
            $sClass = $tValue . Str::studly('_controller');
            $sClass = 'App\Cmf\Project\\' . $tValue . '\\' . $sClass;
            $menu[$key] = (new $sClass())->menu;
        }
        return $menu;
    }

    /**
     * @param string $dir
     * @return array
     */
    public function getFields(string $dir): array
    {
        $directories = File::directories($dir);
        $controllers = [];
        $menu = [];
        foreach ($directories as $directory) {
            $files = File::allFiles($directory);
            foreach ($files as $file) {
                $str = 'Controller.php';
                $filename = $file->getFilename();
                if (Str::endsWith($filename, $str)) {
                    $name = Str::lower(str_replace($str, '', $filename));
                    $controllers[$name] = $file;
                }
            }
        }
        foreach ($controllers as $key => $controller) {
            $tValue = $this->getTitleCase($key);
            $sClass = $tValue . Str::studly('_controller');
            $sClass = 'App\Cmf\Project\\' . $tValue . '\\' . $sClass;
            $menu[$key] = (new $sClass())->fields;
        }
        return $menu;
    }

    /**
     * @return array
     */
    public function getFieldsCache(): array
    {
        return remember('fields', function () {
            return $this->getFields(app_path('Cmf/Project'));
        });
    }

    /**
     * Контроллеры сущностей
     *
     * @param array $content
     * @return array
     */
    public function getAliases(array $content)
    {
        $array = [];
        foreach ($content as $key => $value) {
            $tValue = $this->getTitleCase($value);
            $sClass = $tValue . Str::studly('_controller');
            $sClass = 'App\Cmf\Project\\' . $tValue . '\\' . $sClass;
            $sModel = 'App\Models\\' . $tValue;
            if (class_exists($sClass) && class_exists($sModel)) {
                $oController = new $sClass();
                if (isset($oController->menu)) {
                    $array[$value] = $oController->menu;
                }
                if (method_exists($oController, 'getImageFilters')) {
                    $array[$value]['filters'] = $oController->getImageFilters();
                }
                $array[$value]['class'] = $sClass;
            }
        }
        return $array;
    }

    /**
     * Обычный title_case с возможностью принимать значение вида key_name и транформирую в KeyName
     *
     * @param string $value
     * @return mixed
     */
    protected function getTitleCase(string $value)
    {
        return str_replace(' ', '', Str::title(str_replace('_', ' ', $value)));
    }

    /**
     * По config.admin.options
     *
     * @param array $aCurrentOptions
     * @return mixed
     */
    private function setDefaultOptions(array $aCurrentOptions)
    {
        $defaultOptions = config('cmf.options');
        foreach ($defaultOptions as $key => $aOptions) {
            foreach ($aOptions as $subKey => $aOption) {
                if (is_array($aOption)) {
                    foreach ($aOption as $k => $v) {
                        if (!isset($aCurrentOptions[$key][$subKey][$k])) {
                            $aCurrentOptions[$key][$subKey][$k] = $v;
                        }
                    }
                } else {
                    if (!isset($aCurrentOptions[$key][$subKey])) {
                        $aCurrentOptions[$key][$subKey] = $aOption;
                    }
                }
            }
        }
        return $aCurrentOptions;
    }
}
