<?php

declare(strict_types=1);

namespace App\Console\Commands\Documentation;

use App\Console\Commands\Common\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class DocumentationCheckCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * Если не режим тестирования, то при ошибках будет прервано dd()
     *
     * php artisan documentation:check
     *
     * @var string
     */
    protected $signature = 'documentation:check
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
    private $envTarget = '.env.documentation';

    /**
     * @var string
     */
    private $envProd = '.env';

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
        $checkFile = file_exists(base_path($this->envTarget));
        $this->writeEvent('File ' . $this->envTarget . ' exists', $checkFile);
        if (!$checkFile) {
            $this->aMessages[] = 'File ' . $this->envTarget . ' was not found.';
            return false;
        }

        $envDocLoad = DotenvEditor::load($this->envTarget)->getKeys();
        $envProdLoad = DotenvEditor::load($this->envProd)->getKeys();

        $this->checkEnvironment($envProdLoad);
        $this->checkDocumentationEnvironment($envDocLoad);
        $this->checkApiAuthSanctum($envDocLoad);
        $this->checkEnvUrl($envDocLoad, $envProdLoad);
        $this->checkEnvApiUrl($envDocLoad, $envProdLoad);
        $this->checkEnvDatabase($envDocLoad, $envProdLoad);

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
     * @param array $envProdLoad
     * @return bool
     */
    public function checkEnvironment(array $envProdLoad)
    {
        $title = 'Check APP_ENV is not production';
        $success = $envProdLoad['APP_ENV']['value'] !== 'production';
        $aDescription = [
            '-- APP_ENV (' . $this->envProd . ') !== production',
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        $error = 'APP_ENV is "production" in ' . $this->envProd . '';
        return $this->checkCommon($title, $success, $aDescription, $error);
    }

    /**
     * @param array $envProdLoad
     * @return bool
     */
    public function checkDocumentationEnvironment(array $envProdLoad)
    {
        $title = 'Check APP_ENV is documentation';
        $success = $envProdLoad['APP_ENV']['value'] === 'documentation';
        $aDescription = [
            '-- APP_ENV (' . $this->envTarget . ') must be equals to documentation',
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        $error = 'APP_ENV is not "documentation" in ' . $this->envTarget . '';
        return $this->checkCommon($title, $success, $aDescription, $error);
    }

    /**
     * @param array $envProdLoad
     * @return bool
     */
    public function checkApiAuthSanctum(array $envProdLoad)
    {
        $title = 'Check API_AUTH_SANCTUM_ENABLED is false';
        $success = $envProdLoad['API_AUTH_SANCTUM_ENABLED']['value'] !== false;
        $aDescription = [
            '-- API_AUTH_SANCTUM_ENABLED (' . $this->envTarget . ') is false',
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        $error = 'API_AUTH_SANCTUM_ENABLED is not false in ' . $this->envTarget . '';
        return $this->checkCommon($title, $success, $aDescription, $error);
    }

    /**
     * @param array $envDuskLoad
     * @param array $envProdLoad
     * @return bool
     */
    public function checkEnvUrl(array $envDuskLoad, array $envProdLoad): bool
    {
        $title = 'Check APP_URL';
        $success = $envDuskLoad['APP_URL']['value'] !== $envProdLoad['APP_URL']['value'];
        $aDescription = [
            '-- APP_URL ' . $envDuskLoad['APP_URL']['value'] . '(' . $this->envTarget . ') must be not equals to ' . $envProdLoad['APP_URL']['value'] . '(' . $this->envProd . ')',
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        $error = 'APP_URL is equals on .env in ' . $this->envTarget . '';
        return $this->checkCommon($title, $success, $aDescription, $error);
    }

    /**
     * @param array $envDuskLoad
     * @param array $envProdLoad
     * @return bool
     */
    public function checkEnvApiUrl(array $envDuskLoad, array $envProdLoad): bool
    {
        $title = 'Check API_URL';
        $success = $envDuskLoad['API_URL']['value'] !== $envProdLoad['API_URL']['value'];
        $aDescription = [
            '-- API_URL ' . $envDuskLoad['API_URL']['value'] . '(' . $this->envTarget . ') must be not equals to ' . $envProdLoad['API_URL']['value'] . '(' . $this->envProd . ')',
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        $error = 'API_URL is equals on .env in ' . $this->envTarget . '';
        return $this->checkCommon($title, $success, $aDescription, $error);
    }

    /**
     * @param array $envDuskLoad
     * @param array $envProdLoad
     * @return bool
     */
    public function checkEnvDatabase(array $envDuskLoad, array $envProdLoad): bool
    {
        $title = 'Check DB_DATABASE';
        $success = $envDuskLoad['DB_DATABASE']['value'] !== $envProdLoad['DB_DATABASE']['value'];
        $aDescription = [
            '-- DB_DATABASE ' . $envDuskLoad['DB_DATABASE']['value'] . '(' . $this->envTarget . ') must be not equals to ' . $envProdLoad['DB_DATABASE']['value'] . '(' . $this->envProd . ')',
        ];
        if (!$this->details && $success) {
            $aDescription = [];
        }
        $error = 'DB_DATABASE is equals on .env in ' . $this->envTarget . '';
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
