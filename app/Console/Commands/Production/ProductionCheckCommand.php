<?php

declare(strict_types=1);

namespace App\Console\Commands\Production;

use App\Console\Commands\Common\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class ProductionCheckCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * Если не режим тестирования, то при ошибках будет прервано dd()
     *
     * php artisan production:check
     *
     * @var string
     */
    protected $signature = 'production:check
                            {--details : вывод с деталями}
                            {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверить настройки системы';

    /**
     *  Не будет записывать в обычные логи, только по Logger
     *
     * @var bool
     */
    private $log = false;

    /**
     * @var bool
     */
    private $details = false;

    /**
     * @var array
     */
    private $aMessages = [];

    /**
     * @var string
     */
    private $env = '.env';

    /**
     * @var string
     */
    private $envKeys = '.keys.production';

    /**
     * @var bool
     */
    private $hasErrors = false;

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        $this->start();

        if (!$this->checkEnv()) {
            foreach ($this->aMessages as $message) {
                //$this->error($message);
            }
            $this->finish();
            !$this->testing
                ? abort(500, 'ERROR')
                : Log::info('ERROR');
        } else {
            $this->finish();
        }
    }

    /**
     * @return bool
     */
    private function checkEnv()
    {
        $checkFile = file_exists(base_path($this->env));
        $this->writeEvent('File ' . $this->env . ' exists', $checkFile);
        if (!$checkFile) {
            $this->aMessages[] = 'File ' . $this->envKeys . ' was not found.';
            return false;
        }

        $checkFile = file_exists(base_path($this->envKeys));
        $this->writeEvent('File ' . $this->envKeys . ' exists', $checkFile);
        if (!$checkFile) {
            $this->aMessages[] = 'File ' . $this->envKeys . ' was not found.';
            return false;
        }

        $envProdLoad = DotenvEditor::load($this->env)->getKeys();
        $keysProdLoad = DotenvEditor::load($this->envKeys)->getKeys();

        $this->checkValue($envProdLoad, 'APP_ENV', 'production');
        $this->checkValue($envProdLoad, 'APP_DEBUG', 'false');
        $this->checkValue($envProdLoad, 'LOG_CHANNEL', 'stack');
        $this->checkValue($envProdLoad, 'CLOCKWORK_ENABLE', 'false');
        $this->checkValue($envProdLoad, 'CACHE_DRIVER', 'redis');
        $this->checkValue($envProdLoad, 'MAIL_MAILER', 'smtp');
        $this->checkValue($envProdLoad, 'MAIL_HOST', 'smtp.gmail.com');
        $this->checkValue($envProdLoad, 'MAIL_USERNAME', 'no-reply@staymenity.com');
        $this->checkValue($envProdLoad, 'MAIL_FROM_ADDRESS', 'no-reply@staymenity.com');
        $this->checkValue($envProdLoad, 'API_AUTH_SANCTUM_ENABLED', 'true');
        //$this->checkValue($envProdLoad, 'SANCTUM_STATEFUL_DOMAINS', parse_url(config('app.web_url'))['host']);
        $this->checkValue($envProdLoad, 'LOG_SLACK_DEBUG_EXCEPTION_ENABLED', 'true');
        $this->checkValue($envProdLoad, 'GOOGLE_MAPS_ENABLED', 'true');
        $this->checkValue($envProdLoad, 'QUEUE_CHANNEL_MAIL_ENABLED', 'false');
        $this->checkValue($envProdLoad, 'QUEUE_CHANNEL_SMS_ENABLED', 'false');
        $this->checkValue($envProdLoad, 'QUEUE_CHANNEL_NOTIFICATION_ENABLED', 'false');
        $this->checkValue($envProdLoad, 'QUEUE_CHANNEL_SYNC_ENABLED', 'true');
        $this->checkValue($envProdLoad, 'GOOGLE_ANALYTICS_ENABLED', 'false');
        $this->checkValue($envProdLoad, 'YANDEX_MAP_ENABLED', 'false');
        $this->checkValue($envProdLoad, 'FIREBASE_ENABLED', 'true');
        $this->checkValue($envProdLoad, 'IOS_PUSH_IS_SANDBOX', 'false');
        $this->checkValue($envProdLoad, 'AUTOHOST_ENABLED', 'true');
        $this->checkValue($envProdLoad, 'NEXMO_ENABLED', 'true');
        $this->checkValue($envProdLoad, 'STRIPE_PUBLISHABLE_KEY', function ($actual) {
            return Str::startsWith($actual, 'pk_live');
        });
        $this->checkValue($envProdLoad, 'STRIPE_SECRET_KEY', function ($actual) {
            return Str::startsWith($actual, 'sk_live');
        });
        $this->checkValue($envProdLoad, 'MAIL_USERNAME', $keysProdLoad);
        $this->checkValue($envProdLoad, 'MAIL_PASSWORD', $keysProdLoad);
        $this->checkValue($envProdLoad, 'MAIL_FROM_ADDRESS', $keysProdLoad);
        $this->checkValue($envProdLoad, 'NEXMO_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'NEXMO_SECRET', $keysProdLoad);
        $this->checkValue($envProdLoad, 'NEXMO_APPLICATION_ID', $keysProdLoad);
        $this->checkValue($envProdLoad, 'NEXMO_FROM', $keysProdLoad);

        $this->checkValue($envProdLoad, 'GOOGLE_MAPS_API_KEY', $keysProdLoad);

        $this->checkValue($envProdLoad, 'GOOGLE_CLIENT_ID', $keysProdLoad);
        $this->checkValue($envProdLoad, 'GOOGLE_IOS_CLIENT_ID', $keysProdLoad);
        $this->checkValue($envProdLoad, 'GOOGLE_CLIENT_SECRET', $keysProdLoad);
        $this->checkValue($envProdLoad, 'GOOGLE_REDIRECT_URI', $keysProdLoad);

        $this->checkValue($envProdLoad, 'FACEBOOK_CLIENT_ID', $keysProdLoad);
        $this->checkValue($envProdLoad, 'FACEBOOK_CLIENT_SECRET', $keysProdLoad);
        $this->checkValue($envProdLoad, 'FACEBOOK_REDIRECT_URI', $keysProdLoad);

        $this->checkValue($envProdLoad, 'APPLE_KEY_ID', $keysProdLoad);
        $this->checkValue($envProdLoad, 'APPLE_CLIENT_ID', $keysProdLoad);
        $this->checkValue($envProdLoad, 'APPLE_CLIENT_SECRET', $keysProdLoad);
        $this->checkValue($envProdLoad, 'APPLE_REDIRECT_URI', $keysProdLoad);

        $this->checkValue($envProdLoad, 'STRIPE_TEST_PUBLISHABLE_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'STRIPE_TEST_SECRET_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'STRIPE_PUBLISHABLE_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'STRIPE_SECRET_KEY', $keysProdLoad);

        $this->checkValue($envProdLoad, 'FIREBASE_API_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'FIREBASE_PAIR_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'FIREBASE_CREDENTIALS', $keysProdLoad);
        $this->checkValue($envProdLoad, 'FIREBASE_DATABASE_URL', $keysProdLoad);

        $this->checkValue($envProdLoad, 'AUTOHOST_KEY', $keysProdLoad);
        $this->checkValue($envProdLoad, 'AUTOHOST_URL', $keysProdLoad);

        $this->checkValue($envProdLoad, 'APP_RESERVATION_SYNC_AFTER_STORE', 'false');
        $this->checkValue($envProdLoad, 'HOSTFULLY_ENABLED', 'true');

        if ($this->hasErrors) {
            return false;
        }
        return true;
    }

    /**
     * @param string $title
     * @param bool $result
     * @param array $aDescription
     */
    private function writeEvent(string $title, bool $result, $aDescription = []): void
    {
        $this->output->writeln(($result ? "<info>success</info>" : '<error>failed</error>') . ' ' . "$title");
        if (!empty($aDescription)) {
            foreach ($aDescription as $description) {
                $this->output->writeln($description);
            }
        }
    }

    /**
     * @param array $data
     * @param string $key
     * @param mixed $expected
     * @return bool
     */
    private function checkValue(array $data, string $key, $expected)
    {
        $actual = $data[$key]['value'];
        if (is_callable($expected)) {
            $title = 'Check ' . $key . ' is callback';
            $success = $expected($actual);
            $error = $key . ' is not ' . (string)'callback' . ' in ' . $this->env . ', actual is ' . $actual;
        } elseif (is_array($expected)) {
            $expected = $expected[$key]['value'];
            $title = 'Check ' . $key . ' is ' . $expected;
            $success = $expected === $actual;
            $error = $key . ' is not ' . (string)$expected . ' in ' . $this->env . ', actual is ' . $actual;
        } else {
            $title = 'Check ' . $key . ' is ' . $expected;
            $success = $expected === $actual;
            $error = $key . ' is not ' . (string)$expected . ' in ' . $this->env . ', actual is ' . $actual;
        }

        $aDescription = [
            '-- ' . $error,
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        return $this->checkCommon($title, $success, $aDescription, $error);
    }

    /**
     * @param string $title
     * @param bool $success
     * @param array $aDescription
     * @param string $error
     * @return bool
     */
    private function checkCommon(string $title, bool $success, array $aDescription, string $error)
    {
        $this->writeEvent($title, $success, $aDescription);
        if (!$success) {
            $this->aMessages[] = $error;
            $this->hasErrors = true;
            return false;
        }
        return true;
    }
}
