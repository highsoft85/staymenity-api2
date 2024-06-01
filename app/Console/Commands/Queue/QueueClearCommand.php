<?php

declare(strict_types=1);

namespace App\Console\Commands\Queue;

use Illuminate\Console\Command;
use Illuminate\Queue\QueueManager;
use Illuminate\Contracts\Queue\Factory as FactoryContract;

class QueueClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:clear';

    /**
     * The name and signature of the console command.
     *
     * php artisan queue:clear --connection=redis --queue=user
     *
     * @var string
     */
    protected $signature = 'queue:clear
                            {--connection= : The connection of the queue driver to clear.}
                            {--queue= : The name of the queue / pipe to clear.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистить все ожидающие задачи.';

    /**
     * @var FactoryContract
     */
    protected $manager;

    /**
     * QueueClearCommand constructor.
     * @param FactoryContract $manager
     */
    public function __construct(FactoryContract $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $connection = $this->option('connection') ?: config('queue.default');
        $queue = $this->option('queue') ?: config('queue.connections.' . $connection . '.queue');

        $this->info(sprintf('Clearing queue "%s" on "%s"', $queue, $connection));
        $cleared = $this->clear($connection, $queue);
        $this->info(sprintf('Cleared %d jobs', $cleared));
    }

    /**
     * @param string $connection
     * @param string $queue
     * @return int
     */
    public function clear(string $connection, string $queue): int
    {
        $count = 0;
        $connection = $this->manager->connection($connection);

        $count += $this->clearJobs($connection, $queue);
        $count += $this->clearJobs($connection, $queue . ':reserved');
        $count += $this->clearDelayedJobs($connection, $queue);

        return $count;
    }

    /**
     * @param object $connection
     * @param string $queue
     * @return int
     */
    protected function clearJobs(object $connection, string $queue): int
    {
        $count = 0;

        while ($job = $connection->pop($queue)) {
            $job->delete();
            $count++;
        }

        return $count;
    }

    /**
     * @param object $connection
     * @param string $queue
     * @return int
     */
    protected function clearDelayedJobs(object $connection, string $queue): int
    {
        if (method_exists($connection, 'getRedis')) {
            return $this->clearDelayedJobsOnRedis($connection, $queue);
        }

        throw new \InvalidArgumentException('Queue Connection not supported');
    }

    /**
     * @param object $connection
     * @param string $queue
     * @return int
     */
    protected function clearDelayedJobsOnRedis(object $connection, string $queue): int
    {
        $key = "queues:{$queue}:delayed";
        $redis = $connection->getRedis()->connection(config('queue.connections.redis.connection'));
        $count = $redis->zcount($key, '-inf', '+inf');
        $redis->del($key);

        return $count;
    }
}
