<?php

namespace App\Console;

use App\Http\Controllers\AuditController;
use App\Http\Controllers\CronController;
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
        Commands\RegisterUser::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
// //        $schedule->command('backup:run --only-db --only-to-disk=backup-cloud')->hourly()->between('07:00', '23:05'); // last time at 23:00
//         $schedule->command('backup:clean')->dailyAt('02:00');
//         $schedule->command('backup:run')->dailyAt('02:00');

//         $schedule->call(fn() => AuditController::clearOldestAuditFiles())->dailyAt('03:00');

            $schedule->command('user:register')->daily();

//         $schedule->call(fn() => CronController::deleteOldNotifications())->dailyAt('04:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
