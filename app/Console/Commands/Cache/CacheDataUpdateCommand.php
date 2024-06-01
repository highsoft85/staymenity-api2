<?php

declare(strict_types=1);

namespace App\Console\Commands\Cache;

use App\Console\Commands\Common\CommandTrait;
use App\Http\Controllers\Api\Index\Data;
use App\Http\Controllers\Api\IndexController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class CacheDataUpdateCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'cache:data-update';

    /**
     * The name and signature of the console command.
     *
     * php artisan cache:data-update
     *
     * @var string
     */
    protected $signature = self::SIGNATURE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновить кэш для /data.';

    /**
     *  Не будет записывать в обычные логи, только по Logger
     *
     * @var bool
     */
    private $log = false;

    /**
     * CacheDataUpdateCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->start();
        $this->call('cache:clear');
        (new Data())->dataUpdate(new Request());
        $this->log('Application /data cached!');
        $this->finish();
    }
}
