<?php

declare(strict_types=1);

if (!function_exists('routeCmf')) {
    /**
     * Generate the URL to a named route.
     *
     * routeCmf('create.modal.post')
     *
     * @param string $view
     * @param array $parameters
     * @param bool $absolute
     * @return string
     * @see \App\Cmf\Core\RouteCmf::resource()
     *
     */
    function routeCmf(string $view, array $parameters = [], bool $absolute = true)
    {
        $prefix = config('cmf.as');
        if ($prefix !== '') {
            $view = $prefix . '.' . $view;
        }
        return app('url')->route($view, $parameters, $absolute);
    }
}

if (!function_exists('cmfHelper')) {

    function cmfHelper($type = 'cmf')
    {
        return new \App\Cmf\Core\Helper($type);
    }
}

if (!function_exists('isImageable')) {
    /**
     * @param string $model
     * @return bool
     */
    function isImageable(string $model)
    {
        $array = [
            //\App\Cmf\Project\User\UserController::NAME,
        ];
        return in_array($model, $array);
    }
}

if (!function_exists('formatPhoneUs')) {
    /**
     * @param string $sPhone
     * @param string $sFormat
     * @return string
     */
    function formatPhoneUs(string $sPhone, string $sFormat = "+%d (%d%d%d) %d%d%d-%d%d%d%d")
    {
        $sPhone = preg_replace("/[^0-9]/", '', $sPhone);
        if (strlen($sPhone) === 11) {
            $sPhone = substr_replace($sPhone, '1', 0, 1);
        } elseif (strlen($sPhone) === 10) {
            $sPhone = '1' . $sPhone;
        }
        $aNumbers = str_split($sPhone);
        extract($aNumbers, EXTR_PREFIX_ALL, "n");
        return sprintf($sFormat);
    }
}

if (!function_exists('ddWithoutExit')) {
    /**
     * @param mixed ...$vars
     * @return null
     */
    function ddWithoutExit(...$vars)
    {
        foreach ($vars as $v) {
            \Symfony\Component\VarDumper\VarDumper::dump($v);
        }
        return null;
    }
}

if (!function_exists('isDeveloperMode')) {
    /**
     * @return bool
     */
    function isDeveloperMode()
    {
        return \Illuminate\Support\Facades\Session::exists(\App\Cmf\Core\MainController::MODE_DEVELOPER);
    }
}

if (!function_exists('cmfToInvoke')) {

    function cmfToInvoke(\App\Models\User $oUser, $class, $request = null, array $data = [], ?int $id = null)
    {
        $data = array_merge($data, [
            'admin-visual' => 1,
        ]);
        if (is_null($request)) {
            $request = new \Illuminate\Http\Request();
            $request->merge($data);
        } else {
            $request = new $request();
            $request->merge($data);
        }
        (new \App\Cmf\Core\AccessController())->setActionUserId($oUser);
        if (!is_null($id)) {
            return (new $class())->__invoke($request, $id);
        }
        return (new $class())->__invoke($request);
    }
}

if (!function_exists('cmfIsAdminVisual')) {

    function cmfIsAdminVisual($request)
    {
        return $request->exists('admin-visual');
    }
}
