<?php

declare(strict_types=1);

namespace App\Console\Commands\Version;

use App\Services\GitVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class VersionGitCommand extends Command
{
    /**
     *
     */
    const SIGNATURE = 'version:git';

    /**
     * The name and signature of the console command.
     *
     * php artisan version:git
     * php artisan version:git --testing
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
    protected $description = 'Поставить версию';

    /**
     *  Не будет записывать в обычные логи, только по Logger
     *
     * @var bool
     */
    private $log = false;

    /**
     * CheckQueuesCommand constructor.
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
        $file = base_path() . '/.version';
        if (File::exists($file)) {
            File::delete($file);
        }
        File::put($file, GitVersion::getVersion());
        Artisan::call('cache:clear');
    }
}
