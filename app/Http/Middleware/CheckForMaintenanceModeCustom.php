<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Symfony\Component\HttpFoundation\IpUtils;

class CheckForMaintenanceModeCustom
{
    /**
     * Означает что только для супер админой будет доступно приложение
     *
     * php artisan down --allow=admin
     */
    const ALLOWED_ADMIN_IP = 'admin';

    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        '/*', // когда admin.prefix = admin, то /admin и /admin/*
    ];

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            $data = json_decode(file_get_contents($this->app->storagePath() . '/framework/down'), true);

            if (isset($data['allowed']) && IpUtils::checkIp($request->ip(), (array)$data['allowed'])) {
                return $next($request);
            }

            // если разрешен ip вида admin-ip, значит в админку доступ открыт
            // если не было, то это обычный php artisan down, т.е. надо и админку закрыть
            if (!isset($data['allowed']) || !in_array(self::ALLOWED_ADMIN_IP, (array)$data['allowed'])) {
                $this->except = [];
            }

            if ($this->inExceptArray($request)) {
                return $next($request);
            }

            throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should be accessible in maintenance mode.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        $prefix = $request->exists('phpunit') ? $request->get('prefix') : config('cmf.prefix');
        // если префикса нет, то домен другой у админки
        if ($prefix === '') {
            // если домен не совпадает с запросом, то отказуем
            if ($request->root() !== config('cmf.url')) {
                return false;
            }
            // если сопадает, то будет проверяться /* - т.е. все урл админки
        } else {
            // т.к. если пустой, то значит принудительно пустой
            // соответственно это обычный down
            if (!empty($this->except)) {
                // добавить /admin
                // и /* сделать вида /admin/*
                // так в админку пропустит
                $aExcept = $this->except;
                $this->except = [];
                $this->except[] = '/' . $prefix;
                foreach ($aExcept as $except) {
                    $this->except[] = '/' . $prefix . $except;
                }
            }
        }
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getExcept(): array
    {
        return $this->except;
    }
}
