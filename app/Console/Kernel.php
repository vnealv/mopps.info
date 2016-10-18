<?php

namespace App\Console;

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
        // Commands\Inspire::class,
//        'App\Console\Command\HappyBirthday'
        Commands\ClearActiveParkingSession::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
//        $filePath = "/Applications/MAMP/htdocs/mopps.info/storage/logs/schedule.log";
        $filePath = "/var/www/html/storage/logs/schedule.log";
        $schedule->command('api:clearActive')->cron('*/15 * * * *')->withoutOverlapping()->appendOutputTo($filePath);
    }
}



