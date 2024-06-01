<?php

declare(strict_types=1);

namespace App\Console\Commands\Sync;

use App\Console\Commands\Common\CommandTrait;
use App\Models\Listing;
use App\Services\Database\RedisRateService;
use App\Services\Hostfully\ExternalCalendars\Index;
use App\Services\Hostfully\HostfullyPropertiesService;
use App\Services\Logger\Logger;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncListingsCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'sync:listings';

    /**
     * The name and signature of the console command.
     *
     * php artisan sync:listings
     * php artisan sync:listings --service=hostfully
     * php artisan sync:listings --id=3
     * php artisan sync:listings --testing
     * php artisan sync:listings --store
     * php artisan sync:listings --from
     * php artisan sync:listings --to
     * php artisan sync:listings --to --id=3
     * php artisan sync:listings --to --id=3 --force
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--service=hostfully : сервис}
        {--id= : листинг}
        {--from : из hostfully}
        {--to : в hostfully}
        {--force : перезаписать}
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация';

    /**
     * @var bool
     */
    private bool $log = false;

    /**
     * @var bool
     */
    private bool $force  = false;

    /**
     * @var bool
     */
    private bool $reservations = false;

    /**
     * @var bool
     */
    private bool $from = false;

    /**
     * @var bool
     */
    private bool $to = false;

    /**
     * @var string
     */
    private string $service = 'hostfully';

    /**
     * @var string|int
     */
    private $id;

    /**
     * @var Logger|null
     */
    private $logger = null;

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
        //dd(Carbon::parse('2021-05-22 00:00:00')->diffInDays(Carbon::parse('2021-05-29 23:59:59')));
//        $start = Carbon::parse('2021-05-22 00:00:00');
//        $end = Carbon::parse('2021-05-29 00:00:00');
//        /** @var Carbon[] $dates */
//        $dates = [];
//        while ($start->lte($end)) {
//            $dates[] = $start->format('d-m-Y');
//            $start->addDay();
//        }
//        dd($dates);
        //(new RedisRateService('test', 1000))->increment();
        //dd((new RedisRateService('test', 1000))->get());


        $this->logger = (new Logger())->setName('sync/' . $this->option('service') . '/listings')->log();

        $this->start();

        if (!config('hostfully.enabled')) {
            $this->log('SYNC ENABLED: false');
            $this->finish();
            return;
        }

        if ($this->from) {
            $this->from();
        }
        if ($this->to) {
            $this->to();
        }

        $this->finish();
    }

    /**
     *
     */
    private function from()
    {
        $oService = (new HostfullyPropertiesService());
        $aId = $oService->get();
        foreach ($aId as $uid) {
            $data = $oService->show($uid);
            //dd((new Index())->__invoke($uid));
            $oService->syncFrom($data);
            dd('');
        }
    }

    /**
     *
     */
    private function to()
    {
        $query = Listing::active();

        if (!is_null($this->id)) {
            $query->whereIn('id', [$this->id]);
        }

        /** @var Listing[] $oItems */
        $oItems = $query->get();

        foreach ($oItems as $oItem) {
            $oService = (new HostfullyPropertiesService($oItem->user->details->hostfully_agency_uid));
            if ($this->force) {
                $oService->setForce();
            }
            $oService->syncTo($oItem);
            dd('');
        }
    }
}
