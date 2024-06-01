<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

use App\Http\Middleware\CheckForMaintenanceModeCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

trait UserMaintenanceTrait
{
    /**
     * @param Request $request
     * @return array
     */
    public function actionChangeMaintenance(Request $request): array
    {
        $phpUnit = $request->exists('phpunit');
        $up = isDownForMaintenance() || ($request->exists('phpunit') && $request->get('up'));
        if ($up) {
            !$phpUnit ? Artisan::call('up') : Log::info('php artisan up');
            return responseCommon()->success([], 'Режим обслуживания успешно отключен. Приложение включено');
        } else {
            !$phpUnit
                ? Artisan::call('down', ['--allow' => CheckForMaintenanceModeCustom::ALLOWED_ADMIN_IP])
                : Log::info('php artisan down');
            return responseCommon()->success([], 'Режим обслуживания успешно включен. Приложение отключено');
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function actionCheckMaintenanceMode(Request $request): array
    {
        $view = view('cmf.components.maintenance.button')->render();
        return responseCommon()->success([
            'view' => $view,
        ]);
    }
}
