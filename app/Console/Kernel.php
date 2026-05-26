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
        // $schedule->command('inspire')->hourly();
        $schedule->command('booking:update-status')->everyMinute();
        $schedule->command('table:sync-status')->everyMinute();
        $schedule->command('table:check-finished')->everyMinute();
        $schedule->command('subscription:process-status')->dailyAt('00:05');
        $schedule->command('cron:cleanup-dormant')->dailyAt('00:30');
        // Kirim notifikasi WA barang expired setiap hari pukul 08:00
        $schedule->command('expired:send-alert')->dailyAt('08:00');
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
