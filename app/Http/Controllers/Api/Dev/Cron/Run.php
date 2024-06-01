<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Dev\Cron;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class Run
{
    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request)
    {
        Artisan::call('schedule:run');
        return responseCommon()->success();
    }
}
