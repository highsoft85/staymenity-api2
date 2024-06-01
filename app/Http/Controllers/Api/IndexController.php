<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\TestEvent;
use Dingo\Api\Routing\Helpers;

class IndexController extends ApiController
{
    use Helpers;

    /**
     * @return string
     */
    public function index()
    {
        slackInfo(request()->all());
        event(new TestEvent());
        return '200';
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function docs()
    {
        return $this->response->noContent();
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function keys()
    {
        return $this->response->noContent();
    }
}
