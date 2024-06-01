<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Services\Environment;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Env;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (config('sentry.breadcrumbs.enabled')) {
            if (app()->bound('sentry') && $this->shouldReport($exception)) {
                app('sentry')->captureException($exception);
            }
        }
        if ($this->shouldReport($exception)) {
            $this->sendSlackException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * @param Throwable $exception
     */
    private function sendSlackException(Throwable $exception)
    {
        $enabled = config('logging.channels.slack-debug.exception');
        $hasUrl = !empty(config('logging.channels.slack-debug.url'));
        if ($enabled && $hasUrl) {
            $message = $exception->getMessage() . "\n" . $exception->getFile() . ':' . $exception->getLine();
            (new SlackDebugNotification())->error($message);
        }
    }
}
