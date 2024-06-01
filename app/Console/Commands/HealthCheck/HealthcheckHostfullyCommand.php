<?php

namespace App\Console\Commands\HealthCheck;

use App\Console\Commands\Common\CommandTrait;
use App\Services\HealthCheck\Hostfully;
use Illuminate\Console\Command;

class HealthCheckHostfullyCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'health-check:hostfully';

    /**
     * The name and signature of the console command.
     *
     * php artisan health-check:hostfully
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка хостфулли';

    /**
     *  Не будет записывать в обычные логи, только по Logger
     *
     * @var bool
     */
    private $log = false;

    /**
     * ExportConverterUsersCommand constructor.
     */
    public function __construct()
    {
        @parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        (new Hostfully())->check();
    }
}
