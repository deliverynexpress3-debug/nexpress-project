<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('settlement:cron')->dailyAt('00:00');
        $schedule->command('lowstock-reminder-cron')->dailyAt('09:30');
        $schedule->command('send-daily-notification-morning')->dailyAt('09:30');
        $schedule->command('send-daily-notification-evening')->dailyAt('16:00');
        $schedule->command('send:auto-noti')->name('send_daily_noti')->everyMinute();
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
