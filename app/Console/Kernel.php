<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\Dev\ScheduleRunCommand;
use App\Console\Commands\HealthCheck\HealthCheckHostfullyCommand;
use App\Console\Commands\Listing\ListingClearDeletedCommand;
use App\Console\Commands\Listing\ListingNewMailCommand;
use App\Console\Commands\Reservation\ReservationCheckBeginningCommand;
use App\Console\Commands\Reservation\ReservationCheckPassedCommand;
use App\Console\Commands\Reservation\ReservationCheckPayoutCommand;
use App\Console\Commands\Reservation\ReservationClearCommand;
use App\Console\Commands\Sync\SyncReservationsCommand;
use App\Console\Commands\User\UserCheckCalendarCommand;
use App\Console\Commands\User\UserCheckNewMessageCommand;
use App\Console\Commands\User\UserClearDeletedCommand;
use App\Console\Commands\User\UserCreateCommand;
use App\Console\Commands\User\UserVerificationIdentityCheckCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UserCreateCommand::class,
        ReservationCheckPassedCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        byEnv('*', function () use ($schedule) {
            /**
             * Проверка крона
             */
            //$schedule->command(ReservationCheckPassedCommand::SIGNATURE)->everyMinute();
            $schedule->command(ReservationCheckPassedCommand::SIGNATURE)->hourlyAt(1);

            $schedule->command(ReservationCheckBeginningCommand::SIGNATURE)->hourlyAt(1);
            //$schedule->command(ReservationCheckPayoutCommand::SIGNATURE)->hourlyAt(1);

            $schedule->command(ReservationClearCommand::SIGNATURE)->everyFiveMinutes();
            $schedule->command(UserCheckNewMessageCommand::SIGNATURE)->everyFiveMinutes();

            // удаление удаленных листингов за сутки и без броней
            $schedule->command(ListingClearDeletedCommand::SIGNATURE)->dailyAt('06:00');
            $schedule->command(UserClearDeletedCommand::SIGNATURE)->dailyAt('06:00');

            // проверка статуса заявки на верификацию юзера
            $schedule->command(UserVerificationIdentityCheckCommand::SIGNATURE)->everyFiveMinutes();

            // уведомление о новых листингах каждый час, когторые создались в прошлый час
            $schedule->command(ListingNewMailCommand::SIGNATURE)->hourly();

            // синхронизировать брони
            //$schedule->command(SyncReservationsCommand::SIGNATURE . ' --from')->everyMinute();

            // проверка календарей юзера после синхронизации из hostfully
            $schedule->command(UserCheckCalendarCommand::SIGNATURE)->everyFiveMinutes();

            // проверка хостфулли
            $schedule->command(HealthCheckHostfullyCommand::SIGNATURE)->everyMinute();

            $schedule->command('horizon:snapshot')->everyFiveMinutes();
        });
        byEnvProduction(function () use ($schedule) {
            // database backup only for production
            $schedule->command('snapshot:create')->dailyAt('00:00');
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
